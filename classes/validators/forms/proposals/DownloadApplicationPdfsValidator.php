<?php

declare(strict_types=1);

namespace App\validators\forms\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\irtf\IrtfUtilities;
use App\core\common\AbstractDebug          as Debug;
use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for handling the Download Application Pdfs logic.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */

class DownloadApplicationPdfsValidator extends BaseValidator
{
    private $secret;

    public function __construct(
        string $secret,
        ?Debug $debug = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Store the hash secret
        $this->secret = $secret ?? '';

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Validator initialisation complete.");
    }

    public function validateFormData(
        array $form
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateFormData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "form");
        // Set up the arrays needed to validate values
        $db = [
            'type' => ['add', 'app'],
            'token' => [
                'count' => 4,
                'parts' => ['code', 'file', 'type', 'timestamp'],
                'hash'  => $this->secret,
            ],
        ];
        // Validate the form data and return the validated array
        $validData = $this->validateDataForDownload($form, $db);
        // Return array
        return $validData;
    }

    private function validateDataForDownload(
        array $form,
        array $db
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDataForDownload");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "{$debugHeading} -- form");
        $this->debug->debugVariable($db, "{$debugHeading} -- db");

        // Build the validated data array for download
        $valid = [];

        // File type
        $valid['type'] = $this->validateSelection(
            [$form['type'] ?? ''],
            $db['type'],
            'type',
            true,
            'Invalid download file request.',
            false
        )[0];

        // File token
        $valid['token'] = $this->validateToken(
            $form['t'] ?? '',
            $db['token'],
            'token',
            true,
            'Invalid download file request.'
        );

        // Download request
        $valid['download'] = $this->validateDownloadRequest(
            $valid['type'],
            $valid['token']['type'],
            'download'
        );

        // After validating, check if errors exist and throw if necessary
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }
        return $valid;
    }
}
