<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ContactRetention
{
    // Bounded transactions; another explicit invocation can remove the next batch.
    public const BATCH_SIZE = 500;

    /** @return array{old: int, eligible: int, unresolved: int, retained: int, queue_blocked: bool} */
    public function inspect(CarbonImmutable $cutoff): array
    {
        $old = DB::table('contact_submissions')->where('created_at', '<', $cutoff);
        $count = (clone $old)->count();
        $completed = (clone $old)->whereIn('id', $this->completedDeliveries())->count();
        $eligible = (clone $old)->whereIn('id', $this->completedDeliveries($cutoff))->count();

        return [
            'old' => $count,
            'eligible' => $eligible,
            'unresolved' => $count - $completed,
            'retained' => $completed - $eligible,
            'queue_blocked' => $this->queueIsOccupied(),
        ];
    }

    public function execute(CarbonImmutable $cutoff): int
    {
        return DB::transaction(function () use ($cutoff): int {
            if ($this->queueIsOccupied(lock: true)) {
                throw new RuntimeException('Contact queue requires review.');
            }

            $ids = DB::table('contact_submissions')->where('created_at', '<', $cutoff)
                ->whereIn('id', $this->completedDeliveries($cutoff))
                ->orderBy('id')->limit(self::BATCH_SIZE)->pluck('id');
            $deleted = 0;

            foreach ($ids as $id) {
                $submission = DB::table('contact_submissions')->where('id', $id)
                    ->where('created_at', '<', $cutoff)->lockForUpdate()->first(['id']);
                if ($submission === null) {
                    continue;
                }

                // Same rows that workers update when claiming/finalising a delivery.
                // Accepted/intercepted are terminal in the existing delivery workflow.
                $deliveries = DB::table('contact_mail_deliveries')->where('contact_submission_id', $id)
                    ->orderBy('id')->lockForUpdate()->get(['id', 'kind', 'status', 'finished_at']);
                if ($deliveries->count() !== 2
                    || $deliveries->pluck('kind')->sort()->values()->all() !== ['notification', 'receipt']
                    || $deliveries->contains(fn ($delivery) => ! in_array($delivery->status, ['accepted', 'intercepted'], true)
                        || $delivery->finished_at === null
                        || CarbonImmutable::parse($delivery->finished_at)->greaterThanOrEqualTo($cutoff))) {
                    continue;
                }
                if ($this->queueIsOccupied(lock: true)) {
                    throw new RuntimeException('Contact queue changed during retention.');
                }

                // Foreign key is RESTRICT: both intents and their parent disappear atomically.
                DB::table('contact_mail_deliveries')->whereIn('id', $deliveries->pluck('id'))->delete();
                $deleted += DB::table('contact_submissions')->where('id', $id)->delete();
            }

            return $deleted;
        }, attempts: 5);
    }

    private function completedDeliveries(?CarbonImmutable $cutoff = null): Builder
    {
        // Count all rows as well, so missing, extra or unknown intents fail closed.
        return DB::table('contact_mail_deliveries')->select('contact_submission_id')
            ->groupBy('contact_submission_id')->havingRaw('COUNT(*) = 2')
            ->havingRaw('COUNT(DISTINCT kind) = 2')
            ->havingRaw("SUM(CASE WHEN kind IN ('notification', 'receipt') AND status IN ('accepted', 'intercepted') AND finished_at IS NOT NULL THEN 1 ELSE 0 END) = 2")
            ->when($cutoff !== null, fn (Builder $query) => $query->havingRaw('MAX(finished_at) < ?', [$cutoff]));
    }

    /**
     * Workers can change the queue between calls within this process.
     *
     * @phpstan-impure
     */
    private function queueIsOccupied(bool $lock = false): bool
    {
        $connection = DB::getDefaultConnection();
        // This guard must see the same database as the contact workers. Unknown
        // storage is a configuration error, never permission to ignore mail tasks.
        if (config('queue.connections.database.driver') !== 'database'
            || (config('queue.connections.database.connection') ?: $connection) !== $connection
            || ! in_array(config('queue.failed.driver'), ['database', 'database-uuids'], true)
            || (config('queue.failed.database') ?: $connection) !== $connection) {
            throw new RuntimeException('Unsupported queue storage for contact retention.');
        }

        foreach (['queue.connections.database.table', 'queue.failed.table'] as $setting) {
            $table = config($setting);
            if (! is_string($table) || $table === '') {
                throw new RuntimeException('Missing queue table for contact retention.');
            }
            $query = DB::table($table)->where('queue', config('contact.queue'));
            if ($lock) {
                $query->lockForUpdate();
            }
            if ($query->first(['id']) !== null) {
                return true;
            }
        }

        return false;
    }
}
