<?php

namespace andy87\sdk\client\base\components;

use andy87\sdk\client\base\interfaces\AuthorizationInterface;
use andy87\sdk\client\base\interfaces\MockInterface;

/**
 * Класс Prompt
 *
 * Представляет собой запрос к API, содержащий метод, путь, заголовки и другие параметры
 *
 * @package src/base
 */
abstract class Prompt
{
    /** @var bool Статус использования префикса конфига */
    public const USE_PREFIX = true;

    /** @var array|AuthorizationInterface[] Применяемые для авторизации классы */
    public const AUTH = [];



    /**
     * Схема запроса, определяющая структуру и правила валидации.
     *
     * @var string $schema
     */
    protected string $schema;


    /**
     * Метод HTTP запроса (GET, POST, PUT, DELETE и т.д.).
     *
     * @var string $method
     */
    protected string $method;

    /**
     * Путь запроса, указывающий на конечную точку API.
     *
     * @var string $path
     */
    protected string $path;

    /**
     * Заголовки запроса, которые могут включать авторизационные токены и другие метаданные.
     *
     * @var array $headers
     */
    protected array $headers = [];


    /**
     * Тип контента запроса, например, 'application/json'.
     *  Может быть null, если тип не задан.
     *
     * @var null|string $contentType
     */
    protected ?string $contentType = null;

    /**
     * Мок запроса, если мок задан.
     *
     * @var null|MockInterface $mock
     */
    public ?MockInterface $mock = null;



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
        $array = get_object_vars($this) ?? null;

        if($array)
        {
            $array = array_filter($array, function ($value) {
                return $value !== null;
            });
        }

        return $array;
    }
}