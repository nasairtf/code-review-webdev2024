<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                               as Debug;
use App\domains\schedule\ScheduleManager                        as Manager;
use App\models\proposals\ProcessObsRemindersModel               as Model;
use App\views\forms\proposals\ProcessObsRemindersView           as View;
use App\validators\forms\proposals\ProcessObsRemindersValidator as Validator;

/**
 * Controller for handling the Update TAC Comments logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ProcessObsRemindersController
{
    private $formatHtml;
    private $debug;
    private $model;
    private $view;
    private $valid;

    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null,
        ?Manager $manager = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->debug);
        $this->view = $view ?? new View($this->formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->debug("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Initialise the additional class(es) needed by this controller
        $this->manager = $manager ?? new Manager($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Additional class successfully initialised.");

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

        // Merge the file data into the form data array
        $initData = $this->model->initializeDefaultData();
        $this->debug->debugVariable($initData, "{$debugHeading} -- initData");

        try {
            // Validate the form data
            $validData = $this->valid->validateFormData($formData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");
            // If validation passes, proceed to processing the form data
            $validData = array_merge($initData, $validData);
            $this->debug->debug("{$debugHeading} -- Validation checks completed.");
            $this->processFormSubmit($validData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");
            // Render the form with errors and user input if validation fails
            $this->renderFormWithErrors(
                array_merge($initData, $formData),
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

        // Process and then render the results page
        try {
            // Pass the file and form data off to the manager for processing
            $results = $this->manager->handleRequest($validData, 'observing');
            // Render the form with the results
            $this->renderResultsPage($results);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage('Error processing observing reminders: ', $e->getMessage());
        }
    }

    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);

        // Logic to generate the first page form
        $pageTitle = "IRTF Observing Reminder Emails";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];

        // data for the form
        $formData = $this->model->initializeDefaultData();
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        // Call the view to render the initial form
        echo $this->view->renderFormPage(
            $pageTitle,
            $formAction,
            $dbData,
            $formData
        );
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

        // Logic to generate the first page form
        $pageTitle = "IRTF Observing Reminder Emails";
        $formAction = $_SERVER['PHP_SELF'];
        $fieldLabels = $this->view->getFieldLabels();
        $dbData = [];

        // Render the errors section and the form
        echo $this->view->renderFormWithErrors(
            $pageTitle,
            $formAction,
            $dbData,
            $formData,
            $dataErrors,
            $fieldLabels
        );
    }

    private function renderResultsPage(
        array $resultMessages
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($resultMessages, "{$debugHeading} -- resultMessages");

        // Prepare to render the edit results page
        $pageTitle = "IRTF Observing Reminder Emails";

        // Call the view to render the results page
        echo $this->view->renderPageWithResults(
            $pageTitle,
            $resultMessages
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
        echo $this->view->renderErrorPage(
            $errorTitle,
            $errorMessage
        );
    }
}
