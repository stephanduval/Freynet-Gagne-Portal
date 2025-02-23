<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => [], // ✅ No cookie authentication
    'guard' => ['api'], // ✅ Use API guard for authentication
    'expiration' => null,

    'middleware' => [], // ✅ Prevent Sanctum from expecting cookies
];
