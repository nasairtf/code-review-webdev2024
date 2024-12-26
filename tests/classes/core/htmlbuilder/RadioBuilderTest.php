<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\RadioBuilder;

/**
 * Unit tests for the RadioBuilder class.
 *
 * @covers \App\core\htmlbuilder\RadioBuilder
 */
class RadioBuilderTest extends TestCase
{
    /**
     * Instance of RadioBuilder for testing.
     *
     * @var RadioBuilder
     */
    private $radioBuilder;

    /**
     * Tests the getRadioButton method.
     *
     * Validates that the method generates a simple radio button with the correct attributes.
     *
     * @return void
     */
    public function testGetRadioButton(): void
    {
        $html = $this->radioBuilder->getRadioButton(
            'test_radio',
            'option1',
            'checked',
            'option1',
            ['class' => 'radio-class'],
            2,
            false
        );

        $expected = '<input type="radio" name="test_radio" value="option1" checked class="radio-class">';
        $this->assertStringContainsString($expected, $html);
    }

    /**
     * Tests the getLabeledRadioButton method.
     *
     * Validates the generation of a labeled radio button with the correct attributes and label.
     *
     * @return void
     */
    public function testGetLabeledRadioButton(): void
    {
        $html = $this->radioBuilder->getLabeledRadioButton(
            'test_radio',
            'option1',
            'checked',
            'option1',
            'Option 1',
            true,
            [],
            0,
            false
        );

        $expected = '<input type="radio" name="test_radio" value="option1" checked> Option 1';
        $this->assertStringContainsString($expected, $html);
    }

    /**
     * Tests the getRadioButtonWithID method.
     *
     * Validates that the method generates a radio button with an `id` attribute.
     *
     * @return void
     */
    public function testGetRadioButtonWithID(): void
    {
        $html = $this->radioBuilder->getRadioButtonWithID(
            'test_radio',
            'option1',
            'checked',
            'option1',
            'radio1',
            [],
            0,
            false
        );

        $expected = '<input type="radio" name="test_radio" value="option1" checked id="radio1">';
        $this->assertStringContainsString($expected, $html);
    }

    /**
     * Tests the getRadioGroupWithInlineOption method.
     *
     * Validates the generation of an inline radio group with proper attributes and options.
     *
     * @return void
     */
    public function testGetRadioGroupWithInlineOption(): void
    {
        $html = $this->radioBuilder->getRadioGroupWithInlineOption(
            'test_group',
            'opt1',
            ['opt1' => 'Option 1', 'opt2' => 'Option 2'],
            true,
            ['class' => 'group-class'],
            0,
            false
        );

        $this->assertStringContainsString('Option 1', $html);
        $this->assertStringContainsString('Option 2', $html);
        $this->assertStringContainsString('class="group-class"', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the getDisabledRadioGroup method.
     *
     * Validates that the method generates a radio group with all options disabled.
     *
     * @return void
     */
    public function testGetDisabledRadioGroup(): void
    {
        $html = $this->radioBuilder->getDisabledRadioGroup(
            'disabled_group',
            ['opt1' => 'Disabled Option 1', 'opt2' => 'Disabled Option 2'],
            [],
            0,
            false
        );

        $this->assertStringContainsString('Disabled Option 1', $html);
        $this->assertStringContainsString('Disabled Option 2', $html);
        $this->assertStringContainsString('disabled', $html);
    }

    /**
     * Tests the getRadioGroup method.
     *
     * Validates the generation of a radio group with the correct attributes and selected option.
     *
     * @return void
     */
    public function testGetRadioGroup(): void
    {
        $html = $this->radioBuilder->getRadioGroup(
            'group',
            'opt2',
            ['opt1' => 'Option 1', 'opt2' => 'Option 2'],
            [],
            0,
            false
        );

        $this->assertStringContainsString('Option 1', $html);
        $this->assertStringContainsString('Option 2', $html);
        $this->assertStringContainsString('checked', $html); // Ensure "opt2" is checked
    }

    /**
     * Sets up the RadioBuilder instance for testing.
     *
     * Initializes the builder with formatted output enabled.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Enable formatted output for tests
        $this->radioBuilder = new RadioBuilder(true);
    }
}
