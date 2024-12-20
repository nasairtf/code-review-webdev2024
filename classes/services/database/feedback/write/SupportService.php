<?php

declare(strict_types=1);

namespace App\services\database\feedback\write;

use App\services\database\feedback\FeedbackService as BaseService;

/**
 * SupportService handles write operations for Support entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class SupportService extends BaseService
{
    /**
     * Query methods that interface directly with DB Class
     */

    public function insertSupportAstronomerRecord(int $feedbackId, string $supportId): int
    {
        return $this->modifyDataWithQuery(
            $this->getSupportAstronomerInsertQuery(),
            [$feedbackId, $supportId],
            'is',
            1,
            'Support astronomer insert failed.'
        );
    }

    /**
     * Helper methods to return the query strings
     */

    protected function getSupportAstronomerInsertQuery(): string
    {
        return "INSERT INTO support (feedback_id, supportID) VALUES (?, ?)";
    }
}
