<?php

namespace andy87\sdk\client\helpers;

/**
 * Class ContentType
 *
 * @package src/hepers
 */
abstract class ContentType
{
    public const TEXT_PLAIN = 'text/plain';
    public const TEXT_HTML = 'text/html';


    public const MULTIPART_FORM_DATA = 'multipart/form-data';
    public const MULTIPART_FILE = 'multipart/form-data';

    public const OCTET_STREAM = 'application/octet-stream';
    public const FORM_URLENCODED = 'application/x-www-form-urlencoded';

    public const ZIP = 'application/zip';
    public const PDF = 'application/pdf';
    public const JSON = 'application/json';
    public const XML = 'application/xml';
}