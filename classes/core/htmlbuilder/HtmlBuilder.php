<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/HtmlBuilder.php
 *
 * A utility class responsible for building common HTML components such as select boxes, text fields, etc.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class HtmlBuilder
{
    /**
     * Whether to format the HTML output (indent and add line breaks).
     *
     * @var bool
     */
    private $formatOutput;

    /**
     * Various builder objects for generating HTML components.
     *
     * @var BaseHtmlBuilder
     * @var ButtonBuilder
     * @var CheckboxBuilder
     * @var PulldownBuilder
     * @var RadioBuilder
     * @var TableBuilder
     * @var TextBuilder
     */
    private $baseHtmlBuilder;
    private $buttonBuilder;
    private $checkboxBuilder;
    private $pulldownBuilder;
    private $radioBuilder;
    private $tableBuilder;
    private $textBuilder;

    /**
     * Constructor for the HtmlBuilder class.
     *
     * Initializes the HtmlBuilder with optional builder dependencies and a flag to determine whether
     * to format the output (i.e., indent and add line breaks).
     *
     * If no builder instances are provided, the constructor will create new instances with the same
     * formatting preference.
     *
     * @param bool              $formatOutput     If true, output will be formatted with indentation and line breaks.
     * @param BaseHtmlBuilder   $baseHtmlBuilder  [optional] An instance of BaseHtmlBuilder. If not provided, a new one is created.
     * @param ButtonBuilder     $buttonBuilder    [optional] An instance of ButtonBuilder. If not provided, a new one is created.
     * @param CheckboxBuilder   $checkboxBuilder  [optional] An instance of CheckboxBuilder. If not provided, a new one is created.
     * @param PulldownBuilder   $pulldownBuilder  [optional] An instance of PulldownBuilder. If not provided, a new one is created.
     * @param RadioBuilder      $radioBuilder     [optional] An instance of RadioBuilder. If not provided, a new one is created.
     * @param TableBuilder      $tableBuilder     [optional] An instance of TableBuilder. If not provided, a new one is created.
     * @param TextBuilder       $textBuilder      [optional] An instance of TextBuilder. If not provided, a new one is created.
     */
    public function __construct(
        bool $formatOutput = false,
        ?BaseHtmlBuilder $baseHtmlBuilder = null,
        ?ButtonBuilder $buttonBuilder = null,
        ?CheckboxBuilder $checkboxBuilder = null,
        ?PulldownBuilder $pulldownBuilder = null,
        ?RadioBuilder $radioBuilder = null,
        ?TableBuilder $tableBuilder = null,
        ?TextBuilder $textBuilder = null
    ) {
        $this->formatOutput = $formatOutput;
        $this->baseHtmlBuilder = $baseHtmlBuilder ?? new BaseHtmlBuilder($formatOutput);
        $this->buttonBuilder = $buttonBuilder ?? new ButtonBuilder($formatOutput);
        $this->checkboxBuilder = $checkboxBuilder ?? new CheckboxBuilder($formatOutput);
        $this->pulldownBuilder = $pulldownBuilder ?? new PulldownBuilder($formatOutput);
        $this->radioBuilder = $radioBuilder ?? new RadioBuilder($formatOutput);
        $this->tableBuilder = $tableBuilder ?? new TableBuilder($formatOutput);
        $this->textBuilder = $textBuilder ?? new TextBuilder($formatOutput);
    }

    /**
     * Generates an HTML break element (<br />) using BaseHtmlBuilder.
     *
     * @param int $pad [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the line break.
     */
    public function getBreak(
        int $pad = 0
    ): string {
        return $this->baseHtmlBuilder->getBreak($pad);
    }

    /**
     * Generates an HTML horizontal rule element (<hr />) using BaseHtmlBuilder.
     *
     * @param array $attributes [optional] Additional attributes for the <hr> element. Default is an empty array.
     * @param int   $pad        [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The generated HTML for the horizontal line.
     */
    public function getLine(
        array $attributes = [],
        int $pad = 0
    ): string {
        return $this->baseHtmlBuilder->getLine($attributes, $pad);
    }

    /**
     * Generates an HTML anchor element (<a>) using BaseHtmlBuilder.
     *
     * @param string $url        The URL for the link.
     * @param string $label      The label for the link.
     * @param array  $attributes [optional] Additional attributes for the <a> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the anchor element.
     */
    public function getLink(
        string $url,
        string $label,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getLink($url, $label, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML mailto link using BaseHtmlBuilder.
     *
     * @param string $email      The email address for the link.
     * @param string $label      [optional] The label for the link. Default is the email address.
     * @param array  $attributes [optional] Additional attributes for the <a> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the mailto link.
     */
    public function getEmailLink(
        string $email,
        string $label = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getEmailLink($email, $label, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML file input element using BaseHtmlBuilder.
     *
     * @param string $name       The name attribute for the file input.
     * @param array  $attributes [optional] Additional attributes for the input element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the file input element.
     */
    public function getFileInput(
        string $name,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getFileInput($name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML image element (<img>) using BaseHtmlBuilder.
     *
     * @param string $src        The source of the image.
     * @param string $alt        [optional] The alt text for the image. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the image element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the image element.
     */
    public function getImage(
        string $src,
        string $alt = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getImage($src, $alt, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML label element (<label>) using BaseHtmlBuilder.
     *
     * @param string $for        The ID of the form element this label is for.
     * @param string $content    The content of the label.
     * @param array  $attributes [optional] Additional attributes for the label element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the label element.
     */
    public function getLabel(
        string $for,
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getLabel($for, $content, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML paragraph element (<p>) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the paragraph.
     * @param array  $attributes [optional] Additional attributes for the paragraph element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the paragraph element.
     */
    public function getParagraph(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getParagraph($content, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML span element (<span>) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the span.
     * @param array  $attributes [optional] Additional attributes for the span element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the span element.
     */
    public function getSpan(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getSpan($content, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML form element (<form>) using BaseHtmlBuilder.
     *
     * @param string $action     The action URL for the form.
     * @param string $method     [optional] The HTTP method (GET or POST). Default is POST.
     * @param string $content    [optional] The content inside the form (e.g., input fields). Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the form element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the form element.
     */
    public function getForm(
        string $action,
        string $method = 'post',
        string $content = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getForm($action, $method, $content, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML heading element (<h1>, <h2>, etc.) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the heading.
     * @param int    $level      The heading level (1-6).
     * @param array  $attributes [optional] Additional attributes for the heading element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the heading element.
     */
    public function getHeading(
        string $content,
        int $level,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getHeading($content, $level, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML list element (<ul> or <ol>) using BaseHtmlBuilder.
     *
     * @param array  $items       An array of list item content.
     * @param bool   $ordered     [optional] If true, generates an ordered list (<ol>), otherwise <ul>. Default is false.
     * @param array  $attributes  [optional] Additional attributes for the list element. Default is an empty array.
     * @param array  $liAttributes [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the list.
     */
    public function getList(
        array $items,
        bool $ordered = false,
        array $attributes = [],
        array $liAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getList($items, $ordered, $attributes, $liAttributes, $pad, $isHtml);
    }

    /**
     * Generates an HTML navigation bar with links using BaseHtmlBuilder.
     *
     * @param array $links        An associative array of links (href => label).
     * @param array $ulAttributes [optional] Additional attributes for the <ul> element. Default is an empty array.
     * @param array $liAttributes [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param array $aAttributes  [optional] Additional attributes for each <a> element. Default is an empty array.
     * @param int   $pad          [optional] Indentation level for formatted output. Default is 0.
     * @param bool  $isHtml       [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the navigation bar.
     */
    public function getNavBar(
        array $links,
        array $ulAttributes = [],
        array $liAttributes = [],
        array $aAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getNavBar($links, $ulAttributes, $liAttributes, $aAttributes, $pad, $isHtml);
    }

    /**
     * Generates the opening <table> tag with attributes.
     *
     * @param array  $attributes  [optional] Additional attributes for the <table> element (e.g., width, border). Default is an empty array.
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
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getHorizontalLine($isOwnTable, $color, $colspan, $pad, $isHtml);
    }

    /**
     * Generates a table cell using TableBuilder.
     *
     * @param string $content       The content for the cell.
     * @param bool   $header        [optional] Whether the cell is a header (<th>) or data (<td>) cell. Default is false.
     * @param bool   $inline        [optional] Whether the cell should be rendered inline or block-level. Default is false.
     * @param array  $attributes    [optional] Additional attributes for the cell (e.g., class, id). Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getTableCell($content, $header, $inline, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a table row with data or header cells using TableBuilder.
     *
     * @param array  $cells      Array of cell content to be placed in the row.
     * @param bool   $header     [optional] Whether the row contains header cells (<th>). Default is false.
     * @param array  $inlines    [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getTableRowFromArray($cells, $header, $inlines, $attributes, $pad, $isHtml);
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
        return $this->tableBuilder->getTableRowFromCells($cells, $attributes, $pad);
    }

    /**
     * Generates a single-cell row in a table using TableBuilder.
     *
     * @param string $content    The content of the cell.
     * @param bool   $header     [optional] Whether the cell is a header (<th>) or data cell (<td>). Default is false.
     * @param bool   $inline     [optional] Whether the cell should be displayed inline. Default is false.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getSingleCellRow($content, $header, $inline, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a table row with alternating background colors using TableBuilder.
     *
     * @param array  $cells         Array of cell content to be placed in the row.
     * @param string $currentColor  The current background color.
     * @param array  $colors        [optional] An array of colors to alternate between. Default is ['#FFFFFF', '#CCCCCC'].
     * @param array  $inlines       [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes    [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getTableRowFromArrayWithAlternatingColor($cells, $currentColor, $colors, $inlines, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a table row with status-based color using TableBuilder.
     *
     * @param array  $cells      Array of cell content to be placed in the row.
     * @param string $status     The status for determining row color (e.g., success, error, warning).
     * @param array  $inlines    [optional] Array of inline flags for each cell. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the row <tr> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getTableRowFromArrayWithStatusColor($cells, $status, $inlines, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a single-column table with multiple rows using TableBuilder.
     *
     * @param array  $rows       An array of content for each row.
     * @param bool   $header     [optional] Whether the first row is treated as a header. Default is false.
     * @param array  $inlines    [optional] An array of inline flags for each row. Default is all rows non-inline.
     * @param array  $attributes [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getSingleColumnTable($rows, $header, $inlines, $attributes, $pad, $isHtml);
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
        return $this->tableBuilder->getTableFromRows($rows, $attributes, $pad);
    }

    /**
     * Generates a full HTML table using TableBuilder.
     *
     * @param array  $rows       A 2D array where each sub-array is a row of cells.
     * @param bool   $header     [optional] Whether the first row is treated as a header. Default is false.
     * @param array  $inlines    [optional] A 2D array of inline flags for each cell. Each sub-array corresponds to a row. Default is all cells non-inline.
     * @param array  $attributes [optional] Additional attributes for the <table> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
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
        return $this->tableBuilder->getTableFromArray($rows, $header, $inlines, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a text input field using TextBuilder.
     *
     * @param string $name       The name attribute for the input field.
     * @param string $value      [optional] The value of the input field. Default is an empty string.
     * @param int    $size       [optional] The size of the input field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the text input field.
     */
    public function getTextInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getTextInput($name, $value, $size, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a textarea element using TextBuilder.
     *
     * @param string $name       The name attribute for the textarea.
     * @param string $content    [optional] The content inside the textarea. Default is an empty string.
     * @param int    $rows       [optional] The number of rows for the textarea. Default is 5.
     * @param int    $cols       [optional] The number of columns for the textarea. Default is 40.
     * @param array  $attributes [optional] Additional attributes for the textarea. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the textarea.
     */
    public function getTextarea(
        string $name,
        string $content = '',
        int $rows = 5,
        int $cols = 40,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getTextarea($name, $content, $rows, $cols, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a password input field using TextBuilder.
     *
     * @param string $name       The name attribute for the password field.
     * @param int    $size       [optional] The size of the password field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the password field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the password field.
     */
    public function getPasswordInput(
        string $name,
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getPasswordInput($name, $size, $attributes, $pad, $isHtml);
    }

    /**
     * Generates an email input field using TextBuilder.
     *
     * @param string $name       The name attribute for the email field.
     * @param string $value      [optional] The value of the email field. Default is an empty string.
     * @param int    $size       [optional] The size of the email field. Default is 25.
     * @param array  $attributes [optional] Additional attributes for the email field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the email field.
     */
    public function getEmailInput(
        string $name,
        string $value = '',
        int $size = 25,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getEmailInput($name, $value, $size, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a number input field using TextBuilder.
     *
     * @param string $name       The name attribute for the number field.
     * @param string $value      [optional] The value of the number field. Default is an empty string.
     * @param int    $min        [optional] The minimum value allowed. Default is 0.
     * @param int    $max        [optional] The maximum value allowed. Default is 100.
     * @param array  $attributes [optional] Additional attributes for the number field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the number input field.
     */
    public function getNumberInput(
        string $name,
        string $value = '',
        int $min = 0,
        int $max = 100,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getNumberInput($name, $value, $min, $max, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a unix timestamp input field using TextBuilder.
     *
     * @param string $name        The name attribute for the timestamp field.
     * @param string $value       [optional] The value of the timestamp field. Default is an empty string.
     * @param array  $attributes  [optional] Additional attributes for the input field. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the Unix timestamp input field.
     */
    public function getUnixTimestampInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getUnixTimestampInput($name, $value, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a URL input field using TextBuilder.
     *
     * @param string $name       The name attribute for the URL field.
     * @param string $value      [optional] The value of the URL field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the URL field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the URL field.
     */
    public function getUrlInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getUrlInput($name, $value, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a hidden input field using TextBuilder.
     *
     * @param string $name       The name attribute for the hidden field.
     * @param string $value      [optional] The value of the hidden field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the hidden field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the hidden input field.
     */
    public function getHiddenInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getHiddenInput($name, $value, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a search input field using TextBuilder.
     *
     * @param string $name       The name attribute for the search field.
     * @param string $value      [optional] The value of the search field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the search field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the search field.
     */
    public function getSearchInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getSearchInput($name, $value, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a telephone input field using TextBuilder.
     *
     * @param string $name       The name attribute for the telephone field.
     * @param string $value      [optional] The value of the telephone field. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the telephone field. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the telephone field.
     */
    public function getTelInput(
        string $name,
        string $value = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->textBuilder->getTelInput($name, $value, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the button element.
     */
    public function getButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getButton($label, $type, $name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a submit button using ButtonBuilder.
     *
     * @param string      $name        The name attribute for the submit button.
     * @param string      $label       The label for the submit button.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the submit button.
     */
    public function getSubmitButton(
        string $name,
        string $label,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getSubmitButton($name, $label, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a button with an icon using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $iconClass   The CSS class for the icon.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the button with an icon.
     */
    public function getIconButton(
        string $label,
        string $iconClass,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getIconButton($label, $iconClass, $type, $name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a confirmation button using ButtonBuilder.
     *
     * @param string      $label              The label to be displayed on the button.
     * @param string      $confirmationMessage The message for the confirmation dialog.
     * @param string      $type               [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name               [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes         [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad                [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml             [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the confirmation button.
     */
    public function getConfirmButton(
        string $label,
        string $confirmationMessage,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getConfirmButton($label, $confirmationMessage, $type, $name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a disabled button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the button.
     * @param string      $type        [optional] The button type (e.g., "submit", "button"). Default is 'button'.
     * @param string|null $name        [optional] Optional name attribute for the button. Default is null.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the disabled button.
     */
    public function getDisabledButton(
        string $label,
        string $type = 'button',
        ?string $name = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getDisabledButton($label, $type, $name, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a dropdown button with a list of items using ButtonBuilder.
     *
     * @param string      $label         The label to be displayed on the button.
     * @param array       $dropdownItems An array of items with 'link' and 'label' keys for the dropdown.
     * @param string      $type          [optional] The button type (e.g., "button"). Default is 'button'.
     * @param string|null $name          [optional] Optional name attribute for the button. Default is null.
     * @param array       $buttonAttributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param array       $dropdownAttributes [optional] Additional attributes for the dropdown container. Default is an empty array.
     * @param array       $contentAttributes [optional] Additional attributes for the dropdown-content div. Default is an empty array.
     * @param int         $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the dropdown button.
     */
    public function getDropdownButton(
        string $label,
        array $dropdownItems,
        string $type = 'button',
        ?string $name = null,
        array $buttonAttributes = [],
        array $dropdownAttributes = [],
        array $contentAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getDropdownButton($label, $dropdownItems, $type, $name, $buttonAttributes, $dropdownAttributes, $contentAttributes, $pad, $isHtml);
    }

    /**
     * Generates a reset button element using ButtonBuilder.
     *
     * @param string      $label       [optional] The label to be displayed on the reset button. Default is 'Reset'.
     * @param array       $attributes  [optional] Additional attributes for the button. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the reset button.
     */
    public function getResetButton(
        string $label = 'Reset',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getResetButton($label, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a link styled as a button element using ButtonBuilder.
     *
     * @param string      $label       The label to be displayed on the link.
     * @param string      $href        The URL the link should point to.
     * @param array       $attributes  [optional] Additional attributes for the link. Default is an empty array.
     * @param int         $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the link styled as a button.
     */
    public function getLinkButton(
        string $label,
        string $href,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getLinkButton($label, $href, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of buttons (submit, reset, or custom) using ButtonBuilder.
     *
     * @param array $buttons     An array of button configurations (e.g., 'name', 'label', 'type', etc.).
     * @param int   $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool  $isHtml      [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the group of buttons.
     */
    public function getButtonGroup(
        array $buttons,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->buttonBuilder->getButtonGroup($buttons, $buttonWidth, $pad, $isHtml);
    }

    /**
     * Generates a single radio button using RadioBuilder.
     *
     * @param string $name       The name attribute for the radio input.
     * @param string $value      The value attribute for the radio input.
     * @param string $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string $data       The data used to determine whether the radio button is checked.
     * @param array  $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the radio button element.
     */
    public function getRadioButton(
        string $name,
        string $value,
        string $status,
        string $data,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getRadioButton($name, $value, $status, $data, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a labeled radio button using RadioBuilder.
     *
     * @param string      $name       The name attribute for the radio input.
     * @param string      $value      The value attribute for the radio input.
     * @param string      $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string      $data       The data used to determine whether the radio button is checked.
     * @param string|null $label      [optional] Optional label for the radio button. Default is null.
     * @param bool        $labelAfter [optional] Whether to place the label after the radio button. Default is true.
     * @param array       $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int         $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the labeled radio button.
     */
    public function getLabeledRadioButton(
        string $name,
        string $value,
        string $status,
        string $data,
        ?string $label = null,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getLabeledRadioButton($name, $value, $status, $data, $label, $labelAfter, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a radio button with a unique ID using RadioBuilder.
     *
     * @param string      $name       The name attribute for the radio input.
     * @param string      $value      The value attribute for the radio input.
     * @param string      $status     The status of the radio button (e.g., "checked", "disabled").
     * @param string      $data       The data used to determine whether the radio button is checked.
     * @param string|null $id         [optional] Optional unique ID for the radio button. Default is null.
     * @param array       $attributes [optional] Additional attributes for the radio input. Default is an empty array.
     * @param int         $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool        $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the radio button with an ID.
     */
    public function getRadioButtonWithID(
        string $name,
        string $value,
        string $status,
        string $data,
        ?string $id = null,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getRadioButtonWithID($name, $value, $status, $data, $id, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of radio buttons with an inline layout option using RadioBuilder.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key => value).
     * @param bool   $inline         [optional] Whether to display the radio buttons inline. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the group of radio buttons.
     */
    public function getRadioGroupWithInlineOption(
        string $name,
        string $selectedOption,
        array $options,
        bool $inline = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getRadioGroupWithInlineOption($name, $selectedOption, $options, $inline, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of disabled radio buttons using RadioBuilder.
     *
     * @param string $name       The name attribute for the radio inputs.
     * @param array  $options    An associative array of options (key => value).
     * @param array  $attributes [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the disabled radio group.
     */
    public function getDisabledRadioGroup(
        string $name,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getDisabledRadioGroup($name, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of radio buttons from an associative array of options using RadioBuilder.
     *
     * @param string $name           The name attribute for the radio inputs.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the radio inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the radio group.
     */
    public function getRadioGroup(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->radioBuilder->getRadioGroup($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a generic checkbox HTML element using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      [optional] The value attribute for the checkbox input. Default is 'on'.
     * @param bool   $isChecked  [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the checkbox element.
     */
    public function getCheckbox(
        string $name,
        string $value = 'on',
        bool $isChecked = false,
        bool $isDisabled = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getCheckbox($name, $value, $isChecked, $isDisabled, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a customizable checkbox using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      The value attribute for the checkbox input.
     * @param string $data       The data used to determine whether the checkbox is checked.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the custom checkbox.
     */
    public function getCustomCheckbox(
        string $name,
        string $value,
        string $data,
        bool $isDisabled = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getCustomCheckbox($name, $value, $data, $isDisabled, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a labeled checkbox using CheckboxBuilder.
     *
     * @param string $name       The name attribute for the checkbox input.
     * @param string $value      The value attribute for the checkbox input.
     * @param string $label      The label to be displayed next to the checkbox.
     * @param bool   $isChecked  [optional] Whether the checkbox is checked. Default is false.
     * @param bool   $isDisabled [optional] Whether the checkbox is disabled. Default is false.
     * @param bool   $labelAfter [optional] Whether the label should appear after the checkbox. Default is true.
     * @param array  $attributes [optional] Additional attributes for the checkbox input. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the labeled checkbox.
     */
    public function getLabeledCheckbox(
        string $name,
        string $value,
        string $label,
        bool $isChecked = false,
        bool $isDisabled = false,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getLabeledCheckbox($name, $value, $label, $isChecked, $isDisabled, $labelAfter, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of checkboxes using CheckboxBuilder.
     *
     * @param string $name           The name attribute for the checkbox inputs.
     * @param array  $selectedValues An array of values that should be pre-selected (checked).
     * @param array  $options        An associative array of options (key => value).
     * @param array  $attributes     [optional] Additional attributes for the checkbox inputs. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the group of checkboxes.
     */
    public function getCheckboxGroup(
        string $name,
        array $selectedValues,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getCheckboxGroup($name, $selectedValues, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a group of disabled checkboxes using CheckboxBuilder.
     *
     * @param string $name      The name attribute for the checkbox inputs.
     * @param array  $options   An associative array of options (key => value).
     * @param int    $pad       [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml    [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the disabled checkbox group.
     */
    public function getDisabledCheckboxGroup(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getDisabledCheckboxGroup($name, $options, $pad, $isHtml);
    }

    /**
     * Generates a hidden checkbox using CheckboxBuilder.
     *
     * @param string $name      The name attribute for the hidden checkbox.
     * @param string $value     [optional] The value attribute for the hidden checkbox. Default is 'on'.
     * @param bool   $isChecked [optional] Whether the checkbox is checked. Default is false.
     * @param int    $pad       [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml    [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the hidden checkbox.
     */
    public function getHiddenCheckbox(
        string $name,
        string $value = 'on',
        bool $isChecked = false,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->checkboxBuilder->getHiddenCheckbox($name, $value, $isChecked, $pad, $isHtml);
    }

    /**
     * Generates a generic dropdown (select box) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the dropdown.
     */
    public function getPulldown(
        string $name,
        string $selectedOption,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getPulldown($name, $selectedOption, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a multi-select dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param array  $selectedOptions An array of options that should be pre-selected.
     * @param array  $options        An associative array of options (key = value attribute, value = display text).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the multi-select dropdown.
     */
    public function getMultiSelectPulldown(
        string $name,
        array $selectedOptions,
        array $options,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getMultiSelectPulldown($name, $selectedOptions, $options, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a grouped dropdown (with <optgroup>) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The value of the option that should be pre-selected.
     * @param array  $groups         An associative array of groups, where each key is the group label and the value is an array of options.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the grouped pulldown.
     */
    public function getGroupedPulldown(
        string $name,
        string $selectedOption,
        array $groups,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getGroupedPulldown($name, $selectedOption, $groups, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a disabled dropdown using PulldownBuilder.
     *
     * @param string $name   The name attribute for the <select> element.
     * @param array  $options An associative array of options (key = value attribute, value = display text).
     * @param int    $pad    [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the disabled dropdown.
     */
    public function getDisabledPulldown(
        string $name,
        array $options,
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDisabledPulldown($name, $options, $pad, $isHtml);
    }

    /**
     * Generates a numeric range dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected number in the range.
     * @param int    $start          The starting number.
     * @param int    $end            The ending number.
     * @param bool   $zeroPadded     [optional] Whether the numbers should be zero-padded. Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the numbers pulldown.
     */
    public function getNumbersPulldown(
        string $name,
        string $selectedOption,
        int $start,
        int $end,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getNumbersPulldown($name, $selectedOption, $start, $end, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a Yes/No dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected option (1 for Yes, 0 for No).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the Yes/No pulldown.
     */
    public function getYesNoPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getYesNoPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a semester dropdown (Spring/Fall) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected semester (A for Spring, B for Fall).
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the semester pulldown.
     */
    public function getSemestersPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getSemestersPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a years range dropdown using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected year in the range.
     * @param int    $startYear      The starting year.
     * @param int    $endYear        [optional] The ending year. Default is 3 years from the current year.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the years pulldown.
     */
    public function getYearsPulldown(
        string $name,
        string $selectedOption,
        int $startYear,
        int $endYear = 0,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getYearsPulldown($name, $selectedOption, $startYear, $endYear, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a numeric months dropdown (1-12|01-12) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param bool   $zeroPadded     [optional] Whether the months should be zero-padded (e.g., 01, 02, ...). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the numeric months pulldown.
     */
    public function getMonthsPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getMonthsPulldown($name, $selectedOption, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a short month names dropdown (Jan, Feb, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the short month names pulldown.
     */
    public function getShortMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getShortMonthNamesPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a full month names dropdown (January, February, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected month.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the full month names pulldown.
     */
    public function getFullMonthNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getFullMonthNamesPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a days in a month dropdown (1-31|01-31) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01, 02, ...). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the days pulldown.
     */
    public function getDaysOfMonthPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDaysOfMonthPulldown($name, $selectedOption, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a numeric days of the week dropdown (1-7|01-07) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day.
     * @param bool   $zeroPadded     [optional] Whether the days should be zero-padded (e.g., 01 for Monday). Default is false.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the days of the week pulldown.
     */
    public function getDaysOfWeekPulldown(
        string $name,
        string $selectedOption,
        bool $zeroPadded = false,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getDaysOfWeekPulldown($name, $selectedOption, $zeroPadded, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a short day names dropdown (Mon, Tue, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the short day names pulldown.
     */
    public function getShortDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getShortDayNamesPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a full day names dropdown (Monday, Tuesday, etc.) using PulldownBuilder.
     *
     * @param string $name           The name attribute for the <select> element.
     * @param string $selectedOption The selected day of the week.
     * @param array  $attributes     [optional] Additional attributes for the <select> element. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The generated HTML for the full day names pulldown.
     */
    public function getFullDayNamesPulldown(
        string $name,
        string $selectedOption,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getFullDayNamesPulldown($name, $selectedOption, $attributes, $pad, $isHtml);
    }

    /**
     * Generates a dropdown (pulldown) with an associated label element.
     *
     * @param string $name           The name attribute for the dropdown.
     * @param string $selectedOption The value of the option to be selected by default.
     * @param array  $options        Array of options for the dropdown, where keys are option values and
     *                               values are the displayed text.
     * @param string $label          The label text displayed with the dropdown.
     * @param bool   $labelAfter     [optional] If true, the label appears after the dropdown. Default is true.
     * @param array  $attributes     [optional] Additional attributes for the dropdown. Default is an empty array.
     * @param int    $pad            [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml         [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the labeled dropdown element.
     */
    public function getLabeledPulldown(
        string $name,
        string $selectedOption,
        array $options,
        string $label,
        bool $labelAfter = true,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->pulldownBuilder->getLabeledPulldown($name, $selectedOption, $options, $label, $labelAfter, $attributes, $pad, $isHtml);
    }

    /**
     * Formats an individual string with optional left padding based on the format flag.
     *
     * @param string $content        The HTML content to format.
     * @param bool   $formatOutput   Whether to format with padding and newlines.
     * @param bool   $prependNewline Whether to prepend a newline.
     * @param int    $pad            Number of spaces for left padding (only applies if $format is true).
     *
     * @return string The formatted string.
     */
    public function formatOutput(
        string $content,
        bool $formatOutput = false,
        bool $prependNewline = false,
        int $pad = 0
    ): string {
        return HtmlBuildUtility::formatOutput($content, $formatOutput, $prependNewline, $pad);
    }

    /**
     * Formats an array of HTML parts into a single string, with optional newlines between parts.
     *
     * @param array $parts        The array of HTML strings to join together.
     * @param bool  $formatOutput Whether to format with newlines between parts.
     *
     * @return string The concatenated HTML string.
     */
    public static function formatParts(
        array $parts,
        bool $formatOutput = false
    ): string {
        return HtmlBuildUtility::formatParts($parts, $formatOutput);
    }

    /**
     * Escapes a string for HTML output.
     *
     * @param string $string The string to escape.
     * @param bool   $isHtml Whether to skip escaping if the content is already HTML.
     *
     * @return string The escaped or raw string.
     */
    public static function escape(
        string $string,
        bool $isHtml = false
    ): string {
        return HtmlBuildUtility::escape($string, $isHtml);
    }
}
