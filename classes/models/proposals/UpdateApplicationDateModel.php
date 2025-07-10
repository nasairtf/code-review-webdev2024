<?php

declare(strict_types=1);

namespace App\models\proposals;

use App\core\common\AbstractDebug                        as Debug;
use App\models\BaseModel;
use App\services\database\troublelog\read\ObsAppService  as DbRead;
use App\services\database\troublelog\write\ObsAppService as DbWrite;

/**
 * Model for updating the application date.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UpdateApplicationDateModel extends BaseModel
{
    private $dbRead;
    private $dbWrite;

    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null,
        ?DbWrite $dbWrite = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Initialise the additional classes needed by this model
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        $this->dbWrite = $dbWrite ?? new DbWrite($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Service classes successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    // Abstract method: initializeDefaultData()

    public function initializeDefaultData(?array $data = null): array
    {
        // stub
        return [];
    }

    /**
     * Query methods that interface directly with DB Class
     *
     * fetchSemesterData - retrieves the semester data
     * fetchProposalData - retrieves the proposal data
     * updateProposal    - updates the proposal
     */

    public function fetchSemesterData(
        int $year,
        string $semester
    ): array {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Model: fetchSemesterData()");

        return $this->dbRead->fetchSemesterProposalListingFormData($year, $semester);
    }

    public function fetchProposalData(
        int $obsAppId
    ): array {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Model: fetchProposalData()");

        return $this->dbRead->fetchProposalListingFormData($obsAppId);
    }

    public function updateProposal(
        int $obsAppId,
        int $timestamp
    ): string {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Model: updateProposal()");

        if ($this->verifyExistingTimestamp($obsAppId, $timestamp)) {
            return "No changes made. The timestamp is already up to date.";
        }
        $rowsAffected = $this->dbWrite->modifyProposalCreationDate($obsAppId, $timestamp);
        return "Successfully updated timestamp.";
    }

    /**
     * Helper methods to verify the query results
     *
     * verifyExistingTimestamp     - ensure the timestamp needs to be updated
     */

    private function verifyExistingTimestamp(
        int $obsAppId,
        int $timestamp
    ): bool {
        // Debug output
        $this->debug->debug("UpdateApplicationDate Model: verifyExistingTimestamp()");

        $currentData = $this->fetchProposalData($obsAppId);
        $currentCreationDate = (int)$currentData[0]['creationDate'];
        $this->debug->debug("Current creationDate: {$currentCreationDate}");
        $this->debug->debug("New creationDate (timestamp): {$timestamp}");
        return ($currentCreationDate === $timestamp);
    }
}
