<?php

declare(strict_types=1);

namespace App\views\forms\proposals;

use Exception;
use App\exceptions\HtmlBuilderException;
use App\core\common\CustomDebug           as Debug;
use App\views\forms\BaseFormView          as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;
use App\legacy\traits\LegacyObsDataRestorationRequestTrait;

/**
 * View for rendering the Observer Data Restoration Request form.
 *
 * @category Views
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsDataRestorationRequestView extends BaseView
{
    use LegacyObsDataRestorationRequestTrait;

    /**
     * Initializes the ListApplicationPdfsView with core builders and configurations.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
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
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- View initialisation complete.");
    }

    // Abstract methods: getFieldLabels(), getPageContents()

    /**
     * Provides field labels for the Login form.
     *
     * Maps internal field names to user-friendly labels.
     *
     * @return array An associative array mapping field names to labels.
     */
    public function getFieldLabels(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getFieldLabels");
        $this->debug->debug($debugHeading);

        // Map internal field names to user-friendly labels
        return [
            'reqname'    => 'Requestor name',
            'reqemail'   => 'Requestor email',
            'y'          => 'The semester year the data were taken',
            's'          => 'The semester tag the data were taken',
            'srcprogram' => 'Program the data were taken under',
            'piprogram'  => 'PI of the program',
            'obsinstr'   => 'Instruments used to take the data',
            'reldetails' => 'Any other details that might be relevant or helpful',
        ];
    }

    /**
     * Generates the main page content for the Schedule Upload form.
     *
     * This method defines the specific HTML structure for the login form,
     * using the provided database and form data. The BaseFormView parent method
     * getContentsForm() passes the contents to renderFormPage(), etc., for rendering.
     *
     * @param array $dbData   Data arrays required to populate form options. Defaults to an empty array.
     * @param array $formData Default data for form fields. Defaults to an empty array.
     * @param int   $pad      Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML content for the form page.
     */
    protected function getPageContents(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getPageContents");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Build the page contents
        return $this->generateDataRequestFormPage($this->debug->isDebugMode(), $formData);
    }

    /**
     * THIS IS A KLUGE METHOD DROPPED IN TO GET THE "REFACTORED" FORM FUNCTIONAL ASAP!!!
     *
     * It's ugly, in the wrong place, and using legacy procedural code.
     *
     * Please for all our sanities, replace it with appropriate modern code as soon as
     * workloads allow.
     */
    public function replaceThisMethodWithCorrectCode(
        array $validData
    ): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "replaceThisMethodWithCorrectCode");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // generate the confirmation email and write out the log of the request
        return $this->generateDataRequestEmail($this->debug->isDebugMode(), $validData);
    }

}
