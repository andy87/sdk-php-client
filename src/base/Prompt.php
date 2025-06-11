<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Method;
use andy87\sdk\client\helpers\ContentType;
use andy87\sdk\client\base\interfaces\PromptInterface;

/**
 * Класс Prompt
 *
 * Представляет собой запрос к API, содержащий метод, путь, заголовки и другие параметры
 *
 * @package src\base
 */
abstract class Prompt implements PromptInterface
{
    public string $schema;

    public string $path;

    public string $method = Method::GET;

    public ?string $contentType = null;

    public bool $isPrivate = false;

    public array $headers = [];
}