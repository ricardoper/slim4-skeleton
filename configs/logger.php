<?php
declare(strict_types=1);

use Monolog\Logger;

return [

    'name' => 'app',
    'path' => env('DOCKER_RUN', false) === true ? 'php://stdout' : storage_path('logs/app.log'),
    'level' => Logger::DEBUG,
];
