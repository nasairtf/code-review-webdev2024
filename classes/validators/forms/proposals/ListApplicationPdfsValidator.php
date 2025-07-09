<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * Validator for handling the List Application Pdfs logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ListApplicationPdfsValidator extends BaseValidator
{
    /**
     * Initializes the validator and sets up debugging.
     *
     * If no debug instance is provided, it defaults to a basic debug setup.
     *
     * @param Debug|null $debug Debug instance for logging and debugging.
     */
    public function __construct(?Debug $debug = null)
    {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");
    }

    // Abstract methods: getValidationPlan(), formatValidData(), formatErrors()

    protected function getValidationPlan(array $data, array $context = []): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "getValidationPlan");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        $minYear = 2000;
        $maxYear = date('Y') + 1;
        return [
            [
                'field'         => 'program',
                'method'        => 'validateProgramNumberField',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            [
                'field'         => 'session',
                'method'        => 'validateSessionCodeField',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
        ];
    }

    protected function formatValidData(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatValidData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdValidData($normalizedPlan);
    }

    protected function formatErrors(array $normalizedPlan): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "formatErrors");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($normalizedPlan, "{$debugHeading} -- normalizedPlan");

        return $this->formatStdErrors($normalizedPlan);
    }

    /**
     * Data validation methods for user input validation
     *
     * validateYear      - validates form1's year field
     * validateSemester  - validates form1's semester field
     * validateObsAppID  - validates form2's obsappid field
     * validateTimestamp - validates form3's timestamp field
     */

    /**
     * Validates a year input to ensure it is an integer within a valid range (1000-9999).
     *
     * @param mixed $year The year to validate.
     *
     * @return int The validated year.
     *
     * @throws \InvalidArgumentException If the year is invalid.
     */
    public function validateYear($year): int
    {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Validator: validateYear()");

        $options = [
            'options' => [
                'min_range' => 1000,
                'max_range' => 9999,
            ],
        ];

        if ($year === null || !filter_var($year, FILTER_VALIDATE_INT, $options)) {
            $this->debug->fail('The year provided is invalid.');
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
