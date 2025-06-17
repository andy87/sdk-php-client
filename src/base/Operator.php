<?php

namespace andy87\sdk\client\base;

use Exception;
use andy87\sdk\client\base\interfaces\OperatorInterface;

/**
 * Класс Operator
 *
 * Отправляет запросы к API.
 *
 * @package src\base
 */
abstract class Operator implements OperatorInterface
{
    protected Client $client;

    /**
     * @param Client $client
     */
    public function __construct( Client $client )
    {
        $this->client = $client;
    }

    /**
     * Обработчик ошибок
     *
     * @param string|array|Exception $data
     *
     * @return void
     */
    protected function errorHandler( string|array|Exception $data ): void
    {
        if ( $data instanceof Exception )
        {
            $error = [
                'message' => $data->getMessage(),
                'position' => $data->getFile() . ':' . $data->getLine(),
                'code' => $data->getCode(),
                'trace' => $data->getTraceAsString()
            ];

        } elseif ( is_string( $data ) ) {

            $error = [ 'message' => $data  ];

        } else {

            $error = $data;
        }

        $this->client->errorHandler( $error );
    }
}