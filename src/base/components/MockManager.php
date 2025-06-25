<?php

namespace andy87\sdk\client\base\components;

use Exception;
use andy87\sdk\client\base\modules\AbstractMock;

/**
 * Класс для реализации моков
 *
 * @package src/base/components
 */
class MockManager
{
    /**
     * @var AbstractMock[]|array<string, class-string<AbstractMock>> Mock map
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
     * @return ?AbstractMock
     */
    public function get( string $promptClass ): ?AbstractMock
    {
        return $this->map[$promptClass] ?? null;
    }
}