<?php

namespace andy87\sdk\client\core\operators;

use andy87\sdk\client\core\Request;
use andy87\sdk\client\core\Response;
use andy87\sdk\client\base\Operator;

/**
 * Класс CurlOperator
 *
 * Отправляет запросы к API с использованием Guzzle HTTP Client.
 *
 * @package src/core/operators
 */
class GuzzleOperator extends Operator
{
    public function sendRequest( Request $request ): Response
    {
        $response = new Response(200, null );

        return $response;
    }
}