<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\Prompt;

/**
 * Интерфейс RequestInterface
 *
 * Определяет методы для настройки запроса к API.
 *
 * @package src/base/interfaces
 */
interface RequestInterface
{
    /**
     * Получает сценарий запроса.
     *
     * @return string Сценарий запроса.
     */
    public function getSchema(): string;

    /**
     * Получает код статуса ответа.
     *
     * @return ?int Код статуса ответа.
     */
    public function getStatusCode(): ?int;

    /**
     * Получает raw ответа.
     *
     * @return ?string Raw ответ от API.
     */
    public function getContent(): ?string;

    /**
     * Получает результат ответа в виде массива.
     *
     * @return ?array Результат ответа или null, если результат отсутствует.
     */
    public function getResult(): ?array;

    /**
     * Устанавливает параметры запроса
     */
    public function setupQuery():void;
}