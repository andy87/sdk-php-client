<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Interface CacheInterface
 * Represents a cache interface for storing and retrieving data.
 *
 * @package andy87\sdk\client\base\interfaces
 */
interface LoggerInterface
{
    public function write( mixed $data );
}