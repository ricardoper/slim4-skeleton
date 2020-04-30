<?php
declare(strict_types=1);

namespace App\Kernel\Controllers;

use App\Emitters\JsonResponseEmitter;
use App\Kernel\App;
use Pimple\Container;
use Psr\Http\Message\ResponseInterface as Response;

abstract class ControllerAbstract
{

    /**
     * App
     *
     * @var App
     */
    protected $app;

    /**
     * Configs
     *
     * @var array
     */
    protected $configs;

    /**
     * App
     *
     * @var Container
     */
    protected $container;


    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->app = app();

        $this->configs = $this->app->getConfigs();

        $this->container = $this->app->getContainer();
    }


    /**
     * Get App
     *
     * @return App
     */
    protected function getApp(): App
    {
        return $this->app;
    }

    /**
     * Get Container
     *
     * @param string|null $name
     * @return mixed|null
     */
    protected function getContainer(string $name = null)
    {
        if ($name === null) {
            return $this->container;
        }

        return $this->container[$name] ?? null;
    }

    /**
     * Get Configs
     *
     * @param string|null $name
     * @param mixed $default
     * @return mixed|null
     */
    protected function getConfigs(string $name = null, $default = null)
    {
        $configs = $this->configs ?? null;

        if ($name === null) {
            return $configs;
        } else if ($configs === null) {
            return $default;
        }

        return $configs[$name] ?? $default;
    }

    /**
     * Get Service From Container
     *
     * @param string $name
     * @return mixed|null
     */
    protected function getService(string $name)
    {
        return $this->container[$name] ?? null;
    }

    /**
     * Get Response
     *
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->container['response'];
    }

    /**
     * Set Emitter
     *
     * @param string $emitter
     */
    protected function setEmitter(string $emitter): void
    {
        $settings = $this->container['settings'];

        $settings['emitters'] = array_merge($settings['emitters'], [$emitter]);

        $this->container['settings'] = $settings;
    }

    /**
     * Write Text Plain
     *
     * @param string $data
     * @return Response
     */
    public function write(string $data): Response
    {
        $response = $this->getResponse();

        $response->getBody()->write($data);

        return $response;
    }

    /**
     * Returns Json Encoded
     *
     * @param array $data
     * @param bool $sendHeaders
     * @return Response
     */
    public function json(array $data, bool $sendHeaders = true): Response
    {
        $response = $this->getResponse();

        $response->getBody()->write(json_encode($data));

        if ($sendHeaders === true) {
            $this->setEmitter(JsonResponseEmitter::class);
        }

        return $response;
    }
}
