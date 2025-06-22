<?php

namespace andy87\sdk\client\core;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\components\Prompt;
use andy87\sdk\client\base\components\Schema;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src/base
 */
class Test
{
    /**
     *  Конструктор класса SdkClientTest.
     *
     * @param SdkClient $client
     */
    public function __construct(
        protected SdkClient $client
    ) { }

    /**
     * @param string $promptClass
     *
     * @return bool
     *
     * @throws Exception
     */
    public function run( string $promptClass ): bool
    {
        /** @var Prompt $promptClass */
        $prompt = new $promptClass();

        /** @var Schema $schema */
        $schema = $this->client->send( $prompt );

        return $schema->validate( $prompt ) ?? false;
    }
}