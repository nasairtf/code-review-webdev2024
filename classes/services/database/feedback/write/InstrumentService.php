<?php

declare(strict_types=1);

namespace App\services\database\feedback\write;

use App\services\database\feedback\FeedbackService as BaseService;

/**
 * InstrumentService handles write operations for Instrument entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class InstrumentService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     */

    public function insertInstrumentRecord(int $feedbackId, string $instrumentId): int
    {
        return $this->modifyDataWithQuery(
            $this->getInstrumentInsertQuery(),
            [$feedbackId, $instrumentId],
            'is',
            1,
            'Instrument insert failed.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    private function getInstrumentInsertQuery(): string
    {
        return "INSERT INTO instrument (feedback_id, hardwareID) VALUES (?, ?)";
    }
}
