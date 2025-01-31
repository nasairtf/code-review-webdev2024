<?php

declare(strict_types=1);

namespace App\controllers\addguest;

use Exception;
use App\exceptions\ExecutionException;
use App\exceptions\ValidationException;
use App\core\common\CustomDebug                       as Debug;
use App\models\addguest\GuestAcctsModel               as Model;
use App\views\forms\addguest\GuestAcctsView           as View;
use App\validators\forms\addguest\GuestAcctsValidator as Validator;

/**
 * Controller for handling the guest account logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class GuestAcctsController
{
    private $debug;
    private $view;
    private $valid;
    private $command;

    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
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

    public function handleRequest(string $command): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($command, "{$debugHeading} -- command");
        $this->debug->debugVariable($_POST, "{$debugHeading} -- _POST");
        //$this->debug->debugVariable($_GET, "{$debugHeading} -- _GET");

        // Verify the command provided in entry point
        try {
            $this->command = $this->validateCommand($command);
        } catch (Exception $e) {
            // If verification fails, render error page
            $this->renderErrorPage('Invalid Command Error: ', $e->getMessage());
            return;
        }

        // Valid command provided, handle requested command form
        if (isset($_POST['submit'])) {
            // Handle form submit
            $this->handleFormSubmit($_POST);
        } else {
            // Display the form page if no form is submitted
            $this->renderFormPage();
        }
    }

    private function handleFormSubmit(array $formData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");

        // Initialize with default structure
        $cleanData = $this->model->initializeDefaultData([$this->command]);
        $this->debug->debugVariable($cleanData, "{$debugHeading} -- cleanData");

        // Merge the form data into the cleanData array
        $mergedData = $this->model->mergeNewDataWithDefaults($cleanData, $formData);
        $this->debug->debugVariable($mergedData, "{$debugHeading} -- mergedData");

        try {
            // Validate the form data
            $validData = $this->valid->validateFormData($mergedData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

            // If validation passes, proceed to processing the feedback data
            $this->debug->debug("{$debugHeading} -- Validation checks completed.");
            $this->processFormSubmit($validData);
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

    private function processFormSubmit(array $validData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Process and then render the results page
        try {
            // Process queuing the command
            $results = $this->model->queueCommand($validData);
            // Render the form with the results
            $title = "Run {$this->command} script";
            $this->renderResultsPage($title, $results);
        } catch (ExecutionException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Command queue error");

            // Handle generating the errors page command queue fails
            $this->renderPageWithErrors(
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If processing fails, prepare the error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);

        // Logic to generate the first page form
        $title = "Run {$this->command} script";
        $formAction = $_SERVER['PHP_SELF'];
        $dbData = [];

        // data for the form
        $formData = $this->model->initializeDefaultData([$this->command]);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        // Call the view to render the initial form
        echo $this->view->renderFormPage(
            $title,
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
        $title = "Run {$this->command} script";
        $formAction = $_SERVER['PHP_SELF'];
        $fieldLabels = $this->view->getFieldLabels();
        $dbData = [];

        // Render the errors section and the form
        echo $this->view->renderFormWithErrors(
            $title,
            $formAction,
            $dbData,
            $formData,
            $dataErrors,
            $fieldLabels
        );
    }

    private function renderPageWithErrors(
        array $errors
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderPageWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($errors, "{$debugHeading} -- errors");

        // Logic to generate the first page form
        $title = "Run {$this->command} script";

        // Render the errors section and the form
        echo $this->view->renderPageWithFormattedResults(
            $title,
            $errors
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

    private function renderErrorPage(
        string $title,
        string $errorMessage
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderErrorPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Call the view to render the error page
        echo $this->view->renderErrorPage(
            $title,
            $errorMessage
        );
    }

    private function validateCommand(string $command): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "validateCommand");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($command, "{$debugHeading} -- command");

        $commands = [
            'addguest',
            'clearguest',
            'createguest',
            'extendguest',
            'removeguest',
        ];

        if (in_array($command, $commands)) {
            return $command;
        } else {
            // throw exception
            $message = 'The command provided is not valid. Please check the paramter and try again.';
            $this->debug->fail($message);
        }
    }
}
