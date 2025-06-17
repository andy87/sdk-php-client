<?php

namespace andy87\sdk\client\base;

use Exception;
use andy87\sdk\client\core\Modules;
use andy87\sdk\client\core\Response;
use andy87\sdk\client\core\Container;
use andy87\sdk\client\base\interfaces\TestInterface;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;

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

    /** @var Modules $modules Модули клиента */
    public Modules $modules;



    /**
     * Конструктор
     *
     * @param Config $config
     *
     * @throws Exception
     */
    public function __construct( Config $config )
    {
        $this->config = $this->prepareConfig( $config );

        $this->setupModules( $this->config );
    }

    /**
     * Кастомизация конфигурации клиента, если необходимо
     *
     * @param Config $config
     *
     * @return Config
     */
    protected function prepareConfig( Config $config ): Config
    {
        return $config;
    }

    /**
     * Устанавливает модули для работы клиента.
     *
     * @param Config $config
     *
     * @return void
     *
     * @throws Exception
     */
    public function setupModules( Config $config ): void
    {
        $container = $this->constructContainer( $config );

        $this->modules = new Modules( $container );
    }

    /**
     * Конструирует контейнер для хранения классов, используемых в клиенте.
     *
     * @param Config $config
     *
     * @return Container
     *
     * @throws Exception
     */
    protected function constructContainer( Config $config ): Container
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
     * Метод для запуска тестов в клиенте.
     */
    public function test(): void
    {
        if ($this->modules->test instanceof TestInterface )
        {
            $this->modules->test->run();
        }
    }



    /**
     * Метод для добавления данных требуемых для авторизации.
     */
    public function prepareAuthentication( RequestInterface $request ): void
    {
        // Логика установки данных для выполнения запросов требующих авторизации
    }

    /**
     * Метод для авторизации пользователя.
     *
     * @param Account $account
     *
     * @return bool
     */
    public function authorization( Account $account ): bool
    {
        // Логика авторизации
        return true;
    }

    public function errorHandler( string|array $data ): void
    {
        // Логика обработки ошибок
    }

    /**
     * Метод проверяет есть ли ошибки в ответе, решаемая повторной авторизацией
     *
     * @param Response $response
     *
     * @return bool
     */
    public function isAuthorizationError( Response $response ): bool
    {
        // Логика проверки ошибок авторизации
        return false;
    }
}