<?php

declare(strict_types=1);

namespace App\validators\common;

use App\validators\common\TokenCompositeUtility;

/**
 * ValidationCoreTokenCompositeTrait
 *
 * Provides wrapper methods for TokenCompositeUtility functionality.
 * Orchestrates token integrity, structure, and authorized request type validation.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait ValidationCoreTokenCompositeTrait
{
    /**
     * Validates that a token is structurally valid and matches the expected request type.
     *
     * Performs the following steps:
     *  - Confirms the request type is allowed
     *  - Validates token structure and HMAC hash
     *  - Ensures the token's embedded type matches the requested type
     *
     * Stores the final validated structure under the top-level field key.
     *
     * @param ValidationResult  $result              The ValidationResult instance to update.
     * @param mixed             $requestValue        The requested action type (e.g., 'download').
     * @param mixed             $tokenValue          The encoded token to validate.
     * @param string            $fieldKey            The field key under which to store results.
     * @param array             $allowedRequestTypes List of permitted request types.
     * @param array             $tokenRules          Rules for token structure validation.
     *                                                - 'count' => number of payload parts
     *                                                - 'parts' => expected field labels
     *                                                - 'hash'  => shared HMAC key
     *
     * @return ValidationResult Updated ValidationResult containing validation results.
     */
    public function validateAuthorizedRequest(
        ValidationResult $result,
        $requestValue,
        $tokenValue,
        string $fieldKey,
        array $allowedRequestTypes,
        array $tokenRules
    ): ValidationResult {
        return TokenCompositeUtility::validateAuthorizedRequest(
            $result,
            $requestValue,
            $tokenValue,
            $fieldKey,
            $allowedRequestTypes,
            $tokenRules
        );
    }
}
