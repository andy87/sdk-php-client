<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\ContentType;
use andy87\sdk\client\helpers\Method;

class Prompt
{
    public string $method = Method::GET;
    public string $contentType = ContentType::APPLICATION_JSON;

    public string $path;

    public bool $isPrivate = false;

    public array $headers = [];
}