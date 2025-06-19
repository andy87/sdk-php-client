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
    /**
     * @var bool $isPrivate
     * Флаг, указывающий, является ли запрос приватным (требует авторизации).
     */
    protected bool $isPrivate = false;

    /**
     * @var string $schema
     * Схема запроса, определяющая структуру и правила валидации.
     */
    protected string $schema;


    /**
     * @var string $method
     * Метод HTTP запроса (GET, POST, PUT, DELETE и т.д.).
     */
    protected string $method = Method::GET;

    /**
     * @var string $path
     * Путь запроса, указывающий на конечную точку API.
     */
    protected string $path;

    /**
     * @var array $headers
     * Заголовки запроса, которые могут включать авторизационные токены и другие метаданные.
     */
    protected array $headers = [];


    /**
     * @var ?string $contentType
     * Тип контента запроса, например, 'application/json'.
     * Может быть null, если тип не задан.
     */
    protected ?string $contentType = null;



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