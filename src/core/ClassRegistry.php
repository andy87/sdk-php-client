<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\components\Account;
use andy87\sdk\client\base\modules\AbstractTest;
use andy87\sdk\client\base\modules\AbstractCache;
use andy87\sdk\client\base\modules\AbstractLogger;
use andy87\sdk\client\base\modules\AbstractTransport;
use andy87\sdk\client\base\interfaces\ClientInterface;

/**
 * Class Container
 *
 * Настройка используемых классов и интерфейсов в контейнере.
 *
 * @package src/core
 */
class ClassRegistry
{
    /**
     * @var array Массив для хранения классов и их соответствующих ID
     */
    public const MAP = [
        ClientInterface::SCHEMA => Schema::class,
        ClientInterface::CLIENT => SdkClient::class,
        ClientInterface::REQUEST => Request::class,
        ClientInterface::RESPONSE => Response::class,
        ClientInterface::OPERATOR => AbstractTransport::class,
        ClientInterface::CACHE => AbstractCache::class,
        ClientInterface::TEST => AbstractTest::class,
        ClientInterface::ACCOUNT => Account::class,
        ClientInterface::LOGGER => AbstractLogger::class,
    ];



    /**
     * @var array $map Массив для хранения классов и их соответствующих ID.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    private array $map;



    /**
     * Конструктор
     *
     * @param array|null $map Массив для хранения классов и их соответствующих ID.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    public function __construct( ?array $map = null )
    {
        $this->map = $map ?? static::MAP;
    }

    /**
     * Получает объект по ID из контейнера.
     *
     * @param string $id
     *
     * @return ?object
     *
     * @throws Exception
     */
    public function getClass( string $id ): ?string
    {
        return $this->map[$id] ?? null;
    }

    /**
     * Получает объект по ID, если он существует в контейнере.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has( string $id ): bool
    {
        return isset($this->map[$id]);
    }
}