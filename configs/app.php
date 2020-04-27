<?php
declare(strict_types=1);

use App\Kernel\Handlers\HttpErrorHandler;
use App\Kernel\Handlers\ShutdownHandler;

return [

    'errorHandler' => HttpErrorHandler::class,

    'shutdownHandler' => ShutdownHandler::class,

    'logErrors' => env('LOG_ERRORS', true),
    'logErrorDetails' => env('LOG_ERRORS_DETAILS', true),
    'displayErrorDetails' => env('APP_DEBUG', false),


    // Includes //
    'logger' => require 'logger.php',
    'services' => require 'services.php',
    'emitters' => require 'emitters.php',
    'middlewares' => require 'middlewares.php',

];
