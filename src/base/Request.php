<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\interfaces\RequestInterface;

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
    protected Query $query;

    protected Prompt $prompt;


    protected ?int $statusCode = null;

    protected ?string $content = null;

    protected ?array $result = null;

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
        $query = new Query();

        $query->method = $this->prompt->method;

        $headers = [
            'Content-Type' => $this->prompt->contentType,
        ];

        if ($this->prompt->isPrivate) {
            $headers['Authorization'] = 'Bearer ' . $this->getAuthorizationToken();
        }

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