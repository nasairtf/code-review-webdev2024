<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\HtmlBuildUtility;

/**
 * Unit tests for the HtmlBuildUtility class.
 *
 * @covers \App\core\htmlbuilder\HtmlBuildUtility
 */
class HtmlBuildUtilityTest extends TestCase
{
    /**
     * Tests that formatOutput adds padding and a newline when required.
     *
     * Validates that the method correctly formats a string with the specified
     * padding level and includes a newline if requested.
     *
     * @return void
     */
    public function testFormatOutputWithPaddingAndNewline(): void
    {
        $result = HtmlBuildUtility::formatOutput('test', true, true, 4);
        $expected = PHP_EOL . '    test';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that formatOutput returns the string as-is when no formatting is applied.
     *
     * Verifies the behavior when padding and newline are disabled.
     *
     * @return void
     */
    public function testFormatOutputWithoutPadding(): void
    {
        $result = HtmlBuildUtility::formatOutput('test', false, false);
        $expected = 'test';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that formatParts joins parts with newlines when formatting is enabled.
     *
     * Confirms that individual parts are concatenated with a newline between each.
     *
     * @return void
     */
    public function testFormatPartsWithNewlines(): void
    {
        $result = HtmlBuildUtility::formatParts(['part1', 'part2'], true);
        $expected = "part1\npart2";
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that formatParts joins parts without newlines when formatting is disabled.
     *
     * Ensures parts are concatenated directly without any separators.
     *
     * @return void
     */
    public function testFormatPartsWithoutNewlines(): void
    {
        $result = HtmlBuildUtility::formatParts(['part1', 'part2'], false);
        $expected = 'part1part2';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that escape properly converts special characters to HTML entities.
     *
     * Validates that `<` and `>` characters are correctly escaped as `&lt;` and `&gt;`.
     *
     * @return void
     */
    public function testEscapeHtmlSpecialChars(): void
    {
        $result = HtmlBuildUtility::escape('<div>', false);
        $expected = '&lt;div&gt;';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that escape skips escaping trusted HTML content.
     *
     * Ensures that content marked as trusted is returned unmodified.
     *
     * @return void
     */
    public function testEscapeTrustedHtml(): void
    {
        $result = HtmlBuildUtility::escape('<div>', true);
        $expected = '<div>';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that buildAttributes converts an associative array into a valid attribute string.
     *
     * Validates the conversion of an array to a properly formatted HTML attribute string.
     *
     * @return void
     */
    public function testBuildAttributes(): void
    {
        $attributes = ['class' => 'btn', 'data-test' => 'value'];
        $result = HtmlBuildUtility::buildAttributes($attributes);
        $expected = ' class="btn" data-test="value"';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that padLeftZero correctly pads numbers with leading zeros.
     *
     * Verifies that the number is padded to the specified total length with zeros.
     *
     * @return void
     */
    public function testPadLeftZero(): void
    {
        $result = HtmlBuildUtility::padLeftZero(42, 5);
        $expected = '00042';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that padLeftString pads a string with spaces on the left.
     *
     * Confirms that the string is correctly aligned to the right by adding spaces to the left.
     *
     * @return void
     */
    public function testPadLeftString(): void
    {
        $result = HtmlBuildUtility::padLeftString('test', 4);
        $expected = '    test';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that padRightString pads a string with spaces on the right.
     *
     * Confirms that the string is correctly aligned to the left by adding spaces to the right.
     *
     * @return void
     */
    public function testPadRightString(): void
    {
        $result = HtmlBuildUtility::padRightString('test', 4);
        $expected = 'test    ';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getStatusColor returns the correct color for a known status.
     *
     * Validates the returned color code for a predefined status string like "success".
     *
     * @return void
     */
    public function testGetStatusColorForKnownStatus(): void
    {
        $result = HtmlBuildUtility::getStatusColor('success');
        $expected = '#00FF00';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getStatusColor returns the default color for an unknown status.
     *
     * Verifies that the method returns a fallback color when the status is not recognized.
     *
     * @return void
     */
    public function testGetStatusColorForUnknownStatus(): void
    {
        $result = HtmlBuildUtility::getStatusColor('unknown');
        $expected = '#FFFFFF';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getCycledColor returns the next color in the sequence.
     *
     * Ensures that the next color in a cyclic array is returned based on the input.
     *
     * @return void
     */
    public function testGetCycledColor(): void
    {
        $colors = ['#CCCCCC', '#FFFFFF'];
        $result = HtmlBuildUtility::getCycledColor('#CCCCCC', $colors);
        $expected = '#FFFFFF';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getCycledColor wraps around to the first color in the sequence.
     *
     * Verifies that the method correctly loops back to the start of the array.
     *
     * @return void
     */
    public function testGetCycledColorWrapsAround(): void
    {
        $colors = ['#CCCCCC', '#FFFFFF'];
        $result = HtmlBuildUtility::getCycledColor('#FFFFFF', $colors);
        $expected = '#CCCCCC';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getAlternatingGrays alternates between two shades of gray.
     *
     * Validates the correct transition between two predefined gray shades.
     *
     * @return void
     */
    public function testGetAlternatingGrays(): void
    {
        $result = HtmlBuildUtility::getAlternatingGrays('#C0C0C0');
        $expected = '#CCCCCC';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getAlternatingBlues alternates between two shades of blue.
     *
     * Confirms the correct alternation between two blue shades for styling purposes.
     *
     * @return void
     */
    public function testGetAlternatingBlues(): void
    {
        $result = HtmlBuildUtility::getAlternatingBlues('#DDEEFF');
        $expected = '#99CCFF';
        $this->assertSame($expected, $result);
    }

    /**
     * Tests that getAlternatingGreens alternates between two shades of green.
     *
     * Verifies the output alternates between two predefined green shades.
     *
     * @return void
     */
    public function testGetAlternatingGreens(): void
    {
        $result = HtmlBuildUtility::getAlternatingGreens('#E0FFE0');
        $expected = '#A0FFA0';
        $this->assertSame($expected, $result);
    }
}
