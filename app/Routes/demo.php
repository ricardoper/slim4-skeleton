<?php
declare(strict_types=1);

use App\Controllers\Demo\HelloController;
use App\Controllers\Demo\HomeController;
use App\Kernel\App\App;

/**
 * @var $this App
 */

$this->get('/', [(new HomeController()), 'index']);

$this->get('/hello/{name}', [(new HelloController()), 'index']);
