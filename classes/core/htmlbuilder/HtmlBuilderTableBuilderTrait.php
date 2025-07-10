<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for HTML table construction methods.
 *
 * Enables creation of structured tables from arrays or pre-built rows,
 * supporting cell formatting, header distinctions, and styling variants
 * like alternating or status-based rows using TableBuilder.
 *
 * This trait powers tabular content rendering within HtmlBuilder and
 * emphasizes clean, accessible table layout.
 *
 * @see TableBuilder
 */
trait HtmlBuilderTableBuilderTrait
{
    /**
     * Generates the opening <table> tag with attributes.
     *
     * @param array  $attributes  [optional] Additional attributes for the <table> element (e.g., width, border).
     *                             Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The opening <table> tag.
     */
    public function getTableOpenTag(
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->getTableOpenTag($attributes, $pad);
    }

    /**
     * Generates the closing </table> tag.
     *
     * @param int $pad  [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The closing </table> tag.
     */
    public function getTableCloseTag(
        int $pad = 0
    ): string {
        return $this->tableBuilder->getTableCloseTag($pad);
    }

    /**
     * Generates a horizontal line in a table using TableBuilder.
     *
     * @param bool   $isOwnTable Whether to create a new table (true = yes, false = no).
     * @param string $color      The background color of the line's cell.
     * @param int    $colspan    [optional] How many columns the line will span. Default is 1.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the horizontal divider line.
     */
    public function getHorizontalLine(
        bool $isOwnTable,
        string $color,
        int $colspan = 1,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getHorizontalLine(
            $isOwnTable,
            $color,
            $colspan,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table cell using TableBuilder.
     *
     * @param string $content       The content for the cell.
     * @param bool   $header        [optional] Whether the cell is a header (<th>) or data (<td>) cell.
     *                               Default is false.
     * @param bool   $inline        [optional] Whether the cell should be rendered inline or block-level.
     *                               Default is false.
     * @param array  $attributes    [optional] Additional attributes for the cell (e.g., class, id).
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the table cell.
     */
    public function getTableCell(
        string $content,
        bool $header = false,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getTableCell(
            $content,
            $header,
            $inline,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table row with data or header cells using TableBuilder.
     *
     * @param array  $cells      Array of cell content to be placed in the row.
     * @param bool   $header     [optional] Whether the row contains header cells (<th>). Default is false.
     * @param array  $inlines    [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the table row.
     */
    public function getTableRowFromArray(
        array $cells,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getTableRowFromArray(
            $cells,
            $header,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table row with already-constructed HTML cells.
     *
     * @param array  $htmlCells   Array of pre-constructed HTML cell content.
     * @param array  $attributes  [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the table row.
     */
    public function getTableRowFromCells(
        array $cells,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->getTableRowFromCells(
            $cells,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a single-cell row in a table using TableBuilder.
     *
     * @param string $content    The content of the cell.
     * @param bool   $header     [optional] Whether the cell is a header (<th>) or data cell (<td>). Default is false.
     * @param bool   $inline     [optional] Whether the cell should be displayed inline. Default is false.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the single-cell row.
     */
    public function getSingleCellRow(
        string $content,
        bool $header = false,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getSingleCellRow(
            $content,
            $header,
            $inline,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table row with alternating background colors using TableBuilder.
     *
     * @param array  $cells         Array of cell content to be placed in the row.
     * @param string $currentColor  The current background color.
     * @param array  $colors        [optional] An array of colors to alternate between.
     *                               Default is ['#FFFFFF', '#CCCCCC'].
     * @param array  $inlines       [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes    [optional] Additional attributes for the row <tr> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the table row with alternating colors.
     */
    public function getTableRowFromArrayWithAlternatingColor(
        array $cells,
        string $currentColor,
        array $colors = ['#FFFFFF', '#CCCCCC'],
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getTableRowFromArrayWithAlternatingColor(
            $cells,
            $currentColor,
            $colors,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table row with status-based color using TableBuilder.
     *
     * @param array  $cells      Array of cell content to be placed in the row.
     * @param string $status     The status for determining row color (e.g., success, error, warning).
     * @param array  $inlines    [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the table row with status-based color.
     */
    public function getTableRowFromArrayWithStatusColor(
        array $cells,
        string $status,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getTableRowFromArrayWithStatusColor(
            $cells,
            $status,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a single-column table with multiple rows using TableBuilder.
     *
     * @param array  $rows       An array of content for each row.
     * @param bool   $header     [optional] Whether the first row is treated as a header. Default is false.
     * @param array  $inlines    [optional] An array of inline flags for each row. Default is all rows non-inline.
     * @param array  $attributes [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the single-column table.
     */
    public function getSingleColumnTable(
        array $rows,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getSingleColumnTable(
            $rows,
            $header,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a full HTML table using TableBuilder.
     *
     * @param array  $rows        Array of HTML rows (already generated via getTableRowFromArray).
     * @param array  $attributes  [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the table.
     */
    public function getTableFromRows(
        array $rows,
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->tableBuilder->getTableFromRows(
            $rows,
            $attributes,
            $pad
        );
    }

    /**
     * Generates a full HTML table using TableBuilder.
     *
     * @param array  $rows       A 2D array where each sub-array is a row of cells.
     * @param bool   $header     [optional] Whether the first row is treated as a header. Default is false.
     * @param array  $inlines    [optional] A 2D array of inline flags for each cell. Each sub-array corresponds
     *                            to a row. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the table.
     */
    public function getTableFromArray(
        array $rows,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->tableBuilder->getTableFromArray(
            $rows,
            $header,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }
}
