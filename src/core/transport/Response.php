<?php

namespace andy87\sdk\client\core\transport;

use andy87\sdk\client\base\interfaces\ResponseInterface;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src/core/transport
 */
class Response implements ResponseInterface
{
    /**
     * @var Request $request Запрос, на который был получен ответ
     */
    protected Request $request;

    /**
     * @var null|int $statusCode Код статуса ответа
     */
    protected ?int $statusCode = null;

    /**
     * @var null|string $content Содержимое Raw ответа
     */
    protected ?string $content = null;

    /**
     * @var null|array $result Результат ответа в виде ассоциативного массива
     */
    protected ?array $result = null;

    /**
     * @var null|array $customParams Дополнительные параметры ответа
     */
    protected ?array $customParams = null;

    /**
     * @var array $errors Список ошибок(если они есть)
     */
    protected array $_errors = [];


    /**
     * Response constructor.
     *
     * @param Request $request
     * @param ?int $statusCode
     * @param ?string $content
     */
    public function __construct( Request $request, ?int $statusCode, ?string $content = null )
    {
        $this->request = $request;

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
    public function getCustomParams(): ?array
    {
        return $this->customParams;
    }

    /**
     * Устанавливает параметры ответа.
     *
     * @param array $customParams
     */
    public function setCustomParams(array $customParams ): void
    {
        $this->customParams = $customParams;
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

    /**
     * Возвращает ошибки, если они есть.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    /**
     * Добавляет ошибку в список ошибок.
     *
     * @param string $error
     */
    public function addError( string $error ): void
    {
        $this->_errors[] = $error;
    }

    /**
     * Возвращает Request
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}