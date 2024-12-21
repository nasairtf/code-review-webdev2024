<?php

declare(strict_types=1);

namespace Tests\classes\services\database\feedback\read;

use App\services\database\feedback\read\FeedbackService as BaseService;

class TestFeedbackService extends BaseService
{
    /**
     * Proxy method for testing the protected getProposalListingFormDataQuery method.
     *
     * @param bool $semester Determines a semester-specific or individual proposal query.
     *
     * @return string        The SQL query string.
     */
    public function getProposalListingFormDataQueryProxy(bool $semester): string
    {
        return $this->getProposalListingFormDataQuery($semester);
    }

    /**
     * Proxy method for testing the protected getProposalProgramDataQuery method.
     *
     * @return string The SQL query string.
     */
    public function getProposalProgramDataQueryProxy(): string
    {
        return $this->getProposalProgramDataQuery();
    }
}
