<?php

declare(strict_types=1);

namespace App\controllers\login;

use Exception;
use App\exceptions\ValidationException;
use App\core\traits\LoginHelperTrait;
use App\core\common\Config;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug             as Debug;
use App\models\login\LoginModel               as Model;
use App\views\forms\login\LoginView           as View;
use App\validators\forms\login\LoginValidator as Validator;

/**
 * Manages login functionality for IRTF forms, handling validation, submission, and redirection.
 *
 * This controller handles requests to the login form, validates user credentials,
 * and manages redirection after successful authentication. It supports both standalone
 * and embeddable login forms, ensuring flexibility across various forms.
 *
 * Note: Embeddable login form removed from class as of 2024/12/31 due to some logic issues.
 *       Will be re-added at a future date.
 *
 * Key responsibilities include:
 * - Validating program and session data via `LoginValidator`.
 * - Checking credentials against the database with `LoginModel`.
 * - Handling allowed redirects for different IRTF application endpoints.
 * - Displaying or re-displaying the login form with error messages on failure.
 *
 * Dependencies:
 * - `LoginModel`: Provides methods to check login credentials against the database.
 * - `LoginView`: Manages HTML generation for the login form.
 * - `LoginValidator`: Handles validation of form input data.
 * - `CustomDebug`: Logs information for debugging purposes, especially useful during development.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug     $debug            Debugging utility for logging.
 * @property Model     $model            Model handling database interactions for login.
 * @property View      $view             View managing form rendering.
 * @property Validator $valid            Validator managing data validation.
 * @property array     $allowedRedirects Configured allowed redirect URLs.
 * @property string    $title            Title for the login form page.
 * @property string    $formAction       URL for form submission.
 * @property string    $instructions     Instructions displayed on the login form.
 * @property string    $redirect         Redirect path after successful login.
 *
 * @see LoginModel
 * @see LoginView
 * @see LoginValidator
 * @see Debug
 */

class LoginController
{
    use LoginHelperTrait;

    // internal login-form specific properties

    /** @var bool Whether to format HTML output. */
    private $formatHtml;

    /** @var Debug Debugging utility for logging. */
    private $debug;

    /** @var Model Model handling database interactions for login. */
    private $model;

    /** @var View View managing form rendering. */
    private $view;

    /** @var Validator Validator managing data validation. */
    private $valid;

    /** @var array Configured allowed redirect URLs. */
    private $allowedRedirects = [];

    // possible to pass in properties from calling form

    /** @var string Page title for the login form. */
    private $title;

    /** @var string URL for form submission. */
    private $formAction;

    /** @var string Instructions displayed on the login form. */
    private $instructions;

    /** @var string Redirect path after successful login. */
    private $redirect;

    /**
     * Initializes the `LoginController` with core dependencies, properties, and allowed redirects.
     *
     * This constructor sets up debugging, model, view, and validator instances,
     * as well as default values for form properties such as title, action URL, and instructions.
     * It also retrieves allowed redirects from a configuration file, providing
     * a safe list for post-login redirection.
     *
     * @param bool|null      $formatHtml Whether HTML output should be formatted (default: false).
     * @param Debug|null     $debug      Debug instance for logging and debugging (default: new instance).
     * @param Model|null     $model      Model instance (default: new Model).
     * @param View|null      $view       View instance (default: new View).
     * @param Validator|null $valid      Validator instance (default: new Validator).
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null
    ) {
        // Start the session for the login form
        $this->sessionSetup();

        // Debug output
        $this->debug = $debug ?? DebugFactory::create('login', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->debug);
        $this->view = $view ?? new View($formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->debug);
        $this->debug->debug("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Initialise form settable properties
        $this->title = 'IRTF Form Login';
        $this->formAction = $_SERVER['PHP_SELF'];
        $this->instructions = $this->view->buildDefaultInstructions();
        $this->redirect = 'default';

        // Fetch allowed redirects from Config
        $this->allowedRedirects = $this->fetchLoginConfig('allowedRedirects');

        // Output the SESSION and default class values
        $this->debug->debugVariable($_SESSION, "{$debugHeading} -- _SESSION");
        $this->debug->debugVariable($this->title, "{$debugHeading} -- this->title");
        $this->debug->debugVariable($this->formAction, "{$debugHeading} -- this->formAction");
        $this->debug->debugVariable($this->instructions, "{$debugHeading} -- this->instructions");
        $this->debug->debugVariable($this->redirect, "{$debugHeading} -- this->redirect");
        $this->debug->debugVariable($this->allowedRedirects, "{$debugHeading} -- this->allowedRedirects");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Controller initialisation complete.");
    }

    /**
     * Set a custom title for the login form.
     *
     * @param string $title Page title for the form.
     * @return self
     */
    public function setTitle(string $title): self
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "setTitle");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($title, "{$debugHeading} -- title");

        $this->title = $title;
        return $this;
    }

    /**
     * Set a custom form action URL for the login form.
     *
     * @param string $formAction Form submission URL.
     * @return self
     */
    public function setFormAction(string $formAction): self
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "setFormAction");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formAction, "{$debugHeading} -- formAction");

        $this->formAction = $formAction;
        return $this;
    }

    /**
     * Set custom instructions for the login form.
     *
     * @param string $instructions HTML instructions for the form.
     * @return self
     */
    public function setInstructions(string $instructions): self
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "setInstructions");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($instructions, "{$debugHeading} -- instructions");

        $this->instructions = $instructions;
        return $this;
    }

    /**
     * Sets the redirect path if it is a valid option.
     *
     * Validates and assigns a redirect key, used to control the post-login redirection.
     *
     * @param string $redirect The redirect key to set.
     * @return self
     */
    public function setRedirect(string $redirect): self
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "setRedirect");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($redirect, "{$debugHeading} -- redirect");

        if ($this->validateRedirect($redirect)) {
            $this->redirect = $redirect;
        }
        return $this;
    }

    /**
     * Retrieves the redirect key.
     *
     * @return string The redirect key or 'default' if none is set.
     */
    public function getRedirect(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "getRedirect");
        $this->debug->debug($debugHeading);

        return $this->redirect ?? 'default';
    }

    /**
     * Retrieves the full URL for the current redirect.
     *
     * @return string The redirect URL.
     */
    public function getRedirectURL(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "getRedirectURL");
        $this->debug->debug($debugHeading);

        return $this->allowedRedirects[$this->redirect] ?? $this->allowedRedirects['default'];
    }

    /**
     * Retrieves the login data from the session, if available.
     *
     * @return array|null The login data from the session, or null if not set.
     */
    public function getLoginData(): ?array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "getLoginData");
        $this->debug->debug($debugHeading);

        return $_SESSION['login_data'] ?? null;
    }

    /**
     * Handles the main login request flow.
     *
     * Determines the appropriate method to handle based on the HTTP method and request data.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);

        // Handle redirect parameter via GET if provided
        if (isset($_GET['redirect'])) {
            $this->setRedirect($_GET['redirect']);
        }
        // Output the SESSION, redirection values
        $this->debug->debugVariable($_SESSION, "{$debugHeading} -- _SESSION");
        $this->debug->debugVariable($this->allowedRedirects, "{$debugHeading} -- allowedRedirects");
        $this->debug->debugVariable($this->redirect, "{$debugHeading} -- redirect");

        // Check for existing session authentication
        if (isset($_SESSION['login_data']['session'])) {
            $this->redirectLoggedInUser($this->getRedirectURL());
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['login'], $_GET['p'], $_GET['s'])) {
            // Handle direct login via GET credentials/token
            $this->handleLoginLink($_GET);
            return;
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
            // Handle login form submission via POST
            $this->handleLoginSubmit($_POST);
            return;
        } else {
            // Display login form
            $this->renderLoginForm();
            return;
        }
    }

    /**
     * Handles login via GET parameters.
     *
     * @param array $formData The GET data containing login credentials.
     * @return void
     */
    private function handleLoginLink(array $formData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleLoginLink");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _GET");
        // Output the redirection values
        $this->debug->debug("Starting login link handling.");
        $this->debug->debugVariable($this->redirect, "{$debugHeading} -- redirect");
        $this->debug->debugVariable($this->allowedRedirects, "{$debugHeading} -- allowedRedirects");

        // Prepare form inputs
        $formData['program'] = $formData['p'] ?? '';
        $formData['session'] = $formData['s'] ?? '';

        try {
            // Validate form inputs, store session, and redirect user
            $this->handleDataValidation($formData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");

            // Handle generating the form with errors and user input if validation fails
            $this->renderFormWithErrors(
                $formData,
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If validation fails, prepare the error page
            $this->debug->log("Error in link login: " . $e->getMessage());
            $this->renderErrorPage('Error in link login: ', $e->getMessage());
        }
    }

    /**
     * Handles login submission via POST data.
     *
     * @param array $formData The POST data containing login credentials.
     * @return void
     */
    private function handleLoginSubmit(array $formData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleLoginSubmit");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- _POST");
        // Output the redirection values
        $this->debug->debug("Starting login submission handling.");
        $this->debug->debugVariable($this->redirect, "{$debugHeading} -- redirect");
        $this->debug->debugVariable($this->allowedRedirects, "{$debugHeading} -- allowedRedirects");

        try {
            // Validate form inputs, store session, and redirect user
            $this->handleDataValidation($formData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");

            // Handle generating the form with errors and user input if validation fails
            $this->renderFormWithErrors(
                $formData,
                $e->getMessages()
            );
        } catch (Exception $e) {
            // If validation fails, prepare the error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    /**
     * Validates login form data and stores session data if valid.
     *
     * @param array $formData The form data to validate.
     * @return void
     * @throws ValidationException If validation fails.
     */
    private function handleDataValidation(array $formData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleDataValidation");
        $this->debug->debug($debugHeading);

        // Validate form inputs
        $validData = $this->valid->validateData($formData);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Check credentials with model
        if (!$this->model->checkCredentials($validData['program'], $validData['session'])) {
            throw new ValidationException("Login failed.", ['program' => 'Invalid program number or session code.']);
        }

        // If login is successful, save login data in session
        $_SESSION['login_data'] = [
            'program' => $validData['program'],
            'session' => $validData['session'],
        ];
        $this->debug->debugVariable($_SESSION, "{$debugHeading} -- _SESSION");
        $this->debug->debug("getRedirectURL: " . $this->getRedirectURL());

        // Redirect to the validated URL
        $this->redirectLoggedInUser($this->getRedirectURL());
    }

    /**
     * Validates the redirect key against the allowed redirects list.
     *
     * @param string $redirect The redirect key to validate.
     * @return bool True if the redirect key is valid, false otherwise.
     */
    private function validateRedirect(string $redirect): bool
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "validateRedirect");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($redirect, "{$debugHeading} -- redirect");

        return array_key_exists($redirect, $this->allowedRedirects);
    }

    /**
     * Prepares the form action URL.
     *
     * @return string The validated form action URL.
     */
    private function validateFormAction(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "validateFormAction");
        $this->debug->debug($debugHeading);
        // Validate formAction
        $formAction = $this->formAction;
        $this->debug->debugVariable($formAction, "{$debugHeading} -- formAction");
        if ($this->redirect) {
            $formAction .= "?redirect=" . urlencode($this->getRedirect());
        }
        $this->debug->debugVariable($formAction, "{$debugHeading} -- formAction");
        return $formAction;
    }

    /**
     * Renders the login form with validation errors.
     *
     * @param array $formData   The form data provided by the user.
     * @param array $dataErrors Validation error messages.
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

        // Logic to generate the form with errors
        $formAction = $this->validateFormAction();
        $fieldLabels = $this->view->getFieldLabels();

        // Call the view to render the errors section and the form
        echo $this->view->renderFormWithErrors(
            $this->title, // title
            $formAction,  // action
            [],           // dbData
            $formData,    // formData
            $dataErrors,  // validation errors
            $fieldLabels  // field labels
        );
    }

    /**
     * Renders the login form page with optional pre-filled data.
     *
     * If no form data is provided, default values are used.
     *
     * @param array $formData [optional] Data to pre-fill in the form fields.
     *
     * @return void Outputs the HTML for the login form page.
     */
    private function renderLoginForm(array $formData = []): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderLoginForm");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        // Logic to generate the first page form
        $formAction = $this->validateFormAction();
        $formData = $formData ?: $this->model->initializeDefaultData();
        $formData['instructions'] = $this->instructions;

        // Call the view to render the form
        echo $this->view->renderFormPage(
            $this->title, // title
            $formAction,  // action
            [],           // dbData
            $formData,    // formData
            true,         // methodPost
            false,        // targetBlank
            0             // pad
        );
    }
}
