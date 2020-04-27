<?php
declare(strict_types=1);

use App\Middlewares\Demo\ExampleMiddleware;
use App\Middlewares\SessionMiddleware;

return [

    SessionMiddleware::class,

    ExampleMiddleware::class,

];
