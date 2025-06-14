<?php

declare(strict_types=1);

namespace App\validators\common\utilities;

/**
 * UploadsBaseUtility
 *
 * Provides atomic validation and basic processing for file uploads.
 * Handles checking upload status and optional MIME type validation.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class UploadsBaseUtility
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
    public static function validateUploadedFile(
        ValidationResult $result,
        array $fileData,
        string $fieldKey,
        array $mimeTypes = []
    ): ValidationResult {
        // Check for upload errors
        if ($fileData['error'] !== UPLOAD_ERR_OK) {
            return $result->addFieldError(
                $fieldKey,
                "File upload error code: {$fileData['error']}."
            );
        }

        // Validate MIME type
        if (!empty($mimeTypes) && !in_array($fileData['type'], $mimeTypes, true)) {
            return $result->addFieldError(
                $fieldKey,
                "Invalid file type: {$fileData['type']}."
            );
        }

        // If validation passes, store file metadata
        return $result->setFieldValue($fieldKey, $fileData);
    }
}
