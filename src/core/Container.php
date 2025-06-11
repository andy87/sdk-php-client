<?php

namespace andy87\sdk\client\core;

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
    protected array $mapping = [
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
     * Конструктор
     *
     * @var array $mapping
     */
    public function __construct( array $mapping = [] )
    {
        if (!empty($mapping)) {
            $this->mapping = array_merge( $this->mapping, $mapping );
        }
    }

    public function get(string $id)
    {
        return $this->mapping[$id];
    }

    public function has(string $id)
    {

    }
}