<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\Account;

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



    /**
     * Собирает конечный URL для API запроса.
     *
     * @param string|int $path
     *
     * @return string
     */
    public function constructEndpoint( string|int $path ): string;

    public function authorization( Account $account ): bool;

    public function errorHandler( string|array $data ): void;
}