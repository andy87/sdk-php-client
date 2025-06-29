<?php

namespace andy87\sdk\client\base\components;

use andy87\sdk\client\base\modules\AbstractMock;
use andy87\sdk\client\base\interfaces\AuthorizationInterface;
use andy87\sdk\client\core\transport\Response;
use andy87\sdk\client\helpers\ContentType;

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

    /** @var bool Статус добавления данных из query string в URL */
    public const APPLY_QUERY_TO_URL = false;

    /** @var false Статус использования дебаг режима */
    public const DEBUG = false;

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
     * @var null|string<AbstractMock::class> $mock
     */
    protected ?string $mock = null;


    /**
     * Возвращает схему запроса.
     *
     * @param Response $response
     *
     * @return string
     */
    public function getSchema( Response $response ): string
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
     * Проверяет, является ли тип контента запроса заданным.
     *
     * @param string<ContentType> $contentType Тип контента для проверки.
     *
     * @return bool
     */
    public function contentTypeIs( string $contentType ): bool
    {
        return $this->contentType === $contentType;
    }

    /**
     * Проверяет, содержится ли тип контента запроса в заданном массиве типов.
     *
     * @param array|string<ContentType>[] $contentTypes Массив типов контента для проверки.
     *
     * @return bool
     */
    public function contentTypeIn( array $contentTypes ): bool
    {
        return in_array( $this->contentType, $contentTypes, true);
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
     *
     * @return ?array
     */
    public function release(): ?array
    {
        return array_filter(
            (array)$this,
            fn($v, $k) => $k[0] !== "\0",
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Устанавливает мок запроса.
     *
     * @param null|string<AbstractMock::class> $mock
     */
    public function setMock( ?string $mock ): void
    {
        if (!$this->mock) $this->mock = $mock;
    }

    /**
     * Возвращает мок запроса, если он установлен.
     *
     * @return ?AbstractMock
     */
    public function getMock(): ?AbstractMock
    {
        if ($this->mock)
        {
            if ( !isset(MockManager::$mockInstances[$this->mock]) )
            {
                $mockClass = $this->mock;

                MockManager::$mockInstances[$this->mock] = new $mockClass();
            }

            return MockManager::$mockInstances[$this->mock];
        }

        return null;
    }
}