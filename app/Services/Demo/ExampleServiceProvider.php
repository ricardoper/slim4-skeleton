<?php
declare(strict_types=1);

namespace App\Services\Demo;

use App\Kernel\ServiceProviderInterface;
use Closure;
use Psr\Container\ContainerInterface;

class ExampleServiceProvider implements ServiceProviderInterface
{

    /**
     * Service register name
     */
    public function name(): string
    {
        return 'example';
    }

    /**
     * Register new service on dependency container
     */
    public function register(): Closure
    {
        return function (ContainerInterface $c) {
            unset($c);

            return new Example();
        };
    }
}
