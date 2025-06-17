<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Интерфейс RequestInterface
 *
 * Определяет методы для настройки запроса к API.
 *
 * @package src/base/interfaces
 */
interface RequestInterface
{
    /**
     * Устанавливает параметры запроса
     */
    public function setupQuery():void;
}