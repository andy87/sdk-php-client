<?php

namespace andy87\sdk\client\base;

use andy87\sdk\client\interfaces\DtoInterface;

/**
 * Class Response
 * Represents the response from an API request.
 *
 * @package src\base
 */
class Response implements DtoInterface
{
    public Request $request;

    protected ?int $statusCode = null;

    protected ?string $content = null;

    protected ?array $result = null;



    /**
     * Response constructor.
     *
     * @param Request $request
     */
    public function __construct( Request $request )
    {
        // Обработка ответа
    }

    /**
     * {@inheritdoc}
     */
    public function validate( string $scenario ) : bool
    {
        return true;
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