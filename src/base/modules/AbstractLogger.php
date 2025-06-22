<?php

namespace andy87\sdk\client\base\modules;

/**
 * Class Logger
 * Implements the LoggerInterface to provide logging functionality.
 *
 * @package src/core
 */
abstract class AbstractLogger
{
    abstract public function errorHandler( string|array $data ): void;
}