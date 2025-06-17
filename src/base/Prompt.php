<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\helpers\Method;
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
    protected string $schema;

    protected string $path;

    protected string $method = Method::GET;

    protected ?string $contentType = null;

    protected bool $isPrivate = false;

    protected array $headers = [];


    /**
     * Возвращает схему запроса.
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Возвращает путь запроса.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Возвращает метод запроса.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Возвращает тип контента запроса.
     *
     * @return ?string
     */
    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    /**
     * Возвращает значение, является ли запрос приватным( требует авторизации).
     *
     * @return bool
     */
    public function isPrivate(): bool
    {
        return $this->isPrivate;
    }

    /**
     * Возвращает заголовки запроса.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Возвращает все публичные свойства запроса.
     * используя простой каст в массив.
     *
     * @return ?array
     */
    public function release(): ?array
    {
        return get_object_vars($this) ?? null;
    }
}