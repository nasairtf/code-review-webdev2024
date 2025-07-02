<?php

declare(strict_types=1);

namespace App\validators\common\core;

use App\validators\common\ValidationResult;
use App\validators\common\utilities\TokensBaseUtility;

/**
 * TokensBaseTrait
 *
 * Provides wrapper methods for TokensBaseUtility functionality.
 * Validates encoded tokens and ensures request type consistency with embedded metadata.
 *
 * @category Validation
 * @package  IRTF
 * @version  1.0.0
 */
trait TokensBaseTrait
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
    public function validateTokenStructure(
        ValidationResult $result,
        $value,
        string $fieldKey,
        array $tokenRules
    ): ValidationResult {
        return TokensBaseUtility::validateTokenStructure(
            $result,
            $value,
            $fieldKey,
            $tokenRules
        );
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
    public function validateTokenRequestType(
        ValidationResult $result,
        $requestValue,
        $tokenType,
        string $fieldKey
    ): ValidationResult {
        return TokensBaseUtility::validateTokenRequestType(
            $result,
            $requestValue,
            $tokenType,
            $fieldKey
        );
    }
}
