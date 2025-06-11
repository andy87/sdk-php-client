<?php

namespace andy87\sdk\client\core;

use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс Test
 *
 * Represents a base class for tests.
 *
 * @package src\base
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
        $this->query = new Query(
            $this->prompt->method,
            $this->client->constructEndpoint($this->prompt->path)
        );

        $headers = [];

        if ($this->prompt->contentType) {
            $headers['Content-Type'] = $this->prompt->contentType;
        }

        if ($this->prompt->isPrivate) {
            $this->client->prepareAuthorization($headers);
        }

        $this->query->headers = $headers;
    }

    public function call()
    {
        $response = new Response();
        $response->request = $this;
        $response->statusCode = 200; // Example status code
        $response->content = 'Response content'; // Example content

        return $response;
    }

    /**
     * Возвращает сценарий запроса.
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->prompt->schema;
    }

    /**
     * Возвращает токен авторизации.
     *
     * @return string
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }
}