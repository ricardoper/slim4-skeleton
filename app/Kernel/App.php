<?php
declare(strict_types=1);

namespace App\Kernel;

use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\App as SlimApp;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Psr7\Factory\ResponseFactory;

class App
{

    /**
     * Instance
     *
     * @var App|null
     */
    protected static $instance;

    /**
     * Container
     *
     * @var Container
     */
    protected $container;

    /**
     * Configs
     *
     * @var array
     */
    protected $configs;

    /**
     * Slim App
     *
     * @var SlimApp
     */
    protected $slimApp;

    /**
     * Response
     *
     * @var ResponseInterface
     */
    protected $response;


    /**
     * App constructor
     */
    public function __construct()
    {
        static::$instance = $this;

        $this->configs = require configs_path('app.php');

        $container = $this->container = new Container();

        $container['app'] = $this;

        $this->slimApp = new SlimApp((new ResponseFactory()), (new PsrContainer($container)));

        $this->response = $this->init();

        return $this;
    }

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
     * Get Container
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Get Configs
     *
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * Get Slim App
     *
     * @return SlimApp
     */
    public function getSlimApp(): SlimApp
    {
        return $this->slimApp;
    }

    /**
     * Get Response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }


    /**
     * Init App
     *
     * @return ResponseInterface
     */
    protected function init(): ResponseInterface
    {
        $this->registerConfigs();

        $this->setRouteStrategy();

        $this->registerServices();

        $this->registerMiddlewares();

        $this->registerRoutes();


        $request = $this->getRequestObject();

        $errorHandler = $this->setErrorHandler();

        $this->setShutdownHandler($request, $errorHandler);

        $this->setErrorMiddleware($errorHandler);

        $this->response = $this->slimApp->handle($request);


        $this->registerResponseEmitters($this->response);

        return $this->response;
    }

    /**
     * Register Configs
     */
    protected function registerConfigs(): void
    {
        $this->container['settings'] = $this->configs;
    }

    /**
     * Set Route Strategy
     */
    protected function setRouteStrategy(): void
    {
        $routeCollector = $this->slimApp->getRouteCollector();

        $routeCollector->setDefaultInvocationStrategy(new RouteStrategy());
    }

    /**
     * Register Services
     */
    protected function registerServices(): void
    {
        $services = $this->configs['services'];

        if (is_array($services) && !empty($services)) {
            foreach ($services as $service) {
                /**
                 * @var $instance ServiceProviderInterface
                 */
                $instance = new $service();

                $this->container[$instance->name()] = $instance->register($this->container);
            }
        }
    }

    /**
     * Register Middlewares
     */
    protected function registerMiddlewares(): void
    {
        $middlewares = array_reverse($this->configs['middlewares']);

        if (is_array($middlewares) && !empty($middlewares)) {
            foreach ($middlewares as $middleware) {
                $this->slimApp->add($middleware);
            }
        }
    }

    /**
     * Register Routes
     */
    protected function registerRoutes(): void
    {
        $app = $this->slimApp;

        require app_path('Routes/app.php');

        unset($app);
    }

    /**
     * Register Request Object
     *
     * @return ServerRequestInterface
     */
    protected function getRequestObject(): ServerRequestInterface
    {
        return ServerRequestCreatorFactory::create()
            ->createServerRequestFromGlobals();
    }

    /**
     * Set Error Handler
     *
     * @return SlimErrorHandler
     */
    protected function setErrorHandler(): SlimErrorHandler
    {
        return new $this->configs['errorHandler'](
            $this->slimApp->getCallableResolver(),
            $this->slimApp->getResponseFactory(),
            $this->container[LoggerInterface::class] ?? null
        );
    }

    /**
     * Set Shutdown Handler
     *
     * @param ServerRequestInterface $request
     * @param SlimErrorHandler $errorHandler
     */
    protected function setShutdownHandler(ServerRequestInterface $request, SlimErrorHandler $errorHandler): void
    {
        $configs = $this->configs;

        $shutdownHandler = new $configs['shutdownHandler'](
            $request,
            $errorHandler,
            $configs['displayErrorDetails'],
            $configs['logErrors'],
            $configs['logErrorDetails']
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
        $configs = $this->configs;

        $errorMiddleware = $this->slimApp->addErrorMiddleware(
            $configs['displayErrorDetails'],
            $configs['logErrors'],
            $configs['logErrorDetails']
        );

        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }

    /**
     * Register Response Emitters
     *
     * @param ResponseInterface $response
     */
    protected function registerResponseEmitters(ResponseInterface $response): void
    {
        $emitters = $this->container['settings']['emitters'];

        if (is_array($emitters) && !empty($emitters)) {
            foreach ($emitters as $emitter) {
                (new $emitter())->emit($response);
            }
        }
    }
}
