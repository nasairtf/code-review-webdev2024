<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\BaseHtmlBuilder;

/**
 * Unit tests for the BaseHtmlBuilder class.
 *
 * @covers \App\core\htmlbuilder\BaseHtmlBuilder
 */
class BaseHtmlBuilderTest extends TestCase
{
    /**
     * Instance of BaseHtmlBuilder for testing.
     *
     * @var BaseHtmlBuilder
     */
    private $baseHtmlBuilder;

    /**
     * Tests the getBreak method for generating a line break.
     *
     * Verifies the correct generation of a `<br />` element with padding.
     *
     * @return void
     */
    public function testGetBreak(): void
    {
        $html = $this->baseHtmlBuilder->getBreak(2);
        $expected = '  <br />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLine method for generating a horizontal line with attributes.
     *
     * Validates the output of an `<hr />` element with custom attributes.
     *
     * @return void
     */
    public function testGetLine(): void
    {
        $html = $this->baseHtmlBuilder->getLine(['class' => 'line-class'], 0);
        $expected = '<hr class="line-class"/>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLink method for generating a hyperlink element.
     *
     * Ensures that the method generates a valid `<a>` element with attributes and padding.
     *
     * @return void
     */
    public function testGetLink(): void
    {
        $html = $this->baseHtmlBuilder->getLink(
            'https://example.com',
            'Example',
            ['target' => '_blank'],
            2
        );
        $expected = '  <a href="https://example.com" target="_blank">Example</a>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getEmailLink method for generating a mailto link.
     *
     * Confirms the proper output of a `<a>` element for email addresses.
     *
     * @return void
     */
    public function testGetEmailLink(): void
    {
        $html = $this->baseHtmlBuilder->getEmailLink(
            'test@example.com',
            'Send Email',
            [],
            0
        );
        $expected = '<a href="mailto:test@example.com">Send Email</a>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getFileInput method for generating a file input element.
     *
     * Validates the output of a `<input type="file" />` element with attributes.
     *
     * @return void
     */
    public function testGetFileInput(): void
    {
        $html = $this->baseHtmlBuilder->getFileInput(
            'upload_file',
            ['class' => 'file-input'],
            0
        );
        $expected = '<input type="file" name="upload_file" class="file-input" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getImage method for generating an image element.
     *
     * Ensures that an `<img />` element with `src`, `alt`, and additional attributes is correctly generated.
     *
     * @return void
     */
    public function testGetImage(): void
    {
        $html = $this->baseHtmlBuilder->getImage(
            'image.png',
            'Test Image',
            ['class' => 'image-class'],
            0
        );
        $expected = '<img class="image-class" src="image.png" alt="Test Image" />';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLabel method for generating a label element.
     *
     * Validates that a `<label>` element with `for` and custom attributes is generated correctly.
     *
     * @return void
     */
    public function testGetLabel(): void
    {
        $html = $this->baseHtmlBuilder->getLabel(
            'input-id',
            'Test Label',
            ['class' => 'label-class'],
            0
        );
        $expected = '<label class="label-class" for="input-id">Test Label</label>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getParagraph method for generating a paragraph element.
     *
     * Verifies the output of a `<p>` element with content and custom attributes.
     *
     * @return void
     */
    public function testGetParagraph(): void
    {
        $html = $this->baseHtmlBuilder->getParagraph(
            'This is a test paragraph.',
            ['class' => 'paragraph-class'],
            0
        );
        $expected = '<p class="paragraph-class">' . PHP_EOL
            . 'This is a test paragraph.' . PHP_EOL
            . '</p>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getSpan method for generating a span element.
     *
     * Validates the output of a `<span>` element with content and custom attributes.
     *
     * @return void
     */
    public function testGetSpan(): void
    {
        $html = $this->baseHtmlBuilder->getSpan(
            'Span content',
            ['class' => 'span-class'],
            0
        );
        $expected = '<span class="span-class">Span content</span>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getForm method for generating a form element.
     *
     * Ensures that a `<form>` element with attributes and nested content is generated correctly.
     *
     * @return void
     */
    public function testGetForm(): void
    {
        $html = $this->baseHtmlBuilder->getForm(
            '/submit',
            'post',
            '<input type="text" name="test" />',
            ['class' => 'form-class'],
            0,
            true
        );
        $expected = '<form class="form-class" action="/submit" method="post">' . PHP_EOL
            . '<input type="text" name="test" />' . PHP_EOL
            . '</form>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getHeading method for generating a heading element.
     *
     * Validates that the method generates the correct heading level and attributes.
     *
     * @return void
     */
    public function testGetHeading(): void
    {
        $html = $this->baseHtmlBuilder->getHeading(
            'Heading Content',
            2,
            ['class' => 'heading-class'],
            0
        );
        $expected = '<h2 class="heading-class">Heading Content</h2>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getList method for generating an unordered list.
     *
     * Ensures that the method generates a valid `<ul>` or `<ol>` element with nested `<li>` items.
     *
     * @return void
     */
    public function testGetList(): void
    {
        $html = $this->baseHtmlBuilder->getList(
            ['Item 1', 'Item 2'],
            false,
            ['class' => 'list-class'],
            [],
            0
        );
        $expected = '<ul class="list-class">' . PHP_EOL
            . '  <li>Item 1</li>' . PHP_EOL
            . '  <li>Item 2</li>' . PHP_EOL
            . '</ul>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getNavBar method for generating a navigation bar.
     *
     * Validates that the method generates a `<ul>` element with nested `<li>` and `<a>` links.
     *
     * @return void
     */
    public function testGetNavBar(): void
    {
        $html = $this->baseHtmlBuilder->getNavBar(
            ['https://example.com' => 'Example'],
            ['class' => 'navbar-class'],
            [],
            [],
            0
        );
        $expected = '<ul class="navbar-class">' . PHP_EOL
            . '  <li><a href="https://example.com">Example</a></li>' . PHP_EOL
            . '</ul>';
        $this->assertSame($expected, $html);
    }

    /**
     * Sets up the test environment by initializing the BaseHtmlBuilder instance.
     *
     * Enables formatted output for the tested methods.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->baseHtmlBuilder = new BaseHtmlBuilder(true);
    }
}
