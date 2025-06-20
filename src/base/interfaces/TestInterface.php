<?php

namespace andy87\sdk\client\base\interfaces;

use andy87\sdk\client\base\BasePrompt;

/**
 * Интерфейс TestingInterface
 * Represents a cache interface for storing and retrieving data.
 *
 * @package src/base/interfaces
 */
interface TestInterface
{
    public function run( string $promptClass ): bool;
}