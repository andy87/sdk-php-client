<?php

namespace andy87\sdk\client\core;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src\base
 */
class Response
{
    protected ?int $statusCode = null;

    protected ?string $content = null;

    protected ?array $result = null;



    /**
     * Response constructor.
     *
     * @param int $statusCode
     * @param ?string $content
     */
    public function __construct( int $statusCode, ?string $content = null )
    {
        $this->statusCode = $statusCode;

        $this->content = $content;

        if ($this->content) $this->result = json_decode( $content, true );
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult(): ?array
    {
        if ( $this->result === null && $this->content ) {
            $this->result = json_decode( $this->content, true );
        }

        return $this->result;
    }
}