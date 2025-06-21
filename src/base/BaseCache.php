<?php

namespace andy87\sdk\client\base;

/**
 * Класс Cache
 *
 * Родительский класс для пользовательских реализаций класса содержащего логику кэширования данных.
 *
 * @package src/base
 */
abstract class BaseCache
{
    /** @var array $data Массив для хранения данных. */
    protected array $data = [];



    /**
     * Конструктор класса Cache.
     *
     * @param AbstractAccount $account
     * @param array $data
     */
    public function __construct(AbstractAccount $account, array $data= [] )
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
     * @param AbstractAccount $account
     * @param array $data
     *
     * @return bool
     */
    abstract public function setData(AbstractAccount $account, array $data ): bool;

    /**
     * Получает данные из кэша для указанного аккаунта.
     *
     * @param AbstractAccount $account
     *
     * @return ?array
     */
    abstract public function getData(AbstractAccount $account ): ?array;
}