<?php
declare(strict_types=1);

namespace App\Services\Demo;

class Example
{

    /**
     * Data example
     *
     * @param string $name
     * @return array
     */
    public function data(string $name): array
    {
        return ['Hello' => ucwords($name)];
    }
}
