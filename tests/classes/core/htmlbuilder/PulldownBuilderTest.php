<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\PulldownBuilder;

/**
 * Unit tests for the PulldownBuilder class.
 *
 * @covers \App\core\htmlbuilder\PulldownBuilder
 */
class PulldownBuilderTest extends TestCase
{
    /**
     * Instance of PulldownBuilder for testing.
     *
     * @var PulldownBuilder
     */
    private $pulldownBuilder;

    /**
     * Sets up the test environment by initializing the PulldownBuilder instance.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->pulldownBuilder = new PulldownBuilder(true);
    }

    /**
     * Tests the getPulldown method for generating a single-select dropdown.
     *
     * Validates the exact output, including selected options and HTML structure.
     *
     * @return void
     */
    public function testGetPulldown(): void
    {
        $html = $this->pulldownBuilder->getPulldown(
            'fruit',
            'apple',
            ['apple' => 'Apple', 'banana' => 'Banana'],
            ['class' => 'dropdown'],
            0,
            false
        );
        $expected = '<select name="fruit" class="dropdown">' . PHP_EOL
            . '  <option value="apple" selected>Apple</option>' . PHP_EOL
            . '  <option value="banana">Banana</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getMultiSelectPulldown method for generating a multi-select dropdown.
     *
     * Validates the inclusion of multiple selected options.
     *
     * @return void
     */
    public function testGetMultiSelectPulldown(): void
    {
        $html = $this->pulldownBuilder->getMultiSelectPulldown(
            'fruit',
            ['apple', 'banana'],
            ['apple' => 'Apple', 'banana' => 'Banana', 'cherry' => 'Cherry'],
            ['class' => 'multi-dropdown'],
            0,
            false
        );
        $expected = '<select name="fruit" class="multi-dropdown" multiple="multiple">' . PHP_EOL
            . '  <option value="apple" selected>Apple</option>' . PHP_EOL
            . '  <option value="banana" selected>Banana</option>' . PHP_EOL
            . '  <option value="cherry">Cherry</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getGroupedPulldown method for generating a dropdown with optgroups.
     *
     * Validates the presence of correctly labeled groups and selected options.
     *
     * @return void
     */
    public function testGetGroupedPulldown(): void
    {
        $groups = [
            'Fruits' => ['apple' => 'Apple', 'banana' => 'Banana'],
            'Vegetables' => ['carrot' => 'Carrot', 'spinach' => 'Spinach'],
        ];
        $html = $this->pulldownBuilder->getGroupedPulldown(
            'food',
            'carrot',
            $groups,
            ['class' => 'grouped-dropdown'],
            0,
            false
        );
        $expected = '<select name="food" class="grouped-dropdown">' . PHP_EOL
            . '  <optgroup label="Fruits">' . PHP_EOL
            . '    <option value="apple">Apple</option>' . PHP_EOL
            . '    <option value="banana">Banana</option>' . PHP_EOL
            . '  </optgroup>' . PHP_EOL
            . '  <optgroup label="Vegetables">' . PHP_EOL
            . '    <option value="carrot" selected>Carrot</option>' . PHP_EOL
            . '    <option value="spinach">Spinach</option>' . PHP_EOL
            . '  </optgroup>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getDisabledPulldown method for generating a disabled dropdown.
     *
     * Validates the presence of the `disabled` attribute in the generated HTML.
     *
     * @return void
     */
    public function testGetDisabledPulldown(): void
    {
        $html = $this->pulldownBuilder->getDisabledPulldown(
            'fruit',
            ['apple' => 'Apple', 'banana' => 'Banana'],
            0,
            false
        );
        $expected = '<select name="fruit" disabled="disabled">' . PHP_EOL
            . '  <option value="apple">Apple</option>' . PHP_EOL
            . '  <option value="banana">Banana</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getNumbersPulldown method for generating a numeric range dropdown.
     *
     * Validates correct numeric values and the selected option.
     *
     * @return void
     */
    public function testGetNumbersPulldown(): void
    {
        $html = $this->pulldownBuilder->getNumbersPulldown(
            'numbers',
            '5',
            1,
            10,
            false,
            ['class' => 'numbers-dropdown'],
            0,
            false
        );
        $expected = '<select name="numbers" class="numbers-dropdown">' . PHP_EOL
            . '  <option value="1">1</option>' . PHP_EOL
            . '  <option value="2">2</option>' . PHP_EOL
            . '  <option value="3">3</option>' . PHP_EOL
            . '  <option value="4">4</option>' . PHP_EOL
            . '  <option value="5" selected>5</option>' . PHP_EOL
            . '  <option value="6">6</option>' . PHP_EOL
            . '  <option value="7">7</option>' . PHP_EOL
            . '  <option value="8">8</option>' . PHP_EOL
            . '  <option value="9">9</option>' . PHP_EOL
            . '  <option value="10">10</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getYesNoPulldown method for generating a Yes/No dropdown.
     *
     * Validates that the generated pulldown contains the correct "Yes" and "No" options,
     * with the selected value properly applied.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetYesNoPulldown(): void
    {
        $html = $this->pulldownBuilder->getYesNoPulldown(
            'confirm',
            '1',
            [],
            0,
            false
        );
        $expected = '<select name="confirm">' . PHP_EOL
            . '  <option value="1" selected>Yes</option>' . PHP_EOL
            . '  <option value="0">No</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getSemestersPulldown method for generating a semester dropdown.
     *
     * @return void
     */
    public function testGetSemestersPulldown(): void
    {
        $html = $this->pulldownBuilder->getSemestersPulldown(
            'semester',
            'A',
            [],
            0,
            false
        );
        $expected = '<select name="semester">' . PHP_EOL
            . '  <option value="A" selected>Spring</option>' . PHP_EOL
            . '  <option value="B">Fall</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getYearsPulldown method for generating a years dropdown.
     *
     * @return void
     */
    public function testGetYearsPulldown(): void
    {
        $html = $this->pulldownBuilder->getYearsPulldown(
            'year',
            '2023',
            2020,
            2025,
            [],
            0,
            false
        );
        $expected = '<select name="year">' . PHP_EOL
            . '  <option value="2020">2020</option>' . PHP_EOL
            . '  <option value="2021">2021</option>' . PHP_EOL
            . '  <option value="2022">2022</option>' . PHP_EOL
            . '  <option value="2023" selected>2023</option>' . PHP_EOL
            . '  <option value="2024">2024</option>' . PHP_EOL
            . '  <option value="2025">2025</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getMonthsPulldown method for generating a numeric months dropdown.
     *
     * Validates that the generated pulldown contains all 12 months represented as numeric
     * options (01-12), with the correct value selected.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetMonthsPulldown(): void
    {
        $html = $this->pulldownBuilder->getMonthsPulldown(
            'month',
            '2',
            true,
            [],
            0,
            false
        );
        $expected = '<select name="month">' . PHP_EOL
            . '  <option value="1">01</option>' . PHP_EOL
            . '  <option value="2" selected>02</option>' . PHP_EOL
            . '  <option value="3">03</option>' . PHP_EOL
            . '  <option value="4">04</option>' . PHP_EOL
            . '  <option value="5">05</option>' . PHP_EOL
            . '  <option value="6">06</option>' . PHP_EOL
            . '  <option value="7">07</option>' . PHP_EOL
            . '  <option value="8">08</option>' . PHP_EOL
            . '  <option value="9">09</option>' . PHP_EOL
            . '  <option value="10">10</option>' . PHP_EOL
            . '  <option value="11">11</option>' . PHP_EOL
            . '  <option value="12">12</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getShortMonthNamesPulldown method for generating a short month names dropdown.
     *
     * @return void
     */
    public function testGetShortMonthNamesPulldown(): void
    {
        $html = $this->pulldownBuilder->getShortMonthNamesPulldown(
            'month',
            '3',
            [],
            0,
            false
        );
        $expected = '<select name="month">' . PHP_EOL
            . '  <option value="1">Jan</option>' . PHP_EOL
            . '  <option value="2">Feb</option>' . PHP_EOL
            . '  <option value="3" selected>Mar</option>' . PHP_EOL
            . '  <option value="4">Apr</option>' . PHP_EOL
            . '  <option value="5">May</option>' . PHP_EOL
            . '  <option value="6">Jun</option>' . PHP_EOL
            . '  <option value="7">Jul</option>' . PHP_EOL
            . '  <option value="8">Aug</option>' . PHP_EOL
            . '  <option value="9">Sep</option>' . PHP_EOL
            . '  <option value="10">Oct</option>' . PHP_EOL
            . '  <option value="11">Nov</option>' . PHP_EOL
            . '  <option value="12">Dec</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getFullMonthNamesPulldown method for generating a full month names dropdown.
     *
     * @return void
     */
    public function testGetFullMonthNamesPulldown(): void
    {
        $html = $this->pulldownBuilder->getFullMonthNamesPulldown(
            'month',
            '5',
            [],
            0,
            false
        );
        $expected = '<select name="month">' . PHP_EOL
            . '  <option value="1">January</option>' . PHP_EOL
            . '  <option value="2">February</option>' . PHP_EOL
            . '  <option value="3">March</option>' . PHP_EOL
            . '  <option value="4">April</option>' . PHP_EOL
            . '  <option value="5" selected>May</option>' . PHP_EOL
            . '  <option value="6">June</option>' . PHP_EOL
            . '  <option value="7">July</option>' . PHP_EOL
            . '  <option value="8">August</option>' . PHP_EOL
            . '  <option value="9">September</option>' . PHP_EOL
            . '  <option value="10">October</option>' . PHP_EOL
            . '  <option value="11">November</option>' . PHP_EOL
            . '  <option value="12">December</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getDaysOfMonthPulldown method for generating a days of the month dropdown.
     *
     * @return void
     */
    public function testGetDaysOfMonthPulldown(): void
    {
        $html = $this->pulldownBuilder->getDaysOfMonthPulldown(
            'day',
            '15',
            false,
            [],
            0,
            false
        );
        $expected = '<select name="day">' . PHP_EOL
            . '  <option value="1">1</option>' . PHP_EOL
            . '  <option value="2">2</option>' . PHP_EOL
            . '  <option value="3">3</option>' . PHP_EOL
            . '  ...' . PHP_EOL
            . '  <option value="15" selected>15</option>' . PHP_EOL
            . '  ...' . PHP_EOL
            . '  <option value="31">31</option>' . PHP_EOL
            . '</select>';
        $this->assertStringContainsString('<option value="15" selected>15</option>', $html);
    }

    /**
     * Tests the getDaysOfWeekPulldown method for generating a numeric days of the week dropdown.
     *
     * @return void
     */
    public function testGetDaysOfWeekPulldown(): void
    {
        $html = $this->pulldownBuilder->getDaysOfWeekPulldown(
            'day',
            '4',
            false,
            [],
            0,
            false
        );
        $expected = '<select name="day">' . PHP_EOL
            . '  <option value="1">1</option>' . PHP_EOL
            . '  <option value="2">2</option>' . PHP_EOL
            . '  <option value="3">3</option>' . PHP_EOL
            . '  <option value="4" selected>4</option>' . PHP_EOL
            . '  <option value="5">5</option>' . PHP_EOL
            . '  <option value="6">6</option>' . PHP_EOL
            . '  <option value="7">7</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getShortDayNamesPulldown method for generating a short day names dropdown.
     *
     * @return void
     */
    public function testGetShortDayNamesPulldown(): void
    {
        $html = $this->pulldownBuilder->getShortDayNamesPulldown(
            'day',
            '2',
            [],
            0,
            false
        );
        $expected = '<select name="day">' . PHP_EOL
            . '  <option value="1">Mon</option>' . PHP_EOL
            . '  <option value="2" selected>Tue</option>' . PHP_EOL
            . '  <option value="3">Wed</option>' . PHP_EOL
            . '  <option value="4">Thu</option>' . PHP_EOL
            . '  <option value="5">Fri</option>' . PHP_EOL
            . '  <option value="6">Sat</option>' . PHP_EOL
            . '  <option value="7">Sun</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getFullDayNamesPulldown method for generating a full day names dropdown.
     *
     * @return void
     */
    public function testGetFullDayNamesPulldown(): void
    {
        $html = $this->pulldownBuilder->getFullDayNamesPulldown(
            'day',
            '5',
            [],
            0,
            false
        );
        $expected = '<select name="day">' . PHP_EOL
            . '  <option value="1">Monday</option>' . PHP_EOL
            . '  <option value="2">Tuesday</option>' . PHP_EOL
            . '  <option value="3">Wednesday</option>' . PHP_EOL
            . '  <option value="4">Thursday</option>' . PHP_EOL
            . '  <option value="5" selected>Friday</option>' . PHP_EOL
            . '  <option value="6">Saturday</option>' . PHP_EOL
            . '  <option value="7">Sunday</option>' . PHP_EOL
            . '</select>';
        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getLabeledPulldown method for generating a labeled dropdown.
     *
     * Validates that the generated pulldown includes both the options and an associated
     * label element. Ensures proper association between the label and pulldown, as well as
     * correct selection of the specified value.
     *
     * This is a strict output matching test.
     *
     * @return void
     */
    public function testGetLabeledPulldown(): void
    {
        $html = $this->pulldownBuilder->getLabeledPulldown(
            'fruit',
            'banana',
            ['apple' => 'Apple', 'banana' => 'Banana'],
            'Choose a fruit:',
            true,
            ['class' => 'dropdown'],
            0,
            false
        );
        $expected = '<select name="fruit" class="dropdown">' . PHP_EOL
            . '  <option value="apple">Apple</option>' . PHP_EOL
            . '  <option value="banana" selected>Banana</option>' . PHP_EOL
            . '</select> <label>Choose a fruit:</label>';
        $this->assertSame($expected, $html);
    }
}
