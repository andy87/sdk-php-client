<?php

namespace andy87\sdk\client;

use andy87\sdk\client\base\{ Client, Prompt, Schema, Request, Response };

/**
 * Класс SdkClient
 *
 * Базовый класс для SDK клиента, который отправляет запросы к API и обрабатывает ответы.
 *
 * @package src
 */
abstract class SdkClient extends Client
{
    /**
     * Отправляет запрос к API и возвращает схему ответа.
     *
     * @param Prompt $prompt
     *
     * @return ?Schema
     */
    protected function send( Prompt $prompt ): ?Schema
    {
        $request = $this->constructRequest( $prompt );

        $response = $this->operator->sendRequest( $request );

        $schema = $this->constructSchema( $prompt, $response );

        if ( $schema instanceof Schema )
        {
            if ( $schema->validate( $request->getSchema() ) )

            return $schema;
        }

        $this->errorHandler([
            'method' => __METHOD__,
            'prompt' => $prompt,
            'schema' => $schema
        ]);

        return null;
    }

    /**
     * @param Prompt $prompt
     *
     * @return Request
     */
    private function constructRequest( Prompt $prompt ): Request
    {
        $className = $this->config->classRequest ?? Request::class;

        return new $className( $this, $prompt );
    }

    /**
     * @param Response $response
     *
     * @return ?Schema
     */
    private function constructSchema( Prompt $prompt, Response $response ): ?Schema
    {
        $schemaClassName = $prompt->schema;

        if ( class_exists( $schemaClassName ) )
        {
            $result = $response->getResult();

            if ( $result ) {

                /** @var Schema $schema */
                $schema = new $schemaClassName( $result );

                return $schema;
            }

            $this->errorHandler([
                'method' => __METHOD__,
                'message' => 'Response result is empty',
                'schema' => $schemaClassName
            ]);

            return null;
        }

        $this->errorHandler([
            'method' => __METHOD__,
            'message' => 'Schema class not found',
            'schema' => $schemaClassName
        ]);

        return null;
    }

    protected function setupCache( ?Schema $schema )
    {
        if ( $schema && method_exists( $schema, 'getCacheKey' ) )
        {
            $key = $this->cache->getCacheKey( $this->config );

            $this->cache->set($key, $schema, $this->config->cache->ttl);
        }
    }
}