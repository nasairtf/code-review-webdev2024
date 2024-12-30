<?php

declare(strict_types=1);

namespace App\views\forms\proposals;

use App\core\common\CustomDebug;
use App\exceptions\HtmlBuilderException;
use App\views\forms\BaseFormView          as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;
use App\core\irtf\IrtfLinks;

/**
 * View for rendering the schedule upload form.
 *
 * @category Views
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadScheduleFileView extends BaseView
{
    private $irtfLinks;

    /**
     * Initializes the UpdateApplicationDateView with core builders and configurations.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param CustomDebug|null $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     * @param IrtfLinks|null   $irtfLinks   Links utiltiy getter for site.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?CustomDebug $debug = null,
        ?HtmlBuilder $htmlBuilder = null, // Dependency injection to simplify unit testing
        ?CompBuilder $compBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfBuilder $irtfBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfLinks $irtfLinks = null      // Dependency injection to simplify unit testing
    ) {
        // Use parent class' constructor
        parent::__construct($formatHtml, $debug, $htmlBuilder, $compBuilder, $irtfBuilder);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Set up the links instance
        $this->irtfLinks = $irtfLinks ?? new IrtfLinks();
        $this->debug->debug("{$debugHeading} -- Links class is successfully initialised.");

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
            'file'               => 'Schedule file',
            'loadtype'           => 'Schedule Load Type',
            'access'             => 'Schedule Access',
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
        // Use provided pageInfo or default to the config values
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Select the FastTrack time allocation file for upload\n";
        $code .= "      <input type='hidden' name='MAX_FILE_SIZE' value='60000000' >\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center'><input type='file' name='file' /></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td colspan='3' align='center'>If a full schedule load is needed, select 'Yes' below.</td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td align='center'>\n";
        $code .= "        <input type='radio' name='loadtype' value='partial' checked />No\n";
        $code .= "        &nbsp;\n";
        $code .= "        <input type='radio' name='loadtype' value='full' />Yes\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td colspan='3' align='center'>\n";
        $code .= "       For only private txt/pdf files (no public access), click 'Private' below.\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td align='center'>\n";
        $code .= "        <input type='radio' name='access' value='public' checked />Public\n";
        $code .= "        &nbsp;\n";
        $code .= "        <input type='radio' name='access' value='private' />Private\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";

        $ack = $this->htmlBuilder->getLink($this->irtfLinks->getEditInstruments(), 'acknowledgement page', [], 0, true);
        $ack = $this->htmlBuilder->getLink($this->irtfLinks->getEditOperators(), 'acknowledgement page', [], 0, true);
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td>\n";
        $code .= "       <p>A full schedule load will remove all of the entries for the current semester from "
            . "the database and then load all entries in the schedule file uploaded here.</p>\n";
        $code .= "       <p>A partial schedule load will remove only entries in the future (data from "
            . date("m/d/Y") . " and later) and will leave all historical data in the database.</p>\n";
        $code .= "       <p>Generally a full schedule load should occur at the beginning of the semester and "
            . "updates done over the course of the semester can use the partial schedule load. Partial schedule "
            . "loading is the default behavior.</p>\n";
        $code .= "       <p><strong>Pease note</strong>: check the instrument and operator/night attendant listing "
            . "<a href='EditDatabase.php'>here</a> and add any instruments or personnel not already listed on the "
            . "page.</p>\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      <input type='submit' name='clear' value='Clear' style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Upload File' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
}
