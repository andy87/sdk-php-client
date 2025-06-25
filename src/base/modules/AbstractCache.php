<?php

namespace andy87\sdk\client\base\modules;

use andy87\avito\client\schema\auth\AccessTokenSchema;
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
     * @param Account $account
     *
     * @return null|AccessTokenSchema
     */
    public function getCacheAccessTokenSchema( Account $account ): ?AccessTokenSchema
    {
        $data = $this->getData( $account );

        if ( empty( $data ) )
        {
            return null;
        }

        /** @var AccessTokenSchema $accessTokenSchema */
        $accessTokenSchema = unserialize($data, [ 'allowed_classes' => [ AccessTokenSchema::class ] ]);

        return $accessTokenSchema;
    }

    /**
     * Записывает данные в кэш для указанного аккаунта.
     *
     * @param Account $account
     * @param string $data
     *
     * @return bool
     */
    abstract public function setData( Account $account, string $data ): bool;

    /**
     * Получает данные из кэша для указанного аккаунта.
     *
     * @param Account $account
     *
     * @return string
     */
    abstract public function getData( Account $account ): string;

}