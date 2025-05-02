<?php

declare(strict_types=1);

namespace App\validators\common;

use App\validators\common\UploadsBaseUtility;

/**
 * ValidationCoreUploadsBaseTrait
 *
 * Provides wrapper methods for UploadsBaseUtility functionality.
 * Enables consistent validation of uploaded file structure and MIME type.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ValidationCoreUploadsBaseTrait
{
    /**
     * Validates an uploaded file's basic upload status and MIME type.
     *
     * @param ValidationResult  $result      The ValidationResult instance to update.
     * @param array             $fileData    The uploaded file data (e.g., $_FILES element).
     * @param string            $fieldKey    The field key associated with the file.
     * @param array             $mimeTypes   Allowed MIME types for the file (optional).
     *
     * @return ValidationResult Updated ValidationResult containing errors or file data.
     */
    public function validateUploadedFile(
        ValidationResult $result,
        array $fileData,
        string $fieldKey,
        array $mimeTypes = []
    ): ValidationResult {
        return UploadsBaseUtility::validateUploadedFile(
            $result,
            $fileData,
            $fieldKey,
            $mimeTypes
        );
    }
}
