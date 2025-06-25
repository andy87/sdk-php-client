<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\core\transport\Response;

/**
 * Интерфейс для классов, которые реализуют логику моков.
 *
 * @package src/base/interfaces
 */
interface MockInterface
{
    public const BREAKPOINT_REQUEST = 'request';
    public const BREAKPOINT_RESPONSE = 'response';


    /**
     * Возвращает ответ API метода или сервера.
     *
     * @return Schema|Response
     */
    public function getData(): Schema|Response;
}