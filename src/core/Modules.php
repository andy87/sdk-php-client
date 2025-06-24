<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\base\components\Mock;
use andy87\sdk\client\base\modules\AbstractTest;
use andy87\sdk\client\base\modules\AbstractCache;
use andy87\sdk\client\base\modules\AbstractLogger;
use andy87\sdk\client\base\modules\AbstractTransport;
use andy87\sdk\client\base\interfaces\ClientInterface;

/**
 * Class Modules
 * Содержит модули, используемые в клиенте.
 *
 * @package src/core
 */
class Modules
{
    /** @var Container $container Контейнер для хранения зависимостей */
    protected Container $container;

    /** @var AbstractTransport $transport Интерфейс для отправки запросов к API */
    protected AbstractTransport $transport;



    /** @var null|Mock $mock Обект для реализации моков ответов API */
    protected ?Mock $mock = null;

    /** @var null|AbstractCache $cache Обект для реализации кэширования */
    protected ?AbstractCache $cache;


    /** @var null|AbstractTest $test Обект для реализации тестирования API */
    protected ?AbstractTest $test = null;


    /** @var null|AbstractLogger $logger Обект для реализации логирования запросов и ответов API */
    protected ?AbstractLogger $logger = null;



    /**
     * Modules constructor.
     *
     * @param Container $container
     *
     * @throws Exception
     */
    public function __construct( Container $container )
    {
        $this->container = $container;

        $this->transport = $this->getContainer()->get(ClientInterface::TRANSPORT);

        $this->cache = $this->getContainer()->get(ClientInterface::CACHE);

        $this->test = $this->getContainer()->get(ClientInterface::TEST);
    }

    /**
     * Получает контейнер, содержащий зависимости клиента.
     *
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * Получает интерфейс для работы с отправкой запросов
     *
     * @return AbstractTransport
     */
    public function getTransport(): AbstractTransport
    {
        return $this->transport;
    }

    /**
     * Получает интерфейс для работы с кэшем
     *
     * @return null|AbstractCache
     */
    public function getCache(): ?AbstractCache
    {
        return $this->cache;
    }

    /**
     * Получает интерфейс для тестирования
     *
     * @return null|AbstractTest
     */
    public function getTest(): ?AbstractTest
    {
        return $this->test;
    }

    /**
     * Получает интерфейс для логирования
     *
     * @return null|AbstractLogger
     */
    public function getLogger(): ?AbstractLogger
    {
        return $this->logger;
    }

    /**
     * Получает интерфейс для моков
     *
     * @return null|AbstractLogger
     */
    public function getMock(): ?Mock
    {
        return $this->mock;
    }

    /**
     * Устанавливает интерфейс для работы с кэшем
     *
     * @param AbstractCache $cache
     *
     * @return void
     */
    public function setCache( AbstractCache $cache ): void
    {
        if ( !$this->cache ) $this->cache = $cache;
    }

    /**
     * Устанавливает интерфейс для логирования
     *
     * @param AbstractLogger $logger
     *
     * @return void
     */
    public function setLogger( AbstractLogger $logger ): void
    {
        if ( !$this->logger ) $this->logger = $logger;
    }

    /**
     * Устанавливает интерфейс для тестирования
     *
     * @param AbstractTest $test
     *
     * @return void
     */
    public function setTest( AbstractTest $test ): void
    {
        if ( !$this->test ) $this->test = $test;
    }

}