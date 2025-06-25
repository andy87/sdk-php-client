<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\components\Account;

/**
 * Интерфейс для классов котовые реализуют Client
 *
 * @package src/base/interfaces
 */
interface ClientInterface
{
    public const CONTAINER = 'container';

    public const ACCOUNT = 'account';
    public const CONFIG = 'config';

    public const CLIENT = 'client';

    public const CACHE = 'cache';
    public const LOGGER = 'logger';
    public const TEST = 'test';
    public const MOCK = 'mock';

    public const PROMPT = 'prompt';
    public const SCHEMA = 'schema';

    public const QUERY = 'query';
    public const TRANSPORT = 'transport';
    public const REQUEST = 'request';
    public const RESPONSE = 'response';
    public const URL = 'url';


    /**
     * Собирает конечный URL для API запроса.
     *
     * @param Prompt $prompt
     *
     * @return string
     */
    public function constructEndpoint( Prompt $prompt ): string;

    /**
     * Авторизация в API партнера
     *
     * @param Account $account
     * @param bool $isGetFromCache
     *
     * @return bool
     */
    public function authorization( Account $account, bool $isGetFromCache = true ): bool;

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
     * @param string $method
     * @param int $line
     * @param string|array $data
     */
    public function errorHandler( string $method, int $line, string|array $data ): void;
}