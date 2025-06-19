<?php

namespace andy87\sdk\client\helpers;

/**
 * Class Method
 *
 * @package sdk-php-client\src\helpers
 */
class Method
{
    public const GET = 'GET';

    public const POST = 'POST';

    public const PATCH = 'PATCH';

    public const PUT = 'PUT';

    public const DELETE = 'DELETE';

    public const LIST = [
        self::GET,
        self::POST,
        self::PATCH,
        self::PUT,
        self::DELETE
    ];
}