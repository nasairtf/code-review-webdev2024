<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ExecutionException;
use App\exceptions\ValidationException;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                            as Debug;
use App\models\proposals\QueueDataRestoreModel               as Model;
use App\views\forms\proposals\QueueDataRestoreView           as View;
use App\validators\forms\proposals\QueueDataRestoreValidator as Validator;

/**
 * Controller for handling the Queue Observer Data Restoration logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class QueueDataRestoreController
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
        $this->view = $view ?? new View($this->formatHtml, $this->debug);
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
        $this->debug->debugVariable($_POST, "{$debugHeading} -- _POST");

        if (isset($_POST['submit'])) {
            // Handle form submit
            $this->handleQueueDataRestoreSubmit($_POST);
        } else {
            // Display the form page if no form is submitted
            $this->renderQueueDataRestoreFormPage();
        }
    }

    private function handleQueueDataRestoreSubmit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleQueueDataRestoreSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");

        // Initialize with default structure
        $cleanData = $this->model->initializeDefaultData();
        $this->debug->debugVariable($cleanData, "{$debugHeading} -- cleanData");

        // Merge the form data into the cleanData array
        $mergedData = $this->model->mergeNewDataWithDefaults($cleanData, $formData);
        $this->debug->debugVariable($mergedData, "{$debugHeading} -- mergedData");

        try {
            // retrieve session codes if needed
            if ($mergedData['codesrc'] === $cleanData['codesrc'] ||
                $mergedData['codedst'] === $cleanData['codedst']) {
                $dbData = $this->model->fetchSessionCodes($mergedData['usersrc'], $mergedData['userdst']);
                $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");

                $mergedData['codesrc'] = $dbData['codesrc'];
                $mergedData['codedst'] = $dbData['codedst'];
                $this->debug->debugVariable($mergedData, "{$debugHeading} -- mergedData");
            }

            // Validate the form data
            $validData = $this->valid->validateData($mergedData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

            // If validation passes, proceed to processing the data
            $this->debug->debug("{$debugHeading} -- Validation checks completed.");
            $this->processQueueDataRestoreSubmit($validData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");

            // Render the form with errors and user input if validation fails
            $this->renderFormWithErrors(
                $mergedData,
                $e->getMessages(),
                []
            );
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    private function processQueueDataRestoreSubmit(
        array $validData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processQueueDataRestoreSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Verify the program accounts exist
        try {
            // Verify the source and destination program accounts exist
            $this->model->verifyPrograms($validData);
        } catch (ExecutionException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Program verification error");
            // Handle generating the errors page command queue fails
            $this->renderPageWithErrors(
                "Program verification error",
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If processing fails, prepare the error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }

        // Process and then render the results page
        try {
            // Process queuing the command
            $results = $this->model->queueCommand($validData);
            // Render the form with the results
            $title = "Run run_restore_on_webserver script";
            $this->renderResultsPage($title, $results);
        } catch (ExecutionException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Command queue error");
            // Handle generating the errors page command queue fails
            $this->renderPageWithErrors(
                "Command queue error",
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If processing fails, prepare the error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    private function renderQueueDataRestoreFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderQueueDataRestoreFormPage");
        $this->debug->debug($debugHeading);

        // Prepare to render the initial form
        $pageTitle = "Queue Data Restoration Form";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];

        // data for the form
        $formData = $this->model->initializeDefaultData();
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        // Call the view to render the initial form
        echo $this->view->renderFormPage(
            $pageTitle,  // title
            $formAction, // action
            $dbData,     // dbData
            $formData,   // formData
            true,        // methodPost
            false,       // targetBlank
            0            // pad
        );
    }

    private function renderResultsPage(
        string $title,
        array $resultMessages
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($resultMessages, "{$debugHeading} -- resultMessages");

        // Call the view to render the results page
        echo $this->view->renderPageWithResults(
            $title,
            $resultMessages
        );
    }

    private function renderFormWithErrors(
        array $formData,
        array $dataErrors,
        array $dbData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");

        // Logic to generate the first page form
        $pageTitle = "Queue Data Restoration Form";
        $formAction = $_SERVER['PHP_SELF'];
        $fieldLabels = $this->view->getFieldLabels();

        // Render the errors section and the form
        $code = $this->view->renderFormWithErrors(
            $pageTitle,
            $formAction,
            $dbData,
            $formData,
            $dataErrors,
            $fieldLabels
        );
        echo $code;
    }

    private function renderPageWithErrors(
        string $errorTitle,
        array $errors
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderPageWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($errorTitle, "{$debugHeading} -- errorTitle");
        $this->debug->debugVariable($errors, "{$debugHeading} -- errors");

        // Render the errors section and the form
        echo $this->view->renderPageWithFormattedResults(
            $errorTitle,
            $errors
        );
        exit;
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
        exit;
    }
}
