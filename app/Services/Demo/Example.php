<?php
declare(strict_types=1);

namespace App\Services\Demo;

use App\Kernel\Exceptions\ServiceException;

class Example
{

    /**
     * Capitalize Name
     *
     * @param string $name
     * @return string
     */
    public function capName(string $name): string
    {
        return ucwords($name);
    }

    /**
     * To Json
     *
     * @param string $name
     * @return array
     */
    public function toJson(string $name): array
    {
        return ['Hello' => $this->capName($name)];
    }

    /**
     * Service Exception
     *
     * @throws ServiceException
     */
    public function exception(): void
    {
        throw new ServiceException('Example Exception');
    }
}
