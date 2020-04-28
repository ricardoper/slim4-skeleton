<?php
declare(strict_types=1);

use App\Controllers\Demo\HelloController;
use App\Controllers\Demo\HomeController;
use Slim\App;

/**
 * @var $app App
 */

$app->get('/', [(new HomeController()), 'index']);

$app->get('/dump', [(new HomeController()), 'dump']);

$app->get('/hello/{name}', [(new HelloController()), 'index']);
