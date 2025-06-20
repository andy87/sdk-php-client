<?php

namespace andy87\sdk\client\operators;

use andy87\sdk\client\base\BaseOperator;
use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;

/**
 * Класс CurlOperator
 *
 * Отправляет запросы к API с использованием Guzzle HTTP Client.
 *
 * @package src/core/operators
 */
class GuzzleBaseOperator extends BaseOperator
{
    public function sendRequest( Request $request ): Response
    {
        return new Response(200, null );
    }
}