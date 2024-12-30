<?php

declare(strict_types=1);

namespace Tests\utilities\assertions;

use Tests\utilities\helpers\PrivatePropertyHelperTrait;

/**
 * Trait for asserting private/protected property dependencies in unit tests.
 *
 * NOTE: This trait is intended exclusively for use in test classes and
 * should never be used in production code.
 */
trait AssertPrivateDependenciesTrait
{
    use PrivatePropertyHelperTrait;

    /**
     * Helper method to assert a mocked dependency is correctly set as a private property.
     *
     * @param mixed  $expected  The expected mock object.
     * @param string $property  The private property name to check.
     * @param object $instance  The instance containing the private property.
     *
     * @return void
     */
    public function assertPrivateDependency($expected, string $property, $instance): void
    {
        $actual = $this->getPrivateProperty($instance, $property);
        $this->assertSame($expected, $actual, "Failed asserting that '{$property}' is correctly set.");
    }
}
