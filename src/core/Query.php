<?php

namespace andy87\sdk\client\core;

/**
 * Класс Query
 *
 * Содержит информацию о HTTP запросе
 *
 * @package src/base
 */
class Query
{
    public string $method;

    public string $endpoint;

    public array $headers = [];

    public ?array $body = null;


    /**
     * Конструктор класса Query
     *
     * @param string $method Метод HTTP запроса (GET, POST, PUT, DELETE и т.д.)
     * @param string $endpoint URL-адрес конечной точки API, к которой будет отправлен запрос
     * @param array $data Дополнительные данные запроса
     */
    public function __construct( string $method, string $endpoint, array $data = [] )
    {
        $this->method = $method;

        $this->endpoint = $endpoint;

        foreach ($data as $key => $value)
        {
            if (property_exists($this, $key))
            {
                $this->{$key} = $value;
            }
        }
    }
}