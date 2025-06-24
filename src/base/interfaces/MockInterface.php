<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\components\Schema;

/**
 * Интерфейс для классов, которые реализуют логику моков.
 *
 * @package src/base/interfaces
 */
interface MockInterface
{
    public function response(): ?Schema;
}