<?php

declare(strict_types=1);

namespace Tests\App\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\ButtonBuilder;

/**
 * Unit tests for the ButtonBuilder class.
 *
 * @covers \App\core\htmlbuilder\ButtonBuilder
 */
class ButtonBuilderTest extends TestCase
{
    /**
     * Instance of ButtonBuilder for testing.
     *
     * @var ButtonBuilder
     */
     private $buttonBuilder;

    /**
     * Sets up the test environment by initializing the ButtonBuilder instance.
     *
     * Enables formatted output for the generated HTML elements.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->buttonBuilder = new ButtonBuilder(true);
    }

    /**
     * Tests the getButton method for generating a basic button.
     *
     * Validates that the button is correctly rendered with its label, type,
     * name, and additional attributes. Ensures proper indentation and formatting.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetButton(): void
    {
        $html = $this->buttonBuilder->getButton(
            'Click Me',
            'button',
            'myButton',
            ['class' => 'btn-primary'],
            2,
            false
        );

        $expected = '  <button type="button" class="btn-primary" name="myButton">Click Me</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getSubmitButton method for generating a submit button.
     *
     * Ensures that the generated submit button includes the correct name, label,
     * and attributes. Validates strict output formatting.
     *
     * @return void
     */
    public function testGetSubmitButton(): void
    {
        $html = $this->buttonBuilder->getSubmitButton(
            'submitBtn',
            'Submit',
            ['class' => 'btn-submit'],
            0,
            false
        );

        $expected = '<button type="submit" class="btn-submit" name="submitBtn">Submit</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getDisabledButton method for generating a disabled button.
     *
     * Verifies that the button includes the "disabled" attribute and all other
     * required elements, such as the label, type, and name.
     *
     * @return void
     */
    public function testGetDisabledButton(): void
    {
        $html = $this->buttonBuilder->getDisabledButton(
            'Disabled Button',
            'button',
            'disabledBtn',
            ['class' => 'btn-disabled'],
            0,
            false
        );

        $expected =
            '<button type="button" class="btn-disabled" disabled="disabled" ' .
            'name="disabledBtn">Disabled Button</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getResetButton method for generating a reset button.
     *
     * Ensures that the reset button is rendered with the correct type and
     * attributes, and that it includes the appropriate label.
     *
     * @return void
     */
    public function testGetResetButton(): void
    {
        $html = $this->buttonBuilder->getResetButton(
            'Reset',
            ['class' => 'btn-reset'],
            0,
            false
        );

        $expected = '<button type="reset" class="btn-reset">Reset</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLinkButton method for generating a link styled as a button.
     *
     * Verifies that the anchor element includes the correct URL, label, and
     * additional attributes, and ensures the output matches the expected format.
     *
     * @return void
     */
    public function testGetLinkButton(): void
    {
        $html = $this->buttonBuilder->getLinkButton(
            'Click Here',
            'https://example.com',
            ['class' => 'btn-link'],
            0,
            false
        );

        $expected = '<a href="https://example.com" class="btn-link">Click Here</a>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getIconButton method for generating a button with an icon.
     *
     * Ensures that the icon is correctly nested within the button and that all
     * attributes, including the class, type, and name, are rendered as expected.
     *
     * @return void
     */
    public function testGetIconButton(): void
    {
        $html = $this->buttonBuilder->getIconButton(
            'Download',
            'icon-download',
            'button',
            'iconButton',
            ['class' => 'btn-icon'],
            0,
            true
        );

        $expected =
            '<button type="button" class="btn-icon" name="iconButton">' .
            '<i class="icon-download"></i> Download</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getButtonGroup method for generating a group of buttons.
     *
     * Validates that multiple buttons are rendered as part of a single group,
     * with each button including the correct type, name, label, and attributes.
     *
     * @return void
     */
    public function testGetButtonGroup(): void
    {
        $buttons = [
            [
                'name' => 'submit1',
                'label' => 'Submit',
                'type' => 'submit',
                'attributes' => ['class' => 'btn-submit']
            ],
            [
                'name' => 'reset1',
                'label' => 'Reset',
                'type' => 'reset',
                'attributes' => ['class' => 'btn-reset']
            ]
        ];

        $html = $this->buttonBuilder->getButtonGroup($buttons, 0, false);

        $expected =
            '<button type="submit" class="btn-submit" name="submit1">Submit</button>' .
            '<button type="reset" class="btn-reset" name="reset1">Reset</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getConfirmButton method for generating a confirmation button.
     *
     * Validates that the button includes an `onclick` attribute for triggering
     * a confirmation dialog, along with the correct type, name, and label.
     *
     * @return void
     */
    public function testGetConfirmButton(): void
    {
        $html = $this->buttonBuilder->getConfirmButton(
            'Confirm Action',
            'Are you sure?',
            'button',
            'confirmBtn',
            ['class' => 'btn-confirm'],
            0,
            false
        );

        $expected =
            '<button type="button" class="btn-confirm" onclick="return confirm(\'Are you sure?\')" ' .
            'name="confirmBtn">Confirm Action</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getDropdownButton method for generating a dropdown button.
     *
     * Validates that the dropdown button includes the correct label and that
     * the dropdown container renders the associated items as clickable links.
     * Ensures attributes and formatting are applied correctly.
     *
     * @return void
     */
    public function testGetDropdownButton(): void
    {
        $dropdownItems = [
            ['label' => 'Item 1', 'link' => '#item1'],
            ['label' => 'Item 2', 'link' => '#item2']
        ];

        $html = $this->buttonBuilder->getDropdownButton(
            'Menu',
            $dropdownItems,
            'button',
            'menuButton',
            ['class' => 'btn-dropdown'],
            ['class' => 'dropdown-container'],
            ['class' => 'dropdown-content'],
            0,
            false
        );

        $expected =
            '<div class="dropdown-container">' .
            '<button type="button" class="btn-dropdown" name="menuButton">Menu</button>' .
            '<div class="dropdown-content">' .
            '<a href="#item1">Item 1</a>' .
            '<a href="#item2">Item 2</a>' .
            '</div></div>';
        $this->assertSame($expected, $html);
    }
}
