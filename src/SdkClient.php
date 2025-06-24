<?php

namespace andy87\sdk\client;

use Exception;
use andy87\sdk\client\core\transport\Url;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\base\components\Config;
use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\base\components\Schema;
use andy87\sdk\client\base\components\Account;
use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\core\transport\Response;

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
            'func_get_args' => func_get_args(),
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

        $this->modules->getLogger()?->errorHandler($log);

        return null;
    }

    /**
     * @param Prompt $prompt
     *
     * @return RequestInterface
     *
     * @throws Exception
     */
    protected function constructRequest( Prompt $prompt ): RequestInterface
    {
        $requestClassName = $this->modules->getContainer()->getClassRegistry( ClientInterface::REQUEST );

        return $this->prepareAuthentication(
            $prompt,
            new $requestClassName( $this, $prompt )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function constructEndpoint( Prompt $prompt ): string
    {
        return $this
            ->constructUrl( $this->getConfig(), $prompt )
            ->getFullPath();
    }

    /**
     * Конструирует путь для запроса на основе объекта Prompt.
     *
     * @param Config $config
     * @param Prompt $prompt
     *
     * @return Url
     */
    private function constructUrl( Config $config, Prompt $prompt ): Url
    {
        if ( $prefix = $config->getPrefix() )
        {
            $prefix = ( $prompt::USE_PREFIX ) ? $prefix : null;
        }

        return new Url(
            $config->getProtocol(),
            $config->getHost(),
            prefix:$prefix,
            path:$prompt->getPath()
        );
    }

    /**
     * Запуск тестов в клиенте.
     *
     * @throws Exception
     */
    public function test(): void
    {
        $this->getModule(ClientInterface::TEST)->run();
    }

    /**
     * @param RequestInterface $request
     * @param Response $response
     *
     * @return ?Schema
     */
    protected function constructSchema( RequestInterface $request, Response $response ): ?Schema
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
            $account = $this->getConfig()->getAccount();

            if ( $this->authorization( $account ) )
            {
                $request = $this->constructRequest( $prompt );

                $response = $this->modules->getTransport()->sendRequest( $request );

                if ($response->isOk())
                {
                    if ( $this->isTokenInvalid( $response ) )
                    {
                        $this->modules->getLogger()?->errorHandler([
                            'method' => __METHOD__,
                            'message' => 'Authorization error after re-authorization',
                            'prompt' => $prompt,
                            'request' => $request,
                            'response' => $response
                        ]);
                    }
                } else {

                    $this->modules->getLogger()?->errorHandler([
                        'method' => __METHOD__,
                        'message' => 'Next response after re-authorization',
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
    public function prepareAuthentication( Prompt $prompt, RequestInterface $request ): RequestInterface
    {


        return $request;
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