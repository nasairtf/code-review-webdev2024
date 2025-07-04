<?php

declare(strict_types=1);

namespace App\transformers\common\core;

use App\transformers\common\utilities\LabelUtility;

trait LabelTrait
{
    /**
     * Converts a numeric rating to its descriptive text equivalent.
     *
     * @param int $rating The numeric rating (0-5).
     *
     * @return string The descriptive rating text, e.g., "Excellent".
     */
    public function returnRatingText(
        int $rating
    ): string {
        return LabelUtility::returnRatingText(
            $rating
        );
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    public function returnLocationText(
        int $location
    ): string {
        return LabelUtility::returnLocationText(
            $location
        );
    }

    /**
     * Converts the numeric flag for email sending into human-readable form.
     *
     * @param int $emailType The email sending mode (0 for dummy, 1 for real).
     *
     * @return string Descriptive text for the email sending type.
     */
    public function returnEmailsSendTypeText(
        int $emailType
    ): string {
        return LabelUtility::returnEmailsSendTypeText(
            $emailType
        );
    }

    /**
     * Converts a numeric interval unit code into its label.
     *
     * @param int $unitType The interval unit (0 = Days, 1 = Weeks).
     *
     * @return string Descriptive text for the interval unit.
     */
    public function returnIntervalUnitTypeText(
        int $unitType
    ): string {
        return LabelUtility::returnIntervalUnitTypeText(
            $unitType
        );
    }

    /**
     * Retrieves the descriptive names for selected items and returns them as a comma-separated string.
     *
     * Maps each key in the options array to its corresponding value in the allowed
     * array, then concatenates them into a single comma-separated string.
     *
     * @param array $options Selected option keys.
     * @param array $allowed Associative array of allowed options with keys and names.
     *
     * @return string A comma-separated list of names for the selected options.
     */
    public function returnSelectionText(
        array $options,
        array $allowed
    ): string {
        return LabelUtility::returnSelectionText(
            $options,
            $allowed
        );
    }

    public function returnTextDate(
        int $timestamp,
        string $format = 'M d, Y'
    ): string {
        return LabelUtility::returnTextDate(
            $timestamp,
            $format
        );
    }
}
