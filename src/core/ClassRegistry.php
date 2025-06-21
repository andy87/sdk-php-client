<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\BaseTest;
use andy87\sdk\client\base\BaseCache;
use andy87\sdk\client\base\BaseSchema;
use andy87\sdk\client\base\BaseOperator;
use andy87\sdk\client\base\AbstractAccount;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;
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
     * @var array $mapping Массив для хранения классов и их соответствующих ID
     */
    public const DEFAULT = [
        ClientInterface::TEST => BaseTest::class,
        ClientInterface::CACHE => BaseCache::class,
        ClientInterface::LOGGER => Logger::class,
        ClientInterface::SCHEMA => BaseSchema::class,
        ClientInterface::CLIENT => SdkClient::class,
        ClientInterface::ACCOUNT => AbstractAccount::class,
        ClientInterface::REQUEST => Request::class,
        ClientInterface::RESPONSE => Response::class,
        ClientInterface::OPERATOR => BaseOperator::class,
    ];

    private array $mapping;



    /**
     * Конструктор
     *
     * @param array|null $mapping Массив для хранения классов и их соответствующих ID.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    public function __construct( ?array $mapping = null )
    {
        $this->mapping = $mapping ?? static::DEFAULT;
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
    public function getClass(string $id): ?string
    {
        return $this->mapping[$id] ?? null;
    }

    /**
     * Получает объект по ID, если он существует в контейнере.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->mapping[$id]);
    }
}