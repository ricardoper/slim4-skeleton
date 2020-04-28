<?php
declare(strict_types=1);

namespace App\Services\Demo;

use App\Kernel\ServiceProviderInterface;
use Closure;
use Pimple\Container;

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
     *
     * @param Container $c
     * @return Closure
     */
    public function register(Container $c): Closure
    {
        return function (Container $c) {
            unset($c);

            return new Example();
        };
    }
}
