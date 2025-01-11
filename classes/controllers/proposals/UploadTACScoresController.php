<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\CustomDebug                             as Debug;
use App\domains\tac\TACManager                              as Manager;
use App\views\forms\proposals\UploadTACScoresView           as View;
use App\validators\forms\proposals\UploadTACScoresValidator as Validator;

/**
 * Controller for handling the Update TAC Scores logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadTACScoresController
{
    private $formatHtml;
    private $debug;
    private $manager;
    private $view;
    private $valid;

    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Manager $manager = null,
        ?View $view = null,
        ?Validator $valid = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Initialise dependencies with fallbacks. Model is not needed.
        $this->view = $view ?? new View($formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->log("{$debugHeading} -- View, Validator classes are successfully initialised.");

        // Initialise the additional class(es) needed by this controller
        $this->manager = $manager ?? new Manager($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Additional class successfully initialised.");

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
        $this->debug->debugVariable($fileData, "{$debugHeading} -- _FILES");

        // Merge the file data into the form data array
        $formData['filess'] = $fileData['tacss'];
        $formData['filenss'] = $fileData['tacnss'];
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

        // Process and then render the results page
        try {
            // Pass the file and form data off to the manager for processing
            $results = $this->manager->handleRequest($validData, 'results');
            // Render the form with the results
            $this->renderResultsPage($results);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage('Error processing TAC upload: ', $e->getMessage());
        }
    }

    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);

        // Logic to generate the first page form
        $pageTitle = "IRTF Upload TAC Scores Form";
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

    private function renderFormWithErrors(
        array $formData,
        array $dataErrors
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormWithErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($dataErrors, "{$debugHeading} -- dataErrors");
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");

        // Logic to generate the first page form
        $pageTitle = "IRTF Upload TAC Scores Form";
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
        $pageTitle = "IRTF Upload TAC Scores Files";

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
