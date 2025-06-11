<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Port;
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



    /** Конструктор класса Config.
     *
     * @param Account $account Аккаунт, связанный с конфигурацией.
     */
    public function __construct( AccountInterface $account )
    {
        $this->setAccount($account);
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
     * Установка аккаунта.
     *
     * @param AccountInterface $account Аккаунт, связанный с конфигурацией.
     */
    public function setAccount( AccountInterface $account ): void
    {
        $this->account = $account;
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