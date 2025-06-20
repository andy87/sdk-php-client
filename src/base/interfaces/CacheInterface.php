<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\BaseAccount;

/**
 * Интерфейс CacheInterface
 *
 *
 * @package src/base/interfaces
 */
interface CacheInterface
{
    public function setData(BaseAccount $account, array $data ): bool;

    public function getData(BaseAccount $account ): ?array;
}