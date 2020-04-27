<?php
declare(strict_types=1);

namespace App\Kernel\App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App as SlimApp;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class App extends SlimApp
{

    /**
     * App Configs
     *
     * @var array
     */
    protected $settings = [];

    /**
     * App Instance
     *
     * @var App|null
     */
    private static $instance;


    /**
     * Get App Instance
     *
     * @return App|null
     */
    public static function getInstance(): ?App
    {
        return static::$instance;
    }

    /**
     * Init App
     *
     * @return ResponseInterface
     */
    public function init(): ResponseInterface
    {
        static::$instance = $this;

        $this->settings = require configs_path('app.php');


        $this->registerMiddlewares();

        $this->registerRoutes();


        $request = $this->getRequestObject();

        $errorHandler = $this->setErrorHandler();

        $this->setShutdownHandler($request, $errorHandler);

        $this->setErrorMiddleware($errorHandler);

        $response = $this->handle($request);


        $this->registerResponseEmitters($response);

        return $response;
    }


    /**
     * Register Middlewares
     */
    protected function registerMiddlewares(): void
    {
        $middlewares = $this->settings['middlewares'];

        if (is_array($middlewares) && !empty($middlewares)) {
            foreach ($middlewares as $middleware) {
                $this->add($middleware);
            }
        }
    }

    /**
     * Register Routes
     */
    protected function registerRoutes(): void
    {
        require app_path('Routes/app.php');
    }

    /**
     * Register Request Object
     *
     * @return ServerRequestInterface
     */
    protected function getRequestObject(): ServerRequestInterface
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();

        return $serverRequestCreator->createServerRequestFromGlobals();
    }

    /**
     * Set Error Handler
     *
     * @return SlimErrorHandler
     */
    protected function setErrorHandler(): SlimErrorHandler
    {
        $errorHandler = $this->settings['errorHandler'];

        $responseFactory = $this->getResponseFactory();

        $logger = $this->container->get(LoggerInterface::class);

        return new $errorHandler($this->getCallableResolver(), $responseFactory, $logger);
    }

    /**
     * Set Shutdown Handler
     *
     * @param ServerRequestInterface $request
     * @param SlimErrorHandler $errorHandler
     */
    protected function setShutdownHandler(ServerRequestInterface $request, SlimErrorHandler $errorHandler): void
    {
        $settings = $this->settings;

        $shutdownHandler = new $settings['shutdownHandler'](
            $request,
            $errorHandler,
            $settings['displayErrorDetails'],
            $settings['logErrors'],
            $settings['logErrorDetails']
        );

        register_shutdown_function($shutdownHandler);
    }

    /**
     * Set Error Middleware
     *
     * @param SlimErrorHandler $errorHandler
     */
    protected function setErrorMiddleware(SlimErrorHandler $errorHandler): void
    {
        $displayErrorDetails = $this->settings['displayErrorDetails'];

        $errorMiddleware = $this->addErrorMiddleware($displayErrorDetails, true, true);

        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    /**
     * Register Response Emitters
     *
     * @param ResponseInterface $response
     */
    protected function registerResponseEmitters(ResponseInterface $response): void
    {
        $emitters = $this->settings['emitters'];

        if (is_array($emitters) && !empty($emitters)) {
            foreach ($emitters as $emitter) {
                (new $emitter())->emit($response);
            }
        }
    }
}
