<?php

namespace andy87\sdk\client;

use andy87\sdk\client\base\Client;
use andy87\sdk\client\base\Prompt;
use andy87\sdk\client\base\Response;

abstract class SdkClient extends Client
{
    public array $headers = [];

    abstract public function authorization(): bool;
    abstract public function errorHandler( Prompt $prompt, Response $response ): bool;
}