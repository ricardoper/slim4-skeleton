<?php
declare(strict_types=1);

namespace App\Kernel\Services\Logger;

use DateTime;
use Exception;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Monolog\Processor\UidProcessor;
use Pimple\Container;
use Psr\Log\LoggerInterface;

class Logger
{

    /**
     * Max rotation files
     *
     * @var int
     */
    protected $maxFiles = 0;

    /**
     * File date format
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d';

    /**
     * Filename format
     *
     * @var string
     */
    protected $filenameFormat = '{filename}-{date}.log';

    /**
     * Log filename
     *
     * @var string
     */
    protected $filename;

    /**
     * Timed Log filename
     *
     * @var string
     */
    protected $timedFilename;


    /**
     * Builder Logger
     *
     * @param Container $container
     * @return LoggerInterface
     * @throws Exception
     */
    public function builder(Container $container): LoggerInterface
    {
        $loggerConfigs = $container['configs']->get('logger');

        $this->maxFiles = $loggerConfigs['maxFiles'];
        $this->filename = $loggerConfigs['path'];

        $this->getTimedFilename();
        $this->rotate();


        $logger = new MonologLogger($loggerConfigs['name']);

        $logger->pushProcessor((new UidProcessor()));

        $formatter = new LineFormatter(
            null,
            null,
            true,
            true
        );

        $handler = new StreamHandler($this->timedFilename, $loggerConfigs['level']);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);

        return $logger;
    }


    /**
     * Get timed filename
     */
    protected function getTimedFilename(): void
    {
        $date = (new DateTime('now'))->format($this->dateFormat);

        $filename = str_replace('.log', '', basename($this->filename));

        $this->timedFilename = strtr(
            dirname($this->filename) . '/' . $this->filenameFormat,
            [
                '{filename}' => $filename,
                '{date}' => $date,
            ]
        );
    }

    /**
     * Rotates the files
     *
     * @throws Exception
     */
    protected function rotate(): void
    {
        $filename = $this->timedFilename;

        // Check if rotation is needed
        if (file_exists($filename)) {
            return;
        }

        // Check unlimited files flag
        if ($this->maxFiles === 0) {
            return;
        }

        // Touch log file
        if (is_writable(dirname($filename))) {
            touch($filename);
        }

        // Check if exists files to remove
        $logFiles = glob($this->getGlobPattern());
        if ($this->maxFiles >= count($logFiles)) {
            return;
        }

        rsort($logFiles);

        // Remove older files
        foreach (array_slice($logFiles, $this->maxFiles) as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Get Glob function pattern
     *
     * @return string
     */
    protected function getGlobPattern(): string
    {
        $date = (new DateTime('now'))->format($this->dateFormat);

        $filename = str_replace([$date, '.log'], '', $this->timedFilename);

        return $filename . '*.log';
    }
}
