<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                               as Debug;
use App\models\proposals\ListApplicationPdfsModel               as Model;
use App\views\forms\proposals\ListApplicationPdfsView           as FormView;
use App\views\pages\proposals\ListApplicationPdfsView           as ListView;
use App\validators\forms\proposals\ListApplicationPdfsValidator as Validator;

/**
 * Controller for handling the List Application Pdfs logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ListApplicationPdfsController
{
    private $formatHtml;
    private $debug;
    private $model;
    private $formView;
    private $listView;
    private $valid;

    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?FormView $formView = null,
        ?ListView $listView = null,
        ?Validator $valid = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->debug);
        $this->formView = $formView ?? new FormView($this->formatHtml, $this->debug);
        $this->listView = $listView ?? new ListView($this->formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->debug("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Controller initialisation complete.");
    }

    /**
     * Handles incoming requests.
     *
     * Determines whether to process a form submission or render the form page.
     * Delegates specific actions to helper methods.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($_GET, "{$debugHeading} -- _GET");
        $this->debug->debugVariable($_POST, "{$debugHeading} -- _POST");

        if (isset($_GET['submit'])) {
            // Handle first form submit
            $this->handleSemesterSubmit($_GET);
        } else {
            // Display the first page if no form is submitted
            $this->renderSemesterSelectFormPage();
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     *
     * handleSemesterSubmit - validates form data and passes to semester processor method
     */

    private function handleSemesterSubmit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleSemesterSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        try {
            // Validate the form data
            $year = $this->valid->validateYear($formData['y'] ?? null);
            $semester = $this->valid->validateSemester($formData['s'] ?? null);
            // If validation passes, proceed to processing the semester data
            $this->processSemesterSubmit($year, $semester);
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('The year and/or semester are not valid.', $e->getMessage());
        }
    }

    /**
     * Data processing methods that set up interface with the DB Class
     *
     * processSemesterSubmit - delivers the selected semester's data to renderer
     */

    private function processSemesterSubmit(
        int $year,
        string $semester
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processSemesterSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");

        try {
            // Fetch the semester data
            $proposals = $this->model->fetchSemesterData($year, $semester);
            // Generate the tokens
            $proposals = $this->model->generateProposalLinks($proposals);
            // Render the next form with the retrieved data
            $this->renderSemesterListingPage($proposals);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching semester data", $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderSemesterSelectFormPage - renders the semester choosing form
     * renderSemesterListingPage    - renders the semester listing page
     * renderErrorPage              - renders the error page displayed for caught exceptions
     */

    private function renderSemesterSelectFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderSemesterSelectFormPage");
        $this->debug->debug($debugHeading);

        // Prepare to render the initial form
        $pageTitle = "IRTF Proposal PDF Lister Semester Chooser";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];
        $formData = [];

        // Call the view to render the initial form
        echo $this->formView->renderFormPage(
            $pageTitle,  // title
            $formAction, // action
            $dbData,     // dbData
            $formData,   // formData
            false,       // methodPost
            true,        // targetBlank
            0            // pad
        );
    }

    private function renderSemesterListingPage(
        array $proposals
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderSemesterListingPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($proposals, "{$debugHeading} -- proposals");

        // Prepare to render the semester list forms page
        $pageTitle = "IRTF Proposal PDF Semester Listing";
        $dbData = $proposals;
        $pageData = [];
        $pad = 0;

        // Call the view to render the list of proposals
        echo $this->listView->renderDisplayPage(
            $pageTitle,
            $dbData,
            $pageData,
            $pad
        );
    }

    private function renderErrorPage(
        string $errorTitle,
        string $errorMessage
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderErrorPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($errorTitle, "{$debugHeading} -- errorTitle");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Call the view to render the error page
        echo $this->listView->renderErrorPage(
            $errorTitle,
            $errorMessage
        );
    }
}
