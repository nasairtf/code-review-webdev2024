<?php

declare(strict_types=1);

namespace App\controllers\feedback;

use Exception;
use App\exceptions\ValidationException;
use App\core\traits\LoginHelperTrait;
use App\core\common\Config;
use App\core\common\CustomDebug                     as Debug;
use App\services\email\feedback\FeedbackService     as Email;
use App\models\feedback\FeedbackModel               as Model;
use App\views\forms\feedback\FeedbackView           as View;
use App\validators\forms\feedback\FeedbackValidator as Validator;

/**
 * Controller for handling the Feedback form logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackController
{
    use LoginHelperTrait;

    private $formatHtml;
    private $debug;
    private $model;
    private $view;
    private $valid;
    private $email;
    private $redirect;

    /**
     * Constructs the Controller, initializing all required dependencies.
     *
     * @param bool|null      $formatHtml Enable or disable HTML formatting (default: false).
     * @param Debug|null     $debug      Debug instance for logging and debugging (default: new instance).
     * @param Model|null     $model      Model instance (default: new Model).
     * @param View|null      $view       View instance (default: new View).
     * @param Validator|null $valid      Validator instance (default: new Validator).
     * @param Email|null     $email      Email instance (default: new Email).
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null,
        ?Email $email = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Fetch the Feedback form config from Config
        //$redirectConfig = Config::get('login_config', 'formRedirects');
        $config = $this->fetchLoginConfig('formRedirects');
        $this->redirect = $config['feedback'] ?? '';
        $this->debug->debug("{$debugHeading} -- Config successfully fetched.");
        $this->debug->debugVariable($this->redirect, "{$debugHeading} -- this->redirect");

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->debug);
        $this->view = $view ?? new View($this->formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->debug("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Initialise the additional classes needed by this controller
        $this->email = $email ?? new Email($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Email class successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Controller initialisation complete.");
    }

    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);

        // Logic to check login status and redirect to login page if needed
        $this->checkLoginStatus($this->redirect);

        if (isset($_POST['submit'])) {
            // Handle feedback form
            $this->handleFormSubmit($_POST);
        } else {
            // Display the form page if no form is submitted
            $this->renderFormPage();
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     *
     * handleFormSubmit - validates form data and passes to processor method
     */

    private function handleFormSubmit(array $formData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");

        // Initialize with default structure
        $cleanData = $this->model->initializeDefaultFormData();
        $this->debug->debugVariable($cleanData, "{$debugHeading} -- cleanData");

        // Merge the form data into the cleanData array
        $mergedData = $this->mergeFormDataWithDefaults($cleanData, $formData);
        $this->debug->debugVariable($mergedData, "{$debugHeading} -- mergedData");

        try {
            // retrieve data lists
            $dbData = $this->model->fetchFormLists($cleanData['program']);
            $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");

            // Validate the form data
            $validData = $this->valid->validateFormData($mergedData, $dbData);
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
                $dbData
            );
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    /**
     * Processes form data, saves it, and sends a confirmation email.
     *
     * @param array $validData Validated data for processing.
     */
    private function processFormSubmit(array $validData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processFormSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        try {
            // Save the form data
            if ($this->model->saveFeedback($validData['db'])) {
                // Send confirmation email
                $this->sendConfirmationEmail($validData['email']);

                // clean up session
                $this->sessionCleanup();

                // output success page
                $message = 'Feedback submitted successfully! Please check your inbox for your emailed copy.';
                $this->renderResultPage($message);
            } else {
                // output failure page
                $errorTitle = 'Submission failed';
                $errorMsg = 'Unable to save your feedback. Please try again.';
                $this->renderErrorPage($errorTitle, $errorMsg);
            }
        } catch (Exception $e) {
            // Handle any errors during the data saving process
            $this->renderErrorPage('Error saving form data', $e->getMessage());
        }
    }

    private function sendConfirmationEmail(array $data): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "sendConfirmationEmail");
        $this->debug->debug($debugHeading);

        try {
            $this->email->prepareFeedbackEmail($data)->send();
            $this->debug->debug('Feedback email sent successfully.');
        } catch (Exception $e) {
            $this->debug->log("Error sending feedback email: {$e->getMessage()}", 'red');
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderFormPage   - renders the semester choosing form
     * renderResultPage - renders the successful result page
     * renderErrorPage  - renders the error page displayed for caught exceptions
     */

    private function renderFormPage(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderFormPage");
        $this->debug->debug($debugHeading);

        // Logic to generate the first page form
        $pageTitle = "IRTF Feedback Form";
        $formAction = $_SERVER['PHP_SELF'];

        // data for the form
        $formData = $this->model->initializeDefaultFormData();
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        // retrieve data lists
        $dbData = $this->model->fetchFormLists($formData['program']);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");

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
        $pageTitle = "IRTF Feedback Form";
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

    private function renderResultPage(string $resultMessage): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderResultPage");
        $this->debug->debug($debugHeading);

        // Render the results
        $pageTitle = "IRTF Feedback Form";
        $code = $this->view->renderResultsPage($pageTitle, $resultMessage);
        echo $code;
    }

    private function renderErrorPage(string $errorTitle, string $errorMessage): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderErrorPage");
        $this->debug->debug($debugHeading);

        // Render the errors
        echo $this->view->renderErrorPage($errorTitle, $errorMessage);
    }

    /**
     * Recursively merges user-submitted form data with default data.
     *
     * @param array $defaults The default data array with all form fields.
     * @param array $submitted The user-submitted data array (e.g., $_POST).
     * @return array The merged array with defaults filled where missing.
     */
    private function mergeFormDataWithDefaults(array $defaults, array $submitted): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "mergeFormDataWithDefaults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($defaults, "{$debugHeading} -- defaults");
        $this->debug->debugVariable($submitted, "{$debugHeading} -- submitted");

        $merged = $defaults;
        foreach ($submitted as $key => $value) {
            // If value is an array and exists in defaults as an array, recurse
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $merged[$key] = $this->mergeFormDataWithDefaults($defaults[$key], $value);
            } else {
                // Otherwise, use the submitted value, overriding defaults
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
}
