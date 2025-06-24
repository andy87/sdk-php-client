<?php

namespace andy87\sdk\client\base\components;

use andy87\sdk\client\helpers\Port;
use andy87\sdk\client\core\ClassRegistry;

/**
 * BКонфигурация базового клиента API.
 *
 * @package src/base
 */
abstract class Config
{
    /** @var string $protocol Базовый Port для HTTP запросов к API (http\https) */
    protected string $protocol = Port::HTTPS;


    /** @var string $host Базовый URI API, (например: api.example.com") */
    protected string $host;


    /** @var null|string $prefix префикс запросов (например: "v1", "api", "api/v1") */
    protected ?string $prefix = null;


    /** @var array $headers Заголовки используемые всеми запросами */
    protected array $headers = [];


    /** @var Account $account */
    protected Account $account;

    /** @var array $registryOverrides Контейнер для переназначения используемых классов */
    protected array $registryOverrides = [];



    /** Конструктор класса Config.
     *
     * @param Account $account Аккаунт, связанный с конфигурацией.
     * @param array $classRegistry Список конфигурации контейнера
     */
    public function __construct( Account $account, array $classRegistry = [] )
    {
        $this->account = $account;

        $this->registryOverrides = array_merge(ClassRegistry::MAP, $classRegistry );
    }

    /**
     * Получение аккаунта.
     *
     * @return Account Аккаунт, связанный с конфигурацией.
     */
    public function getAccount(): Account
    {
        return $this->account;
    }

    /**
     * Возвращает протокол, используемый для запросов к API.
     *
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * Возвращает хост, используемый для запросов к API.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Возвращает префикс, используемый для запросов к API.
     *
     * @return ?string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Возвращает массив заголовков, используемых для запросов к API.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Возвращает массив классов, используемых в клиенте.
     *
     * @return array
     */
    public function getRegistryOverrides(): array
    {
        return $this->registryOverrides;
    }
}