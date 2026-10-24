<?php

declare(strict_types=1);

// Read-only daily check, no Laravel boot, references, recipients, payloads or logs.
ini_set('display_errors', '0');
ini_set('log_errors', '0');
try {
    if ($argc !== 2 || ! is_file($argv[1])) {
        throw new RuntimeException('Existing database required');
    }
    $db = new SQLite3($argv[1], SQLITE3_OPEN_READONLY);
    $db->enableExceptions(true);
    $db->busyTimeout(2000);
    $counts = [];
    foreach (['failed', 'uncertain', 'processing'] as $status) {
        $statement = $db->prepare('SELECT COUNT(*) FROM contact_mail_deliveries WHERE status = :status');
        $statement->bindValue(':status', $status, SQLITE3_TEXT);
        $counts[$status] = (int) $statement->execute()->fetchArray(SQLITE3_NUM)[0];
    }
    $counts['overdue'] = (int) $db->querySingle("SELECT COUNT(*) FROM contact_mail_deliveries WHERE status IN ('pending','queued','processing') AND created_at < datetime('now', '-1 day')");
    $counts['failed_jobs'] = (int) $db->querySingle("SELECT COUNT(*) FROM failed_jobs WHERE queue = 'contact-mail'");
    echo json_encode($counts, JSON_THROW_ON_ERROR)."\n";
    exit($counts['failed'] + $counts['uncertain'] + $counts['overdue'] + $counts['failed_jobs'] > 0 ? 2 : 0);
} catch (Throwable) {
    fwrite(STDERR, "Mail health unavailable; operator investigation required.\n");
    exit(1);
}
