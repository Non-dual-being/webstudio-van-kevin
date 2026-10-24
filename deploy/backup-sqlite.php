<?php

declare(strict_types=1);

// Standalone: no Laravel, .env, query of request content or queue processing.
ini_set('display_errors', '0');
ini_set('log_errors', '0');
umask(0077);
try {
    if ($argc !== 3 || ! is_file($argv[1]) || file_exists($argv[2]) || ! is_dir(dirname($argv[2]))) {
        throw new RuntimeException('Usage: backup-sqlite.php EXISTING_DB NEW_PRIVATE_BACKUP');
    }
    $handle = fopen($argv[2], 'x');
    if ($handle === false) {
        throw new RuntimeException('Destination unavailable');
    }
    fclose($handle);
    $source = new SQLite3($argv[1], SQLITE3_OPEN_READONLY);
    $source->enableExceptions(true);
    $source->busyTimeout(5000);
    $target = new SQLite3($argv[2], SQLITE3_OPEN_READWRITE);
    $target->enableExceptions(true);
    if (! $source->backup($target) || $target->querySingle('PRAGMA quick_check') !== 'ok') {
        throw new RuntimeException('Backup validation failed');
    }
    $target->close();
    $source->close();
    echo "Consistent SQLite backup verified; secure storage/retention remains an operator responsibility.\n";
} catch (Throwable) {
    // Keep any incomplete destination for investigation. Never overwrite or auto-delete.
    fwrite(STDERR, "Backup failed; do not use destination. Check paths, extensions, rights and free space.\n");
    exit(1);
}
