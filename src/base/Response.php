<?php

namespace andy87\sdk\client\base;

class Response
{
    public Request $request;

    public int $statusCode;

    public string $content;
}