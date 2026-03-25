<?php

return [
    'emails' => array_values(array_filter(array_map(
        static fn (string $email) => trim($email),
        explode(',', env('ADMIN_EMAILS', 'test@example.com'))
    ))),
];
