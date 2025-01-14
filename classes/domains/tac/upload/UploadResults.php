<?php

declare(strict_types=1);

namespace App\domains\tac\upload;

use Exception;
use App\core\common\CustomDebug as Debug;
use App\legacy\traits\LegacyTacUploadUploadResultsTrait;

/**
 * /home/webdev2024/classes/domains/tac/upload/UploadResults.php
 *
 * A single entry point for all tac-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadResults
{
    use LegacyTacUploadUploadResultsTrait;

    private $debug;

    /**
     * Constructor for the UploadManager class.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Upload Manager initialisation complete.");
    }

    public function handleUploadingTACResults(
        array $uploadData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadingTACResults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        $break = [' '];
        try {
            // Process the uploaded files
            $resultSS = $this->processTACResultsTxt(
                $this->debug->isDebugMode(),
                '',
                $uploadData['year'],
                $uploadData['semester'],
                'ss',
                $uploadData['filess']
            );
            $resultNSS = $this->processTACResultsTxt(
                $this->debug->isDebugMode(),
                '',
                $uploadData['year'],
                $uploadData['semester'],
                'nss',
                $uploadData['filenss']
            );
            $results = array_merge($resultSS, $break, $resultNSS);
            return $results;
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac results: " . $e->getMessage());
        }
    }

    public function handleUploadingTACFilemaker(
        array $uploadData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadingTACFilemaker");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        $break = [' '];
        try {
            // Process the uploaded file
            $results = $this->processTACResultsFMP(
                $this->debug->isDebugMode(),
                '',
                $uploadData['file']
            );
            return $results;
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac results filemaker: " . $e->getMessage());
        }
    }

    public function handleUploadingTACComments(
        array $uploadData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadingTACComments");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        $break = [' '];
        try {
            // Process the uploaded files
            $resultSS = $this->processTACResultsComments(
                $this->debug->isDebugMode(),
                '',
                $uploadData['year'],
                $uploadData['semester'],
                'ss',
                $uploadData['filess']
            );
            $resultNSS = $this->processTACResultsComments(
                $this->debug->isDebugMode(),
                '',
                $uploadData['year'],
                $uploadData['semester'],
                'nss',
                $uploadData['filenss']
            );
            $results = array_merge($resultSS, $break, $resultNSS);
            return $results;
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac comments: " . $e->getMessage());
        }
    }
}
