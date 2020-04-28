<?php
declare(strict_types=1);

namespace App\Kernel;

use Closure;
use Pimple\Container;

interface ServiceProviderInterface
{

    /**
     * Service register name
     */
    public function name(): string;

    /**
     * Register new service on dependency container
     *
     * @param Container $c
     * @return Closure
     */
    public function register(Container $c): Closure;
}
