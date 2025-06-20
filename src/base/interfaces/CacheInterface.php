<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\Account;

/**
 * Интерфейс CacheInterface
 *
 *
 * @package src/base/interfaces
 */
interface CacheInterface
{
    public function setData( Account $account, array $data ): bool;

    public function getData( Account $account ): ?array;
}