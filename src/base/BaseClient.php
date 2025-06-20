<?php

namespace andy87\sdk\client\base;

use Exception;
use andy87\sdk\client\core\Modules;
use andy87\sdk\client\core\Container;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\TestInterface;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс Client
 *  Базовый класс клиента содержащий методы для работы клиента.
 *
 * @package src/base
 */
abstract class BaseClient implements ClientInterface
{
    protected const CONTAINER_CLASS = Container::class;



    /** @var BaseConfig $config Конфигурация клиента */
    protected BaseConfig $config;

    /** @var Modules $modules Модули клиента */
    public Modules $modules;



    /**
     * Конструктор
     *
     * @param BaseConfig $config
     *
     * @throws Exception
     */
    public function __construct( BaseConfig $config )
    {
        $this->config = $this->prepareConfig( $config );

        $this->setupModules( $this->config );
    }

    /**
     * Кастомизация конфигурации клиента, если необходимо
     *
     * @param BaseConfig $config
     *
     * @return BaseConfig
     */
    protected function prepareConfig(BaseConfig $config ): BaseConfig
    {
        return $config;
    }

    /**
     * Устанавливает модули для работы клиента.
     *
     * @param BaseConfig $config
     *
     * @return void
     *
     * @throws Exception
     */
    public function setupModules(BaseConfig $config ): void
    {
        $container = $this->constructContainer( $config );

        $this->modules = new Modules( $container );
    }

    /**
     * Конструирует контейнер для хранения классов, используемых в клиенте.
     *
     * @param BaseConfig $config
     *
     * @return Container
     *
     * @throws Exception
     */
    protected function constructContainer(BaseConfig $config ): Container
    {
        $containerClass = static::CONTAINER_CLASS;

        $container = new $containerClass( $config->classes );

        if ( $container instanceof Container )
        {
            return $container;
        }

        throw new Exception( 'Container not found' );
    }

    /**
     * {@inheritDoc}
     */
    public function constructEndpoint( string|int $path ): string
    {
        return $this->config->getBaseUri() . '/' . $path;
    }

    /**
     * Запуск тестов в клиенте.
     */
    public function test(): void
    {
        if ($this->modules->test instanceof TestInterface )
        {
            $this->modules->test->run();
        }
    }

    /**
     * Добавление данных требуемых для авторизации.
     */
    public function prepareAuthentication( RequestInterface $request ): void
    {
        // Логика установки данных для выполнения запросов требующих авторизации
    }

    /**
     * Авторизация пользователя.
     *
     * @param BaseAccount $account
     *
     * @return bool
     */
    abstract public function authorization(BaseAccount $account ): bool;

    /**
     * Проверка есть ли ошибки в ответе, решаемые повторной авторизацией
     *
     * @param Response $response
     *
     * @return bool
     */
    abstract public function isTokenInvalid( Response $response ): bool;

    /**
     * Обработчик ошибок клиента.
     *
     * @param string|array $data
     *
     * @return void
     */
    abstract public function errorHandler( string|array $data ): void;

}