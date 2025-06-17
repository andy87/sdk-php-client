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
    protected string $method;

    protected string $endpoint;

    protected array $headers = [];

    protected mixed $data = null;

    protected ?array $params = null;


    /**
     * Конструктор класса Query
     *
     * @param string $method Метод HTTP запроса (GET, POST, PUT, DELETE и т.д.)
     * @param string $endpoint URL-адрес конечной точки API, к которой будет отправлен запрос
     */
    public function __construct( string $method, string $endpoint)
    {
        $this->method = $method;

        $this->endpoint = $endpoint;
    }

    /**
     * Возвращает метод HTTP запроса
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Возвращает URL-адрес конечной точки API
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Возвращает заголовки запроса
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Устанавливает заголовки запроса
     *
     * @param array $headers Ассоциативный массив заголовков
     */
    public function setHeaders( array $headers ): void
    {
        $this->headers = $headers;
    }

    /**
     * Возвращает данные запроса
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Устанавливает данные запроса
     *
     * @param mixed $data Данные запроса (могут быть в формате JSON, массиве и т.д.)
     */
    public function setData( mixed $data ): void
    {
        $this->data = $data;
    }

    /**
     * Возвращает параметры запроса
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Устанавливает параметры запроса
     *
     * @param array $params Ассоциативный массив параметров запроса
     */
    public function setParams( array $params ): void
    {
        $this->params = $params;
    }
}