<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\core\Container;
use Psr\Container\ContainerInterface;
use andy87\sdk\client\base\interfaces\ClientInterface;

/**
 * Класс Client
 *  Содержет методы для отправки запросов к API и обработки ответов.
 *
 * @package src\base
 */
abstract class Client implements ClientInterface
{
    protected const CONTAINER_CLASS = Container::class;

    /** @var Config $config Конфигурация клиента */
    protected Config $config;

    /** @var ?ContainerInterface $container Контейнер для хранения классов, используемых в клиенте */
    public ?ContainerInterface $container = null;



    /**
     * Конструктор
     *
     * @param Config $config
     */
    public function __construct( Config $config, array $container = [] )
    {
        $this->config = $config;

        $this->setupContainer($container);

        $this->setupOperator();

        $this->setupCache();
    }

    /**
     * Устанавливает контейнер для хранения классов, используемых в клиенте.
     *
     * @param array $container
     *
     * @return void
     */
    private function setupContainer( array $container ): void
    {
        if ( $this->container === null )
        {
            $className = $this->config->getContainerClass() ?? self::CONTAINER_CLASS;

            if ( class_exists( $className ) )
            {
                $this->container = new $className();
            } else {
                $this->errorHandler([
                    'method' => __METHOD__,
                    'message' => 'Container class not found. Please set the container class in the config.',
                    'class' => self::CONTAINER_CLASS,
                ]);
            }
        }
    }

    /**
     * Устанавливает оператор для отправки запросов.
     *
     * @return void
     */
    private function setupOperator(): void
    {
        $className = $this->container[self::OPERATOR] ?? null;

        if ($className)
        {
            $this->operator = new $className( $this, $this->config );

        } else {
            $this->errorHandler([
                'method' => __METHOD__,
                'message' => 'Operator class not found. Please set the operator class in the container.',
                'class' => self::OPERATOR,
            ]);
        }
    }

    private function setupCache(): void
    {
        $className = $this->container[self::CACHE] ?? null;

        if ( $this->config->classCache )
        {
            $className = $this->config->classCache;

            $this->cache = new $className( $this );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function constructEndpoint( string|int $path ): string
    {
        return $this->config->getBaseUri() . '/' . $path;
    }

    public function test()
    {
        $this->test->run();
    }

    abstract public function authorization(): bool;

    abstract public function errorHandler( string|array $data ): bool;
}