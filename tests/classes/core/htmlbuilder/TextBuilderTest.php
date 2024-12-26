<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\TextBuilder;

/**
 * Unit tests for the TextBuilder class.
 *
 * @covers \App\core\htmlbuilder\TextBuilder
 */
class TextBuilderTest extends TestCase
{
    /**
     * Instance of TextBuilder for testing.
     *
     * @var TextBuilder
     */
    private $textBuilder;

    /**
     * Tests the getTextInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper HTML structure and attributes for a text input.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getTextInput
     *
     * @return void
     */
    public function testGetTextInput(): void
    {
        $html = $this->textBuilder->getTextInput(
            'username',
            'JohnDoe',
            25,
            ['class' => 'text-input-class']
        );

        $expected = '<input type="text" name="username" value="JohnDoe" size="25" class="text-input-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getPasswordInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper HTML structure for a password input.
     * - Proper default handling for empty values.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getPasswordInput
     *
     * @return void
     */
    public function testGetPasswordInput(): void
    {
        $html = $this->textBuilder->getPasswordInput(
            'password',
            20,
            ['class' => 'password-class']
        );

        $expected = '<input type="password" name="password" value="" size="20" class="password-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getEmailInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper HTML structure and attributes for an email input.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getEmailInput
     *
     * @return void
     */
    public function testGetEmailInput(): void
    {
        $html = $this->textBuilder->getEmailInput(
            'email',
            'user@example.com',
            30,
            ['class' => 'email-class']
        );

        $expected = '<input type="email" name="email" value="user@example.com" size="30" class="email-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getNumberInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Correct min, max, and value attributes are rendered for a number input.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getNumberInput
     *
     * @return void
     */
    public function testGetNumberInput(): void
    {
        $html = $this->textBuilder->getNumberInput(
            'quantity',
            '10',
            1,
            100,
            ['class' => 'number-class']
        );

        $expected = '<input type="number" name="quantity" value="10" min="1" max="100" class="number-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTextarea method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper rendering of a `<textarea>` element.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getTextarea
     *
     * @return void
     */
    public function testGetTextarea(): void
    {
        $html = $this->textBuilder->getTextarea(
            'comments',
            'Sample content.',
            5,
            50,
            ['class' => 'textarea-class']
        );

        $expected = '<textarea name="comments" rows="5" cols="50" class="textarea-class">Sample content.</textarea>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getUnixTimestampInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper rendering of a number input for Unix timestamps with min/max attributes.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getUnixTimestampInput
     *
     * @return void
     */
    public function testGetUnixTimestampInput(): void
    {
        $html = $this->textBuilder->getUnixTimestampInput(
            'timestamp',
            '1609459200',
            ['class' => 'timestamp-class']
        );

        $expected =
            '<input type="number" name="timestamp" value="1609459200" min="0" ' .
            'max="2147483647" class="timestamp-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getUrlInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper HTML attributes for a URL input.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getUrlInput
     *
     * @return void
     */
    public function testGetUrlInput(): void
    {
        $html = $this->textBuilder->getUrlInput(
            'website',
            'https://example.com',
            ['class' => 'url-class']
        );

        $expected = '<input type="url" name="website" value="https://example.com" class="url-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getHiddenInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper rendering of a hidden input field with appropriate attributes.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getHiddenInput
     *
     * @return void
     */
    public function testGetHiddenInput(): void
    {
        $html = $this->textBuilder->getHiddenInput(
            'hidden_field',
            'hidden_value',
            ['class' => 'hidden-class']
        );

        $expected = '<input type="hidden" name="hidden_field" value="hidden_value" class="hidden-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getSearchInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper rendering of a search input field.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getSearchInput
     *
     * @return void
     */
    public function testGetSearchInput(): void
    {
        $html = $this->textBuilder->getSearchInput(
            'search_query',
            'Search something...',
            ['class' => 'search-class']
        );

        $expected = '<input type="search" name="search_query" value="Search something..." class="search-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTelInput method.
     *
     * This test validates the **exact output** of the method to ensure:
     * - Proper rendering of a telephone input field.
     *
     * @covers \App\core\htmlbuilder\TextBuilder::getTelInput
     *
     * @return void
     */
    public function testGetTelInput(): void
    {
        $html = $this->textBuilder->getTelInput(
            'phone',
            '+123456789',
            ['class' => 'tel-class']
        );

        $expected = '<input type="tel" name="phone" value="+123456789" class="tel-class" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Sets up the test environment.
     *
     * Initializes the TextBuilder with formatted output enabled.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->textBuilder = new TextBuilder(true);
    }
}
