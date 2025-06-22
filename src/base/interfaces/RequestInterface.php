<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\core\transport\Query;

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

    /**
     * @return Query
     */
    public  function getQuery(): Query;
}