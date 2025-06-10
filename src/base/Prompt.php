<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Method;
use andy87\sdk\client\helpers\ContentType;
use andy87\sdk\client\interfaces\PromptInterface;

/**
 * Класс Prompt
 *
 * Представляет собой запрос к API, содержащий метод, путь, заголовки и другие параметры
 *
 * @package src\base
 */
class Prompt implements PromptInterface
{
    public string $schema;

    public string $path;

    public string $method = Method::GET;

    public string $contentType = ContentType::APPLICATION_JSON;

    public bool $isPrivate = false;

    public array $headers = [];
}