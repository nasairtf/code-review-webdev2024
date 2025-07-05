<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * Validator for handling the Queue Observer Data Restoration logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 *
 * @property Debug $debug Debugging utility for logging and error tracing.
 */

class QueueDataRestoreValidator extends BaseValidator
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
            // Validate `usersrc` field [Source Program Username]
            [
                'field'         => 'usersrc',
                'method'        => 'validateProgramNumberField',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `userdst` field [Destination Program Username]
            [
                'field'         => 'userdst',
                'method'        => 'validateProgramNumberField',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `codesrc` field [Source Program Code]
            [
                'field'         => 'codesrc',
                'method'        => 'validateSessionCodeField',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `codedst` field [Destination Program Code]
            [
                'field'         => 'codedst',
                'method'        => 'validateSessionCodeField',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `test` field [Restore data in test mode]
            [
                'field'         => 'test',
                'method'        => 'validateOnOffRadio',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `delete` field [Restore data in delete mode]
            [
                'field'         => 'delete',
                'method'        => 'validateOnOffRadio',
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
}
