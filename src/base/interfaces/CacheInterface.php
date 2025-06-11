<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Интерфейс CacheInterface
 *
 *
 * @package src/interfaces
 */
interface CacheInterface
{
    public function setData( array $data );

    public function getData();
}