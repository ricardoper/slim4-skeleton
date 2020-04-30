<?php
declare(strict_types=1);

use App\Handlers\ErrorHandler;
use App\Handlers\ShutdownHandler;

return [

    // Debugging //
    'debug' => env('APP_DEBUG', false),
    'logErrors' => env('LOG_ERRORS', true),
    'logErrorDetails' => env('LOG_ERRORS_DETAILS', true),
    'displayErrorDetails' => env('APP_DEBUG', false),
    'logToOutput'  => env('LOG_TO_OUTPUT', false),


    // Handlers //
    'errorHandler' => ErrorHandler::class,
    'shutdownHandler' => ShutdownHandler::class,


    // Includes //
    'logger' => require 'logger.php',
    'services' => require 'services.php',
    'emitters' => require 'emitters.php',
    'middlewares' => require 'middlewares.php',

];
