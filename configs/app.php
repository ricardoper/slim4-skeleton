<?php
declare(strict_types=1);

use App\Kernel\Handlers\HttpErrorHandler;
use App\Kernel\Handlers\ShutdownHandler;

return [

    // Deployment //
    'inProd'  => env('APP_IN_PROD', false),
    'inDocker'  => env('APP_IN_DOCKER', false),


    // Debugging //
    'debug' => env('APP_DEBUG', false),
    'logErrors' => env('LOG_ERRORS', true),
    'logErrorDetails' => env('LOG_ERRORS_DETAILS', true),
    'displayErrorDetails' => env('APP_DEBUG', false),


    // Handlers //
    'errorHandler' => HttpErrorHandler::class,
    'shutdownHandler' => ShutdownHandler::class,


    // Includes //
    'logger' => require 'logger.php',
    'services' => require 'services.php',
    'emitters' => require 'emitters.php',
    'middlewares' => require 'middlewares.php',

];
