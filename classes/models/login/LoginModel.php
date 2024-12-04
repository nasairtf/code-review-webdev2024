<?php

namespace App\models\login;

use App\core\common\Debug;

use App\services\database\troublelog\read\GuestAcctsService as DbRead;

/**
 * Model for handling login-related data operations.
 *
 * The `LoginModel` class interacts with the data layer to validate user credentials.
 * It communicates with the `GuestAcctsService` to access login information, primarily
 * focusing on program and session code validation.
 *
 * @category Models
 * @package  IRTF
 */

class LoginModel
{
    /**
     * @var Debug Debugging utility for logging and error handling.
     */
    private $debug;

    /**
     * @var GuestAcctsService Service instance for reading data related to guest accounts.
     */
    private $dbRead;

    /**
     * Initializes the `LoginModel` with debugging and data service components.
     *
     * @param Debug $debug Optional. Debugging utility instance for logging.
     *                     If not provided, a default instance is created.
     */
    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('login', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this model
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Service class successfully initialised.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Model initialisation complete.");
    }

    /**
     * Checks if the given program number and session code match a record in the database.
     *
     * This method queries the `GuestAcctsService` to validate if a given
     * program-session pair exists, ensuring that users can only log in with valid credentials.
     *
     * @param string $program Program number provided by the user.
     * @param string $session Session code provided by the user.
     *
     * @return bool True if credentials are valid, otherwise false.
     */
    public function checkCredentials(string $program, string $session): bool
    {
        // Debug output
        $this->debug->debug("Login Model: checkCredentials()");
        $this->debug->debugVariable($program, "program");
        $this->debug->debugVariable($session, "session");
        // Return the validation result
        $result = $this->dbRead->fetchProgramValidation($program, $session);
        $this->debug->debugVariable($result, "result");
        return ($result[0]['count'] > 0);
    }

    /**
     * Provides default data for the login form fields.
     *
     * @return array Default values for the login form fields.
     */
    public function initializeDefaultFormData(): array
    {
        // Debug output
        $this->debug->debug("Login Model: initializeDefaultFormData()");
        // Return the data
        return [
            // Program Information
            'program' => '',    // Program Number
            'session' => '',    // Session Code
            // Error Information
            'error' => '',      // Failed validation errors
        ];
    }
}
