<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\core\transport\Request;
use andy87\sdk\client\core\transport\Response;

/**
 * Интерфейс для отправки запросов к API.
 *
 * @package src/base/interfaces
 */
interface OperatorInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendRequest( Request $request ): Response;
}