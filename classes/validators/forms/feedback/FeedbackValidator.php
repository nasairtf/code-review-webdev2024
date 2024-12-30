<?php

declare(strict_types=1);

namespace App\validators\forms\feedback;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\Debug;
use App\core\irtf\IrtfUtilities;
use App\validators\forms\BaseFormValidator as BaseValidator;

/**
 * Validator for handling the Feedback form logic.
 *
 * This class handles validation for feedback form fields, including respondent
 * information, date validation, ratings, and specific selection fields for
 * instruments and location. It provides two sets of validated data: one for
 * database storage and one for email formatting.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */

class FeedbackValidator extends BaseValidator
{
    /**
     * Constructor to initialize the FeedbackValidator with a Debug instance.
     *
     * @param Debug|null $debug Optional. An instance of Debug for logging; defaults to null.
     */
    public function __construct(Debug $debug = null)
    {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");
    }

    /**
     * Validates and transforms form data for database storage and email output.
     *
     * This method validates the form input data against database reference data,
     * generates a structured array for storing in the database, and prepares
     * a transformed version of the data for email output.
     *
     * @param array $form The form input data to validate.
     * @param array $db   The database reference data for validation.
     *
     * @return array An associative array with 'db' and 'email' keys, each containing validated data.
     */
    public function validateFormData(
        array $form,
        array $db
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateFormData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($form, "form");
        $this->debug->debugVariable($db, "db");
        // Validate the form data and return the array for database input
        $validData = $this->validateDataForDatabase($form, $db);
        // Transform the validated data and return the array for email output
        $emailData = $this->transformDataForEmail($validData, $form, $db);
        // Return both arrays
        return ['db' => $validData, 'email' => $emailData];
    }

    /**
     * Validates form data for database storage.
     *
     * This method checks all relevant fields of the feedback form, ensuring that
     * data adheres to expected formats and constraints, and structures it for
     * insertion into the database.
     *
     * @param array $form Form input data to validate.
     * @param array $db   Reference data from the database.
     *
     * @return array Validated data for database storage.
     * @throws ValidationException If validation errors occur.
     */
    private function validateDataForDatabase(
        array $form,
        array $db
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDataForDatabase");
        $this->debug->debug($debugHeading);
        // Build the validated data array for database
        $valid = [];
        $fb = 'feedback';
        $in = 'instruments';
        $op = 'operators';
        $sp = 'support';

        // Basic info
        $valid[$fb]['name'] = $this->validateName(
            $form['respondent'] ?? '',
            'respondent',
            true
        );
        $valid[$fb]['email'] = $this->validateEmail(
            $form['email'] ?? '',
            'email',
            true
        );
        // Program Information
        $valid[$fb]['programID'] = $db['program']['p'];
        $valid[$fb]['a'] = $db['program']['a'];
        //$valid[$fb]['i'] = $db['program']['i'];
        //$valid[$fb]['n'] = $db['program']['n'];
        $valid[$fb]['semesterID'] = $db['program']['s'];
        //$valid[$email]['semesterID'] = $db['program']['s'];
        // Observing Dates
        $this->validateDates(
            $form['startmonth'],
            $form['startday'],
            $form['startyear'],
            $form['endmonth'],
            $form['endday'],
            $form['endyear'],
            $form['s'],
            'dates',
            true
        );
        $valid[$fb]['start_date'] = IrtfUtilities::returnUnixDate(
            $form['startmonth'],
            $form['startday'],
            $form['startyear']
        );
        $valid[$fb]['end_date'] = IrtfUtilities::returnUnixDate(
            $form['endmonth'],
            $form['endday'],
            $form['endyear']
        );
        // Support Staff
        $valid[$sp] = $this->validateSelection(
            $form['support_staff'] ?? [],
            $db['support'],
            'support_staff',
            true,
            'Invalid support astronomer selection.'
        );
        // Telescope Operators
        $valid[$op] = $this->validateSelection(
            $form['operator_staff'] ?? [],
            $db['operator'],
            'operator_staff',
            true,
            'Invalid telescope operator selection.'
        );
        // Instruments [nested filter removes 'none' from visitor arrays]
        $instruments = $this->transformInstruments(
            $form['instruments'],
            $form['visitor_instrument'],
            $db['facility'],
            $db['visitor']
        );
        $valid[$in] = $this->validateSelection(
            $instruments['form'],
            $instruments['db'],
            'instruments',
            true,
            'Invalid instrument selection.'
        );
        // Technical Feedback
        $valid[$fb]['location'] = $this->validateLocation(
            $form['location'],
            'location',
            true
        );
        $valid[$fb]['technical_rating'] = $this->validateRating(
            $form['experience'],
            false,
            'experience',
            true
        );
        $valid[$fb]['technical_comments'] = $this->validateLongTextField(
            $form['technical'] ?? '',
            500,
            'technical',
            true
        );
        // Personnel Feedback
        $valid[$fb]['scientific_staff_rating'] = $this->validateRating(
            $form['scientificstaff'],
            true,
            'scientificstaff',
            true
        );
        $valid[$fb]['TO_rating'] = $this->validateRating(
            $form['operators'],
            true,
            'operators',
            true
        );
        $valid[$fb]['daycrew_rating'] = $this->validateRating(
            $form['daycrew'],
            true,
            'daycrew',
            true
        );
        $valid[$fb]['personnel_comment'] = $this->validateLongTextField(
            $form['personnel'] ?? '',
            500,
            'personnel',
            false
        );
        // Scientific Results
        $valid[$fb]['scientific_results'] = $this->validateLongTextField(
            $form['scientific'] ?? '',
            500,
            'scientific',
            false
        );
        // Suggestions
        $valid[$fb]['suggestions'] = $this->validateLongTextField(
            $form['comments'] ?? '',
            500,
            'comments',
            false
        );
        // After validating, check if errors exist and throw if necessary
        if (!empty($this->errors)) {
            throw new ValidationException("Validation errors occurred.", $this->errors);
        }
        return $valid;
    }

    /**
     * Transforms validated data for email output format.
     *
     * This method converts database-friendly data into a format suitable for email output,
     * including formatting dates and converting selection keys into human-readable text.
     *
     * @param array $valid The validated data array for database.
     * @param array $form  The original form input data.
     * @param array $db    The database reference data.
     *
     * @return array Transformed data formatted for email output.
     */
    private function transformDataForEmail(
        array $valid,
        array $form,
        array $db
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "transformDataForEmail");
        $this->debug->debug($debugHeading);
        // Build the transformed data array for email
        $email = [];
        $fb = 'feedback';
        $in = 'instruments';
        $op = 'operators';
        $sp = 'support';
        // Basic info
        $email['name'] = $valid[$fb]['name'];
        $email['email'] = $valid[$fb]['email'];
        // Program Information
        $email['program'] = $db['program']['a'];
        // Observing Dates
        $email['start_date'] = IrtfUtilities::returnTextDate($valid[$fb]['start_date'], 'M d, Y');
        $email['end_date'] = IrtfUtilities::returnTextDate($valid[$fb]['end_date'], 'M d, Y');
        // Support Staff
        $email[$sp] = $this->returnSelectionText($form['support_staff'] ?? [], $db['support']);
        // Telescope Operators
        $email[$op] = $this->returnSelectionText($form['operator_staff'] ?? [], $db['operator']);
        // Instruments
        $instruments = $this->transformInstruments(
            $form['instruments'],
            $form['visitor_instrument'],
            $db['facility'],
            $db['visitor']
        );
        $email[$in] = $this->returnSelectionText(
            $instruments['form'],
            $instruments['db']
        );
        // Technical Feedback
        $email['location'] = $this->returnLocationText($valid[$fb]['location']);
        $email['technical_rating'] = $this->returnRatingText($valid[$fb]['technical_rating']);
        $email['technical_comments'] = $valid[$fb]['technical_comments'];
        // Personnel Feedback
        $email['scientific_staff_rating'] = $this->returnRatingText($valid[$fb]['scientific_staff_rating']);
        $email['TO_rating'] = $this->returnRatingText($valid[$fb]['TO_rating']);
        $email['daycrew_rating'] = $this->returnRatingText($valid[$fb]['daycrew_rating']);
        $email['personnel_comment'] = $valid[$fb]['personnel_comment'];
        // Scientific Results
        $email['scientific_results'] = $valid[$fb]['scientific_results'];
        // Suggestions
        $email['suggestions'] = $valid[$fb]['suggestions'];
        return $email;
    }
}
