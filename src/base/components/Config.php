<?php

namespace andy87\sdk\client\base\components;

use andy87\sdk\client\core\ClassRegistry;
use andy87\sdk\client\core\transport\Url;

/**
 * BКонфигурация базового клиента API.
 *
 * @package src/base
 */
abstract class Config
{
    public Url $url;


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
        return $this->url->getFullPath();
    }
}