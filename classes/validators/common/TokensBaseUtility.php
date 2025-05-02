<?php

declare(strict_types=1);

namespace App\validators\common;

/**
 * TokensBaseUtility
 *
 * Provides atomic validation for token structures, including:
 * - Base64 decoding
 * - Token structure validation
 * - HMAC hash checking
 *
 * This class assumes input has been pre-sanitized and focuses
 * purely on token validation, not generation or transformation.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */
class TokensBaseUtility
{
    /**
     * Validates that a token is properly base64-encoded and follows expected structure.
     *
     * @param ValidationResult  $result      The ValidationResult instance to update.
     * @param mixed             $value       The token value to validate.
     * @param string            $fieldKey    The field key associated with the token.
     * @param array             $tokenRules  Rules for token structure:
     *                                        - 'count' => number of payload parts
     *                                        - 'parts' => expected labels
     *                                        - 'hash'  => secret key for HMAC
     *
     * @return ValidationResult Updated ValidationResult with either validation errors or validated payload.
     */
    public static function validateTokenStructure(
        ValidationResult $result,
        $value,
        string $fieldKey,
        array $tokenRules
    ): ValidationResult {
        // Explicitly cast value to string type
        $value = (string) $value;

        // Validate base64 decoding
        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return $result->addFieldError(
                $fieldKey,
                "Token is not valid base64."
            );
        }

        // Decompose decoded token into its constituent parts
        $parts = explode('|', $decoded);
        if (count($parts) !== $tokenRules['count'] + 1) { // +1 for hash
            return $result->addFieldError(
                $fieldKey,
                "Token structure invalid."
            );
        }

        // Split into payload and hash
        $payload = array_slice($parts, 0, $tokenRules['count']);
        $providedHash = $parts[$tokenRules['count']];
        $rawPayload = implode('|', $payload);

        // Validate HMAC hash
        $expectedHash = hash_hmac('sha256', $rawPayload, $tokenRules['hash']);
        if (!hash_equals($expectedHash, $providedHash)) {
            return $result->addFieldError(
                $fieldKey,
                "Token hash mismatch."
            );
        }

        // Map payload parts to expected structure labels
        $validated = array_combine($tokenRules['parts'], $payload);
        if ($validated === false) {
            return $result->addFieldError(
                $fieldKey,
                "Token field mismatch."
            );
        }

        // Store validated token structure
        return $result->setFieldValue($fieldKey, $validated);
    }

    /**
     * Validates that a request type matches the token's authorized type.
     *
     * This is useful for verifying that the type of action (e.g., 'download', 'login')
     * requested by the user matches the token's intended purpose.
     *
     * @param ValidationResult  $result       The ValidationResult instance to update.
     * @param mixed             $requestValue The external requested type (e.g., from form or route).
     * @param mixed             $tokenType    The authorized type extracted from the validated token.
     * @param string            $fieldKey     The field key associated with this validation.
     *
     * @return ValidationResult Updated ValidationResult containing any mismatch errors or the confirmed type.
     */
    public static function validateTokenRequestType(
        ValidationResult $result,
        $requestValue,
        $tokenType,
        string $fieldKey
    ): ValidationResult {
        $requestValue = (string) $requestValue;
        $tokenType = (string) $tokenType;

        if ($requestValue !== $tokenType) {
            return $result->addFieldError(
                $fieldKey,
                "Token request type mismatch."
            );
        }

        // Store validated download request type
        return $result->setFieldValue($fieldKey, $requestValue);
    }
}
