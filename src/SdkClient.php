<?php

namespace andy87\sdk\client;

use Exception;
use andy87\sdk\client\base\AbstractClient;
use andy87\sdk\client\base\modules\AbstractMock;
use andy87\sdk\client\base\components\{ Account, Config, Prompt, Schema };
use andy87\sdk\client\base\interfaces\{ClientInterface, RequestInterface};
use andy87\sdk\client\core\transport\{Request, Url, Response};

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

        if( $mock && $mock->typeIs($mock::BREAKPOINT_REQUEST) )
        {
            return $mock->getData();
        }

        $response = $this->sendRequest( $request );

        $response = $this->handleResponse( $request, $response );

        if ( $response->isOk() || $prompt::DEBUG )
        {
            if ( $schema = $this->constructSchema( $request, $response ) )
            {
                if ( !$schema->validate( $prompt ) || $prompt::DEBUG )
                {
                    $schema->addLog([
                        'response' => $response
                    ]);
                }

                return $schema;

            } else {

                $log = [
                    'message' => 'Schema class not found or invalid response',
                    'response' => $response,
                ];
            }

        } else {

            $log = [
                'message' => 'Response error (is not OK). Status code: ' . $response->getStatusCode(),
                'response' => $response,
            ];
        }

        $this->errorHandler(__METHOD__, __LINE__, $log);

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

        if ( $requestClassName )
        {
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

        throw new Exception( 'Request not found' );
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
    protected function mockHandle( Prompt $prompt ): ?string
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

                    $this->errorHandler(__METHOD__, __LINE__, [
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
    protected function constructUrl( Config $config, Prompt $prompt ): Url
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
     *
     * @throws Exception
     */
    protected function constructSchema( RequestInterface $request, Response $response ): ?Schema
    {
        $schemaClassName = $request->getPrompt()->getSchema( $response );

        if ( class_exists( $schemaClassName ) )
        {
            $result = $response->getResult();

            /** @var Schema $schema */
            $schema = new $schemaClassName( $result );

            return $schema;

        } else {
            $this->errorHandler(__METHOD__, __LINE__, [
                'error' => 'Schema class not found',
                'request' => $request,
                'response' => $response,
                'schemaClassName' => $schemaClassName
            ]);
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
    protected function handleResponse( Request $request, Response $response ): Response
    {
        if ( $this->isTokenInvalid( $response ) )
        {
            $account = $this->getConfig()->getAccount();

            if ( $this->reAuthorization( $account ) )
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

                $response = new Response( $request, 0, null );
                $response->addError( 'Authorization failed' );

                $errorLog = [
                    'method' => __METHOD__,
                    'message' => 'Authorization failed',
                    'account' => $account,
                    'request' => $request,
                ];
            }
        }

        if (isset($errorLog)) $this->errorHandler(__METHOD__, __LINE__, $errorLog );

        return $response;
    }


    /**
     * Добавление данных требуемых для авторизации.
     */
    protected function prepareAuthentication( Prompt $prompt, RequestInterface $request ): RequestInterface
    {
        return $request;
    }
}