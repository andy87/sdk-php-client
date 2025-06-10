<?php

namespace andy87\sdk\client\base;

class Request
{
    public function __construct( public Prompt $prompt ) {}

    public function call()
    {
        $response = new Response();
        $response->request = $this;
        $response->statusCode = 200; // Example status code
        $response->content = 'Response content'; // Example content

        return $response;
    }
}