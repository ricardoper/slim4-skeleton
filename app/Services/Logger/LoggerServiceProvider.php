<?php
declare(strict_types=1);

namespace App\Services\Logger;

use App\Kernel\ServiceProviderInterface;
use Closure;
use Pimple\Container;
use Psr\Log\LoggerInterface;

class LoggerServiceProvider implements ServiceProviderInterface
{

    /**
     * Service register name
     */
    public function name(): string
    {
        return LoggerInterface::class;
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
            return (new Logger())->build($c);
        };
    }
}
