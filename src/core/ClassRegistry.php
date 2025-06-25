<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\transports\CurlTransport;
use andy87\sdk\client\base\components\MockManager;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\components\{ Schema, Account };
use andy87\sdk\client\core\transport\{Query, Request, Response};
use andy87\sdk\client\base\modules\{ AbstractTest, AbstractCache, AbstractLogger };

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
        ClientInterface::ACCOUNT => Account::class,
        ClientInterface::TRANSPORT => CurlTransport::class,
        ClientInterface::SCHEMA => Schema::class,
        ClientInterface::CLIENT => SdkClient::class,
        ClientInterface::QUERY => Query::class,
        ClientInterface::REQUEST => Request::class,
        ClientInterface::RESPONSE => Response::class,
        ClientInterface::MOCK => MockManager::class,
        ClientInterface::CACHE => null,
        ClientInterface::TEST => null,
        ClientInterface::LOGGER => null,
    ];



    /**
     * @var array $map Массив для хранения классов и их соответствующих ID.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    private array $map;



    /**
     * Конструктор
     *
     * @param array $map Массив для хранения классов и их соответствующих ID.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    public function __construct( array $map = [] )
    {
        $this->map = array_merge(static::MAP, $map );
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

    /**
     * Получает все классы, зарегистрированные в контейнере.
     *
     * @return array|string[]
     */
    public function getMap()
    {
        return $this->map;
    }
}