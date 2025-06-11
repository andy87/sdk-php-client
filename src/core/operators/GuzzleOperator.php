<?php

namespace andy87\sdk\client\core\operators;

use andy87\sdk\client\base\Operator;
use andy87\sdk\client\base\Request;
use andy87\sdk\client\base\Response;

/**
 * Класс CurlOperator
 *
 * Отправляет запросы к API с использованием Guzzle HTTP Client.
 *
 * @package src\operators
 */
class GuzzleOperator extends Operator
{
    public function sendRequest( Request $request ): Response
    {
        $response = new Response(200, null );

        return $response;
    }
}