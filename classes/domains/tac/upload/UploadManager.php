<?php

declare(strict_types=1);

namespace App\domains\tac\upload;

use Exception;
use App\core\common\CustomDebug          as Debug;
use App\domains\tac\upload\UploadResults as Results;

/**
 * /home/webdev2024/classes/domains/tac/upload/UploadManager.php
 *
 * A single entry point for all tac-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class UploadManager
{
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

    public function handleUpload(
        array $uploadData = [],
        ?string $request = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "processUpload");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        try {
            // instantiate upload manager
            $uploader = $uploader ?? new Results($this->debug);
            // Pass the uploaded file information to the tac upload manager
            return $uploader->handleUploadingTACResults($uploadData);
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac results: " . $e->getMessage());
        }
    }
}
