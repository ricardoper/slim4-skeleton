<?php
declare(strict_types=1);

namespace App\Kernel\App;

use App\Kernel\ServiceProviderInterface;
use DI\ContainerBuilder as DIContainerBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;


class ContainerBuilder extends DIContainerBuilder
{

    /**
     * App Configs
     *
     * @var array
     */
    protected $settings = [];


    /**
     * Init Container
     */
    public function init(): void
    {
        $this->settings = require configs_path('app.php');

        if (env('APP_DEBUG', false) === false) {
            parent::enableCompilation(storage_path('cache'));
        }

        $this->registerConfigs();

        $this->registerLogger();

        $this->registerServices();
    }


    /**
     * Register Configs
     */
    protected function registerConfigs(): void
    {
        $this->addDefinitions([
            'settings' => $this->settings,
        ]);
    }

    /**
     * Register Logger
     */
    protected function registerLogger(): void
    {
        $this->addDefinitions([
            LoggerInterface::class => function (ContainerInterface $c) {
                $loggerSettings = $c->get('settings')['logger'];

                $logger = new Logger($loggerSettings['name']);

                $processor = new UidProcessor();
                $logger->pushProcessor($processor);

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
            },
        ]);
    }

    /**
     * Register Services
     */
    protected function registerServices(): void
    {
        $services = $this->settings['services'];

        if (is_array($services) && !empty($services)) {
            foreach ($services as $service) {
                /**
                 * @var $instance ServiceProviderInterface
                 */
                $instance = new $service();

                $this->addDefinitions([$instance->name() => $instance->register()]);
            }
        }
    }
}
