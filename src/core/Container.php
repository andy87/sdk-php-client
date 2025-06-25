<?php

namespace andy87\sdk\client\core;

use Exception;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 *
 * Контейнер для хранения зависимостей, таких как запросы, ответы, операторы, логгеры и кэш.
 *
 * @package src/core
 */
class Container implements ContainerInterface
{
    /**
     * @var string $REGISTRY_CLASS Класс реестра, который будет использоваться для хранения классов.
     */
    protected const REGISTRY_CLASS = ClassRegistry::class;



    /**
     * @var ClassRegistry $classRegistry Объект содержащий список используемых классов.
     */
    private ClassRegistry $classRegistry;

    /**
     * @var array $instances Массив для хранения созданных объектов.
     */
    private array $instances = [];



    /**
     * Конструктор
     *
     * @param array $registryMapping
     */
    public function __construct( array $registryMapping )
    {
        $classRegistry = static::REGISTRY_CLASS;

        $this->classRegistry = new $classRegistry($registryMapping);
    }

    /**
     * Получает объект по заданному ID из контейнера.
     * Если объект не существует, он будет создан на основе класса или вызываемого объекта.
     *
     * @param string $id
     *
     * @return object
     *
     * @throws Exception
     */
    public function get( string $id ): object
    {
        if ( !isset($this->instances[$id]) )
        {
            if ($class = $this->classRegistry->getClass($id))
            {
                if ( class_exists($class) )
                {
                    $this->instances[$id] = new $class();

                } elseif ( is_callable($class) ) {

                    $this->instances[$id] = $class();
                }
            }
        }

        return $this->instances[$id];
    }

    /**
     * Проверяет, существует ли объект в контейнере по заданному ID.
     *
     * @param string $id
     *
     * @return bool
     */
    public function has( string $id ): bool
    {
        return isset($this->classList[$id]) && is_object($this->classList[$id] ?? null);
    }

    /**
     * Возвращает класс реестра по ID.
     *
     * @param string $id ID класса, который нужно получить из реестра.
     *
     * @return ?string
     *
     * @throws Exception
     */
    public function getClassRegistry( string $id ): ?string
    {
        return $this->classRegistry->getClass( $id );
    }
}