<?php

namespace andy87\sdk\client;


use andy87\sdk\client\base\Client;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\base\Prompt;
use andy87\sdk\client\base\Schema;
use andy87\sdk\client\core\Request;
use andy87\sdk\client\core\Response;

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

        $response = $this->modules->operator->sendRequest( $request );

        $log = [
            'method' => __METHOD__,
            'prompt' => $prompt,
            'request' => $request,
            'response' => $response
        ];

        if ( $this->isAuthorizationError( $response ) )
        {
            $account = $this->config->getAccount();

            if ( $this->authorization( $account ) )
            {
               $request = $this->constructRequest( $prompt );

               $response = $this->modules->operator->sendRequest( $request );

                if ( $this->isAuthorizationError( $response ) )
                {
                    $log['message'] = 'Authorization error after re-authorization';
                    $log['request'] = $request;
                    $log['response'] = $response;
                }
            }
        }

        if ( $response->isOk() )
        {
            if ( $schema = $this->constructSchema( $request, $response ) )
            {
                $log['schema'] = [
                    'object' => $schema,
                ];

                if ( $schema->validate( $prompt ) )
                {
                    return $schema;

                } else {

                    $log['message'] = 'Schema validation error';
                    $log['schema']['_errors'] = $schema->getErrors();
                }

            } else {

                $log['message'] = 'Schema class not found or invalid response';
            }
        } else {

            $log['message'] = 'Response error';
        }

        $this->errorHandler($log);

        return null;
    }

    /**
     * @param Prompt $prompt
     *
     * @return Request
     */
    private function constructRequest( Prompt $prompt ): Request
    {
        $requestClassName = $this->modules->container->classList[RequestInterface::class];

        /** @var Request $request */
        $request = new $requestClassName( $this, $prompt );

        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return ?Schema
     */
    private function constructSchema( Request $request, Response $response ): ?Schema
    {
        $schemaClassName = $request->getPrompt()->getSchema();

        if ( class_exists( $schemaClassName ) )
        {
            if ( $result = $response->getResult() )
            {
                /** @var Schema $schema */
                $schema = new $schemaClassName( $result );

                return $schema;
            }
        }

        return null;
    }
}