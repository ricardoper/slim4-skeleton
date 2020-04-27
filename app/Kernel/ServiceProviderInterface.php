<?php
declare(strict_types=1);

namespace App\Kernel;

use Closure;

interface ServiceProviderInterface
{

    /**
     * Service register name
     */
    public function name(): string;

    /**
     * Register new service on dependency container
     */
    public function register(): Closure;
}
