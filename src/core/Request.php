<?php

namespace andy87\sdk\client\core;

use andy87\sdk\client\base\Client;
use andy87\sdk\client\base\Prompt;
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
            $this->prompt->getMethod(),
            $this->client->constructEndpoint(
                $this->prompt->getPath()
            )
        );

        $this->query->setHeaders( $this->getHeaders() );

        $this->query->setData( $this->prompt->release() );
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

    public function getPrompt(): Prompt
    {
        return $this->prompt;
    }

    public function getQuery(): Query
    {
        return $this->query;
    }
}