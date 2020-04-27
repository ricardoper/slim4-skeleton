<?php
declare(strict_types=1);

use App\Kernel\App\App;
use App\Kernel\App\ContainerBuilder;
use Slim\Psr7\Factory\ResponseFactory;

require 'kernel.php';


$container = new ContainerBuilder();

$container->init();

$app = new App((new ResponseFactory), $container->build());

$response = $app->init();
