<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                                     as Debug;
use App\models\proposals\ObsDataRestorationRequestModel               as Model;
use App\views\forms\proposals\ObsDataRestorationRequestView           as View;
use App\validators\forms\proposals\ObsDataRestorationRequestValidator as Validator;

/**
 * Controller for handling the Observer Data Restoration Request logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsDataRestorationRequestController
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
            // Handle first form submit
            $this->handleObsDataRequestSubmit($_POST);
        } else {
            // Display the first page if no form is submitted
            $this->renderObsDataRequestFormPage();
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     *
     * handleObsDataRequestSubmit - validates form data and passes to semester processor method
     */

    private function handleObsDataRequestSubmit(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleObsDataRequestSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");

        // Initialize with default structure
        $cleanData = $this->model->initializeDefaultData();
        $this->debug->debugVariable($cleanData, "{$debugHeading} -- cleanData");

        // Merge the form data into the cleanData array
        $mergedData = $this->model->mergeNewDataWithDefaults($cleanData, $formData);
        $this->debug->debugVariable($mergedData, "{$debugHeading} -- mergedData");

        try {
            // Validate the form data
            $validData = $this->valid->validateFormData($mergedData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

            // If validation passes, proceed to processing the request data
            $this->debug->debug("{$debugHeading} -- Validation checks completed.");
            $this->processObsDataRequestSubmit($validData);
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

    /**
     * Data processing methods that set up interface with the DB Class
     *
     * processObsDataRequestSubmit - delivers the selected semester's data to renderer
     */

    private function processObsDataRequestSubmit(
        array $validData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processObsDataRequestSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Process and then render the results page
        try {
            // Process queuing the command
            $results = $this->view->replaceThisMethodWithCorrectCode($validData);

            // Prepare to render the results page
            $title = "Data Restore Request: "
                . ' source ' . $validData['srcprogram']
                . ', requestor ' . $validData['reqname'];
            // Render the form with the results
            $this->renderResultsPage($title, $results);
        } catch (Exception $e) {
            // If processing fails, prepare the error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderSemesterSelectFormPage - renders the semester choosing form
     * renderSemesterListingPage    - renders the semester listing page
     * renderErrorPage              - renders the error page displayed for caught exceptions
     */

    private function renderObsDataRequestFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderObsDataRequestFormPage");
        $this->debug->debug($debugHeading);

        // Prepare to render the initial form
        $pageTitle = "Data Restore Request Form";
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
        string $resultTitle,
        string $resultMessage
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultsPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($resultTitle, "{$debugHeading} -- resultTitle");
        $this->debug->debugVariable($resultMessage, "{$debugHeading} -- resultMessage");

        // Call the view to render the results page
        echo $this->view->renderResultsPage(
            $resultTitle,
            $resultMessage
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
        $pageTitle = "Data Restore Request Form";
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
