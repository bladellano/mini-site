<?php

use App\Controllers\HomeController;
use App\Controllers\AboutController;

return [
    '/' => [HomeController::class, 'index'],
    '/about' => [AboutController::class, 'index'],
];
