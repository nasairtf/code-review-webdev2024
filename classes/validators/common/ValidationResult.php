<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * ValidationResult class.
 *
 * Holds validated field values and any associated field errors. Each validation
 * method may update this object by adding errors via addFieldError() or by setting
 * sanitized data via setFieldValue().
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class ValidationResult
{
    /**
     * @var array Array of validated values, keyed by field name.
     */
    private $fieldValues = [];

    /**
     * @var array Array of validation errors, keyed by field name. Each entry is an array of messages.
     */
    private $fieldErrors = [];

    /**
     * Records an error message for a given field.
     *
     * @param string $fieldKey The field name/key associated with the error.
     * @param string $message  The error message.
     *
     * @return self
     */
    public function addFieldError(string $fieldKey, string $message): self
    {
        $this->fieldErrors[$fieldKey][] = $message;
        return $this;
    }

    /**
     * Retrieves all error messages for the specified field.
     *
     * @param string $fieldKey The field name/key.
     *
     * @return array An array of error messages, or an empty array if none.
     */
    public function getFieldErrors(string $fieldKey): array
    {
        return $this->fieldErrors[$fieldKey] ?? [];
    }

    /**
     * Stores a sanitized/validated value for the specified field.
     *
     * @param string $fieldKey The field name/key.
     * @param mixed  $value    The validated value.
     *
     * @return self
     */
    public function setFieldValue(string $fieldKey, $value): self
    {
        $this->fieldValues[$fieldKey] = $value;
        return $this;
    }

    /**
     * Retrieves the validated value for the specified field.
     *
     * @param string $fieldKey The field name/key.
     *
     * @return mixed|null The value if set, or null if not found.
     */
    public function getFieldValue(string $fieldKey)
    {
        return $this->fieldValues[$fieldKey] ?? null;
    }

    /**
     * Determines if any value has been recorded for the field.
     *
     * @param string $fieldKey The field name/key.
     *
     * @return bool True if set, or false if not found.
     */
    public function hasFieldValue(string $fieldKey): bool
    {
        return array_key_exists($fieldKey, $this->fieldValues);
    }

    /**
     * Determines if any errors have been recorded.
     *
     * @return bool True if there is at least one field error, false otherwise.
     */
    public function hasErrors(): bool
    {
        return !empty($this->fieldErrors);
    }

    /**
     * Retrieves the entire array of field errors.
     *
     * @return array An associative array of [fieldKey => array of messages].
     */
    public function getAllErrors(): array
    {
        return $this->fieldErrors;
    }
}
