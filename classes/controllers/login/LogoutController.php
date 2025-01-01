<?php

declare(strict_types=1);

namespace App\controllers\login;

use Exception;
use App\core\traits\LoginHelperTrait;
use App\core\common\Config;
use App\core\common\CustomDebug as Debug;

/**
 * Manages logout functionality for IRTF forms, handling session cleanup and redirection.
 *
 * This controller is responsible for terminating user sessions securely and optionally
 * redirecting users to a designated page after logout. It supports debugging for session
 * management activities and leverages reusable logic from `LoginHelperTrait`.
 *
 * Key responsibilities include:
 * - Clearing and destroying active sessions.
 * - Logging session cleanup events for debugging.
 * - Providing flexibility for logout workflows across multiple IRTF applications.
 *
 * Dependencies:
 * - `CustomDebug`: Logs information for debugging purposes, especially useful during development.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class LogoutController
{
    use LoginHelperTrait;

    /** @var Debug Debugging utility for logging. */
    private $debug;

    /**
     * Initializes the `LogoutController` with core dependencies and prepares for session cleanup.
     *
     * @param bool      $formatHtml Whether to enable HTML formatting in debug output (default: false).
     * @param Debug|null $debug     An optional debug instance for logging. If not provided, a new instance is created.
     */
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
     * Handles the logout request by performing session cleanup.
     *
     * This method clears all session data and destroys the session to ensure the user is fully logged out.
     * It leverages the session cleanup logic from `LoginHelperTrait` and logs the process for debugging.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);

        // Clear and destroy the session if it is active
        $this->sessionCleanup();

        echo "Session has been cleared and user is now logged out.";
    }
}
