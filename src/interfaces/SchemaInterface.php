<?php

namespace andy87\sdk\client\interfaces;

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
     * @param string $schema Схема которая проверяется на валидность.
     *
     * @return bool Возвращает true, если ответ валиден, иначе false.
     */
    public function validate( string $schema ): bool;
}