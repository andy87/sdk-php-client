<?php

namespace andy87\sdk\client\base;

class Client
{
    protected Config $config;


    public function __construct( Config $config )
    {
        $this->config = $config;
    }

    protected function send( Request $request ): Response
    {
        // Here you would implement the logic to send the request and return a Response object.
        // This is just a placeholder for demonstration purposes.
        $response = new Response();
        $response->request = $request;
        $response->statusCode = 200; // Example status code
        $response->content = 'Response content'; // Example content

        return $response;
    }
}