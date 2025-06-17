<?php

namespace andy87\sdk\client\core\operators;

use andy87\sdk\client\base\Operator;
use andy87\sdk\client\core\Request;
use andy87\sdk\client\core\Response;
use Exception;

/**
 *  Класс CurlOperator
 *
 * Отправляет запросы к API с использованием cURL.
 *
 * @package src\operators
 */
class CurlOperator extends Operator
{
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

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $query->getMethod());
        curl_setopt($curl, CURLOPT_HTTPHEADER, $query->getHeaders());

        $url = $query->getEndpoint();

        switch ( $query->getMethod() ) {
            case 'GET':
                $data = $query->getData();

                if ($data )
                {
                    $url .= ( str_contains($url, '?') ? '&' : '?' );

                    if ( is_array($data) )
                    {
                        $url .= http_build_query($data);

                    } else if ( is_string($data) ) {

                        $url .= $data;
                    }
                }

                break;
            case 'POST':
            case 'PUT':
                curl_setopt($curl, CURLOPT_POST, true);

                $data = $query->getData();

                if ( is_array($data) )
                {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

                } elseif ( is_string($data) ) {

                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case 'DELETE':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            default:
                throw new Exception('Unsupported HTTP method: ' . $query->getMethod());
        }

        curl_setopt($curl, CURLOPT_URL, $url );

        if ( $params = $query->getParams() )
        {
            foreach ( $params as $key => $value )
            {
                $params[$key] = curl_getinfo($curl, $key);
            }
        }

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $response = new Response( $statusCode, $response );

        if ( $params ) $response->setParams( $params );

        return $response;
    }
}