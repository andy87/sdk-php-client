<?php

namespace andy87\sdk\client\base\components;

use Exception;

/**
 * Класс для реализации моков
 *
 * @package src/base/components
 */
class Mock
{
    /**
     * @var array<string, Schema> Mock map
     */
    public array $map;



    /**
     * Mock constructor.
     *
     * @param array $map
     *
     * @throws Exception
     */
    public function __construct( array $map = [] )
    {
        foreach ($map as $promptClass => $schema )
        {
            if ( $schema instanceof Schema )
            {
                $this->map[$promptClass] = $schema;

            } else {

                throw new Exception(
                    sprintf('%s must be an instance of %s', $promptClass, Schema::class)
                );
            }
        }
    }

    /**
     * @param string $promptClass
     *
     * @return ?Schema
     */
    public function get(string $promptClass ): ?Schema
    {
        return $this->map[$promptClass] ?? null;
    }
}