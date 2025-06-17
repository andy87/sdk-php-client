<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Port;
use andy87\sdk\client\core\Container;
use andy87\sdk\client\base\interfaces\AccountInterface;

/**
 * BКонфигурация базового клиента API.
 *
 * @package src\base
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


    /** @var AccountInterface $account */
    public AccountInterface $account;

    /** @var array $classes Контейнер для хранения дополнительных данных */
    public array $classes = [];



    /** Конструктор класса Config.
     *
     * @param Account $account Аккаунт, связанный с конфигурацией.
     * @param array $classes Список конфигурации контейнера
     */
    public function __construct( AccountInterface $account, array $classes = Container::DEFAULT_CLASS_LIST )
    {
        $this->account = $account;

        $this->classes = $classes;
    }

    /**
     * Получение аккаунта.
     *
     * @return Account Аккаунт, связанный с конфигурацией.
     */
    public function getAccount(): AccountInterface
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
        $host = "$this->port://$this->host";

        $prefix = $this->prefix ? trim($this->prefix, '/') : false;

        return ( $prefix ) ? "$host/$prefix" : $host;
    }
}