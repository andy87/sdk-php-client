<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\base\modules\AbstractCache;
use andy87\sdk\client\base\modules\AbstractLogger;
use andy87\sdk\client\base\modules\AbstractTest;
use andy87\sdk\client\base\modules\AbstractTransport;
use Exception;
use andy87\sdk\client\core\Modules;
use andy87\sdk\client\core\Container;
use andy87\sdk\client\base\components\Config;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\core\transport\Response;

/**
 * Класс Client
 *  Основной Абстрактный слой клиентов.
 *
 * @package src/base
 */
abstract class AbstractClient implements ClientInterface
{
    /**
     * Константа для класса контейнера, используемого в клиенте.
     * Для переопределения в наследуемых классах, при необходимости.
     *
     * @var string
     */
    protected const CONTAINER_CLASS = Container::class;



    /** @var Config $config Конфигурация клиента */
    protected Config $config;

    /** @var Modules $modules Модули клиента */
    protected Modules $modules;



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

        $this->setupModules( $this->getConfig() );
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

        $this->modules = new Modules( $this, $container );
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

        $container = new $containerClass( $config->getRegistryOverrides() );

        if ( $container instanceof Container )
        {
            return $container;
        }

        throw new Exception( 'Container not found' );
    }

    /**
     * Возвращает объект конфигурации клиента.
     *
     * @param string $id
     *
     * @return null|AbstractTest|AbstractCache|AbstractLogger|AbstractTransport|Container
     *
     * @throws Exception
     */
    public function getModule( string $id ): null|AbstractTest|AbstractCache|AbstractLogger|AbstractTransport|Container
    {
        return match ($id)
        {
            ClientInterface::TEST => $this->modules->getTest(),
            ClientInterface::CACHE => $this->modules->getCache(),
            ClientInterface::LOGGER => $this->modules->getLogger(),
            ClientInterface::TRANSPORT => $this->modules->getTransport(),
            ClientInterface::CONTAINER => $this->modules->getContainer(),
            default => throw new Exception( "Module with ID '$id' not found." ),
        };
    }

    /**
     * Возвращает конфигурацию клиента.
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Возвращает контейнер, используемый клиентом.
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->modules->getContainer();
    }

    /**
     * Отправляет запрос к API.
     *
     * @param RequestInterface $request
     *
     * @return Response
     *
     * @throws Exception
     */
    protected function sendRequest( RequestInterface $request ): Response
    {
        $mock = $request->getPrompt()->getMock();

        if( $mock && $mock::BREAKPOINT == $mock::BREAKPOINT_RESPONSE )
        {
            return $mock->getData();
        }

        return $this->modules->getTransport()->sendRequest( $request );
    }

    /**
     * Обработчик ошибок клиента.
     *
     * @param string|array $data
     *
     * @return void
     *
     * @throws Exception
     */
    public function errorHandler( string|array $data ): void
    {
        if ( $logger = $this->modules->getLogger() )
        {
            $logger->errorHandler( $data );
        }
    }
}