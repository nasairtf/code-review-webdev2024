<?php

declare(strict_types=1);

namespace App\models\login;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                           as Debug;
use App\services\database\troublelog\read\GuestAcctsService as DbRead;

/**
 * Model for handling login-related data operations.
 *
 * The `LoginModel` class serves as the interface between the login business logic and the database.
 * It utilizes the `GuestAcctsService` to validate user credentials, ensuring a secure and seamless
 * login process. This class also provides default data structures for the login form fields.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug   $debug  Debugging utility for logging and error handling.
 * @property DbRead  $dbRead Database read service for accessing guest account data.
 *
 * @see Debug
 * @see DbRead
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
     * The constructor ensures that debugging and database service dependencies are properly initialized.
     * If not explicitly provided, it defaults to creating new instances with minimal configuration.
     *
     * @param Debug|null $debug Optional debugging utility instance for logging.
     *                          If not provided, a default instance is created.
     * @param DbRead|null $dbRead Optional database read service for guest accounts.
     *                            If not provided, a default instance is created.
     */
    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('login', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this model
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Service class successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    /**
     * Checks if the provided program number and session code are valid.
     *
     * This method validates the provided credentials by querying the `GuestAcctsService`.
     * It ensures that the credentials exist in the database and are allowed for login.
     *
     * @param string $program Program number submitted by the user.
     * @param string $session Session code submitted by the user.
     *
     * @return bool True if the credentials are valid; false otherwise.
     */
    public function checkCredentials(string $program, string $session): bool
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "checkCredentials");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "{$debugHeading} -- program");
        $this->debug->debugVariable($session, "{$debugHeading} -- session");

        // Return the validation result
        $result = $this->dbRead->fetchProgramValidation($program, $session);
        $this->debug->debugVariable($result, "result");

        // Return true if at least one valid match is found
        return ($result[0]['count'] > 0);
    }

    /**
     * Initializes default values for login form fields.
     *
     * This method provides default values for the login form fields, ensuring a consistent structure
     * for the form input. These defaults are used when rendering a blank or reset form.
     *
     * @return array Default values for the login form fields.
     */
    public function initializeDefaultFormData(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "initializeDefaultFormData");
        $this->debug->debug($debugHeading);

        // Return default field values
        return [
            'program' => '', // Default empty program number
            'session' => '', // Default empty session code
            'error'   => '', // Placeholder for validation error messages
        ];
    }
}
