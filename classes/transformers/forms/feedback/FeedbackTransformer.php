<?php

declare(strict_types=1);

namespace App\transformers\forms\feedback;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug as Debug;
use App\transformers\BaseTransformer;

class FeedbackTransformer extends BaseTransformer
{
    /**
     * Constructor for FeedbackTransformer.
     *
     * @param Debug|null $debug Optional debugging utility instance.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Transformer", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");
    }

    /**
     * Orchestrates transformation of validated data for backend use.
     *
     * Produces two parallel output formats: one structured for database
     * insertion and another formatted for email display.
     *
     * @param array $data    The validated form data.
     * @param array $context The trusted server-side context data.
     *
     * @return array An array containing 'db' and 'email' keys with transformed output.
     */
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

    /**
     * Transforms validated input data into the structure required for database storage.
     *
     * Flattens composite fields and remaps input names to match expected
     * database column schema.
     *
     * @param array $data    The validated form input.
     * @param array $context The server-side trusted values for lookup.
     *
     * @return array Structured data ready for database persistence.
     */
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

    /**
     * Transforms validated input data into a human-readable format for email output.
     *
     * Converts selection keys and numeric codes into descriptive labels
     * and formats timestamps as readable dates.
     *
     * @param array $data    The validated form input.
     * @param array $context The server-side trusted values including label maps.
     *
     * @return array Structured data ready for inclusion in email body.
     */
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
}
