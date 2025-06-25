<?php

namespace andy87\sdk\client\base\modules;

use Exception;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс Operator
 *
 * Отправляет запросы к API.
 *
 * @package src/base
 */
abstract class AbstractTransport
{
    /** @var AbstractClient $client Экземпляр клиента, используемый для отправки запросов к API. */
    protected AbstractClient $client;



    /**
     * Конструктор класса Operator
     * Инициализирует экземпляр клиента, который будет использоваться для отправки запросов к API.
     *
     * @param AbstractClient $client
     */
    public function __construct( AbstractClient $client )
    {
        $this->client = $client;
    }

    /**
     * @param RequestInterface $request
     *
     * @return Response
     */
    abstract public function sendRequest( RequestInterface $request ): Response;

    /**
     * Обработчик ошибок
     *
     * @param string $method
     * @param int $line
     * @param string|array|Exception $data
     *
     * @return void
     *
     * @throws Exception
     */
    protected function errorHandler( string $method, int $line, string|array|Exception $data ): void
    {
        if ( $data instanceof Exception )
        {
            $error = [
                'message' => $data->getMessage(),
                'position' => $data->getFile() . ':' . $data->getLine(),
                'code' => $data->getCode(),
                'trace' => $data->getTraceAsString()
            ];

        } else {

            $error = $data;
        }

        $this->client->errorHandler( $method, $line, $error );
    }
}