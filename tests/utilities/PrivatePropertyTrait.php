<?php

declare(strict_types=1);

namespace Tests\utilities;

/**
 * Trait for accessing private/protected properties in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait PrivatePropertyTrait
{
    /**
     * Accesses a private or protected property of an object for testing purposes.
     *
     * @param object $object   The object instance.
     * @param string $property The name of the property to access.
     *
     * @return mixed The value of the property.
     */
    private function getPrivateProperty(object $object, string $property)
    {
        $reflection = new \ReflectionClass($object);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);
        return $propertyReflection->getValue($object);
    }

    private function setPrivateProperty(\ReflectionClass $reflection, $instance, string $property, $value): void
    {
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($instance, $value);
    }
}
