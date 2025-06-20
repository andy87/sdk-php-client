<?php

namespace andy87\sdk\client\tests;

use andy87\sdk\client\SdkClient;

/**
 * Класс SdkClientTest.
 *
 * Содержит тесты для проверки работы клиента SDK.
 *
 * @package andy87\sdk\client\tests
 */
abstract class SdkClientTest
{
    public string $promptClass;

    /**
     *  Конструктор класса SdkClientTest.
     *
     * @param SdkClient $sdkClient
     */
    public function __construct(
        private SdkClient $sdkClient
    ) { }

    /**
     * Запускает тесты для проверки работы клиента SDK.
     *
     * @return void
     */
    public function run(): void
    {
        $result = $this->sdkClient->modules->test->run( $this->promptClass );

        $this->displayResult( $this->promptClass, $result );
    }

    /**
     * Отображает результат теста.
     *
     * @param string $prompt
     * @param bool $result
     *
     * @return array
     */
    abstract public function displayResult(string $prompt, bool $result ): array;
}