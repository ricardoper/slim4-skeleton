<?php
declare(strict_types=1);

namespace App\Handlers\Renderers;

use Slim\Error\AbstractErrorRenderer;
use Throwable;
use function get_class;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;

class JsonErrorRenderer extends AbstractErrorRenderer
{

    /**
     * @param Throwable $exception
     * @param bool $displayErrorDetails
     * @return string
     */
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $error = ['message' => 'Application Error'];

        if ($displayErrorDetails === true) {
            $error = [
                'message' => $this->formatExceptionFragment($exception),
                'exception' => [],
            ];

            do {
                $error['exception'][] = explode("\n", $exception->getTraceAsString());
            } while ($exception = $exception->getPrevious());
        }

        return (string)json_encode($error, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }


    /**
     * @param Throwable $exception
     * @return array
     */
    protected function formatExceptionFragment(Throwable $exception): array
    {
        return [
            'type' => get_class($exception),
            'code' => $exception->getCode(),
            'message' => ucfirst($exception->getMessage()),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }
}
