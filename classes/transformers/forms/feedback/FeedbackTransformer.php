<?php

declare(strict_types=1);

namespace App\transformers\forms\feedback;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug as Debug;
use App\core\irtf\IrtfUtilities;

class FeedbackTransformer
{
    protected $debug;

    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Transformer", "__construct");
        $this->debug->debug($debugHeading);

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Class initialisation complete.");
    }

    public function transformData(
        array $data,
        array $context
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "transformData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");
        // Transform the validated data and return the array for database input
        $dbData = $this->transformDataForDatabase($data, $context);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        // Transform the validated data and return the array for email output
        $emailData = $this->transformDataForEmail($data, $context);
        $this->debug->debugVariable($emailData, "{$debugHeading} -- emailData");
        // Return both arrays
        return ['db' => $dbData, 'email' => $emailData];
    }

    public function transformInstruments(
        array $facilityValues,
        array $visitorValues,
        array $allowedFacilityValues,
        array $allowedVisitorValues
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "transformInstruments");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($facilityValues, "{$debugHeading} -- facilityValues");
        $this->debug->debugVariable($visitorValues, "{$debugHeading} -- visitorValues");
        $this->debug->debugVariable($allowedFacilityValues, "{$debugHeading} -- allowedFacilityValues");
        $this->debug->debugVariable($allowedVisitorValues, "{$debugHeading} -- allowedVisitorValues");

        // Consolidate the selected instruments
        $values = array_merge(
            $facilityValues ?? [],
            array_filter(
                $visitorValues ?? [],
                function ($v) {
                    return $v !== 'none';
                }
            )
        );

        // Consolidate the allowed instruments
        $allowedValues = array_merge(
            $allowedFacilityValues ?? [],
            array_filter(
                $allowedVisitorValues ?? [],
                function ($key) {
                    return $key !== 'none';
                },
                ARRAY_FILTER_USE_KEY
            )
        );

        // Return both arrays
        return ['values' => $values ?? [], 'allowed' => $allowedValues ?? []];
    }

    private function transformDataForDatabase(
        array $data,
        array $context
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "validateDataForDatabase");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        // Build the validated data array for database
        $dbData = [];
        $fb = 'feedback';
        $in = 'instruments';
        $op = 'operators';
        $sp = 'support';

        // Basic info
        $dbData[$fb]['name'] = (string) $data['respondent'];
        $dbData[$fb]['email'] = (string) $data['email'];
        // Program Information
        $dbData[$fb]['programID'] = (int) $context['program']['p'];
        $dbData[$fb]['a'] = (string) $context['program']['a'];
        //$dbData[$fb]['i'] = (int) $context['program']['i'];
        //$dbData[$fb]['n'] = (string) $context['program']['n'];
        $dbData[$fb]['semesterID'] = (string) $context['program']['s'];
        // Observing Dates
        $dbData[$fb]['start_date'] = (int) $data['start_date'];
        $dbData[$fb]['end_date'] = (int) $data['end_date'];
        // Support Staff
        $dbData[$sp] = $data['support_staff'];
        // Telescope Operators
        $dbData[$op] = $data['operator_staff'];
        // Instruments
        $dbData[$in] = $data['instruments'];
        // Technical Feedback
        $dbData[$fb]['location'] = (int) $data['location'][0];
        $dbData[$fb]['technical_rating'] = (int) $data['experience'][0];
        $dbData[$fb]['technical_comments'] = (string) $data['technical'];
        // Personnel Feedback
        $dbData[$fb]['scientific_staff_rating'] = (int) $data['scientificstaff'][0];
        $dbData[$fb]['TO_rating'] = (int) $data['operators'][0];
        $dbData[$fb]['daycrew_rating'] = (int) $data['daycrew'][0];
        $dbData[$fb]['personnel_comment'] = (string) ($data['personnel'] ?? '');
        // Scientific Results
        $dbData[$fb]['scientific_results'] = (string) ($data['scientific'] ?? '');
        // Suggestions
        $dbData[$fb]['suggestions'] = (string) ($data['comments'] ?? '');

        return $dbData;
    }

    private function transformDataForEmail(
        array $data,
        array $context
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "transformDataForEmail");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($context, "{$debugHeading} -- context");

        // Build the transformed data array for email
        $email = [];
        $fb = 'feedback';
        $in = 'instruments';
        $op = 'operators';
        $sp = 'support';

        // Basic info
        $email['name'] = (string) $data['respondent'];
        $email['email'] = (string) $data['email'];
        // Program Information
        $email['program'] = (string) $context['program']['a'];
        // Observing Dates
        $email['start_date'] = $this->returnTextDate((int) $data['start_date']);
        $email['end_date'] = $this->returnTextDate((int) $data['end_date']);
        // Support Staff
        $email[$sp] = $this->returnSelectionText($data['support_staff'], $context['support']);
        // Telescope Operators
        $email[$op] = $this->returnSelectionText($data['operator_staff'], $context['operator']);
        // Instruments
        $email[$in] = $this->returnSelectionText($data['instruments'], $context['instruments']);
        // Technical Feedback
        $email['location'] = $this->returnLocationText((int) $data['location'][0]);
        $email['technical_rating'] = $this->returnRatingText((int) $data['experience'][0]);
        $email['technical_comments'] = (string) $data['technical'];
        // Personnel Feedback
        $email['scientific_staff_rating'] = $this->returnRatingText((int) $data['scientificstaff'][0]);
        $email['TO_rating'] = $this->returnRatingText((int) $data['operators'][0]);
        $email['daycrew_rating'] = $this->returnRatingText((int) $data['daycrew'][0]);
        $email['personnel_comment'] = (string) ($data['personnel'] ?? '');
        // Scientific Results
        $email['scientific_results'] = (string) ($data['scientific'] ?? '');
        // Suggestions
        $email['suggestions'] = (string) ($data['comments'] ?? '');

        return $email;
    }

    // Protected helper methods

    /**
     * Converts a numeric rating to its descriptive text equivalent.
     *
     * @param int $rating The numeric rating (0-5).
     *
     * @return string The descriptive rating text, e.g., "Excellent".
     */
    protected function returnRatingText(
        int $rating
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnRatingText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rating, "{$debugHeading} -- rating");
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
    protected function returnLocationText(
        int $location
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnLocationText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($location, "{$debugHeading} -- location");
        // Method text
        $locationText = [
            'Remote',
            'Onsite',
        ];
        return $locationText[$location];
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
    protected function returnSelectionText(
        array $options,
        array $allowed
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnSelectionText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($options, "{$debugHeading} -- options");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");
        // Method text
        return implode(
            ', ',
            array_map(
                [IrtfUtilities::class, 'escape'],
                array_intersect_key($allowed, array_flip($options))
            )
        );
    }

    protected function returnTextDate(
        int $timestamp,
        string $format = 'M d, Y'
    ): string {
        return date($format, $timestamp);
    }
}
