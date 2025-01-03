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
 * This controller manages the logic for uploading schedule files, including:
 * - Validating uploaded data.
 * - Processing schedule files via the `ScheduleManager`.
 * - Rendering appropriate views based on user input or errors.
 *
 * It interacts with:
 * - `UploadScheduleFileValidator` for validating input and uploaded files.
 * - `UploadScheduleFileView` for rendering form and result pages.
 * - `ScheduleManager` for processing the uploaded schedule files.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property bool        $formatHtml Determines whether HTML output is formatted.
 * @property Debug       $debug      Debugging utility for logging and error tracing.
 * @property View        $view       View instance for rendering HTML pages.
 * @property Validator   $valid      Validator instance for validating form data and files.
 * @property Manager     $schedule   Manager instance for handling schedule operations.
 */

class UploadScheduleFileController
{
    private $formatHtml;
    private $debug;
    private $view;
    private $valid;
    private $schedule;

    /**
     * Constructs the controller, initializing dependencies and utilities.
     *
     * @param bool|null      $formatHtml Enable or disable HTML formatting (default: false).
     * @param Debug|null     $debug      Debugging utility for logging (default: new Debug instance).
     * @param View|null      $view       View instance for rendering forms and pages (default: new View).
     * @param Validator|null $valid      Validator instance for form validation (default: new Validator).
     * @param Manager|null   $schedule   Manager instance for processing schedules (default: new Manager).
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?View $view = null,
        ?Validator $valid = null,
        ?Manager $schedule = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Initialise the view and validator. Model is not needed.
        $this->view = $view ?? new View($formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->log("{$debugHeading} -- View, Validator classes are successfully initialised.");

        // Initialise the additional class(es) needed by this controller
        $this->schedule = $schedule ?? new Manager($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Additional class successfully initialised.");
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
     * Handles form submissions, including validation and processing.
     *
     * @param array $formData The submitted form data.
     * @param array $fileData The uploaded file data.
     *
     * @return void
     * @throws ValidationException If validation errors occur.
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
     * Processes validated form data by delegating to the ScheduleManager.
     *
     * @param array $validData Validated form and file data.
     *
     * @return void
     * @throws Exception If processing fails.
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
     * Collects the form information and calls the view to render the initial form page.
     *
     * @return void
     */
    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);

        // Prepare to render the initial form
        $pageTitle = "Upload Schedule File";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];
        $formData = [];

        // Call the view to render the initial form
        echo $this->view->renderFormPage(
            $pageTitle,
            $formAction,
            $dbData,
            $formData
        );
    }

    /**
     * Renders the form page with validation errors.
     *
     * @param array $formData   The submitted form data.
     * @param array $dataErrors Validation error messages.
     *
     * @return void
     */
    private function renderFormWithErrors(
        array $formData,
        array $dataErrors
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");

        // Prepare to render the form with errors
        $pageTitle = "Upload Schedule File";
        $formAction = $_SERVER['PHP_SELF'];
        $fieldLabels = $this->view->getFieldLabels();

        // Call the view to render the errors section and the form
        echo $this->view->renderFormWithErrors(
            $pageTitle,
            $formAction,
            [],
            $formData,
            $dataErrors,
            $fieldLabels
        );
    }

    /**
     * Renders the results page after processing the form.
     *
     * @param array $resultMessages Messages or data to display on the results page.
     *
     * @return void
     */
    private function renderResultsPage(
        array $resultMessages
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($resultMessages, "{$debugHeading} -- resultMessages");

        // Call the view to render the results page
        echo $this->view->renderPageWithResults(
            'Schedule File Upload Results',
            $resultMessages
        );
    }

    /**
     * Renders an error page with a provided error message.
     *
     * @param string $errorTitle   The title of the error page.
     * @param string $errorMessage The error message to display.
     *
     * @return void
     */
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
        echo $this->view->renderErrorPage(
            $errorTitle,
            $errorMessage
        );
    }
}
