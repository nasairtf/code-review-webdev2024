<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\TableBuilder;
use App\core\htmlbuilder\HtmlBuildUtility;

/**
 * Unit tests for the TableBuilder class.
 *
 * @covers \App\core\htmlbuilder\TableBuilder
 */
class TableBuilderTest extends TestCase
{
    /**
     * Instance of TableBuilder for testing.
     *
     * @var TableBuilder
     */
    private $tableBuilder;

    /**
     * Tests the getTableOpenTag method for generating the opening <table> tag.
     *
     * Verifies the correct rendering of the `<table>` tag with attributes and indentation.
     *
     * @return void
     */
    public function testGetTableOpenTag(): void
    {
        $html = $this->tableBuilder->getTableOpenTag(['border' => '1', 'class' => 'table-class'], 2);

        $expected = '  <table border="1" class="table-class">';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableCloseTag method for generating the closing </table> tag.
     *
     * Ensures proper indentation of the closing `</table>` tag.
     *
     * @return void
     */
    public function testGetTableCloseTag(): void
    {
        $html = $this->tableBuilder->getTableCloseTag(2);

        $expected = '  </table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableFromRows method for generating a table from an array of rows.
     *
     * Verifies that a `<table>` element is constructed with the given rows and attributes.
     *
     * @return void
     */
    public function testGetTableFromRows(): void
    {
        $rows = [
            '<tr><td>Row 1, Cell 1</td><td>Row 1, Cell 2</td></tr>',
            '<tr><td>Row 2, Cell 1</td><td>Row 2, Cell 2</td></tr>',
        ];

        $html = $this->tableBuilder->getTableFromRows($rows, ['border' => '1'], 0);

        $expected = '<table border="1">' . PHP_EOL
            . '<tr><td>Row 1, Cell 1</td><td>Row 1, Cell 2</td></tr>' . PHP_EOL
            . '<tr><td>Row 2, Cell 1</td><td>Row 2, Cell 2</td></tr>' . PHP_EOL
            . '</table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableFromArray method for generating a full HTML table from an array of rows.
     *
     * Verifies that:
     * - The table open and close tags are correctly generated.
     * - Row content is accurately transformed into table rows.
     * - The first row is treated as a header when specified.
     *
     * @return void
     */
    public function testGetTableFromArray(): void
    {
        $rows = [
            ['Header 1', 'Header 2'],
            ['Row 1, Cell 1', 'Row 1, Cell 2'],
            ['Row 2, Cell 1', 'Row 2, Cell 2'],
        ];
        $attributes = ['class' => 'custom-table', 'border' => '1'];
        $inlines = [
            [false, false], // Header row
            [true, false],  // First row of data
            [false, true],  // Second row of data
        ];

        $html = $this->tableBuilder->getTableFromArray(
            $rows,
            true, // Treat the first row as a header.
            $inlines,
            $attributes,
            2,
            false
        );

        $expected = '  <table class="custom-table" border="1">' . PHP_EOL
            . '    <tr>' . PHP_EOL
            . '      <th>' . PHP_EOL
            . '        Header 1' . PHP_EOL
            . '      </th>' . PHP_EOL
            . '      <th>' . PHP_EOL
            . '        Header 2' . PHP_EOL
            . '      </th>' . PHP_EOL
            . '    </tr>' . PHP_EOL
            . '    <tr>' . PHP_EOL
            . '      <td>Row 1, Cell 1</td>' . PHP_EOL
            . '      <td>' . PHP_EOL
            . '        Row 1, Cell 2' . PHP_EOL
            . '      </td>' . PHP_EOL
            . '    </tr>' . PHP_EOL
            . '    <tr>' . PHP_EOL
            . '      <td>' . PHP_EOL
            . '        Row 2, Cell 1' . PHP_EOL
            . '      </td>' . PHP_EOL
            . '      <td>Row 2, Cell 2</td>' . PHP_EOL
            . '    </tr>' . PHP_EOL
            . '  </table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableRowFromArray method for generating a table row from an array of cell content.
     *
     * Verifies that a `<tr>` element with multiple `<td>` elements is generated correctly.
     *
     * @return void
     */
    public function testGetTableRowFromArray(): void
    {
        $html = $this->tableBuilder->getTableRowFromArray(
            ['Cell 1', 'Cell 2'],
            false,
            [],
            ['class' => 'row-class'],
            2,
            false
        );

        $expected = '  <tr class="row-class">' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 1' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 2' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '  </tr>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getSingleColumnTable method for generating a single-column table.
     *
     * Confirms that a `<table>` element with rows containing single `<td>` elements is created.
     *
     * @return void
     */
    public function testGetSingleColumnTable(): void
    {
        $rows = ['Cell 1', 'Cell 2'];

        $html = $this->tableBuilder->getSingleColumnTable($rows, false, [], ['border' => '1'], 0, false);

        $expected = '<table border="1">' . PHP_EOL
            . '  <tr>' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 1' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '  </tr>' . PHP_EOL
            . '  <tr>' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 2' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '  </tr>' . PHP_EOL
            . '</table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableCell method for generating a single table cell.
     *
     * Verifies that a `<td>` element with content and attributes is correctly constructed.
     *
     * @return void
     */
    public function testGetTableCell(): void
    {
        $html = $this->tableBuilder->getTableCell('Test Content', false, false, ['class' => 'cell-class'], 2, false);

        $expected = '  <td class="cell-class">' . PHP_EOL
            . '    Test Content' . PHP_EOL
            . '  </td>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getHorizontalLine method for generating a horizontal line inside a table.
     *
     * Confirms that a `<table>` with a horizontal rule `<hr />` is created.
     *
     * @return void
     */
    public function testGetHorizontalLine(): void
    {
        $html = $this->tableBuilder->getHorizontalLine(true, '#FFFFFF', 2, 0, false);

        $expected = '<table width="100%" border="0" cellspacing="0" cellpadding="6">' . PHP_EOL
            . '<tr bgcolor="#FFFFFF"><td colspan="2" align="center"><hr/></td></tr>' . PHP_EOL
            . '</table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableRowFromArrayWithAlternatingColor method for generating a row with alternating colors.
     *
     * Verifies that a `<tr>` element is created with alternating background colors applied.
     *
     * @return void
     */
    public function testGetTableRowFromArrayWithAlternatingColor(): void
    {
        $html = $this->tableBuilder->getTableRowFromArrayWithAlternatingColor(
            ['Cell 1', 'Cell 2'],
            '#FFFFFF',
            ['#FFFFFF', '#CCCCCC'],
            [],
            ['class' => 'row-class'],
            2,
            false
        );

        $expected = '  <tr class="row-class" bgcolor="#CCCCCC">' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 1' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 2' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '  </tr>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the getTableRowFromArrayWithStatusColor method for generating a table row with a status-based color.
     *
     * Ensures that the correct background color is applied based on the status
     * and that the row content is generated correctly.
     *
     * @return void
     */
    public function testGetTableRowFromArrayWithStatusColor(): void
    {
        $cells = ['Cell 1', 'Cell 2'];
        $status = 'success'; // Assuming 'success' maps to a color like '#00FF00'.
        $attributes = ['class' => 'status-row'];
        $html = $this->tableBuilder->getTableRowFromArrayWithStatusColor(
            $cells,
            $status,
            [],
            $attributes,
            2,
            false
        );

        $expectedColor = HtmlBuildUtility::getStatusColor($status);
        $expected = '  <tr class="status-row" bgcolor="' . $expectedColor . '">' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 1' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '    <td>' . PHP_EOL
            . '      Cell 2' . PHP_EOL
            . '    </td>' . PHP_EOL
            . '  </tr>';

        $this->assertSame($expected, $html);
    }

    /**
     * Sets up the test environment by initializing the TableBuilder instance.
     *
     * Enables formatted output for the generated HTML elements.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tableBuilder = new TableBuilder(true);
    }
}
