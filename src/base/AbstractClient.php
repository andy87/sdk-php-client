<?php

namespace andy87\sdk\client\base;

use Exception;
use andy87\sdk\client\core\Modules;
use andy87\sdk\client\core\Container;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\base\components\Config;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\ClientInterface;

/**
 * Класс Client
 *  Базовый класс клиента содержащий методы для работы клиента.
 *
 * @package src/base
 */
abstract class AbstractClient implements ClientInterface
{
    protected const CONTAINER_CLASS = Container::class;



    /** @var Config $config Конфигурация клиента */
    protected Config $config;

    /** @var Modules $modules Модули клиента */
    public Modules $modules;



    /**
     * Конструктор
     *
     * @param Config $config
     *
     * @throws Exception
     */
    public function __construct( Config $config )
    {
        $this->config = $this->prepareConfig( $config );

        $this->setupModules( $this->config );
    }

    /**
     * Кастомизация конфигурации клиента, если необходимо
     *
     * @param Config $config
     *
     * @return Config
     */
    protected function prepareConfig( Config $config ): Config
    {
        return $config;
    }

    /**
     * Устанавливает модули для работы клиента.
     *
     * @param Config $config
     *
     * @return void
     *
     * @throws Exception
     */
    public function setupModules( Config $config ): void
    {
        $container = $this->constructContainer( $config );

        $this->modules = new Modules( $container );
    }

    /**
     * Конструирует контейнер для хранения классов, используемых в клиенте.
     *
     * @param Config $config
     *
     * @return Container
     *
     * @throws Exception
     */
    protected function constructContainer( Config $config ): Container
    {
        $containerClass = static::CONTAINER_CLASS;

        $container = new $containerClass( $config->classes );

        if ( $container instanceof Container )
        {
            return $container;
        }

        throw new Exception( 'Container not found' );
    }

    /**
     * Отправляет запрос к API.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    protected function sendRequest( Request $request ): Response
    {
        return $this->modules->transport->sendRequest( $request );
    }

    /**
     * Обработчик ошибок клиента.
     *
     * @param string|array $data
     *
     * @return void
     */
    public function errorHandler( string|array $data ): void
    {
        if ( $logger = $this->modules->logger )
        {
            $logger->errorHandler( $data );
        }
    }
}