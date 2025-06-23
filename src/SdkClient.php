<?php

namespace andy87\sdk\client;

use Exception;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\base\components\Account;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\base\modules\AbstractTest;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;

/**
 * Класс SdkClient
 *
 * SDK клиента, представляющий слой который содержет логику тправки запросов к API и обрабатку ответа.
 *
 * @package src/
 */
abstract class SdkClient extends AbstractClient
{
    /**
     * Отправляет запрос к API и возвращает схему ответа.
     *
     * @param Prompt $prompt
     *
     * @return ?Schema
     *
     * @throws Exception
     */
    public function send( Prompt $prompt ): ?Schema
    {
        $request = $this->constructRequest( $prompt );

        $response = $this->sendRequest( $request );

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
                if ( $schema->validate( $prompt ) )
                {
                    return $schema;

                } else {

                    $log['message'] = 'Schema validation error';
                    $log['schema'] = [
                        'object' => $schema,
                        '_errors' => $schema->getErrors()
                    ];
                }

            } else {

                $log['message'] = 'Schema class not found or invalid response';
            }

        } else {

            $log['message'] = 'Response error (is not OK). Status code: ' . $response->getStatusCode();
        }

        $this->modules->logger->errorHandler($log);

        return null;
    }

    /**
     * @param Prompt $prompt
     *
     * @return Request
     *
     * @throws Exception
     */
    protected function constructRequest( Prompt $prompt ): Request
    {
        $requestClassName = $this->modules->container->getClassRegistry( ClientInterface::REQUEST );

        /** @var Request $request */
        $request = new $requestClassName( $this, $prompt );

        $this->prepareAuthentication( $request );

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function constructEndpoint( string|int $path ): string
    {
        return $this->config->getBaseUri() . '/' . $path;
    }

    /**
     * Запуск тестов в клиенте.
     *
     * @throws Exception
     */
    public function test( string $promptClass ): void
    {
        if ($this->modules->test instanceof AbstractTest )
        {
            $this->modules->test->run( $promptClass );
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return ?Schema
     */
    protected function constructSchema( Request $request, Response $response ): ?Schema
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

    /**
     * Проверяет, является ли ответ ошибкой авторизации.
     *
     * @param Prompt $prompt Объект запроса, содержащий информацию о запросе.
     * @param Response $response Ответ от API, который нужно проверить на наличие ошибок авторизации.
     *
     * @return Response
     *
     * @throws Exception
     */
    private function handleResponse( Prompt $prompt, Response $response ): Response
    {
        if ( $this->isTokenInvalid( $response ) )
        {
            $account = $this->config->getAccount();

            if ( $this->authorization( $account ) )
            {
                $request = $this->constructRequest( $prompt );

                $response = $this->modules->transport->sendRequest( $request );

                if ( $this->isTokenInvalid( $response ) )
                {
                    $this->modules->logger->errorHandler([
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
     * Добавление данных требуемых для авторизации.
     */
    public function prepareAuthentication( RequestInterface $request ): void
    {
        // Логика установки данных для выполнения запросов требующих авторизации
    }

    /**
     * Авторизация пользователя.
     *
     * @param Account $account
     *
     * @return bool
     */
    abstract public function authorization( Account $account ): bool;

    /**
     * Проверка есть ли ошибки в ответе, решаемые повторной авторизацией
     *
     * @param Response $response
     *
     * @return bool
     */
    abstract public function isTokenInvalid( Response $response ): bool;
}