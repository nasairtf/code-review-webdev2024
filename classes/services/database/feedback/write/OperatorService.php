<?php

namespace App\services\database\feedback\write;

use App\services\database\feedback\FeedbackService as BaseService;

/**
 * OperatorService handles write operations for Operator entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class OperatorService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     */

    public function insertOperatorRecord(int $feedbackId, string $operatorId): int
    {
        return $this->modifyDataWithQuery(
            $this->getOperatorInsertQuery(),
            [$feedbackId, $operatorId],
            'is',
            1,
            'Telescope operator insert failed.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    private function getOperatorInsertQuery(): string
    {
        return "INSERT INTO operator (feedback_id, operatorID) VALUES (?, ?)";
    }
}
