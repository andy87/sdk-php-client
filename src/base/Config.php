<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Port;

/**
 * BКонфигурация базового клиента API.
 *
 * @package src\base
 */
abstract class Config
{
    /** @var string $port Базовый Port для HTTP запросов к API (http\https) */
    public string $port = Port::HTTPS;


    /** @var string $host Базовый URI API, (например: https://api.example.com") */
    public string $host;


    /** @var ?string $prefix префикс запросов (например: "v1", "api", "api/v1") */
    public ?string $prefix = null;


    /** @var array $headers Заголовки используемые всеми запросами */
    public array $headers = [];

    public string $classRequest = Request::class;
    public string $classResponse = Response::class;
    public string $classOperator = Operator::class;

    public string $classLogger;


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
