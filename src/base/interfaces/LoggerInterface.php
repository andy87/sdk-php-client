<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Interface CacheInterface
 * Represents a cache interface for storing and retrieving data.
 *
 * @package src/base/interfaces
 */
interface LoggerInterface
{
    public function errorHandler( string|array $data ): void;
}