<?php

declare(strict_types=1);

namespace App\services\database\feedback;

use App\exceptions\DatabaseException;
use App\services\database\DBConnection;
use App\core\common\AbstractDebug         as Debug;
use App\services\database\DatabaseService as BaseService;
use App\services\database\feedback\write\FeedbackService as   FeedbackServiceWrite;
use App\services\database\feedback\write\InstrumentService as InstrumentServiceWrite;
use App\services\database\feedback\write\OperatorService as   OperatorServiceWrite;
use App\services\database\feedback\write\SupportService as    SupportServiceWrite;

/**
 * FeedbackService class that provides core functionality for all feedback services.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackService extends BaseService
{
    private $feedbackWrite;
    private $instrumentWrite;
    private $operatorWrite;
    private $supportWrite;

    public function __construct(
        bool $debugMode = false,
        ?FeedbackServiceWrite $feedbackWrite = null,
        ?InstrumentServiceWrite $instrumentWrite = null,
        ?OperatorServiceWrite $operatorWrite = null,
        ?SupportServiceWrite $supportWrite = null,
        ?DBConnection $db = null,
        ?Debug $debug = null
    ) {
        parent::__construct('feedback', $debugMode, $db, $debug);
        $this->feedbackWrite = $feedbackWrite;
        $this->instrumentWrite = $instrumentWrite;
        $this->operatorWrite = $operatorWrite;
        $this->supportWrite = $supportWrite;
    }

    public function insertFeedbackWithDependencies(
        array $feedback,
        array $instruments,
        array $operators,
        array $supportAstronomers
    ): bool {
        $this->db->beginTransaction();

        try {
            // Ensure main feedback service is present
            if (!$this->feedbackWrite) {
                $this->debug->failDatabase('FeedbackServiceWrite is required for insert operations.');
            }

            // Insert main feedback record
            $this->feedbackWrite->insertFeedbackRecord($feedback);
            $feedbackId = $this->feedbackWrite->returnFeedbackRecordId();

            // Insert related instruments, operators, and support staff using the feedback ID
            if ($this->instrumentWrite) {
                foreach ($instruments as $hardwareID) {
                    $this->instrumentWrite->insertInstrumentRecord($feedbackId, $hardwareID);
                }
            }

            if ($this->operatorWrite) {
                foreach ($operators as $operatorID) {
                    $this->operatorWrite->insertOperatorRecord($feedbackId, $operatorID);
                }
            }

            if ($this->supportWrite) {
                foreach ($supportAstronomers as $supportID) {
                    $this->supportWrite->insertSupportAstronomerRecord($feedbackId, $supportID);
                }
            }

            // Commit if all inserts succeed
            $this->db->commit();
            return true;
        } catch (DatabaseException $e) {
            // Rollback on any failure
            $this->db->rollback();
            $this->debug->failDatabase("Transaction failed: " . $e->getMessage());
            return false;
        }
    }
}
