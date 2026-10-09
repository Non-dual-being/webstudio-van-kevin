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
    // No deletion until a retention policy is approved and explicitly configured.
    // Never schedule contact:prune automatically as part of enabling this value.
    'retention_days' => env('CONTACT_RETENTION_DAYS'),
    // Explicit opt-in, additionally restricted to local/testing environments.
    'allow_test_transport' => (bool) env('CONTACT_ALLOW_TEST_TRANSPORT', false),
];
