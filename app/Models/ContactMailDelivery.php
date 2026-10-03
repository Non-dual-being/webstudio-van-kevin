<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMailDelivery extends Model
{
    protected $guarded = ['id'];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'available_at' => 'immutable_datetime',
            'queued_at' => 'immutable_datetime',
            'started_at' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
            'attempts' => 'integer',
            'attempt_limit' => 'integer',
        ];
    }

    /** @return BelongsTo<ContactSubmission, $this> */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(ContactSubmission::class, 'contact_submission_id');
    }
}
