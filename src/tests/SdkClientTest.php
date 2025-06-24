<?php

namespace andy87\sdk\client\tests;

use andy87\sdk\client\base\interfaces\ClientInterface;
use andy87\sdk\client\SdkClient;
use Exception;

/**
 * Класс SdkClientTest.
 *
 * Содержит тесты для проверки работы клиента SDK.
 *
 * @package andy87\sdk\client\tests
 */
abstract class SdkClientTest
{
    /**
     * Класс, который будет использоваться для создания промпта.
     *
     * @var string
     */
    public string $promptClass;

    /**
     * @var SdkClient $sdkClient Экземпляр клиента SDK.
     */
    private SdkClient $sdkClient;

    /**
     *  Конструктор класса SdkClientTest.
     *
     * @param SdkClient $sdkClient
     *
     * @throws Exception
     */
    public function __construct( SdkClient $sdkClient )
    {
        $this->sdkClient = $sdkClient;
    }

    /**
     * Запускает тесты для проверки работы клиента SDK.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run(): void
    {
        $result = $this->sdkClient->getModule( ClientInterface::TEST )->run();

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