<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\core\transport\Request;

/**
 * Интерфейс для представления ответа от API.
 */
interface ResponseInterface
{
    /**
     * Возвращает Request
     *
     * @return Request
     */
    public function  getRequest(): Request;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * Возвращает код статуса ответа.
     *
     * @return ?int
     */
    public function getStatusCode(): ?int;

    /**
     * Возвращает содержимое ответа.
     *
     * @return ?string
     */
    public function getContent(): ?string;

    /**
     * Возвращает результат ответа в виде ассоциативного массива.
     *
     * @return ?array
     */
    public function getResult(): ?array;

    /**
     * Возвращает параметры ответа.
     *
     * @return ?array
     */
    public function getCustomParams(): ?array;

    /**
     * Устанавливает параметры ответа.
     *
     * @param array $customParams
     */
    public function setCustomParams(array $customParams ): void;

    /**
     * Проверяет, является ли ответ успешным (код статуса 2xx).
     *
     * @return bool
     */
    public function isOk(): bool;

}