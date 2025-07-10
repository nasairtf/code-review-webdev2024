<?php

declare(strict_types=1);

namespace App\core\htmlbuilder;

/**
 * Wrapper for base HTML element generation methods.
 *
 * Provides proxy methods for generating fundamental HTML elements like
 * line breaks, links, images, paragraphs, and form containers using
 * the BaseHtmlBuilder class.
 *
 * This trait enables the HtmlBuilder to expose simplified calls to
 * lower-level HTML rendering utilities, supporting consistent formatting
 * and escaping logic across components.
 *
 * @see BaseHtmlBuilder
 */
trait HtmlBuilderBaseHtmlBuilderTrait
{
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
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getLink(
            $url,
            $label,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML mailto link using BaseHtmlBuilder.
     *
     * @param string $email      The email address for the link.
     * @param string $label      [optional] The label for the link. Default is the email address.
     * @param array  $attributes [optional] Additional attributes for the <a> element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getEmailLink(
            $email,
            $label,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML file input element using BaseHtmlBuilder.
     *
     * @param string $name       The name attribute for the file input.
     * @param array  $attributes [optional] Additional attributes for the input element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the file input element.
     */
    public function getFileInput(
        string $name,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getFileInput(
            $name,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML image element (<img>) using BaseHtmlBuilder.
     *
     * @param string $src        The source of the image.
     * @param string $alt        [optional] The alt text for the image. Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the image element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getImage(
            $src,
            $alt,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML label element (<label>) using BaseHtmlBuilder.
     *
     * @param string $for        The ID of the form element this label is for.
     * @param string $content    The content of the label.
     * @param array  $attributes [optional] Additional attributes for the label element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getLabel(
            $for,
            $content,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML paragraph element (<p>) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the paragraph.
     * @param array  $attributes [optional] Additional attributes for the paragraph element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the paragraph element.
     */
    public function getParagraph(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getParagraph(
            $content,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML span element (<span>) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the span.
     * @param array  $attributes [optional] Additional attributes for the span element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
     *
     * @return string The generated HTML for the span element.
     */
    public function getSpan(
        string $content,
        array $attributes = [],
        int $pad = 0,
        bool $isHtml = false
    ): string {
        return $this->baseHtmlBuilder->getSpan(
            $content,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML form element (<form>) using BaseHtmlBuilder.
     *
     * @param string $action     The action URL for the form.
     * @param string $method     [optional] The HTTP method (GET or POST). Default is POST.
     * @param string $content    [optional] The content inside the form (e.g., input fields).
     *                            Default is an empty string.
     * @param array  $attributes [optional] Additional attributes for the form element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getForm(
            $action,
            $method,
            $content,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML heading element (<h1>, <h2>, etc.) using BaseHtmlBuilder.
     *
     * @param string $content    The content of the heading.
     * @param int    $level      The heading level (1-6).
     * @param array  $attributes [optional] Additional attributes for the heading element. Default is an empty array.
     * @param int    $pad        [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml     [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getHeading(
            $content,
            $level,
            $attributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML list element (<ul> or <ol>) using BaseHtmlBuilder.
     *
     * @param array  $items       An array of list item content.
     * @param bool   $ordered     [optional] If true, generates an ordered list (<ol>), otherwise <ul>.
     *                             Default is false.
     * @param array  $attributes  [optional] Additional attributes for the list element. Default is an empty array.
     * @param array  $liAttributes [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param int    $pad         [optional] Indentation level for formatted output. Default is 0.
     * @param bool   $isHtml      [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getList(
            $items,
            $ordered,
            $attributes,
            $liAttributes,
            $pad,
            $isHtml
        );
    }

    /**
     * Generates an HTML navigation bar with links using BaseHtmlBuilder.
     *
     * @param array $links        An associative array of links (href => label).
     * @param array $ulAttributes [optional] Additional attributes for the <ul> element. Default is an empty array.
     * @param array $liAttributes [optional] Additional attributes for each <li> element. Default is an empty array.
     * @param array $aAttributes  [optional] Additional attributes for each <a> element. Default is an empty array.
     * @param int   $pad          [optional] Indentation level for formatted output. Default is 0.
     * @param bool  $isHtml       [optional] If true, content is treated as pre-escaped HTML. Default is false.
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
        return $this->baseHtmlBuilder->getNavBar(
            $links,
            $ulAttributes,
            $liAttributes,
            $aAttributes,
            $pad,
            $isHtml
        );
    }
}
