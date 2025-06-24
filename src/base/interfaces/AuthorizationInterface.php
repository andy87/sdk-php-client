<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\core\transport\Query;

/**
 * Интерфейс для классов, которые реализуют логику авторизации.
 *
 * @package src/base/interfaces
 */
interface AuthorizationInterface
{
    public function run( AbstractClient $client, Query $query ): void;
}