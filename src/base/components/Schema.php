<?php

namespace andy87\sdk\client\base\components;

/**
 * Класс Dto
 *
 * Содержет структуру данных получаемых от API.
 *
 * @package src/base
 */
abstract class Schema
{
    protected const ERROR_PROPERTY_NOT_FOUND = 'Property "%s" not found in schema "%s".';
    protected const ERROR_CLASS_NOT_FOUND = 'Class "%s" not found for property "%s" in schema "%s".';



    /**
     * ```
     * [
     *      'object'    => SomeClass::class,
     *      'array'     => [ SomeClass::class ],
     * ]
     * ```
     *
     * @var array
     */
    protected const MAPPING = [];



    /**
     * Содержит ошибки схемы
     *
     * @var array|null
     */
    protected ?array $_errors = null;



    /**
     * @param array $data
     */
    public function __construct( array $data = [] )
    {
        foreach ( $data as $key => $value )
        {
            $this->setupProperty( $key, $value );
        }
    }

    /**
     * @param string $key
     * @param mixed $data
     *
     * @return void
     */
    private function setupProperty( string $key, mixed $data ): void
    {
        if ( property_exists( $this, $key ) )
        {
            $params = static::MAPPING[$key];

            if ( is_array( $params ) && is_array( $data ) )
            {
                $className = $params[0];

                if ( class_exists( $className ) )
                {
                    $value = [];

                    foreach ( $data as $item )
                    {
                        $value[] = new $className( $item );
                    }
                } else {

                    $this->_errors[] = sprintf( static::ERROR_PROPERTY_NOT_FOUND, $className, $key, static::class );
                }

            } elseif ( is_string( $params ) && class_exists( $params ) ) {

                $value = new $params( $data );
            }

            $this->$key = $value ?? $data;

        } else {

            $this->_errors[] = sprintf( static::ERROR_CLASS_NOT_FOUND, $key, static::class );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validate( Prompt $prompt ): bool
    {
        return true;
    }

    /**
     * Получает ошибки схемы
     */
    public function getErrors(): ?array
    {
        return $this->_errors;
    }
}