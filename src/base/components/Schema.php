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
     * Содержит: ошибки\дебаг информацию и т.п.
     *
     * @var ?array
     */
    protected ?array $_log = null;



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
            $params = static::MAPPING[$key] ?? null;

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

                    $this->addLog(sprintf( static::ERROR_CLASS_NOT_FOUND, $className, $key, static::class ));
                }

            } elseif ( is_string( $params ) && class_exists( $params ) ) {

                $value = new $params( $data );
            }

            $this->$key = $value ?? $data;

        } else {

            $this->addLog(sprintf( static::ERROR_PROPERTY_NOT_FOUND, $key, static::class ));
        }
    }

    /**
     * Валидация ответа от API
     *
     * @param Prompt $prompt
     *
     * @return bool
     */
    public function validate( Prompt $prompt ): bool
    {
        return true;
    }

    /**
     * Получает ошибки схемы
     */
    public function getLog(): ?array
    {
        return $this->_log;
    }

    /**
     * Добавляет информацию в лог
     *
     * @param string|array $log
     */
    public function addLog( string|array $log ): void
    {
        if ( $this->_log === null ) $this->_log = [];

        $this->_log[] = $log;
    }
}