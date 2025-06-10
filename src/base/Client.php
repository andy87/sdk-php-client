<?php

namespace andy87\sdk\client\base;

/**
 * Класс Client
 *  Содержет методы для отправки запросов к API и обработки ответов.
 *
 * @package src\base
 */
abstract class Client
{
    public array $headers = [];

    protected Config $config;

    protected string $baseUrl;

    //protected array $events = [];



    public function __construct( Config $config )
    {
        $this->config = $config;

        $this->baseUrl = $config->getBaseUri();
    }


    abstract public function authorization(): bool;

    abstract public function errorHandler( Prompt $prompt, Schema $schema ): bool;

}