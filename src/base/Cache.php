<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\base\interfaces\CacheInterface;

/**
 * Класс Cache
 *
 * Base class for cache implementations.
 * Provides a structure for setting and getting cached data.
 *
 * @package src\base
 */
abstract class Cache implements CacheInterface
{
    protected array $data = [];

    public function __construct( array $data= [] )
    {
        if ( !empty( $data ) )
        {
            $this->setData( $data );
        }
    }

    abstract public function setData( array $data ): void;

    abstract public function getData(): array;
}