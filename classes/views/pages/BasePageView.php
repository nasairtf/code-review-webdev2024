<?php

declare(strict_types=1);

namespace App\views\pages;

use App\exceptions\HtmlBuilderException;
use App\core\common\CustomDebug           as Debug;
use App\views\BaseView                    as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;

/**
 * Base class for rendering page views.
 *
 * This abstract class provides standard functionality for generating HTML forms
 * and results/error pages, with integrated debugging and layout building tools.
 * Child classes are expected to implement specific methods to define the form
 * structure and field labels.
 *
 * Key Features:
 * - Support for formatted (readable) HTML output.
 * - Debugging utilities for tracking rendering and form processing logic.
 * - Integration with multiple HTML builders for modular page construction.
 *
 * @category Views
 * @package  IRTF
 * @version  1.0.0
 */

abstract class BasePageView extends BaseView
{
    /**
     * Constructor for the BaseFormView class.
     *
     * Initializes debugging, formatting preferences, and the necessary builder instances. Defaults are provided
     * if no specific instances or configurations are passed.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?HtmlBuilder $htmlBuilder = null, // Dependency injection to simplify unit testing
        ?CompBuilder $compBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfBuilder $irtfBuilder = null  // Dependency injection to simplify unit testing
    ) {
        // Use parent class' constructor
        parent::__construct($formatHtml, $debug, $htmlBuilder, $compBuilder, $irtfBuilder);
        $debugHeading = $this->debug->debugHeading("BasePageView", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent View initialisation complete.");
    }

    // Abstract methods: getPageContents()

    /**
     * Abstract method to generate the main page content for a form.
     *
     * Each child class must implement this method to define the specific
     * content structure for its page. By default, data arrays are empty
     * and padding is set to 0. The BasePageView parent method renderDisplayPage() passes the
     * contents to renderPage(), etc., for rendering.
     *
     * @param array $dbData   Data arrays required to populate page. Defaults to an empty array.
     * @param array $pageData Default data for page. Defaults to an empty array.
     * @param int   $pad      Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML content for the form page.
     */
    abstract protected function getPageContents(
        array $dbData = [],
        array $pageData = [],
        int $pad = 0
    ): string;

    /**
     * Renders the main page.
     *
     * This method generates a forms page with the provided title and form data.
     * It wraps the form in the site's standard layout.
     *
     * @param string $title    The title of the form page.
     * @param string $action   The form submission URL.
     * @param array  $dbData   Data arrays required to populate page.
     * @param array  $pageData Default data for page fields.
     * @param int    $pad      Optional padding level for formatted output (default: 0).
     *
     * @return string The complete HTML of the form page.
     */
    public function renderDisplayPage(
        string $title = '',
        array $dbData = [],
        array $pageData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("BasePageView", "renderDisplayPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($pageData, "{$debugHeading} -- pageData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Wrap site meta around the body content
        return $this->renderPage(
            $title,
            $this->getPageContents(
                $dbData,
                $pageData,
                $pad
            )
        );
    }
}
