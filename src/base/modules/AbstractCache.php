<?php

namespace andy87\sdk\client\base\modules;

use andy87\sdk\client\base\components\Account;

/**
 * Класс Cache
 *
 * Родительский класс для пользовательских реализаций класса содержащего логику кэширования данных.
 *
 * @package src/base
 */
abstract class AbstractCache
{
    /** @var array $data Массив для хранения данных. */
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
    abstract public function setData( Account $account, mixed $data ): bool;

    /**
     * Получает данные из кэша для указанного аккаунта.
     *
     * @param Account $account
     *
     * @return mixed
     */
    abstract public function getData( Account $account ): mixed;
}