<?php

declare(strict_types=1);

namespace App\controllers\login;

use Exception;
use App\core\traits\LoginHelperTrait;
use App\core\common\Config;
use App\core\common\CustomDebug as Debug;

/**
 * Manages logout functionality for IRTF forms, handling validation, submission, and redirection.
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
 * - `CustomDebug`: Logs information for debugging purposes, especially useful during development.
 *
 * @category Controllers
 * @package  IRTF
 * @version  1.0.0
 *
 * @property Debug  $debug            Debugging utility for logging.
 * @property array  $allowedRedirects Configured allowed redirect URLs.
 * @property string $title            Title for the login form page.
 * @property string $formAction       URL for form submission.
 * @property string $instructions     Instructions displayed on the login form.
 * @property string $redirect         Redirect path after successful login.
 *
 * @see Debug
 */

class LogoutController
{
    use LoginHelperTrait;

    // internal login-form specific properties
    private $debug;

    public function __construct(
        bool $formatHtml = false,
        ?Debug $debug = null
    ) {
        // Start the session if it hasn't been started
        $this->sessionSetup();
        // internal login-form specific properties
        $this->debug = $debug ?? new Debug('login', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);
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
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);

        // Clear and destroy the session if it is active
        $this->sessionCleanup();
    }
}
