<?php

namespace andy87\sdk\client\core\transport;

use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\helpers\ContentType;
use andy87\sdk\client\helpers\MethodRegistry;

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
     * Проверяет, является ли метод HTTP запроса указанным методом
     *
     * @param string|string<MethodRegistry> $method Метод HTTP запроса (например, 'GET', 'POST', 'PUT', 'DELETE')
     *
     * @return bool Возвращает true, если метод совпадает, иначе false
     */
    public function methodIs( string $method ): bool
    {
        return $this->method === $method;
    }

    /**
     * Возвращает URL-адрес конечной точки API
     *
     * @param RequestInterface $request
     *
     * @return string
     */
    public function getEndpoint( RequestInterface $request ): string
    {
        $url = $this->endpoint;

        $url = rtrim($url, '?&/');

        if ( $this->isApplyQueryDataToUrl( $request ) )
        {
            $url = $this->applyDataToUrl( $url, $this->getData() );
        }

        return $url;
    }

    /**
     * Проверяет, нужно ли добавлять данные к URL-адресу конечной точки API
     *
     * @param RequestInterface $request Данные запроса
     *
     * @return bool Возвращает true, если данные нужно добавить к URL, иначе false
     */
    private function isApplyQueryDataToUrl(RequestInterface $request ): bool
    {
        if ( !empty( $request->getQuery()->getData() ) )
        {
            if ( $this->methodIs( MethodRegistry::GET ) )
            {
                return true;
            }

            if ( $request->getPrompt()::APPLY_QUERY_TO_URL )
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Добавляет к переданному URL-адресу конечной точки API данные
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    private function applyDataToUrl( string $url, array $data ): string
    {
        if ( !empty($data) )
        {
            $url .= ( str_contains($url, '?') ? '&' : '?' );
            $url .= http_build_query($data);
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