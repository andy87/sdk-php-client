<?php

namespace andy87\sdk\client\base\modules;

use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\MockInterface;

/**
 * Class AbstractMock
 *
 * @package src/base/modules
 */
abstract class AbstractMock implements MockInterface
{
    /**
     * Тип мока, который определяет точку останова.
     * 'request' или 'response':
     *  - self::BREAKPOINT_REQUEST - возвращает Schema.
     *  - self::BREAKPOINT_RESPONSE - возвращает Response.
     *
     * @var string
     */
    protected string $type;



    /**
     * Проверяет, является ли тип мока указанным типом.
     *
     * @param string $type
     *
     * @return bool
     */
    public function typeIs( string $type ): bool
    {
        return $this->type === $type;
    }

    /**
     * Возвращает ответ API метода или сервера.
     *
     * @return Schema|Response
     */
    abstract public function getData(): Schema|Response;
}