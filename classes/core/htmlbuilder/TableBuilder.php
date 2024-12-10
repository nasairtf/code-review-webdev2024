<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

use App\core\htmlbuilder\HtmlBuildUtility;

/**
 * /home/webdev2024/classes/core/htmlbuilder/TableBuilder.php
 *
 * A utility class for generating HTML tables with configurable rows, columns, and formatting.
 * Includes support for headers, inline cells, alternating row colors, and row-specific styles.
 *
 * Formatting preferences determine whether the output includes indentation and line breaks.
 *
 * Example:
 * ```
 * $builder = new TableBuilder(true);
 * echo $builder->getTableFromRows([['Header 1', 'Header 2'], ['Row 1, Cell 1', 'Row 1, Cell 2']]);
 * ```
 *
 * @category Utilities
 * @package  IRTF
 * @version  1.0.0
 * @since    1.0.0
 */

class TableBuilder
{
    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Constructor to set the formatting preference.
     *
     * @param bool $formatOutput Whether to format the HTML (indentation, line breaks).
     */
    public function __construct(
        ?bool $formatOutput = null
    ) {
        $this->formatOutput = $formatOutput ?? false;
    }

    /**
     * Generates the opening <table> tag with attributes.
     *
     * @param array  $attributes  [optional] Additional attributes for the <table> element (e.g., width, border).
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The opening <table> tag.
     */
    public function getTableOpenTag(
        array $attributes = [],
        int $pad = 0
    ): string {
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        return HtmlBuildUtility::formatOutput(
            sprintf('<table%s>', $attrString),
            $this->formatOutput,
            false,
            $pad
        );
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
        return HtmlBuildUtility::formatOutput(
            '</table>',
            $this->formatOutput,
            false,
            $pad
        );
    }

    /**
     * Generates a complete table from an array of rows.
     *
     * @param array  $rows        Array of HTML rows (already generated via getTableRowFromArray).
     * @param array  $attributes  [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The complete HTML for the table.
     */
    public function getTableFromRows(
        array $rows,
        array $attributes = [],
        int $pad = 0
    ): string {
        $htmlParts = [
            $this->getTableOpenTag($attributes, $pad),
            HtmlBuildUtility::formatParts($rows, $this->formatOutput),
            $this->getTableCloseTag($pad),
        ];
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a full HTML table from an array of row content.
     *
     * @param array  $rows          A 2D array where each sub-array is a row of cell contents.
     * @param bool   $header        [optional] Whether the first row should be treated as a header. Default is false.
     * @param array  $inlines       [optional] A 2D array of inline flags for each cell. Each sub-array corresponds to
     *                               a row. Default is all cells non-inline.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the table.
     */
    public function getTableFromArray(
        array $rows,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $htmlParts = [];
        $htmlParts[] = $this->getTableOpenTag($attributes, $pad);
        foreach ($rows as $index => $cells) {
            $isHeaderRow = ($header && $index === 0);
            $rowInlines = $inlines[$index] ?? array_fill(0, count($cells), false);
            $htmlParts[] = $this->getTableRowFromArray(
                $cells,
                $isHeaderRow,
                $rowInlines,
                [],
                $pad + 2,
                $isHtml
            );
        }
        $htmlParts[] = $this->getTableCloseTag($pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a single-column table with multiple rows, where each row has a single cell.
     *
     * @param array  $rows          An array of content for each row.
     * @param bool   $header        [optional] Whether the first row should be treated as a header. Default is false.
     * @param array  $inlines       [optional] An array of inline flags for each row. Default is all rows non-inline.
     * @param array  $attributes    [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the single-column table.
     */
    public function getSingleColumnTable(
        array $rows,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $htmlParts = [];
        $htmlParts[] = $this->getTableOpenTag($attributes, $pad);
        foreach ($rows as $index => $rowContent) {
            $isHeaderRow = ($header && $index === 0);
            $htmlParts[] = $this->getSingleCellRow(
                $rowContent,
                $isHeaderRow,
                $inlines[$index] ?? false,
                [],
                $pad + 2,
                $isHtml
            );
        }
        $htmlParts[] = $this->getTableCloseTag($pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Returns a horizontal divider line, optionally wrapped in its own table.
     *
     * @param bool   $isOwnTable    Whether to create a new table (true = yes, false = no).
     * @param string $color         The background color of the line's cell.
     * @param int    $colspan       [optional] How many columns the line will span. Default is 1.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the horizontal divider line.
     */
    public function getHorizontalLine(
        bool $isOwnTable,
        string $color,
        int $colspan = 1,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedColor = HtmlBuildUtility::escape((string) $color, $isHtml);
        $htmlParts = [];
        if ($isOwnTable) {
            $htmlParts[] = $this->getTableOpenTag(
                ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'],
                $pad
            );
        }
        $htmlParts[] = sprintf(
            '<tr bgcolor="%s"><td colspan="%d" align="center"><hr/></td></tr>',
            $escapedColor,
            $colspan
        );
        if ($isOwnTable) {
            $htmlParts[] = $this->getTableCloseTag($pad);
        }
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table cell (either <td> or <th>).
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
     * @return string The HTML for the table cell.
     */
    public function getTableCell(
        string $content,
        bool $header = false,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $escapedContent = HtmlBuildUtility::escape(trim((string) $content), $isHtml);
        $tag = $header ? 'th' : 'td';
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        // if formatOutput is false, use inline rendering even for non-inline content
        if (!$this->formatOutput || $inline) {
            $html = sprintf('<%s%s>%s</%s>', $tag, $attrString, $escapedContent, $tag);
            return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
        }
        // if formatOutput is true and inline is false, return cell content with padding and line breaks
        $htmlParts = [
            HtmlBuildUtility::padLeftString(sprintf('<%s%s>', $tag, $attrString), $pad),
            HtmlBuildUtility::padLeftString($escapedContent, $pad + 2),
            HtmlBuildUtility::padLeftString(sprintf('</%s>', $tag), $pad),
        ];
        return implode(PHP_EOL, $htmlParts);
    }

    /**
     * Generates a table row (<tr>) with data cells (<td> or <th>).
     *
     * @param array  $cells         Array of cell content to be placed in the row.
     * @param bool   $header        [optional] Whether the row contains header cells (<th>) instead of data
     *                               cells (<td>). Default is false.
     * @param array  $inlines       [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes    [optional] Additional attributes for the row <tr> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the table row.
     */
    public function getTableRowFromArray(
        array $cells,
        bool $header = false,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $rowParts = [];
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $htmlParts[] = HtmlBuildUtility::formatOutput(
            sprintf('<tr%s>', $attrString),
            $this->formatOutput,
            false,
            $pad
        );
        foreach ($cells as $index => $cell) {
            $inline = $inlines[$index] ?? false;
            $htmlParts[] = $this->getTableCell(
                $cell,
                $header,
                $inline,
                [],
                $pad + 2,
                $isHtml
            );
        }
        $htmlParts[] = HtmlBuildUtility::formatOutput(
            '</tr>',
            $this->formatOutput,
            false,
            $pad
        );
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
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
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $htmlParts = [
            HtmlBuildUtility::formatOutput(sprintf('<tr%s>', $attrString), $this->formatOutput, false, $pad),
            HtmlBuildUtility::formatParts($cells, $this->formatOutput),
            HtmlBuildUtility::formatOutput('</tr>', $this->formatOutput, false, $pad),
        ];
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a table row with a single cell, which can be either a header (<th>) or a data cell (<td>).
     *
     * @param string $content       The content of the cell.
     * @param bool   $header        [optional] Whether the cell is a header (<th>) or data (<td>). Default is false.
     * @param bool   $inline        [optional] Whether the cells should be displayed inline. Default is false.
     * @param array  $attributes    [optional] Additional HTML attributes for the row <tr> element.
     *                               Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the table row with a single cell.
     */
    public function getSingleCellRow(
        string $content,
        bool $header = false,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->getTableRowFromArray(
            [$content],
            $header,
            [$inline],
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a table row with alternating background colors.
     *
     * @param array  $cells         Array of cell content for the row.
     * @param string $currentColor  The current row background color.
     * @param array  $colors        [optional] Array of colors to alternate between. Default: ['#FFFFFF', '#CCCCCC'].
     * @param array  $inlines       [optional] Array of inline flags for each cell. Default: all false.
     * @param array  $attributes    [optional] Additional attributes for the row. Default: empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default: 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default: false.
     *
     * @return string The formatted table row.
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
        $newColor = HtmlBuildUtility::getCycledColor($currentColor, $colors);
        $attributes['bgcolor'] = $newColor;
        return $this->getTableRowFromArray(
            $cells,
            false,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates a non-header table row with a color based on status (e.g., success, error, warning).
     *
     * @param array  $cells         Array of cell content to be placed in the row.
     * @param string $status        The status for determining row color (e.g., success, error, warning).
     * @param array  $inlines       [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes    [optional] Additional attributes for the row. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The HTML for the table row with status-based color.
     */
    public function getTableRowFromArrayWithStatusColor(
        array $cells,
        string $status,
        array $inlines = [],
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $color = HtmlBuildUtility::getStatusColor($status);
        $attributes['bgcolor'] = $color;
        return $this->getTableRowFromArray(
            $cells,
            false,
            $inlines,
            $attributes,
            $pad,
            $isHtml
        );
    }
}
