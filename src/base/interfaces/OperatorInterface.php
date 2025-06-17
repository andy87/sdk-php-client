<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\core\Request;
use andy87\sdk\client\core\Response;

/**
 * Интерфейс для отправки запросов к API.
 *
 * @package andy87\sdk\client\base\interfaces
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