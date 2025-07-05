<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\AbstractDebug as Debug;
use App\validators\BaseValidator;

/**
 * Validator for handling the Observer Data Restoration Request logic.
 *
 * @category Validators
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsDataRestorationRequestValidator extends BaseValidator
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

        $minYear = 2016;
        $maxYear = date('Y') + 1;
        return [
            // Validate `reqname` field [Requestor name]
            [
                'field'         => 'reqname',
                'method'        => 'validateNameField',
                'args'          => [50],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `reqemail` field [Requestor email]
            [
                'field'         => 'reqemail',
                'method'        => 'validateEmailField',
                'args'          => [70],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `y` field [The semester year the data were taken]
            [
                'field'         => 'y',
                'method'        => 'validateYear',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `s` field [The semester tag the data were taken]
            [
                'field'         => 's',
                'method'        => 'validateSemesterTagField',
                'args'          => [],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `srcprogram` field [Program the data were taken under]
            [
                'field'         => 'srcprogram',
                'method'        => 'validateProgramNumberField',
                'args'          => [$minYear, $maxYear],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `piprogram` field [PI of the program]
            [
                'field'         => 'piprogram',
                'method'        => 'validateNameField',
                'args'          => [50],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `obsinstr` field [Instruments used to take the data]
            [
                'field'         => 'obsinstr',
                'method'        => 'validateNameField',
                'args'          => [50],
                'required'      => true,
                'required_msg'  => 'This field is required',
            ],
            // Validate `reldetails` field [Any other details that might be relevant or helpful
            [
                'field'         => 'reldetails',
                'method'        => 'validateLongTextField',
                'args'          => [500],
                'required'      => false,
                'required_msg'  => '',
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
