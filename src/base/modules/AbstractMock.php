<?php

namespace andy87\sdk\client\base\modules;

use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\base\interfaces\MockInterface;
use andy87\sdk\client\core\transport\Response;

/**
 * Class AbstractMock
 *
 * @package src/base/modules
 */
abstract class AbstractMock implements MockInterface
{
    public const BREAKPOINT = self::BREAKPOINT_REQUEST;


    /**
     * Возвращает ответ API метода или сервера.
     *
     * @return Schema|Response
     */
    abstract public function getData(): Schema|Response;
}