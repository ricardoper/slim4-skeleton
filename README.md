# PHP Slim Framework v4 Skeleton

Use this skeleton application to quickly setup and start working on a new Slim Framework v4 application.

This skeleton is very customizable with a sane and organized folder structure. The code is simpler to understand too.

This skeleton application was built for Composer. This makes setting up a new Slim Framework v4 application quick and easy.

- PHP >= 7.2

- Customizable with easy configuration:
  + Routes
  + Configs
  + Middlewares
  + Service Providers
  + Response Emitters
  + Error Handler
  + Shutdown Handler

- Controllers
- Global Helpers
- Logging with Monolog
- Environment variables with Dotenv
- ddumper (based on Symfony VarDumper)

## How to install this skeleton

Run this command from the directory in which you want to install your new Slim Framework v4 Skeleton.

```bash
composer create-project ricardoper/slim4-skeleton [my-app-name]
```

Replace ```[my-app-name]``` with the desired directory name for your new application. You'll want to:
- Point your virtual host document root to your new application's ```public/``` directory.
- Ensure ```storage/``` is web writeable.

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

You can add as many *Controllers* as you want in a clean way (```/app/Controllers```).

After add your *Controller*, you can enable or disable it in your *Routes*.

```php
use App\Controllers\Demo\HelloController;
use App\Controllers\Demo\HomeController;
use App\Kernel\App\App;

/**
 * @var $this App
 */

$this->get('/', [(new HomeController()), 'index']);

$this->get('/hello/{name}', [(new HelloController()), 'index']);
```

## Response Emitters

You can add as many *Response Emitters* as you want in a clean way (```/app/Emitters```).

After add your *Response Emitter*, you can enable or disable it in ```config/emitters.php``` configuration file.

```php
use App\Emitters\ResponseEmitter;

return [

    ResponseEmitter::class,

];
```

## Handlers

You can override the following Handlers in a clean way (```/app/Handlers```):
- *ErrorHandler* (default locatedl in ```/app/Kernel/Handlers/HttpErrorHandler```)
- *ShutdownHandler* (default locatedl in ```/app/Kernel/Handlers/ShutdownHandler```)

After add your *Handler*, you can enable or disable it in ```config/app.php``` configuration file.

```php
use App\Kernel\Handlers\HttpErrorHandler;
use App\Kernel\Handlers\ShutdownHandler;

return [

    'errorHandler' => HttpErrorHandler::class,

    'shutdownHandler' => ShutdownHandler::class,
```

## Middlewares

You can add as many *Middlewares* as you want in a clean way (```/app/Middlewares```).

After add your *Middleware*, you can enable or disable it in ```config/middlewares.php``` configuration file.

```php
use App\Middlewares\Demo\ExampleMiddleware;

return [

    ExampleMiddleware::class,

];
```

## Services Providers

You can add as many *Services Providers* as you want in a clean way (```/app/Services```).

After add your *Services Provider*, you can enable or disable it in ```config/services.php``` configuration file.

```php
use App\Services\Demo\ExampleServiceProvider;

return [

    ExampleServiceProvider::class,

];
```

Service Providers must respect the **ServiceProviderInterface** located in ```/app/Kernel``` folder.

Service Provider Example:
```php
use App\Kernel\ServiceProviderInterface;
use Closure;
use Psr\Container\ContainerInterface;

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
     */
    public function register(): Closure
    {
        return function (ContainerInterface $c) {
            return new Example();
        };
    }
}
```

## Routes

You can add as many routes files as you want (```/app/Routes```), but you need to enable these files in ```/apps/Routes/app.php``` file.

You can organize this routes as you like. This skeleton have a little Demo that you can see how to organize this files.

## Configurations

You can add as many configurations files as you want (```/config```), but you need to enable these files in ```/config/app.php``` file.

## Demo

This skeleton have a little Demo that you can see all this points in action.

Demo URL's:
- /
- /hello/{name} - Replace {name} with your name

## Logging

Logging is enabled by default and you can see all the output in ```/storage/logs/app.log```.

You can set this parameters in ```/.env``` file you overwrite the ```/configs/app.php```

*LOG_ERRORS* - *logErrors* - *bool* - Enable/Disable logging

*LOG_ERRORS_DETAILS* - *logErrorDetails* - *bool* - Enable/Disable extra details in the logging file

## Debugging

Debugging is disabled by default. You can set this parameters in ```/.env``` file you overwrite the ```/configs/app.php```

*APP_DEBUG* - *displayErrorDetails* - *bool* - Enable/Disable debugging

**NOTE**: When debugging is disabled, this skeleton will enable PHP-DI Compilation automatically. You can get more details in [PHP-DI Performances](http://php-di.org/doc/performances.html)

> ###Deployment in production:
> 
> When a container is configured to be compiled, it will be compiled once and never be regenerated again. That allows for maximum performances in production.
> 
> When you deploy new versions of your code to production you must delete the generated file (or the directory that contains it) to ensure that the container is re-compiled.

___

### Enjoy the simplicity :oP
