<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\interfaces\SchemaInterface;

/**
 * Класс Dto
 *
 * Содержет структуру данных получаемых от API.
 *
 * @package src/base
 */
class Schema implements SchemaInterface
{
    /**
     * {@inheritDoc}
     */
    public function validate( string $schema ): bool
    {
        return true;
    }
}