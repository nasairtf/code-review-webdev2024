<?php

declare(strict_types=1);

namespace Tests\classes\core\irtf;

use PHPUnit\Framework\TestCase;
use App\core\irtf\IrtfUtilities;

class IrtfUtilitiesTest extends TestCase
{
    // Dates

    public function testReturnUnixDate(): void
    {
        $expectedTimestamp = mktime(0, 0, 0, 10, 25, 2024);
        $this->assertEquals($expectedTimestamp, IrtfUtilities::returnUnixDate(10, 25, 2024));
    }

    public function testReturnTextDate(): void
    {
        $timestamp = mktime(0, 0, 0, 10, 25, 2024);
        $expectedDate = date('M d, Y', $timestamp);
        $this->assertEquals($expectedDate, IrtfUtilities::returnTextDate($timestamp));

        $customFormat = 'Y-m-d';
        $expectedCustomDate = date($customFormat, $timestamp);
        $this->assertEquals($expectedCustomDate, IrtfUtilities::returnTextDate($timestamp, $customFormat));
    }

    // Strings

    public function testEscape(): void
    {
        $rawString = '<script>alert("XSS")</script>';
        $escapedString = htmlspecialchars($rawString, ENT_QUOTES, 'UTF-8');
        $this->assertEquals($escapedString, IrtfUtilities::escape($rawString));

        $rawStringWithQuotes = 'Test "double quotes" and \'single quotes\'';
        $escapedStringWithQuotes = htmlspecialchars($rawStringWithQuotes, ENT_QUOTES, 'UTF-8');
        $this->assertEquals($escapedStringWithQuotes, IrtfUtilities::escape($rawStringWithQuotes));
    }

    // Semesters

    public function testReturnSemester(): void
    {
        // Test within Semester A
        $this->assertEquals('2024A', IrtfUtilities::returnSemester(3, 15, 2024)); // March 15, 2024
        $this->assertEquals('2024A', IrtfUtilities::returnSemester(7, 31, 2024)); // July 31, 2024

        // Test within Semester B
        $this->assertEquals('2024B', IrtfUtilities::returnSemester(8, 1, 2024));  // Aug 1, 2024
        $this->assertEquals('2024B', IrtfUtilities::returnSemester(12, 15, 2024)); // Dec 15, 2024

        // Test January of the following year in Semester B
        $this->assertEquals('2024B', IrtfUtilities::returnSemester(1, 15, 2025)); // Jan 15, 2025

        // Test date before February 1 of the year (falls under previous year's B semester)
        $this->assertEquals('2023B', IrtfUtilities::returnSemester(1, 31, 2024)); // Jan 31, 2024
    }
}
