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
    /**
     * Массив для хранения данных.
     *
     * @var array $data
     */
    protected array $data = [];



    /**
     * Конструктор класса Cache.
     *
     * @param Account $account
     * @param array $data
     */
    public function __construct( Account $account, array $data= [] )
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
     * @param Account $account
     * @param array $data
     *
     * @return bool
     */
    abstract public function setData( Account $account, array $data ): bool;

    /**
     * Получает данные из кэша для указанного аккаунта.
     *
     * @param Account $account
     *
     * @return ?array
     */
    abstract public function getData( Account $account ): ?array;
}