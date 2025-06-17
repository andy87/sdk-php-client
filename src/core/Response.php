<?php

namespace andy87\sdk\client\core;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src\base
 */
class Response
{
    /**
     * @var int|null $statusCode Код статуса ответа
     */
    protected ?int $statusCode = null;

    /**
     * @var string|null $content Содержимое Raw ответа
     */
    protected ?string $content = null;

    /**
     * @var array|null $result Результат ответа в виде ассоциативного массива
     */
    protected ?array $result = null;

    protected ?array $params = null;



    /**
     * Response constructor.
     *
     * @param int $statusCode
     * @param ?string $content
     */
    public function __construct( int $statusCode, ?string $content = null )
    {
        $this->statusCode = $statusCode;

        $this->content = $content;

        if ($this->content) $this->result = json_decode( $content, true );
    }

    /**
     * Возвращает код статуса ответа.
     *
     * @return ?int
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * Возвращает содержимое ответа.
     *
     * @return ?string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Возвращает результат ответа в виде ассоциативного массива.
     *
     * @return ?array
     */
    public function getResult(): ?array
    {
        if ( $this->result === null && $this->content ) {
            $this->result = json_decode( $this->content, true );
        }

        return $this->result;
    }

    /**
     * Возвращает параметры ответа.
     *
     * @return ?array
     */
    public function getParams(): ?array
    {
        return $this->params;
    }

    /**
     * Устанавливает параметры ответа.
     *
     * @param array $params
     */
    public function setParams( array $params ): void
    {
        $this->params = $params;
    }


    /**
     * Проверяет, является ли ответ успешным (код статуса 2xx).
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->statusCode AND ( $this->statusCode >= 200 && $this->statusCode < 300 );
    }
}