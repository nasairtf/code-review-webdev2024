<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\FormElementsBuilder;
use App\core\htmlbuilder\HtmlBuilder;

/**
 * Unit tests for the FormElementsBuilder class.
 *
 * @covers \App\core\htmlbuilder\FormElementsBuilder
 */
class FormElementsBuilderTest extends TestCase
{
    /**
     * Instance of FormElementsBuilder for testing.
     *
     * @var FormElementsBuilder
     */
    private $formElementsBuilder;

    /**
     * Tests the buildLineTableCell method for generating a line in a table cell.
     *
     * Verifies that a `<tr>` element with a centered `<hr />` is correctly constructed.
     *
     * @return void
     */
    public function testBuildLineTableCell(): void
    {
        $html = $this->formElementsBuilder->buildLineTableCell(3, 2);
        $expected = '<tr bgcolor="#FFFFFF"><td colspan="3" align="center"><hr/></td></tr>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the buildSemesterChooserActionButtons method for generating reset and submit buttons.
     *
     * Confirms that the method outputs two `<button>` elements with appropriate styles and attributes.
     *
     * @return void
     */
    public function testBuildSemesterChooserActionButtons(): void
    {
        $html = $this->formElementsBuilder->buildSemesterChooserActionButtons('Submit', 0);
        $expected = '<button type="reset" style="width: 120px;">Reset</button>' . PHP_EOL
            . '<button type="submit" style="width: 120px;" name="Submit">Generate</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the buildProposalActionTrigger method for generating a hidden input and a submit button.
     *
     * Validates the correct construction of a hidden input element and a button with inline styles.
     *
     * @return void
     */
    public function testBuildProposalActionTrigger(): void
    {
        $html = $this->formElementsBuilder->buildProposalActionTrigger(
            'save',
            'Save Proposal',
            '12345',
            '#FF5733',
            4
        );
        $expected = '    <input type="hidden" name="i" value="12345" />' . PHP_EOL
            . '    <button type="submit" style="width: 120px; background-color: #FF5733;" name="save">'
            . 'Save Proposal</button>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the buildSemesterProposalListButtonForm method for generating a form with a button.
     *
     * Verifies that the method outputs a `<form>` element with nested input and button elements.
     *
     * @return void
     */
    public function testBuildSemesterProposalListButtonForm(): void
    {
        $html = $this->formElementsBuilder->buildSemesterProposalListButtonForm(
            '/edit-proposal',
            '12345',
            '#FF5733',
            2
        );
        $expected = '  <form enctype="multipart/form-data" target="_blank" style="margin: 0px; padding: 0px;" '
            . 'action="/edit-proposal" method="post">' . PHP_EOL
            . '    <input type="hidden" name="i" value="12345" />' . PHP_EOL
            . '    <button type="submit" style="width: 120px; background-color: #FF5733;" '
            . 'name="select">Edit</button>' . PHP_EOL
            . '  </form>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the buildSemesterProposalListFormRow method for generating a table row for a proposal.
     *
     * Ensures that the `<tr>` element and nested `<td>` elements are constructed correctly.
     *
     * @return void
     */
    public function testBuildSemesterProposalListFormRow(): void
    {
        $proposal = [
            'ObsApp_id' => '12345',
            'code' => 'P-2024',
            'semesterYear' => '2024',
            'semesterCode' => 'A',
            'ProgramNumber' => 1,
            'InvLastName1' => 'Doe',
        ];
        $html = $this->formElementsBuilder->buildSemesterProposalListFormRow(
            '/edit-proposal',
            $proposal,
            '#F0F0F0',
            2
        );
        $this->assertStringContainsString('<tr style="height: 32px; background-color: #F0F0F0">', $html);
        $this->assertStringContainsString('<td style="width: 75px;">&nbsp;</td>', $html);
        $teststr1 = '<td align="right" valign="middle" style="padding: 0px 5px 0px 5px;">';
        $this->assertStringContainsString($teststr1, $html);
        $teststr2 = '<button type="submit" style="width: 120px; background-color: lightgreen;" '
            . 'name="select">Edit</button>';
        $this->assertStringContainsString($teststr2, $html);
    }

    /**
     * Tests the buildDatePulldowns method for generating dropdowns for date selection.
     *
     * Verifies the construction of `<select>` elements for year, month, and day with correct options.
     *
     * @return void
     */
    public function testBuildDatePulldowns(): void
    {
        $names = ['year' => 'year', 'month' => 'month', 'day' => 'day'];
        $options = ['year' => '2024', 'month' => '4', 'day' => '15'];
        $html = $this->formElementsBuilder->buildDatePulldowns($names, $options, 2020, 2025, 0);
        $this->assertStringContainsString('<select name="year">', $html);
        $this->assertStringContainsString('<option value="2024" selected>2024</option>', $html);
        $this->assertStringContainsString('<select name="month">', $html);
        $this->assertStringContainsString('<option value="4" selected>Apr</option>', $html);
        $this->assertStringContainsString('<select name="day">', $html);
        $this->assertStringContainsString('<option value="15" selected>15</option>', $html);
    }

    /**
     * Tests the buildThreeNumberPulldowns method for generating three numeric dropdowns.
     *
     * Confirms that three `<select>` elements with correct options are generated.
     *
     * @return void
     */
    public function testBuildThreeNumberPulldowns(): void
    {
        $names = [0 => 'num1', 1 => 'num2', 2 => 'num3'];
        $options = [0 => '3', 1 => '5', 2 => '7'];
        $html = $this->formElementsBuilder->buildThreeNumberPulldowns($names, $options, 0);
        $this->assertStringContainsString('<select name="num1">', $html);
        $this->assertStringContainsString('<option value="3" selected>3</option>', $html);
        $this->assertStringContainsString('<select name="num2">', $html);
        $this->assertStringContainsString('<option value="5" selected>5</option>', $html);
        $this->assertStringContainsString('<select name="num3">', $html);
        $this->assertStringContainsString('<option value="7" selected>7</option>', $html);
    }

    /**
     * Tests the buildSemesterProgramsPulldown method for generating a semester dropdown.
     *
     * Validates the creation of a `<select>` element with options for semesters.
     *
     * @return void
     */
    public function testBuildSemesterProgramsPulldown(): void
    {
        $options = ['SP24' => 'Spring 2024', 'FA24' => 'Fall 2024'];
        $html = $this->formElementsBuilder->buildSemesterProgramsPulldown(
            'semester',
            'FA24',
            $options,
            0
        );
        $this->assertStringContainsString('<select name="semester">', $html);
        $this->assertStringContainsString('<option value="FA24" selected>Fall 2024</option>', $html);
    }

    /**
     * Sets up the test environment with a mocked HtmlBuilder instance.
     *
     * Initializes the FormElementsBuilder with formatted output enabled for all tests.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $htmlBuilder = new HtmlBuilder(true); // Using formatted output for better readability.
        $this->formElementsBuilder = new FormElementsBuilder(true, $htmlBuilder);
    }
}
