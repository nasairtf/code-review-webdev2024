<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\core\common\Debug;
use App\models\proposals\UpdateApplicationDateModel as Model;
use App\views\forms\proposals\UpdateApplicationDateView as View;
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
    private $view;
    private $valid;

    // Constructor: Initializes the controller, view, and model, and sets up debugging
    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null
    ) {
        $this->formatHtml = $formatHtml; // set the global html formatting
        $this->debug = $debug ?? new Debug('default', false, 0);
        $this->model = new Model($this->debug);
        $this->view = new View($this->formatHtml, $this->debug);
        $this->valid = new Validator($this->debug);
        $this->debug->log("Controller: Debug mode is ON.");
    }

    public function handleRequest(): void
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: handleRequest()");

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
            $this->renderForm1Page();
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
        $this->debug->debug("UpdateApplicationDate Controller: handleForm1Submit()");
        $this->debug->debugVariable($formData, "_GET");
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
        $this->debug->debug("UpdateApplicationDate Controller: handleForm2Submit()");
        $this->debug->debugVariable($formData, "_POST");
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
        $this->debug->debug("UpdateApplicationDate Controller: handleForm3Submit()");
        $this->debug->debugVariable($formData, "_POST");
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
        $this->debug->debug("UpdateApplicationDate Controller: processForm1Submit()");

        try {
            // Fetch the semester data
            $proposals = $this->model->fetchSemesterData($year, $semester);
            // Render the next form with the retrieved data
            $this->renderForm2Page($proposals);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching semester data", $e->getMessage());
        }
    }

    private function processForm2Submit(
        int $obsAppId
    ): void {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: processForm2Submit()");

        try {
            // Fetch the proposal data
            $proposal = $this->model->fetchProposalData($obsAppId);
            // Render the next form with the retrieved proposal data
            $this->renderForm3Page($proposal[0]);
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
        $this->debug->debug("UpdateApplicationDate Controller: processForm3Submit()");

        try {
            // Update the proposal with the new timestamp
            $resultMessage = $this->model->updateProposal($obsAppId, $timestamp);
            // Render the results page with the result message
            $this->renderForm4Page($resultMessage);
        } catch (Exception $e) {
            // Handle any errors during the update process
            $this->renderErrorPage("Error updating proposal timestamp", $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderForm1Page - renders the semester choosing form
     * renderForm2Page - renders the semester listing form
     * renderForm3Page - renders the creation date update form
     * renderForm4Page - renders the successful update result page
     * renderErrorPage - renders the error page displayed for caught exceptions
     */

    private function renderForm1Page(): void
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: renderForm1Page()");

        // Logic to generate the first page form
        $pageTitle = "IRTF Proposal Date Update Semester Chooser";
        $formAction = $_SERVER['PHP_SELF'];
        $code = $this->view->renderForm1Page($pageTitle, $formAction);
        // Render the initial form
        echo $code;
    }

    private function renderForm2Page(
        array $proposals
    ): void {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: renderForm2Page()");
        $this->debug->debugVariable($proposals, "proposals");

        // Logic to generate the second page form
        $pageTitle = "IRTF Proposal Date Update Semester Listing";
        $formAction = $_SERVER['PHP_SELF'];
        $code = $this->view->renderForm2Page($pageTitle, $formAction, $proposals);
        // Render the semester listing form
        echo $code;
    }

    private function renderForm3Page(
        array $proposal
    ): void {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: renderForm3Page()");
        $this->debug->debugVariable($proposal, "proposal");

        // Logic to generate the third page form
        $pageTitle = "IRTF Proposal Creation Date Entry";
        $formAction = $_SERVER['PHP_SELF'];
        $code = $this->view->renderForm3Page($pageTitle, $formAction, $proposal);
        // Render the creation date entry form
        echo $code;
    }

    private function renderForm4Page(
        string $resultMessage
    ): void {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: renderForm4Page()");

        // Logic to generate the fourth page form
        $pageTitle = "IRTF Proposal Submission Date Update";
        $code = $this->view->renderResultsPage($pageTitle, $resultMessage);
        // Render the submission date update form
        echo $code;
    }

    private function renderErrorPage(
        string $errorTitle,
        string $errorMessage
    ): void {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Controller: renderErrorPage()");

        echo $this->view->renderErrorPage($errorTitle, $errorMessage);
    }
}
