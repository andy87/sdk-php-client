<?php

namespace andy87\sdk\client\base\modules;

use Exception;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src/base
 */
abstract class AbstractTest
{
    /**
     * @return bool
     *
     * @throws Exception
     */
    abstract public function run(): bool;
}