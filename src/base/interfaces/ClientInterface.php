<?php

namespace andy87\sdk\client\base\interfaces;

/**
 * Interface CacheInterface
 * Represents a cache interface for storing and retrieving data.
 *
 * @package andy87\sdk\client\base\interfaces
 */
interface ClientInterface
{
    public const REQUEST = 'request';
    public const RESPONSE = 'response';
    public const SCHEMA = 'schema';
    public const OPERATOR = 'operator';
    public const CACHE = 'cache';
    public const CLIENT = 'client';
    public const LOGGER = 'logger';
    public const ACCOUNT = 'account';
    public const OPERATOR = 'operator';



    /**
     * Собирает конечный URL для API запроса.
     *
     * @param string|int $path
     *
     * @return string
     */
    public function constructEndpoint( string|int $path ): string;

    public function authorization();

    public function errorHandler( string|array $data );
}