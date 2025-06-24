<?php

namespace andy87\sdk\client\base\modules;

use Exception;
use andy87\sdk\client\SdkClient;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src/base
 */
abstract class AbstractTest
{
    /**
     * @param SdkClient $client
     *
     * @return bool
     *
     * @throws Exception
     */
    abstract public function run( SdkClient $client ): bool;
}