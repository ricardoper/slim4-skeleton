# PHP Slim Framework v4 Skeleton

Use this skeleton application to quickly setup and start working on a new Slim Framework v4 application.

This skeleton is very customizable with a sane and organized folder structure. The code is simpler to understand too.

This skeleton application is built for Composer. These makes setting up a new Slim Framework v4 application quick and easy.

- PHP >= 7.2
- Customizable with an easy configuration:
  + Logger
  + Routes
  + Configs
  + Handlers
  + Middlewares
  + Service Providers
  + Response Emitters
  + Error Handler
  + Shutdown Handler
- Controllers
- Global Helpers
- [Monolog](https://github.com/Seldaek/monolog) Logging
- Environment variables with [PHP dotenv](https://github.com/vlucas/phpdotenv)
- [Pimple](https://pimple.symfony.com/) Dependency Injection Container
- [ddumper](https://github.com/ricardoper/ddumper) (based on [Symfony VarDumper](https://github.com/symfony/var-dumper))

## How to install this skeleton

Run this command from the directory in which you want to install your new Slim Framework v4 Skeleton.

```bash
composer create-project ricardoper/slim4-skeleton [my-app-name]
```

Replace `[my-app-name]`with the desired directory name for your new application. You'll want to:
- Point your virtual host document root to your new application's `public/` directory.
- Ensure `storage/` is web writeable.

## Most relevant skeleton folders

- /app : *Application* code (PSR-4 **App** Namespace)
  + ./Controllers : Add your *Controllers* here
  + ./Emitters : Add your *Response Emitters* here
  + ./Handlers : Add your *Handlers* here
  + ./Middlewares : Add your *Middlewares* here
  + ./Routes : Add your *Routes* here
  + ./Services : Add your *Service Providers* here
- /configs : Add/modify your *Configurations* here
- /public : Add your *Assets* files here

## Helpers methods

- `env(string $variable, string $default)` - Returns *environment* variables (using DotEnv)
- `app()` - Returns *App* instance
- `container(string $name)` - Returns *Container* registered data
- `configs(string $variable, string $default)` - Returns *Configuration* data
- `base_path(string $path)` - Returns *base path* location
- `app_path(string $path)` - Returns *app path* location
- `configs_path(string $path)` - Returns *configs path* location
- `public_path(string $path)` - Returns *public path* location
- `storage_path(string $path)` - Returns *storage path* location
- `d($var1, $var2, ...)` - Dump vars in colapsed mode by default
- `dd($var1, $var2, ...)` - Dump & die vars in colapsed mode by default
- `de($var1, $var2, ...)` - Dump vars in expanded mode by default
- `dde($var1, $var2, ...)` - Dump & die vars in expanded mode by default

## Controllers

You can add as many *Controllers* as you want in a cleaning way (`/app/Controllers`).

After add your *Controller*, you can enable or disable it in your *Routes*.

```php
use App\Emitters\PlainResponseEmitter;
use App\Kernel\Controllers\ControllerAbstract;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController extends ControllerAbstract
{

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function index(Request $request, Response $response): Response
    {
        unset($request);

        $data = ['Hello' => 'World!'];

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}
```

## Controllers Methods

- `getApp()` - Returns *App* object
- `getContainer(string $name)` - Returns the App *Container*
- `getConfigs(string $name)` - Returns App *Configs*
- `getService(string $service)` - Returns *Service Provider* from container by name
- `getResponse()` - Returns *Response*
- `setEmitter(string $emitter)` : Set *Response Emitter*
- `write(string $data)` - Send *plain text* output
- `json(array $data, bool $sendHeaders)` - Send *JSON encoded* output

## Response Emitters

You can add as many **global** *Response Emitters* as you want in a cleaning way (`/app/Emitters`).

After add your **global** *Response Emitter*, you can enable or disable it in `config/emitters.php` configuration file.

```php
use App\Emitters\ResponseEmitter;

return [

    ResponseEmitter::class,

];
```

**NOTE**: If you need a *Response Emitter* only for one action, please verify `setEmitter(string $emitter)` in *Controllers Methods*.

## Handlers

You can override the following Handlers in a cleaning way (`/app/Handlers`):
- *ErrorHandler* (default locatedl in `/app/Handlers/ErrorHandler`)
- *ShutdownHandler* (default locatedl in `/app/Handlers/ShutdownHandler`)

After add your *Handler*, you can enable or disable it in `config/app.php` configuration file.

```php
use App\Handlers\ErrorHandler;
use App\Handlers\ShutdownHandler;

return [

    // Handlers //
    'errorHandler' => ErrorHandler::class,
    'shutdownHandler' => ShutdownHandler::class,
```

## Middlewares

You can add as many *Middlewares* as you want in a cleaning way (`/app/Middlewares`).

After add your *Middleware*, you can enable or disable it in `config/middlewares.php` configuration file.

```php
use App\Middlewares\Demo\ExampleMiddleware;

return [

    ExampleMiddleware::class,

];
```

## Services Providers

You can add as many *Services Providers* as you want in a cleaning way (`/app/Services`).

After add your *Services Provider*, you can enable or disable it in `config/services.php` configuration file.

**NOTE**: **Logger** is a *Service Provider*, it can be customized as any other *Service Provider*.

```php
use App\Services\Demo\ExampleServiceProvider;

return [

    ExampleServiceProvider::class,

];
```

*Service Providers* must respect the **ServiceProviderInterface** located in `/app/Kernel` folder.

Service Provider Example:
```php
use App\Kernel\ServiceProviderInterface;
use Closure;
use Pimple\Container;

class ExampleServiceProvider implements ServiceProviderInterface
{

    /**
     * Service register name
     */
    public function name(): string
    {
        return 'example';
    }

    /**
     * Register new service on dependency container
     *
     * @param Container $c
     * @return Closure
     */
    public function register(Container $c): Closure
    {
        return function (Container $c) {
            unset($c);

            return new Example();
        };
    }
}
```

## Routes

You can add as many routes files as you want (`/app/Routes`), but you need to enable these files in `/apps/Routes/app.php` file.

You can organize this routes as you like. This skeleton has a little Demo that you can see how to organize this files.

```php
use App\Controllers\Demo\HelloController;
use App\Controllers\Demo\HomeController;
use Slim\App;

/**
 * @var $app App
 */

$app->get('/', [(new HomeController()), 'index']);

$app->get('/dump', [(new HomeController()), 'dump']);

$app->get('/hello/{name}', [(new HelloController()), 'index'])->setName('jsonHello');
```

## Configurations

You can add as many configurations files as you want (`/config`), but you need to enable these files in `/config/app.php` file.

## Demo

This skeleton has a little Demo that you can see all this points in action.

Demo URL's:
- /
- /dump
- /hello/{name} - Replace {name} with your name

## Logging

Logging is enabled by default and you can see all the output in `/storage/logs/app.log`.

You can set this parameters in `/.env` file you overwrite the `/configs/app.php`.

*LOG_ERRORS* - *logErrors* - *bool* - Enable/Disable logging

*LOG_ERRORS_DETAILS* - *logErrorDetails* - *bool* - Enable/Disable extra details in the logging file

*APP_IN_DOCKER* - *inDocker* - *bool* - `true` to output the logs in console, `false` to output logs in file.

## Debugging

Debugging is disabled by default. You can set this parameters in `/.env` file you overwrite the `/configs/app.php`.

*APP_DEBUG* - *displayErrorDetails* - *bool* - Enable/Disable debugging

## Benchmarks

Nothing is free, so let's compare the performance loss with Slim Skeleton.

**Machine:**<br/>
Intel® Core™ i5-8400 CPU @ 2.80GHz × 6<br>
16Gb RAM<br>
SSD<br>

**Versions:**<br/>
Ubuntu 20.04 LTS<br/>
Docker v19.03.8<br>
nginx 1.17.10<br/>
PHP v7.4.3<br/>
Zend OPcache enabled<br/>
SIEGE 4.0.4

**Bench Details:**<br/>
25 concurrent connections<br/>
500 requests per thread<br/>
No delays between requests<br/>
Command: siege -c25 -b -r500 "URL"<br/>
<br/>

|  | My Skeleton | Slim Skeleton |
| --- | :----: | :---: |
| Transactions | 12500 hits | 12500 hits |
| Availability | 100.00 % | 100.00 % |
| Elapsed time | 9.16 secs | 8.80 secs |
| Data transferred | 0.45 MB | 0.45 MB |
| Response time | 0.02 secs | 0.02 secs |
| Transaction rate | 1364.63 trans/sec | 1420.45 trans/sec |
| Throughput | 0.05 MB/sec | 0.05 MB/sec |
| Concurrency | 24.49 | 24.51|
| Successful transactions | 12500 | 12500 |
| Failed transactions | 0 | 0 |
| Longest transaction | 0.05 | 0.05 |
| Shortest transaction | 0.00 | 0.00 |
<br/>

___

### Enjoy the simplicity :oP
