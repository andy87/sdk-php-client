<?php

namespace andy87\sdk\client\core;

use andy87\sdk\client\base\Client;
use andy87\sdk\client\base\Prompt;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс Request
 *
 * Содержет данные запроса и промпт, используемые для отправки запроса к API.
 * Реализует интерфейс RequestInterface.
 *
 * @package src/core
 */
class Request implements RequestInterface
{
    protected Client $client;

    protected Prompt $prompt;

    protected Query $query;



    /**
     * Конструктор класса Request.
     *
     * @param Client $client
     * @param Prompt $prompt
     */
    public function __construct( Client $client, Prompt $prompt )
    {
        $this->client = $client;

        $this->prompt = $prompt;

        $this->setupQuery();
    }

    /**
     * Initializes the query based on the provided prompt.
     */
    public function setupQuery(): void
    {
        $queryClass = $this->client->modules->container->getClassRegistry(Query::class );

        $method = $this->prompt->getMethod();

        $endpoint = $this->client->constructEndpoint( $this->prompt->getPath() );

        $data = $this->prompt->release();

        $headers = $this->getHeaders();

        $this->query = new $queryClass( $method, $endpoint, $data, $headers );
    }

    /**
     * Constructs the headers for the request.
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        $headers = [];

        if ( $contentType = $this->prompt->getContentType()) {
            $headers['Content-Type'] = $contentType;
        }

        return $headers;
    }

    /**
     * Возвращает prompt запроса.
     *
     * @return Prompt
     */
    public function getPrompt(): Prompt
    {
        return $this->prompt;
    }

    /**
     * Возвращает Query запроса.
     *
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }
}