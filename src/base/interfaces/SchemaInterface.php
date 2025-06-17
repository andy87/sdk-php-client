<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\Prompt;

/**
 * Interface ResponseInterface
 *
 * Определяет методы для работы с ответами API.
 *
 * @package src/base/interfaces
 */
interface SchemaInterface
{
    /**
     * Проверяет валидность ответа по схеме.
     *
     * @param Prompt $prompt Объект запроса
     *
     * @return bool Возвращает true, если ответ валиден, иначе false.
     */
    public function validate( Prompt $prompt ): bool;
}