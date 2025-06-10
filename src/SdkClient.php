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

        $schema = $this->constructSchema( $request );

        if ( $schema instanceof Schema )
        {
            if ( $schema->validate( $request->getSchema() ) )

            return $schema;
        }

        $this->errorHandler( $prompt, $schema );

        return null;
    }

    /**
     * @param Prompt $prompt
     *
     * @return Request
     */
    private function constructRequest( Prompt $prompt ): Request
    {
        $request = new Request( $this, $prompt );

        return $request;
    }

    /**
     * @param Request $request
     *
     * @return ?Schema
     */
    private function constructSchema( Request $request ): ?Schema
    {
        $request->call();

        $schema = null;

        if ($schema instanceof Schema) {
            return $schema;
        }

        return null;
    }

    private function setupCache(?Response $Response)
    {

    }
}