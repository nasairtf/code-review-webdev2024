<?php

namespace App\core\htmlbuilder;

/**
 * /home/webdev2024/classes/core/htmlbuilder/BaseHtmlBuilder.php
 *
 * A utility class responsible for building assorted HTML components that don't fit into the larger Builder classes.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class BaseHtmlBuilder
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
     * @param bool $formatOutput If true, output will be formatted with indentation.
     */
    public function __construct(bool $formatOutput = false)
    {
        $this->formatOutput = $formatOutput;
    }

    /**
     * Generates a <br> element for line breaks.
     *
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the line break element.
     */
    public function getBreak(
        int $pad = 0
    ): string {
        return HtmlBuildUtility::formatOutput('<br />', $this->formatOutput, false, $pad);
    }

    /**
     * Generates a <hr> element for horizontal lines (with no tables).
     *
     * @param array  $attributes    [optional] Additional attributes for the <hr> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     *
     * @return string The HTML for the horizontal line element.
     */
    public function getLine(
        array $attributes = [],
        int $pad = 0
    ): string {
        $html = sprintf('<hr%s/>', HtmlBuildUtility::buildAttributes($attributes));
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a hyperlink (<a>).
     *
     * @param string $url           The URL for the link.
     * @param string $label         The label for the link.
     * @param array  $attributes    [optional] Additional attributes for the <a> url element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the hyperlink.
     */
    public function getLink(
        string $url,
        string $label,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedUrl = HtmlBuildUtility::escape($url, false);
        $escapedLabel = HtmlBuildUtility::escape($label, $isHtml);
        $html = sprintf('<a href="%s"%s>%s</a>', $escapedUrl, $attrString, $escapedLabel);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a mailto link (<a href="mailto:">).
     *
     * @param string $email         The email address for the link.
     * @param string $label         [optional] The label for the link. Default is an empty string.
     * @param array  $attributes    [optional] Additional attributes for the <a> mailto element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the mailto link.
     */
    public function getEmailLink(
        string $email,
        string $label = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedEmail = HtmlBuildUtility::escape($email, false);
        $escapedLabel = HtmlBuildUtility::escape($label ?? $email, $isHtml);
        $html = sprintf('<a href="mailto:%s"%s>%s</a>', $escapedEmail, $attrString, $escapedLabel);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a file input element.
     *
     * @param string $name          The name attribute for the file input.
     * @param array  $attributes    [optional] Additional attributes for the file input element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the file input element.
     */
    public function getFileInput(
        string $name,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedName = HtmlBuildUtility::escape($name, $isHtml);
        $html = sprintf('<input type="file" name="%s"%s />', $escapedName, $attrString);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates an <img> element.
     *
     * @param string $src           The source of the image.
     * @param string $alt           [optional] The alt text for the image. Default is an empty string.
     * @param array  $attributes    [optional] Additional attributes for the <img> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the image element.
     */
    public function getImage(
        string $src,
        string $alt = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attributes['src'] = HtmlBuildUtility::escape($src, $isHtml);
        $attributes['alt'] = HtmlBuildUtility::escape($alt, $isHtml);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $html = sprintf('<img%s />', $attrString);
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a label (<label>) for form inputs.
     *
     * @param string $for           The ID of the form element this label is for.
     * @param string $content       The content of the label.
     * @param array  $attributes    [optional] Additional attributes for the <label> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the label element.
     */
    public function getLabel(
        string $for,
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attributes['for'] = HtmlBuildUtility::escape($for, false);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedContent = HtmlBuildUtility::escape($content, $isHtml);
        $html = sprintf(
            '<label%s>%s</label>',
            $attrString,
            $escapedContent
        );
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a paragraph with specific content.
     *
     * @param string $content       The content of the paragraph.
     * @param array  $attributes    [optional] Additional attributes for the <p> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the paragraph element, including any applied attributes.
     */
    public function getParagraph(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $htmlParts = [];
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $htmlParts[] = HtmlBuildUtility::formatOutput(sprintf('<p%s>', $attrString), $this->formatOutput, false, $pad);
        $htmlParts[] = HtmlBuildUtility::escape($content, $isHtml);
        $htmlParts[] = HtmlBuildUtility::formatOutput('</p>', $this->formatOutput, false, $pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a <span> element with content.
     *
     * @param string $content       The content of the span.
     * @param array  $attributes    [optional] Additional attributes for the <span> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the span element.
     */
    public function getSpan(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedContent = HtmlBuildUtility::escape($content, $isHtml);
        $html = sprintf(
            '<span%s>%s</span>',
            $attrString,
            $escapedContent
        );
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a <form> element.
     *
     * @param string $action        The action URL for the form.
     * @param string $method        [optional] The HTTP method (GET or POST). Default is POST.
     * @param string $content       [optional] The content inside the form (e.g., input fields). Default is an empty string.
     * @param array  $attributes    [optional] Additional attributes for the <form> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the form element.
     */
    public function getForm(
        string $action,
        string $method = 'post',
        string $content = '',
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $attributes['action'] = HtmlBuildUtility::escape($action, false);
        $attributes['method'] = HtmlBuildUtility::escape($method, false);
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedContent = HtmlBuildUtility::escape($content, $isHtml);
        $htmlParts = [];
        $htmlParts[] = HtmlBuildUtility::formatOutput(sprintf('<form%s>', $attrString), $this->formatOutput, false, $pad);
        $htmlParts[] = HtmlBuildUtility::formatOutput($escapedContent, $this->formatOutput, false, $pad);
        $htmlParts[] = HtmlBuildUtility::formatOutput('</form>', $this->formatOutput, false, $pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates an HTML heading element (<h1>, <h2>, etc.).
     *
     * @param string $content       The content of the heading.
     * @param int    $level         The heading level (1-6).
     * @param array  $attributes    [optional] Additional attributes for the heading element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the heading element.
     */
    public function getHeading(
        string $content,
        int $level,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $level = max(1, min(6, $level)); // Ensure level is between 1 and 6
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $escapedContent = HtmlBuildUtility::escape($content, $isHtml);
        $html = sprintf(
            '<h%d%s>%s</h%d>',
            $level,
            $attrString,
            $escapedContent,
            $level
        );
        return HtmlBuildUtility::formatOutput($html, $this->formatOutput, false, $pad);
    }

    /**
     * Generates a list (<ul> or <ol>) with list items.
     *
     * @param array  $items         An array of list item content.
     * @param bool   $ordered       [optional] If true, generates an ordered list (<ol>), otherwise <ul>. Default is false.
     * @param array  $attributes    [optional] Additional attributes for the <ul> or <ol> element. Default is an empty array.
     * @param array  $liAttributes  [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the list.
     */
    public function getList(
        array $items,
        bool $ordered = false,
        array $attributes = [],
        array $liAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $tag = $ordered ? 'ol' : 'ul';
        $attrString = HtmlBuildUtility::buildAttributes($attributes);
        $htmlParts = [];
        $htmlParts[] = HtmlBuildUtility::formatOutput(sprintf('<%s%s>', $tag, $attrString), $this->formatOutput, false, $pad);
        foreach ($items as $item) {
            $liAttrString = HtmlBuildUtility::buildAttributes($liAttributes);
            $escapedItem = HtmlBuildUtility::escape($item, $isHtml);
            $listItem = sprintf('<li%s>%s</li>', $liAttrString, $escapedItem);
            $htmlParts[] = HtmlBuildUtility::formatOutput($listItem, $this->formatOutput, false, $pad + 2);
        }
        $htmlParts[] = HtmlBuildUtility::formatOutput(sprintf('</%s>', $tag), $this->formatOutput, false, $pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }

    /**
     * Generates a navigation bar with links.
     *
     * @param array  $links         An associative array of links (href => label).
     * @param array  $ulAttributes  [optional] Additional attributes for the <ul> element. Default is an empty array.
     * @param array  $liAttributes  [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param array  $aAttributes   [optional] Additional attributes for each <a> element. Default is an empty array.
     * @param int    $pad           [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml        [optional] If true, content will not be escaped by htmlspecialchars. Default is false.
     *
     * @return string The HTML for the navigation bar.
     */
    public function getNavBar(
        array $links,
        array $ulAttributes = [],
        array $liAttributes = [],
        array $aAttributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        $navParts = [];
        $ulAttrString = HtmlBuildUtility::buildAttributes($ulAttributes);
        $htmlParts[] = HtmlBuildUtility::formatOutput(sprintf('<ul%s>', $ulAttrString), $this->formatOutput, false, $pad);
        foreach ($links as $href => $label) {
            $liAttrString = HtmlBuildUtility::buildAttributes($liAttributes);
            $linkHtml = $this->getLink($href, $label, $aAttributes, 0, $isHtml);
            $navItem = sprintf('<li%s>%s</li>', $liAttrString, $linkHtml);
            $htmlParts[] = HtmlBuildUtility::formatOutput($navItem, $this->formatOutput, false, $pad + 2);
        }
        $htmlParts[] = HtmlBuildUtility::formatOutput('</ul>', $this->formatOutput, false, $pad);
        return HtmlBuildUtility::formatParts($htmlParts, $this->formatOutput);
    }
}
