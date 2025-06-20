<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\base\interfaces\LoggerInterface;

/**
 * Class Logger
 * Implements the LoggerInterface to provide logging functionality.
 *
 * @package src/core
 */
abstract class BaseLogger implements LoggerInterface
{
    abstract public function error( string|array $data ): void;
}