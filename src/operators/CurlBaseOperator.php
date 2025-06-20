<?php

namespace andy87\sdk\client\operators;

use Exception;
use CurlHandle;
use andy87\sdk\client\helpers\Method;
use andy87\sdk\client\base\BaseOperator;
use andy87\sdk\client\core\transport\Query;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;

/**
 *  Класс CurlOperator
 *
 * Отправляет запросы к API с использованием cURL.
 *
 * @package src/core/operators
 */
class CurlBaseOperator extends BaseOperator
{
    public array $options = [
        CURLOPT_RETURNTRANSFER => true,
    ];



    /**
     * Отправляет запрос к API.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function sendRequest( Request $request ): Response
    {
        $query = $request->getQuery();

        $curl = curl_init();

        if ($curl === false) {

            $error = 'Failed to initialize cURL session.';

            $this->errorHandler([
                'method' => __METHOD__,
                'message' => $error,
            ]);

            $response = new Response(0);

            $response->addError($error);

        } else {

            $this->handleData( $query );

            $this->options[CURLOPT_HTTPHEADER] = $query->getHeaders();
            $this->options[CURLOPT_URL] = $query->getEndpoint();

            curl_setopt_array($curl, $this->options);

            $response = new Response(
                curl_getinfo($curl, CURLINFO_HTTP_CODE) ?: 0,
                curl_exec($curl) ?: null
            );

            $curlInfo = $this->handleCustomParams( $curl, $query );

            curl_close($curl);

            $response->setCustomParams( $curlInfo );
        }

        return $response;
    }

    /**
     * Установка данных запроса в зависимости от HTTP метода.
     *
     * @param Query $query
     *
     * @throws Exception
     */
    private function handleData( Query $query ): void
    {
        $method = $query->getMethod();

        switch ( $method )
        {
            case Method::PUT:
            case Method::POST:
            case Method::PATCH:

                $this->options[CURLOPT_POST] = true;

                $data = $query->getData();

                if ( is_array($data) || is_string($data))
                {
                    $data = is_array($data) ? http_build_query($data) : $data;
                }

                $this->options[CURLOPT_POSTFIELDS] = $data;

                break;

            case Method::DELETE:
            default:
                $this->errorHandler( [
                    'method' => __METHOD__,
                    'message' => 'Unsupported HTTP method: ' . $method,
                ]);
        }

        if ( in_array( $method, [Method::PUT, Method::PATCH, Method::DELETE] ) )
        {
            $this->options[CURLOPT_CUSTOMREQUEST] = $method;
        }
    }

    /**
     * Обрабатывает пользовательские параметры.
     *
     * @param CurlHandle $curl
     * @param Query $query
     *
     * @return array
     *
     * @throws Exception
     */
    private function handleCustomParams(CurlHandle $curl, Query $query): array
    {
        if ( $params = $query->getCustomParams() )
        {
            foreach ( $params as $key => $value )
            {
                $params[$key] = curl_getinfo($curl, $key);
            }
        }

        return $params;
    }
}