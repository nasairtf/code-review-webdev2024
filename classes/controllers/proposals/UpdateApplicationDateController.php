<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                                 as Debug;
use App\models\proposals\UpdateApplicationDateModel               as Model;
use App\views\forms\proposals\UpdateApplicationDateView           as FormView;
use App\views\pages\proposals\UpdateApplicationDateView           as ListView;
use App\validators\forms\proposals\UpdateApplicationDateValidator as Validator;

/**
 * Controller for handling the Update Application Date logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UpdateApplicationDateController
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
            $this->handleForm1Submit($_GET);
        } elseif (isset($_POST['select'])) {
            // Handle second form select
            $this->handleForm2Submit($_POST);
        } elseif (isset($_POST['confirm'])) {
            // Handle third form confirm
            $this->handleForm3Submit($_POST);
        } else {
            // Display the first page if no form is submitted
            $this->renderSemesterSelectFormPage();
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     *
     * handleForm1Submit - validates form data and passes to semester processor method
     * handleForm2Submit - validates form data and passes to listing processor method
     * handleForm3Submit - validates form data and passes to timestamp processor method
     */

    private function handleForm1Submit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleForm1Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        try {
            // Validate the form data
            $year = $this->valid->validateYear($formData['y'] ?? null);
            $semester = $this->valid->validateSemester($formData['s'] ?? null);
            // If validation passes, proceed to processing the semester data
            $this->processForm1Submit($year, $semester);
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('The year and/or semester are not valid.', $e->getMessage());
        }
    }

    private function handleForm2Submit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleForm2Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        try {
            // Validate the form data
            $obsAppId = $this->valid->validateObsAppID($formData['i'] ?? null);
            // If validation passes, proceed to processing the proposal data
            $this->processForm2Submit($obsAppId);
        } catch (Exception $e) {
            // If validation fails, generate an error page
            $this->renderErrorPage('The proposal ID is not valid', $e->getMessage());
        }
    }

    private function handleForm3Submit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleForm3Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        try {
            // Validate the form data
            $obsAppId = $this->valid->validateObsAppID($formData['i'] ?? null);
            $timestamp = $this->valid->validateTimestamp($formData['t'] ?? null);
            // If validation passes, proceed to processing the proposal timestamp
            $this->processForm3Submit($obsAppId, $timestamp);
        } catch (Exception $e) {
            // If validation fails, generate an error page
            $this->renderErrorPage('The proposal ID and/or timestamp are not valid.', $e->getMessage());
        }
    }

    /**
     * Data processing methods that set up interface with the DB Class
     *
     * processForm1Submit - delivers the selected semester's data to renderer
     * processForm2Submit - delivers the selected proposal's data to renderer
     * processForm3Submit - delivers the results of the attempted update to renderer
     */

    private function processForm1Submit(
        int $year,
        string $semester
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processForm1Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");

        try {
            // Fetch the semester data
            $proposals = $this->model->fetchSemesterData($year, $semester);
            // Render the next form with the retrieved data
            $this->renderSemesterListFormsPage($proposals);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching semester data", $e->getMessage());
        }
    }

    private function processForm2Submit(
        int $obsAppId
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processForm2Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($obsAppId, "{$debugHeading} -- obsAppId");

        try {
            // Fetch the proposal data
            $proposal = $this->model->fetchProposalData($obsAppId);
            // Render the next form with the retrieved proposal data
            $this->renderProposalEditFormPage($proposal[0]);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching proposal data", $e->getMessage());
        }
    }

    private function processForm3Submit(
        int $obsAppId,
        int $timestamp
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processForm3Submit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($obsAppId, "{$debugHeading} -- obsAppId");
        $this->debug->debugVariable($timestamp, "{$debugHeading} -- timestamp");

        try {
            // Update the proposal with the new timestamp
            $resultMessage = $this->model->updateProposal($obsAppId, $timestamp);
            // Render the results page with the result message
            $this->renderResultsPage($resultMessage);
        } catch (Exception $e) {
            // Handle any errors during the update process
            $this->renderErrorPage("Error updating proposal timestamp", $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderSemesterSelectFormPage - renders the semester choosing form
     * renderSemesterListFormsPage  - renders the semester listing form
     * renderProposalEditFormPage   - renders the creation date update form
     * renderResultsPage            - renders the successful update result page
     * renderErrorPage              - renders the error page displayed for caught exceptions
     */

    private function renderSemesterSelectFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderSemesterSelectFormPage");
        $this->debug->debug($debugHeading);

        // Prepare to render the initial form
        $pageTitle = "IRTF Proposal Date Update Semester Chooser";
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

    private function renderSemesterListFormsPage(
        array $proposals
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderSemesterListFormsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($proposals, "{$debugHeading} -- proposals");

        // Prepare to render the semester list forms page
        $pageTitle = "IRTF Proposal Date Update Semester Listing";
        $dbData = ['action' => $_SERVER['PHP_SELF']];
        $pageData = $proposals;
        $pad = 0;

        // Call the view to render the list of forms
        echo $this->listView->renderDisplayPage(
            $pageTitle,
            $dbData,
            $pageData,
            $pad
        );
    }

    private function renderProposalEditFormPage(
        array $proposal
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderProposalEditFormPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($proposal, "{$debugHeading} -- proposal");

        // Prepare to render the initial form
        $pageTitle = "IRTF Proposal Creation Date Entry";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];
        $formData = $proposal;

        // Call the view to render the initial form
        echo $this->formView->renderFormPage(
            $pageTitle,
            $formAction,
            $dbData,
            $formData
        );
    }

    private function renderResultsPage(
        string $resultMessage
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($resultMessage, "{$debugHeading} -- resultMessage");

        // Prepare to render the edit results page
        $pageTitle = "IRTF Proposal Submission Date Update";

        // Call the view to render the results page
        echo $this->listView->renderResultsPage(
            $pageTitle,
            $resultMessage
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
