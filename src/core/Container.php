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
 * Контейнер для хранения зависимостей, таких как запросы, ответы, операторы, логгеры и кэш.
 *
 * @package andy87\sdk\client\base
 */
class Container implements ContainerInterface
{
    /**
     * @var array $mapping Массив для хранения классов и их соответствующих ID
     */
    public const DEFAULT_CLASS_LIST = [
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



    /**
     * @var array $classList Массив для хранения классов и их соответствующих объектов.
     * Ключ - это ID, значение - это имя класса или вызываемый объект.
     */
    public array $classList = [];



    /**
     * Конструктор
     *
     * @param array $classList
     */
    public function __construct( array $classList = [] )
    {
        $this->classList = array_merge( static::DEFAULT_CLASS_LIST, $classList );
    }

    /**
     * Получает объект по заданному ID из контейнера.
     * Если объект не существует, он будет создан на основе класса или вызываемого объекта.
     *
     * @param string $id
     *
     * @return object
     *
     * @throws Exception
     */
    public function get(string $id): object
    {
        $object = $this->classList[$id] ?? null;

        if (is_string($object))
        {
            if (class_exists($object))
            {
                $this->classList[$id] = new $object();;

            } else {

                throw new Exception("String '$object' by ID: '$id' does not exists class.");
            }

        } else if (is_callable($object)) {

            $this->classList[$id] = $object();
        }

        return $this->classList[$id];
    }

    /**
     * Проверяет, существует ли объект в контейнере по заданному ID.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->classList[$id]) && is_object($this->classList[$id] ?? null);
    }
}