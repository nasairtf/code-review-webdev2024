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

    /**
     * Helper method to set a private or protected property of an object for testing purposes.
     *
     * @param object $object   The object instance.
     * @param string $property The name of the property to set.
     * @param mixed  $value    The value to assign to the property.
     *
     * @return void
     */
    private function setPrivateProperty(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionClass($object);
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);
    }

    /**
     * Helper method to assert a mocked dependency is correctly set as a private property.
     *
     * @param mixed  $expected  The expected mock object.
     * @param string $property  The private property name to check.
     * @param object $instance  The instance containing the private property.
     *
     * @return void
     */
    public function assertDependency($expected, string $property, $instance): void
    {
        $actual = $this->getPrivateProperty($instance, $property);
        $this->assertSame($expected, $actual, "Failed asserting that '{$property}' is correctly set.");
    }
}
