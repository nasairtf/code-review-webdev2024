<?php

namespace App\validators\forms\login;

use App\core\common\Debug;

/**
 * Validator for login form input.
 *
 * This class handles validation for login-related fields, including
 * program number and session code, using specific format requirements.
 * Any failed validation results in an exception and a debug log.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class LoginValidator
{
    /**
     * @var Debug Debugging utility instance.
     */
    private $debug;

    /**
     * Constructor to initialize the LoginValidator.
     *
     * If no debug instance is provided, it defaults to a basic debug setup.
     *
     * @param Debug|null $debug Optional debugging utility instance.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        $this->debug = $debug ?? new Debug('login', false, 0);
    }

    /**
     * Validates the program number format.
     *
     * The program number must follow the YYYY[A|B]NNN format, where:
     * - YYYY is a year between 2000 and the next calendar year.
     * - A or B represents the semester.
     * - NNN is a zero-padded number from 001 to 999.
     *
     * @param string $program Program number to validate.
     *
     * @return string The valid program number.
     *
     * @throws Exception If validation fails.
     */
    public function validateProgram(string $program): string
    {
        // Debug output
        $this->debug->debug("Login Validator: validateProgram()");

        $pattern = '/^\d{4}[AB]\d{3}$/'; // Matches YYYY[A|B]NNN format

        if (!preg_match($pattern, $program)) {
            $this->debug->fail("Invalid program format: '{$program}'. Expected format: YYYY[A|B]NNN.");
        }

        $year = intval(substr($program, 0, 4));
        $semester = $program[4];
        $number = intval(substr($program, 5, 3));

        if ($year < 2000 || $year > intval(date('Y')) + 1) {
            $this->debug->fail("Invalid year in program number '{$program}'. Year must be between 2000 and next calendar year.");
        }

        if (!in_array($semester, ['A', 'B'])) {
            $this->debug->fail("Invalid semester in program number '{$program}'. Semester must be 'A' or 'B'.");
        }

        if ($number < 1 || $number > 999) {
            $this->debug->fail("Invalid program number in '{$program}'. Number must be between 001 and 999.");
        }

        return $program;
    }

    /**
     * Validates the session code format.
     *
     * The session code must be exactly 10 characters in length and
     * contain only alphanumeric characters.
     *
     * @param string $session Session code to validate.
     *
     * @return string The valid session code.
     *
     * @throws Exception If validation fails.
     */
    public function validateSession(string $session): string
    {
        // Debug output
        $this->debug->debug("Login Validator: validateSession()");

        // Engineering/project accounts
        $engineeringCodes = $this->getEngineeringCodes();
        if (in_array($session, $engineeringCodes, true)) {
            return $session;
        }

        // Guest program accounts
        if (strlen($session) !== 10 || !ctype_alnum($session)) {
            $this->debug->fail("Invalid session code '{$session}'. Must be a 10-character alphanumeric code.");
        }

        return $session;
    }

    private function getEngineeringCodes(): array
    {
        return [
            'tisanpwd',
            'wbtcorar'
        ];
    }
}
