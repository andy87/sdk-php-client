<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\core\transport\Query;

/**
 * Интерфейс для классов, которые реализуют логику настройки HTTP запроса к API.
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
     * Возвращает объект Query, который содержит информацию о запросе.
     *
     * @return Query
     */
    public  function getQuery(): Query;

    /**
     * Возвращает объект Prompt, который содержит информацию о запросе.
     *
     * @return Prompt
     */
    public  function getPrompt(): Prompt;
}