<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\CustomDebug                                as Debug;
use App\schedule\ScheduleManager                               as Manager;
use App\views\forms\proposals\UploadScheduleFileView           as View;
use App\validators\forms\proposals\UploadScheduleFileValidator as Validator;

/**
 * Controller for handling the schedule upload logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadScheduleFileController
{
    private $formatHtml;
    private $debug;
    //private $model;
    private $view;
    private $valid;
    private $schedule;

    // Constructor: Initializes the controller, view, and model, and sets up debugging
    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml;

        // Initialise the view and validator. Model is not needed.
        $this->view = new View($this->formatHtml, $this->debug);
        $this->valid = new Validator($this->debug);
        $this->debug->log("{$debugHeading} -- View, Validator classes are successfully initialised.");

        // Initialise the additional class(es) needed by this controller
        $this->schedule = new Manager($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Additional class successfully initialised.");
    }

    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($_POST, "{$debugHeading} -- _POST");
        $this->debug->debugVariable($_FILES, "{$debugHeading} -- _FILES");

        if (isset($_POST['submit'])) {
            // Handle form submit
            $this->handleFormSubmit($_POST, $_FILES);
        } else {
            // Display the form page if no form is submitted
            $this->renderFormPage();
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     */

    private function handleFormSubmit(
        array $formData,
        array $fileData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");
        $this->debug->debugVariable($fileData, "{$debugHeading} -- _FILES");
        // Merge the file data into the form data array
        $formData['file'] = $fileData['file'];
        $formData['path'] = "/home/proposal/schedule/";
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        try {
            // Validate the form data
            $validData = $this->valid->validateFormData($formData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");
            // If validation passes, proceed to processing the form data
            $this->debug->debug("{$debugHeading} -- Validation checks completed.");
            $this->processFormSubmit($validData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");
            // Render the form with errors and user input if validation fails
            $this->renderFormWithErrors(
                $formData,
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    /**
     * Data processing methods that set up interface with the DB Class
     *
     */

    private function processFormSubmit(array $validData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");
        // The SQL loading mode: fileload ? infile : explicit SQL
        $validData['fileload'] = true;
        // Process and then render the results page
        try {
            // Pass the file and form data off to the schedule manager for processing
            $results = $this->schedule->uploadSchedule($validData);
            // Render the form with the results
            $this->renderResultsPage($results);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching semester data", $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     */

    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);
        // Render the initial form
        $pageTitle = "Upload Schedule File";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];
        $formData = [];
        // Render the initial form
        $code = $this->view->renderFormPage(
            $pageTitle,
            $formAction,
            $dbData,
            $formData
        );
        echo $code;
    }

    private function renderFormWithErrors(
        array $formData,
        array $dataErrors
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        // Render the initial form
        $pageTitle = "Upload Schedule File";
        $formAction = $_SERVER['PHP_SELF'];
        $fieldLabels = $this->view->getFieldLabels();
        // Render the errors section and the form
        $code = $this->view->renderFormWithErrors(
            $pageTitle,
            $formAction,
            [],
            $formData,
            $dataErrors,
            $fieldLabels
        );
        echo $code;
    }

    private function renderResultsPage(
        array $resultMessages
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($resultMessages, "{$debugHeading} -- resultMessages");
        // Render the results page
        $pageTitle = "Schedule File Upload Results";
        echo $this->view->renderPageWithResults($pageTitle, $resultMessages);
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
        // Render the display page
        echo $this->view->renderErrorPage($errorTitle, $errorMessage);
    }
}
