<?php
declare(strict_types=1);

namespace App\Services\Logger;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\UidProcessor;
use Pimple\Container;
use Psr\Log\LoggerInterface;

class Logger
{

    /**
     * Build Logger
     *
     * @param Container $c
     * @return LoggerInterface
     */
    public function build(Container $c): LoggerInterface
    {
        $loggerSettings = $c['settings']['logger'];

        $logger = new MonologLogger($loggerSettings['name']);

        $logger->pushProcessor((new UidProcessor()));

        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }
}
