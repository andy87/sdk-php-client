<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\base\Test;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\Cache;
use andy87\sdk\client\base\Schema;
use andy87\sdk\client\base\Account;
use andy87\sdk\client\base\Operator;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use andy87\sdk\client\base\interfaces\TestInterface;
use andy87\sdk\client\base\interfaces\CacheInterface;
use andy87\sdk\client\base\interfaces\SchemaInterface;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\LoggerInterface;
use andy87\sdk\client\base\interfaces\AccountInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\base\interfaces\OperatorInterface;

/**
 * Class Container
 *
 * Настройка используемых классов и интерфейсов в контейнере.
 *
 * @package andy87\sdk\client\core
 */
class ClassRegistry
{
    /**
     * @var array $mapping Массив для хранения классов и их соответствующих ID
     */
    public const DEFAULT = [
        TestInterface::class => Test::class,
        CacheInterface::class => Cache::class,
        LoggerInterface::class => Logger::class,
        SchemaInterface::class => Schema::class,
        ClientInterface::class => SdkClient::class,
        AccountInterface::class => Account::class,
        RequestInterface::class => Request::class,
        ResponseInterface::class => Response::class,
        OperatorInterface::class => Operator::class,
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
     * @return object|null
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