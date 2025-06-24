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
    /** @var string $port Базовый Port для HTTP запросов к API (http\https) */
    public string $port = Port::HTTPS;


    /** @var string $host Базовый URI API, (например: api.example.com") */
    public string $host;


    /** @var ?string $prefix префикс запросов (например: "v1", "api", "api/v1") */
    public ?string $prefix = null;


    /** @var array $headers Заголовки используемые всеми запросами */
    public array $headers = [];


    /** @var Account $account */
    public Account $account;

    /** @var array $classes Контейнер для переназначения используемых классов */
    public array $classes = [];



    /** Конструктор класса Config.
     *
     * @param Account $account Аккаунт, связанный с конфигурацией.
     * @param array $classes Список конфигурации контейнера
     */
    public function __construct( Account $account, array $classes = ClassRegistry::MAP )
    {
        $this->account = $account;

        $this->classes = $classes;
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
     * Получение полного базового URI.
     *
     * @return string Полный базовый URI, включая префикс, если он задан.
     */
    public function getBaseUri(): string
    {
        return "$this->port://$this->host";
    }
}