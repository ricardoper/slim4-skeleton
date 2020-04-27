<?php
declare(strict_types=1);

namespace App\Kernel\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

class ShutdownHandler
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var HttpErrorHandler
     */
    private $errorHandler;

    /**
     * @var bool
     */
    private $displayErrorDetails;

    /**
     * @var bool
     */
    private $logErrors;

    /**
     * @var bool
     */
    private $logErrorDetails;

    /**
     * ShutdownHandler constructor.
     *
     * @param Request $request
     * @param $errorHandler $errorHandler
     * @param bool $displayErrorDetails
     */
    public function __construct(
        Request $request,
        HttpErrorHandler $errorHandler,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    )
    {
        $this->request = $request;
        $this->errorHandler = $errorHandler;
        $this->displayErrorDetails = $displayErrorDetails;
        $this->logErrors = $logErrors;
        $this->logErrorDetails = $logErrorDetails;
    }

    /**
     * Class Invoke
     */
    public function __invoke(): void
    {
        $error = error_get_last();
        if ($error) {
            $errorFile = $error['file'];
            $errorLine = $error['line'];
            $errorMessage = $error['message'];
            $errorType = $error['type'];
            $message = 'An error while processing your request. Please try again later.';

            if ($this->displayErrorDetails) {
                switch ($errorType) {
                    case E_USER_ERROR:
                        $message = "FATAL ERROR: {$errorMessage}. ";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;

                    case E_USER_WARNING:
                        $message = "WARNING: {$errorMessage}";
                        break;

                    case E_USER_NOTICE:
                        $message = "NOTICE: {$errorMessage}";
                        break;

                    default:
                        $message = "ERROR: {$errorMessage}";
                        $message .= " on line {$errorLine} in file {$errorFile}.";
                        break;
                }
            }

            $exception = new HttpInternalServerErrorException($this->request, $message);
            $response = $this->errorHandler->__invoke(
                $this->request,
                $exception,
                $this->displayErrorDetails,
                $this->logErrors,
                $this->logErrorDetails
            );

            $this->registerResponseEmitters($response);
        }
    }


    /**
     * Register Response Emitters
     *
     * @param ResponseInterface $response
     */
    protected function registerResponseEmitters(ResponseInterface $response): void
    {
        $settings = require configs_path('app.php');

        $emitters = $settings['emitters'];

        if (is_array($emitters) && !empty($emitters)) {
            foreach ($emitters as $emitter) {
                (new $emitter())->emit($response);
            }
        }
    }
}
