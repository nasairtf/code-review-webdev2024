<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * TokenCompositeUtility
 *
 * Provides composite validation logic for token-bound request workflows,
 * combining:
 * - Request type selection validation
 * - Token structure and hash validation
 * - Token-to-request type integrity enforcement
 *
 * This utility is useful for secure workflows involving actions like
 * downloads, logins, or protected form submissions.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class TokenCompositeUtility
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
    public static function validateAuthorizedRequest(
        ValidationResult $result,
        $requestValue,
        $tokenValue,
        string $fieldKey,
        array $allowedRequestTypes,
        array $tokenRules
    ): ValidationResult {
        // Validate that request type is permitted
        $res = SelectionBaseUtility::validateSelection(
            $result,
            [(string) $requestValue],
            "{$fieldKey}_type",
            $allowedRequestTypes,
            false // Validate against VALUES not keys
        );

        // Short-circuit and return if request type validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Validate token structure
        $res = TokensBaseUtility::validateTokenStructure(
            $res,
            $tokenValue,
            "{$fieldKey}_token",
            $tokenRules
        );

        // Short-circuit and return if token structure validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Retrieve type from validated token payload
        $decoded = $res->getFieldValue("{$fieldKey}_token");
        $tokenType = $decoded['type'] ?? null;

        if ($tokenType === null) {
            return $res->addFieldError(
                "{$fieldKey}_token",
                "Token missing 'type' information."
            );
        }

        // Validate that token type matches request type
        $res = TokensBaseUtility::validateTokenRequestType(
            $res,
            $requestValue,
            $tokenType,
            "{$fieldKey}_type_check"
        );

        // Short-circuit and return if token type validation failed
        if ($res->hasErrors()) {
            return $res;
        }

        // Store top-level validated structure
        return $res->setFieldValue($fieldKey, [
            'type'  => (string) $requestValue,
            'token' => $decoded,
        ]);
    }
}
