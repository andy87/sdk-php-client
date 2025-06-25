<?php

namespace andy87\sdk\client\helpers;

/**
 * Содержит список HTTP методов, которые обычно поддерживаются в API.
 *
 * Hypertext Transfer Protocol (HTTP) Method Registry
 * @see https://www.iana.org/assignments/http-methods/http-methods.xhtml
 *
 * @package src/hepers
 */
abstract class MethodRegistry
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PATCH = 'PATCH';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';
    public const HEAD = 'HEAD';
    public const OPTIONS = 'OPTIONS';

    /**
     * Список методов, которые поддерживаются в API.
     *
     *
     */
    public const LIST = [
        self::GET,
        self::POST,
        self::PATCH,
        self::PUT,
        self::DELETE,
        self::HEAD,
        self::OPTIONS
    ];

    /**
     * Список методов, которые передают данные в теле запроса.
     */
    public const POST_FIELDS = [
        self::POST,
        self::PATCH,
        self::PUT
    ];
}