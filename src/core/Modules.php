<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\base\components\MockManager;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\modules\{ AbstractTest, AbstractLogger, AbstractTransport, AbstractCache };

/**
 * Class Modules
 * Содержит модули, используемые в клиенте.
 *
 * @package src/core
 */
class Modules
{
    /** @var AbstractClient $client Клиент, использующий модули */
    protected AbstractClient $client;


    /** @var Container $container Контейнер для хранения зависимостей */
    protected Container $container;

    /** @var AbstractTransport $transport Интерфейс для отправки запросов к API */
    protected AbstractTransport $transport;



    /** @var null|MockManager $mockManager Обект для реализации моков ответов API */
    protected ?MockManager $mockManager = null;

    /** @var null|AbstractCache $cache Обект для реализации кэширования */
    protected ?AbstractCache $cache;


    /** @var null|AbstractTest $test Обект для реализации тестирования API */
    protected ?AbstractTest $test = null;


    /** @var null|AbstractLogger $logger Обект для реализации логирования запросов и ответов API */
    protected ?AbstractLogger $logger = null;



    /**
     * Modules constructor.
     *
     * @param AbstractClient $client
     * @param Container $container
     *
     * @throws Exception
     */
    public function __construct( AbstractClient $client, Container $container )
    {
        $this->client = $client;

        $this->container = $container;

        $this->transport = $this->getContainer()->get( ClientInterface::TRANSPORT );

        $this->cache = $this->getContainer()->get( ClientInterface::CACHE );

        $this->test = $this->getContainer()->get( ClientInterface::TEST );

        $this->mockManager = $this->constructMockManager();
    }

    /**
     * Создает мок-объект для тестирования.
     *
     * @return ?MockManager
     *
     * @throws Exception
     */
    private function constructMockManager(): ?MockManager
    {
        $mockClass = $this->getContainer()->getClassRegistry( ClientInterface::MOCK );

        $mock = null;

        if ( $mockClass )
        {
            $mockList = $this->client->getConfig()->getMockList();

            $mock = new $mockClass( $mockList );

            if ( !$mock instanceof MockManager )
            {
                throw new Exception( 'Mock class not found or not instance of Mock' );
            }
        }

        return $mock;
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
    public function getMockManager(): ?MockManager
    {
        return $this->mockManager;
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