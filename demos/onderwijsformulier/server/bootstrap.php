<?php

declare(strict_types=1);

// Standalone classloader: geen Composer, Laravel, .env of oorspronkelijke bootstrap.
spl_autoload_register(static function (string $class): void {
    foreach (['GeoFort\\' => dirname(__DIR__).'/src/', 'Demo\\' => __DIR__.'/'] as $prefix => $root) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }
        $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
        if (!preg_match('~^[A-Za-z][A-Za-z0-9_/]*$~D', $relative)) {
            return;
        }
        $file = $root.$relative.'.php';
        if (is_file($file)) {
            require_once $file;
        }
    }
});
