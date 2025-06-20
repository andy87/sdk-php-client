<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\BaseTest;
use andy87\sdk\client\base\BaseCache;
use andy87\sdk\client\base\BaseSchema;
use andy87\sdk\client\base\BaseAccount;
use andy87\sdk\client\base\BaseOperator;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\TestInterface;
use andy87\sdk\client\base\interfaces\CacheInterface;
use andy87\sdk\client\base\interfaces\SchemaInterface;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\LoggerInterface;
use andy87\sdk\client\base\interfaces\AccountInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\base\interfaces\OperatorInterface;
use andy87\sdk\client\base\interfaces\ResponseInterface;

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
        TestInterface::class => BaseTest::class,
        CacheInterface::class => BaseCache::class,
        LoggerInterface::class => Logger::class,
        SchemaInterface::class => BaseSchema::class,
        ClientInterface::class => SdkClient::class,
        AccountInterface::class => BaseAccount::class,
        RequestInterface::class => Request::class,
        ResponseInterface::class => Response::class,
        OperatorInterface::class => BaseOperator::class,
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