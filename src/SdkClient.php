<?php

namespace andy87\sdk\client;

use Exception;
use andy87\sdk\client\core\transport\{Request, Url, Response};
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\base\modules\AbstractMock;
use andy87\sdk\client\base\components\{ Account, Config, Prompt, Schema };
use andy87\sdk\client\base\interfaces\{ClientInterface, RequestInterface};

/**
 * Класс SdkClient
 *
 * SDK клиента, представляющий слой который содержет логику отправки запросов к API и обрабатку ответа.
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

        $mock = $request->getPrompt()->getMock();

        if( $mock && $mock::BREAKPOINT == $mock::BREAKPOINT_REQUEST )
        {
            return $mock->getData();
        }

        $response = $this->sendRequest( $request );

        $log = [
            'method' => __METHOD__,
            'func_get_args' => func_get_args(),
            'request' => $request,
            'send response' => $response
        ];

        $response = $this->handleResponse( $request, $response );

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

        $this->errorHandler($log);

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

        $request = $this->prepareAuthentication(
            $prompt,
            new $requestClassName( $this, $prompt )
        );

        if ( $mock = $this->mockHandle($prompt) )
        {
            $request->getPrompt()->setMock($mock);
        }

        return $request;
    }

    /**
     * Создает мок-обработчик для запроса, если он указан в Prompt.
     *
     * @param Prompt $prompt
     *
     * @return null|string<AbstractMock>
     *
     * @throws Exception
     */
    private function mockHandle( Prompt $prompt ): ?string
    {
        if ( !$prompt->getMock() )
        {
            if ( $mockManager = $this->modules->getMockManager() )
            {
                $mockClass = $mockManager->findClass( $prompt::class );

                if ( class_exists( $mockClass ) )
                {
                    return $mockClass;

                } else {

                    $this->errorHandler([
                        'method' => __METHOD__,
                        'message' => 'Mock class not exists',
                        'prompt' => $prompt,
                        'mockClass' => $mockClass
                    ]);
                }
            }
        }

        return null;
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
        $this->getModule(ClientInterface::TEST)->run( $this );
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
     * @param Request $request Запрос, который был отправлен к API.
     * @param Response $response Ответ от API, который нужно проверить на наличие ошибок авторизации.
     *
     * @return Response
     *
     * @throws Exception
     */
    private function handleResponse( Request $request, Response $response ): Response
    {
        if ( $this->isTokenInvalid( $response ) )
        {
            $account = $this->getConfig()->getAccount();

            if ( $this->authorization( $account ) )
            {
                $nextResponse = $this->modules->getTransport()->sendRequest( $request );

                if ($nextResponse->isOk())
                {
                    if ( $this->isTokenInvalid( $nextResponse ) )
                    {
                        $error = 'Token Invalid error after re-authorization';
                    }

                } else {
                    $error = 'Response invalid error after re-authorization';
                }

                if (isset($error))
                {
                    $errorLog = [
                        'method' => __METHOD__,
                        'message' => $error,
                        'request' => $request,
                        'nextResponse' => $nextResponse
                    ];
                }

            } else {

                $response = new Response( $request, 0, null);
                $response->addError( 'Authorization failed' );

                $errorLog = [
                    'method' => __METHOD__,
                    'message' => 'Authorization failed',
                    'account' => $account,
                    'request' => $request,
                ];
            }
        }

        if (isset($errorLog)) $this->errorHandler($errorLog);

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
     *
     * @throws Exception
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