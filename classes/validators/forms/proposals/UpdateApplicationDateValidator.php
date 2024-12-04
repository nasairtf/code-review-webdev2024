<?php

namespace App\validators\forms\proposals;

use App\core\common\Debug;

/**
 * Validator for handling the Update Application Date logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UpdateApplicationDateValidator
{
    private $debug;

    // Constructor: Initializes the controller, view, and model, and sets up debugging
    public function __construct(
        ?Debug $debug = null
    ) {
        $this->debug = $debug ?? new Debug('default', false, 0);
    }

    /**
     * Data validation methods for user input validation
     *
     * validateYear      - validates form1's year field
     * validateSemester  - validates form1's semester field
     * validateObsAppID  - validates form2's obsappid field
     * validateTimestamp - validates form3's timestamp field
     */

    public function validateYear($year): int
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Validator: validateYear()");

        if (is_null($year) || !filter_var($year, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1000, "max_range" => 9999]])) {
            $this->debug->fail("The year provided is invalid.");
        }
        return (int) $year;
    }

    public function validateSemester($semester): string
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Validator: validateSemester()");

        if (is_null($semester) || !in_array(strtoupper($semester), ['A', 'B'], true)) {
            $this->debug->fail("The semester provided is invalid. It must be 'A' or 'B'.");
        }
        return strtoupper($semester);
    }

    public function validateObsAppID($obsapp_id): int
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Validator: validateObsAppID()");

        if (is_null($obsapp_id) || !filter_var($obsapp_id, FILTER_VALIDATE_INT)) {
            $this->debug->fail("The obsapp_id provided is invalid.");
        }
        return (int) $obsapp_id;
    }

    public function validateTimestamp($timestamp): int
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Validator: validateTimestamp()");

        $timestamp = (int) $timestamp;
        if ($timestamp <= 0) {
            $this->debug->fail("The timestamp provided is invalid.");
        }
        $minTimestamp = 0;  // UNIX epoch (1970-01-01 00:00:00 UTC)
        //$maxTimestamp = 2147483647;  // 2038-01-19 03:14:07 UTC for 32-bit systems
        $maxTimestamp = 253402300799;  // December 31, 9999 for 64-bit systems
        if ($timestamp < $minTimestamp || $timestamp > $maxTimestamp) {
            $this->debug->fail("The timestamp is out of range.");
        }
        return (int) $timestamp; // Return the sanitized timestamp
    }
}
