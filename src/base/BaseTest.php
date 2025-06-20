<?php

namespace andy87\sdk\client\base;

use Exception;
use andy87\sdk\client\SdkClient;
use andy87\sdk\client\base\interfaces\TestInterface;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src/base
 */
abstract class BaseTest implements TestInterface
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
        /** @var BasePrompt $promptClass */
        $prompt = new $promptClass();

        /** @var BaseSchema $schema */
        $schema = $this->client->send( $prompt );

        return $schema->validate( $prompt ) ?? false;
    }
}