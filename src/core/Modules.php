<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\base\interfaces\TestInterface;
use andy87\sdk\client\base\interfaces\CacheInterface;
use andy87\sdk\client\base\interfaces\OperatorInterface;

/**
 * Class Modules
 * Содержит модули, используемые в клиенте.
 *
 * @package src/core
 */
class Modules
{
    /**
     * @var Container $container Контейнер для хранения зависимостей
     */
    public Container $container;

    /**
     * @var OperatorInterface $operator Интерфейс для отправки запросов к API
     */
    public OperatorInterface $operator;

    /**
     * @var CacheInterface|null $cache Интерфейс для работы с кэшем
     */
    public ?CacheInterface $cache;

    /**
     * @var TestInterface|null $test Интерфейс для тестирования
     */
    public ?TestInterface $test;


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

        $this->operator = $this->container->get(OperatorInterface::class);

        $this->cache = $this->container->get(CacheInterface::class);

        $this->test = $this->container->get(TestInterface::class);
    }
}