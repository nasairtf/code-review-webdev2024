<?php

declare(strict_types=1);

namespace App\controllers\login;

use Exception;
use App\core\common\Config;
use App\core\common\Debug;
use App\models\login\LoginModel as Model;
use App\views\forms\login\LoginView as View;
use App\validators\forms\login\LoginValidator as Validator;

/**
 * Manages login functionality for IRTF forms, handling validation, submission, and redirection.
 *
 * This controller handles requests to the login form, validates user credentials,
 * and manages redirection after successful authentication. It supports both standalone
 * and embeddable login forms, ensuring flexibility across various forms.
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
 * - `Debug`: Logs information for debugging purposes, especially useful during development.
 *
 * @category Controllers
 * @package  IRTF
 * @version  1.0.0
 *
 * @property Debug         $debug           Debugging utility for logging.
 * @property LoginModel    $model           Model handling database interactions for login.
 * @property LoginView     $view            View managing form rendering.
 * @property LoginValidator $valid          Validator managing data validation.
 * @property array         $allowedRedirects Configured allowed redirect URLs.
 * @property string        $title           Title for the login form page.
 * @property string        $formAction      URL for form submission.
 * @property string        $instructions    Instructions displayed on the login form.
 * @property string        $redirect        Redirect path after successful login.
 *
 * @see LoginModel
 * @see LoginView
 * @see LoginValidator
 * @see Debug
 */

class LoginController
{
    // internal login-form specific properties
    private $debug;
    private $model;
    private $view;
    private $valid;
    private $allowedRedirects = [];
    // possible to pass in properties from calling form
    private $title;
    private $formAction;
    private $instructions;
    private $redirect;

    /**
     * Initializes the `LoginController` with core dependencies, properties, and allowed redirects.
     *
     * This constructor sets up debugging, model, view, and validator instances,
     * as well as default values for form properties such as title, action URL, and instructions.
     * It also retrieves allowed redirects from a configuration file, providing
     * a safe list for post-login redirection.
     *
     * @param bool  $formatHtml Optional. Whether HTML output should be formatted.
     *                          Defaults to false for inline HTML.
     * @param Debug $debug      Optional. Debugging utility for logging purposes. If not provided,
     *                          a default Debug instance is created.
     */
    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null
    ) {
        // Start the session for the login form
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // internal login-form specific properties
        $this->debug = $debug ?? new Debug('login', false, 0);
        $this->model = new Model($this->debug);
        $this->view = new View($formatHtml, $this->debug);
        $this->valid = new Validator($this->debug);
        $this->debug->log("Login Controller: Controller, Model, View, Validator constructed.");
        // calling form settable properties
        $this->title = 'IRTF Form Login';
        $this->formAction = $_SERVER['PHP_SELF'];
        $this->instructions = $this->view->buildDefaultInstructions();
        $this->redirect = 'default';
        // Load the config file for Login form
        $config = require CONFIG_PATH . 'login_config.php';
        $this->allowedRedirects = $config['allowedRedirects'] ?? [];
        // Output the SESSION values
        $this->debug->debugVariable($_SESSION, "_SESSION");
        // Output the redirection values
        $this->debug->debugVariable($config['allowedRedirects'], "config['allowedRedirects']");
        $this->debug->debugVariable($this->redirect, "this->redirect");
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
        $this->debug->debug("Login Controller: setTitle()");

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
        $this->debug->debug("Login Controller: setFormAction()");

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
        $this->debug->debug("Login Controller: setInstructions()");

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
        $this->debug->debug("Login Controller: setRedirect()");

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
        $this->debug->debug("Login Controller: getRedirect()");

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
        $this->debug->debug("Login Controller: getRedirectURL()");

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
        $this->debug->debug("Login Controller: getLoginData()");

        return $_SESSION['login_data'] ?? null;
    }

    /**
     * Generates an embeddable login form.
     *
     * Prepares an embeddable login form with optional customizations for
     * action URL, form data, and instructions.
     *
     * @param string $action       [optional] The URL where the form should be submitted.
     *                              Defaults to the current script.
     * @param array  $data         [optional] Initial data for form fields. Defaults to empty fields.
     * @param string $instructions [optional] Custom instructions displayed above the form. Empty by default.
     *
     * @return string The HTML output for the embeddable login form.
     */
    public function buildEmbeddableLoginForm(string $action = '', array $data = [], string $instructions = ''): string
    {
        // Debug output
        $this->debug->debug("Login Controller: buildEmbeddableLoginForm()");

        if (empty($action)) {
            $action = $_SERVER['PHP_SELF'];
        }
        if (empty($data)) {
            $data = $this->model->initializeDefaultFormData();
        }
        return $this->view->buildEmbeddableLoginForm($action, $data, $instructions);
    }

    /**
     * Manages login requests, including form submission handling and rendering.
     *
     * Checks for a 'redirect' parameter via GET to set up post-login redirection, and
     * determines if the request is a login attempt or form render request.
     */
    public function handleRequest(): void
    {
        // Debug output
        $this->debug->debug("Login Controller: handleRequest()");

        // Handle redirect parameter via GET if provided
        if (isset($_GET['redirect'])) {
            $this->setRedirect($_GET['redirect']);
        }
        // Output the SESSION values
        $this->debug->debugVariable($_SESSION, "_SESSION");
        // Output the redirection values
        $this->debug->debugVariable($this->allowedRedirects, "allowedRedirects");
        $this->debug->debugVariable($this->redirect, "redirect");

        // Check for existing session authentication
        if (isset($_SESSION['login_data']['session'])) {
            $this->redirectUser();
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

    private function handleLoginLink(array $formData): void
    {
        // Debug output
        $this->debug->debug("Login Controller: handleLoginLink()");
        $this->debug->debugVariable($formData, "_GET");
        // Output the redirection values
        $this->debug->debug("Starting login link handling.");
        $this->debug->debugVariable($this->redirect, "redirect");
        $this->debug->debugVariable($this->allowedRedirects, "allowedRedirects");

        try {
            // Validate form inputs
            $program = $this->valid->validateProgram($formData['p'] ?? '');
            $session = $this->valid->validateSession($formData['s'] ?? '');

            // Check credentials with model
            if (!$this->model->checkCredentials($program, $session)) {
                $this->debug->fail("Login failed: Invalid program number or session code.");
            }

            // If login is successful, save login data in session
            $_SESSION['login_data'] = compact('program', 'session');
            $this->debug->debugVariable($_SESSION, "_SESSION");
            $this->debug->debug("getRedirectURL: " . $this->getRedirectURL());

            // Redirect to the validated URL
            $this->redirectUser();
        } catch (Exception $e) {
            // Show error page or log the error as appropriate
            $this->debug->log("Error in link login: " . $e->getMessage());
            $formData['error'] = $e->getMessage();
            $this->renderLoginForm($formData);
        }
    }

    /**
     * Validates credentials and manages login submission.
     *
     * Validates form data, verifies credentials, and stores login data in the session if valid.
     * Redirects to a predefined URL on success or rerenders the form with an error on failure.
     *
     * @param array $formData The form data to validate and authenticate.
     */
    private function handleLoginSubmit(array $formData): void
    {
        // Debug output
        $this->debug->debug("Login Controller: handleLoginSubmit()");
        $this->debug->debugVariable($formData, "_POST");
        // Output the redirection values
        $this->debug->debug("Starting login submission handling.");
        $this->debug->debugVariable($this->redirect, "redirect");
        $this->debug->debugVariable($this->allowedRedirects, "allowedRedirects");

        try {
            // Validate form inputs
            $program = $this->valid->validateProgram($formData['program'] ?? '');
            $session = $this->valid->validateSession($formData['session'] ?? '');

            // Check credentials with model
            if (!$this->model->checkCredentials($program, $session)) {
                $this->debug->fail("Login failed: Invalid program number or session code.");
            }

            // If login is successful, save login data in session
            $_SESSION['login_data'] = compact('program', 'session');
            $this->debug->debugVariable($_SESSION, "_SESSION");
            $this->debug->debug("getRedirectURL: " . $this->getRedirectURL());

            // Redirect to the validated URL
            $this->redirectUser();
        } catch (Exception $e) {
            // Show error page or log the error as appropriate
            $this->debug->log("Error in login submission: " . $e->getMessage());
            $formData['error'] = $e->getMessage();
            $this->renderLoginForm($formData);
        }
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
        $this->debug->debug("Login Controller: renderLoginForm()");
        $this->debug->debugVariable($formData, "formData");

        $formAction = $this->formAction;
        if ($this->redirect) {
            $formAction .= "?redirect=" . urlencode($this->getRedirect());
        }
        if (empty($formData)) {
            $formData = $this->model->initializeDefaultFormData();
        }
        echo $this->view->renderLoginFormPage(
            $this->title,
            $formAction,
            $formData,
            $this->instructions
        );
    }

    /**
     * Validates the redirect key against the allowed redirects list.
     *
     * Checks if the given redirect key exists in the `$allowedRedirects` configuration array.
     *
     * @param string $redirect The redirect key to validate.
     *
     * @return bool True if the redirect key is valid, false otherwise.
     */
    private function validateRedirect(string $redirect): bool
    {
        // Debug output
        $this->debug->debug("Login Controller: validateRedirect()");

        return array_key_exists($redirect, $this->allowedRedirects);
    }

    /**
     * Redirects the user to the designated redirect path if set and valid.
     */
    private function redirectUser(): void
    {
        // Debug output
        $this->debug->debug("Login Controller: redirectUser()");
        // Handle the redirection
        $url = $this->getRedirectURL();
        if ($this->debug->isDebugMode()) {
            // Open redirect url in a new tab/window
            echo "<script>window.open('" . htmlspecialchars($url) . "', '_blank');</script>";
        } else {
            if ($url) {
                // Redirect to the validated URL
                header("Location: " . $url);
                exit;
            } else {
                // Redirect to the default destination
                header("Location: /");
            }
        }
        exit;
    }
}
