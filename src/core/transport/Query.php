<?php

namespace andy87\sdk\client\core\transport;

use andy87\sdk\client\helpers\Method;

/**
 * Класс Query
 *
 * Содержит информацию о HTTP запросе
 *
 * @package src/core/transport
 */
class Query
{
    /**
     * Конструктор класса Query
     *
     * @param string $method Метод HTTP запроса (GET, POST, PUT, DELETE и т.д.)
     * @param string $endpoint URL-адрес конечной точки API, к которой будет отправлен запрос
     * @param array $data Данные запроса
     * @param array $headers Заголовки запроса
     * @param array $customParams Дополнительные параметры запроса
     */
    public function __construct(
        private string $method,
        private string $endpoint,
        private array $data = [],
        private array $headers = [],
        private array $customParams = [],
    ){}

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
        $url = $this->endpoint;

        $url = rtrim($url, '?&');

        if ( $this->method === Method::GET )
        {
            $data = $this->getData();

            if ( !empty($data) )
            {
                $url .= ( str_contains($url, '?') ? '&' : '?' );

                $url .= http_build_query($data);
            }
        }

        return $url;
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
     * Возвращает данные запроса
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Возвращает параметры запроса
     *
     * @return array
     */
    public function getCustomParams(): array
    {
        return $this->customParams;
    }

    /**
     * Добавляет пользовательские заголовки к запросу.
     * Если заголовок с таким ключом уже существует, он будет перезаписан.
     *
     * @param array $headers
     */
    public function addCustomHeaders( array $headers ): void
    {
        foreach ( $headers as $key => $value )
        {
            $this->headers[$key] = $value;
        }
    }
}