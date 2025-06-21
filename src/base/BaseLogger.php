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
    /**
     * 
     * @param string|array $data
     *
     * @return void
     */
    abstract public function errorHandler( string|array $data ): void;
}