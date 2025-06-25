<?php

namespace andy87\sdk\client\transports;

use Exception;
use CurlHandle;
use andy87\sdk\client\helpers\Method;
use andy87\sdk\client\base\modules\AbstractTransport;
use andy87\sdk\client\base\interfaces\RequestInterface;
use andy87\sdk\client\core\transport\{ Query, Response };

/**
 * Класс CurlOperator
 *
 * Отправляет запросы к API с использованием cURL.
 *
 * @package src/core/operators
 */
final class CurlTransport extends AbstractTransport
{
    public array $options = [
        CURLOPT_RETURNTRANSFER => true,
    ];



    /**
     * Отправляет запрос к API.
     *
     * @param RequestInterface $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function sendRequest( RequestInterface $request ): Response
    {
        $query = $request->getQuery();

        $curl = curl_init();

        if ($curl === false) {

            $error = 'Failed to initialize cURL session.';

            $this->errorHandler([
                'method' => __METHOD__,
                'message' => $error,
            ]);

            $response = new Response( $request, 0 );

            $response->addError($error);

        } else {

            $this->handleData( $query );

            $this->options[CURLOPT_HTTPHEADER] = $query->getHeaders();
            $this->options[CURLOPT_URL] = $query->getEndpoint();

            curl_setopt_array($curl, $this->options);

            $content = curl_exec($curl) ?: null;
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE) ?: null;
            $curlInfo = $this->handleCustomParams( $curl, $query );

            $response = new Response( $request, $statusCode, $content );

            curl_close($curl);

            if ( !empty($curlInfo)) $response->setCustomParams( $curlInfo );
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

                if ( !empty($data) )
                {
                    $data = http_build_query($data);

                    $this->options[CURLOPT_POSTFIELDS] = $data;
                }
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