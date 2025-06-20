<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\base\interfaces\CacheInterface;

/**
 * Класс Cache
 *
 * Родительский класс для пользовательских реализаций класса содержащего логику кэширования данных.
 *
 * @package src/base
 */
abstract class BaseCache implements CacheInterface
{
    /** @var array $data Массив для хранения данных. */
    protected array $data = [];



    /**
     * Конструктор класса Cache.
     *
     * @param BaseAccount $account
     * @param array $data
     */
    public function __construct(BaseAccount $account, array $data= [] )
    {
        if ( !empty( $data ) )
        {
            $this->data = $data;

            $this->setData( $account, $this->data );
        }
    }

    /**
     * Записывает данные в кэш для указанного аккаунта.
     *
     * @param BaseAccount $account
     * @param array $data
     *
     * @return bool
     */
    abstract public function setData(BaseAccount $account, array $data ): bool;

    /**
     * Получает данные из кэша для указанного аккаунта.
     *
     * @param BaseAccount $account
     *
     * @return ?array
     */
    abstract public function getData( BaseAccount $account ): ?array;
}