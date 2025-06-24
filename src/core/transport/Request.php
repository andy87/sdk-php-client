<?php

namespace andy87\sdk\client\core\transport;

use Exception;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс Request
 *
 * Содержет данные запроса и промпт, используемые для отправки запроса к API.
 * Реализует интерфейс RequestInterface.
 *
 * @package src/core/transport
 */
class Request implements RequestInterface
{
    protected AbstractClient $client;

    protected Prompt $prompt;

    protected Query $query;


    /**
     * Конструктор класса Request.
     *
     * @param AbstractClient $client
     * @param Prompt $prompt
     *
     * @throws Exception
     */
    public function __construct( AbstractClient $client, Prompt $prompt )
    {
        $this->client = $client;

        $this->prompt = $prompt;

        $this->setupQuery();
    }

    /**
     * Initializes the query based on the provided prompt.
     *
     * @throws Exception
     */
    public function setupQuery(): void
    {
        $method = $this->prompt->getMethod();
        $data = $this->prompt->release();
        $headers = $this->getHeaders();

        $endpoint = $this->client->constructEndpoint( $this->prompt );

        $this->query = $this->constructQuery( $method, $endpoint, $data, $headers);
    }

    /**
     * Конструктор объекта Query.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     *
     * @return Query
     *
     * @throws Exception
     */
    private function constructQuery( string $method, string $endpoint, array $data, array $headers ): Query
    {
        $queryClass = $this->client->modules->container->getClassRegistry( Query::class );

        return new $queryClass( $method, $endpoint, $data, $headers );
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
     * {@inheritdoc}
     *
     * @return Query
     */
    public function getQuery(): Query
    {
        return $this->query;

    }
}