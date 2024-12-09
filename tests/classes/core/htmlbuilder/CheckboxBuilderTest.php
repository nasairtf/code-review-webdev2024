<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\CheckboxBuilder;

/**
 * Unit tests for the CheckboxBuilder class.
 *
 * @covers \App\core\htmlbuilder\CheckboxBuilder
 */
class CheckboxBuilderTest extends TestCase
{
    /**
     * Instance of CheckboxBuilder for testing.
     *
     * @var CheckboxBuilder
     */
    private $checkboxBuilder;

    /**
     * Sets up the test environment by initializing the CheckboxBuilder instance.
     *
     * Enables formatted output for the generated HTML elements.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->checkboxBuilder = new CheckboxBuilder(true);
    }

    /**
     * Tests the getCheckbox method for generating a basic checkbox.
     *
     * Validates that the checkbox includes the correct name, value, state (checked/unchecked),
     * and additional attributes. Ensures proper indentation and strict output matching.
     *
     * @return void
     */
    public function testGetCheckbox(): void
    {
        $html = $this->checkboxBuilder->getCheckbox(
            'accept_terms',
            'yes',
            true,
            false,
            ['class' => 'checkbox-class'],
            2,
            false
        );

        $expected = '  <input type="checkbox" name="accept_terms" value="yes" checked class="checkbox-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getCustomCheckbox method for generating a custom checkbox.
     *
     * Ensures the checkbox includes a specific custom value and attributes,
     * and validates the rendering of the `checked` state.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetCustomCheckbox(): void
    {
        $html = $this->checkboxBuilder->getCustomCheckbox(
            'newsletter',
            'subscribe',
            'subscribe',
            false,
            ['class' => 'custom-checkbox-class'],
            0,
            false
        );

        $expected =
            '<input type="checkbox" name="newsletter" value="subscribe" ' .
            'checked class="custom-checkbox-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLabeledCheckbox method for generating a labeled checkbox.
     *
     * Verifies that the checkbox includes a label rendered correctly after the
     * input element, with all attributes and state properly applied.
     *
     * @return void
     */
    public function testGetLabeledCheckbox(): void
    {
        $html = $this->checkboxBuilder->getLabeledCheckbox(
            'remember_me',
            'yes',
            'Remember Me',
            true,
            false,
            true,
            ['class' => 'labeled-checkbox'],
            0,
            false
        );

        $expected =
            '<input type="checkbox" name="remember_me" value="yes" checked ' .
            'class="labeled-checkbox" /> <label>Remember Me</label>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getCheckboxGroup method for generating a group of checkboxes.
     *
     * Validates that multiple checkboxes are rendered with the correct name,
     * values, states, and additional attributes. Spot-checks specific elements
     * within the group to confirm correct rendering.
     *
     * @return void
     */
    public function testGetCheckboxGroup(): void
    {
        $options = ['opt1' => 'Option 1', 'opt2' => 'Option 2', 'opt3' => 'Option 3'];
        $selectedValues = ['opt1', 'opt3'];
        $html = $this->checkboxBuilder->getCheckboxGroup(
            'choices',
            $selectedValues,
            $options,
            ['class' => 'group-class'],
            0,
            false
        );

        $this->assertStringContainsString('name="choices"', $html);
        $this->assertStringContainsString('value="opt1" checked', $html);
        $this->assertStringContainsString('value="opt2"', $html);
        $this->assertStringContainsString('value="opt3" checked', $html);
    }

    /**
     * Tests the getDisabledCheckboxGroup method for generating a group of disabled checkboxes.
     *
     * Verifies that each checkbox in the group is correctly rendered as disabled,
     * with the appropriate name, values, and attributes.
     *
     * @return void
     */
    public function testGetDisabledCheckboxGroup(): void
    {
        $options = ['opt1' => 'Option 1', 'opt2' => 'Option 2'];
        $html = $this->checkboxBuilder->getDisabledCheckboxGroup(
            'disabled_choices',
            $options,
            0,
            false
        );

        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('value="opt1"', $html);
        $this->assertStringContainsString('value="opt2"', $html);
    }

    /**
     * Tests the getHiddenCheckbox method for generating a hidden checkbox.
     *
     * Validates that the checkbox is correctly styled to be hidden, while still
     * including the correct name, value, and state.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetHiddenCheckbox(): void
    {
        $html = $this->checkboxBuilder->getHiddenCheckbox(
            'hidden_field',
            'hidden_value',
            true,
            0,
            false
        );

        $expected =
            '<input type="checkbox" name="hidden_field" value="hidden_value" ' .
            'checked style="display:none;" />';
        $this->assertSame($expected, $html);
    }
}
