<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\AbstractAccount;
use andy87\sdk\client\core\transport\Response;

/**
 * Interface CacheInterface
 * Represents a cache interface for storing and retrieving data.
 *
 * @package src/base/interfaces
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
    public const TEST = 'test';



    /**
     * Собирает конечный URL для API запроса.
     *
     * @param string|int $path
     *
     * @return string
     */
    public function constructEndpoint( string|int $path ): string;

    /**
     * Авторизация в API партнера
     *
     * @param AbstractAccount $account
     *
     * @return bool
     */
    public function authorization(AbstractAccount $account ): bool;

    /**
     * Проверка отсутствия ошибок валидности токена
     *
     * @param Response $response
     *
     * @return bool
     */
    public function isTokenInvalid( Response $response ): bool;

    /**
     * Обработчик ошибок
     *
     * @param string|array $data
     */
    public function errorHandler( string|array $data ): void;
}