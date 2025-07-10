<?php

declare(strict_types=1);

namespace App\transformers\common\utilities;

use App\core\irtf\IrtfUtilities;

class LabelUtility
{
    /**
     * Converts a numeric rating to its descriptive text equivalent.
     *
     * @param int $rating The numeric rating (0-5).
     *
     * @return string The descriptive rating text, e.g., "Excellent".
     */
    public static function returnRatingText(
        int $rating
    ): string {
        // Method text
        $ratingText = [
            'N/A',
            'Poor',
            'Fair',
            'Good',
            'Very Good',
            'Excellent',
        ];
        return $ratingText[$rating];
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    public static function returnLocationText(
        int $location
    ): string {
        // Method text
        $locationText = [
            'Remote',
            'Onsite',
        ];
        return $locationText[$location];
    }

    /**
     * Converts the numeric flag for email sending into human-readable form.
     *
     * @param int $emailType The email sending mode (0 for dummy, 1 for real).
     *
     * @return string Descriptive text for the email sending type.
     */
    public static function returnEmailsSendTypeText(
        int $emailType
    ): string {
        // Method text
        $emailTypeText = [
            'Yes (send real emails)',
            'No (send dummy emails)',
        ];
        return $emailTypeText[$emailType];
    }

    /**
     * Converts a numeric interval unit code into its label.
     *
     * @param int $unitType The interval unit (0 = Days, 1 = Weeks).
     *
     * @return string Descriptive text for the interval unit.
     */
    public static function returnIntervalUnitTypeText(
        int $unitType
    ): string {
        // Method text
        $unitTypeText = [
            'Days',
            'Weeks',
        ];
        return $unitTypeText[$unitType];
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
    public static function returnSelectionText(
        array $options,
        array $allowed
    ): string {
        // Method text
        return implode(
            ', ',
            array_map(
                [IrtfUtilities::class, 'escape'],
                array_intersect_key($allowed, array_flip($options))
            )
        );
    }

    public static function returnTextDate(
        int $timestamp,
        string $format = 'M d, Y'
    ): string {
        return date($format, $timestamp);
    }
}
