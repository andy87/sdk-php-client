<?php

namespace andy87\sdk\client;

use Exception;
use andy87\sdk\client\base\BaseClient;
use andy87\sdk\client\base\BasePrompt;
use andy87\sdk\client\base\BaseSchema;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс SdkClient
 *
 * SDK клиента, представляющий слой который содержет логику тправки запросов к API и обрабатку ответа.
 *
 * @package src/
 */
abstract class SdkClient extends BaseClient
{
    /**
     * Отправляет запрос к API и возвращает схему ответа.
     *
     * @param BasePrompt $prompt
     *
     * @return ?BaseSchema
     *
     * @throws Exception
     */
    public function send( BasePrompt $prompt ): ?BaseSchema
    {
        $request = $this->constructRequest( $prompt );

        $response = $this->modules->operator->sendRequest( $request );

        $log = [
            'method' => __METHOD__,
            'prompt' => $prompt,
            'request' => $request,
            'send response' => $response
        ];

        $response = $this->handleResponse( $prompt, $response );

        $log['handleResponse'] = $response;

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
     * Проверяет, является ли ответ ошибкой авторизации.
     *
     * @param BasePrompt $prompt Объект запроса, содержащий информацию о запросе.
     * @param Response $response Ответ от API, который нужно проверить на наличие ошибок авторизации.
     *
     * @return Response
     *
     * @throws Exception
     */
    private function handleResponse(BasePrompt $prompt, Response $response ): Response
    {
        if ( $this->isTokenInvalid( $response ) )
        {
            $account = $this->config->getAccount();

            if ( $this->authorization( $account ) )
            {
                $request = $this->constructRequest( $prompt );

                $response = $this->modules->operator->sendRequest( $request );

                if ( $this->isTokenInvalid( $response ) )
                {
                    $this->errorHandler([
                        'method' => __METHOD__,
                        'message' => 'Authorization error after re-authorization',
                        'prompt' => $prompt,
                        'request' => $request,
                        'response' => $response
                    ]);
                }
            }
        }

        return $response;
    }

    /**
     * @param BasePrompt $prompt
     *
     * @return Request
     *
     * @throws Exception
     */
    private function constructRequest(BasePrompt $prompt ): Request
    {
        $requestClassName = $this->modules->container->getClassRegistry( RequestInterface::class );

        /** @var Request $request */
        $request = new $requestClassName( $this, $prompt );

        $this->prepareAuthentication( $request );

        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return ?BaseSchema
     */
    private function constructSchema( Request $request, Response $response ): ?BaseSchema
    {
        $schemaClassName = $request->getPrompt()->getSchema();

        if ( class_exists( $schemaClassName ) )
        {
            if ( $result = $response->getResult() )
            {
                /** @var BaseSchema $schema */
                $schema = new $schemaClassName( $result );

                return $schema;
            }
        }

        return null;
    }
}