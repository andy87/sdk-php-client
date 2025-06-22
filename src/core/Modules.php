<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\base\modules\AbstractCache;
use andy87\sdk\client\base\modules\AbstractLogger;
use andy87\sdk\client\base\modules\AbstractOperator;
use andy87\sdk\client\base\interfaces\ClientInterface;

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
     * @var AbstractOperator $operator Интерфейс для отправки запросов к API
     */
    public AbstractOperator $operator;

    /**
     * @var ?AbstractCache $cache Интерфейс для работы с кэшем
     */
    public ?AbstractCache $cache;

    /**
     * @var ?Test $test Интерфейс для тестирования
     */
    public ?Test $test = null;

    /**
     * @var ?AbstractLogger $test Интерфейс для тестирования
     */
    public ?AbstractLogger $logger = null;



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

        $this->operator = $this->container->get(ClientInterface::OPERATOR);

        $this->cache = $this->container->get(ClientInterface::CACHE);

        $this->test = $this->container->get(ClientInterface::TEST);
    }
}