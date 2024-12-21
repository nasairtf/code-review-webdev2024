<?php

declare(strict_types=1);

namespace App\services\database\feedback\write;

use App\services\database\feedback\FeedbackService as BaseService;

/**
 * FeedbackService handles write operations for Feedback entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackService extends BaseService
{
    /**
     * Retrieve the Feedback ID value for use in the other queries
     */

    public function returnFeedbackRecordId(): int
    {
        return $this->db->getLastInsertId();
    }
    /**
     * Query methods that interface directly with DB Class
     */

    public function insertFeedbackRecord(array $data): int
    {
        return $this->modifyDataWithQuery(
            $this->getFeedbackInsertQuery(),
            $this->getFeedbackInsertParams($data),
            $this->getFeedbackInsertTypes(),
            1,
            'Feedback insert failed.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    protected function getFeedbackInsertQuery(): string
    {
        return "INSERT INTO feedback "
            . "("
            .    "start_date, end_date, technical_rating, "
            .    "technical_comments, scientific_staff_rating, "
            .    "TO_rating, daycrew_rating, personnel_comment, "
            .    "scientific_results, suggestions, "
            .    "name, email, location, programID, semesterID"
            . ") "
            . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    /**
     * Helper methods to return the params arrays
     */

    protected function getFeedbackInsertParams(array $data): array
    {
        return [
            $data['start_date'], $data['end_date'], $data['technical_rating'],
            $data['technical_comments'], $data['scientific_staff_rating'],
            $data['TO_rating'], $data['daycrew_rating'], $data['personnel_comment'],
            $data['scientific_results'], $data['suggestions'],
            $data['name'], $data['email'], $data['location'], $data['programID'], $data['semesterID']
        ];
    }

    /**
     * Helper methods to return the types strings
     */

    protected function getFeedbackInsertTypes(): string
    {
        return 'iiisiiisssssiis';
    }
}
