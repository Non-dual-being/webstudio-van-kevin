<?php

return [
    'queue' => 'contact-mail',
    'cache_store' => env('CONTACT_CACHE_STORE', 'database'),
    'ip_limit' => (int) env('CONTACT_IP_LIMIT', 12),
    'email_limit' => (int) env('CONTACT_EMAIL_LIMIT', 3),
    'limit_seconds' => (int) env('CONTACT_LIMIT_SECONDS', 3600),
    'max_attempts' => 3,
    'backoff' => [60, 300],
    'claim_seconds' => 300,
    // Explicit opt-in, additionally restricted to local/testing environments.
    'allow_test_transport' => (bool) env('CONTACT_ALLOW_TEST_TRANSPORT', false),
];
