<?php

declare(strict_types=1);

namespace App\domains\tac;

use Exception;
use App\core\common\CustomDebug          as Debug;
use App\domains\tac\upload\UploadManager as Uploader;
use App\domains\tac\export\ExportManager as Exporter;

/**
 * /home/webdev2024/classes/domains/tac/TACManager.php
 *
 * A single entry point for all tac-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class TACManager
{
    private $debug;

    /**
     * Constructor for the TACManager class.
     */
    public function __construct(
        ?bool $debugMode = null,
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', $debugMode ?? false, $debugMode ? 1 : 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- TAC Manager initialisation complete.");
    }

    /**
     * Handles incoming requests.
     *
     * @return void
     */
    public function handleRequest(
        array $requestData,
        ?bool $export = null,
        ?string $request = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($requestData, "{$debugHeading} -- requestData");
        $this->debug->debugVariable($export, "{$debugHeading} -- export");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        // Identify the request type
        $export = $export ?? true;
        $request = $request ?? 'filemaker';

        // Handle the request
        if (!$export) {
            // Upload request
            switch ($request) {
                case 'results':
                    // handle tac scores/allocation upload request;
                    return $this->handleUploadResults($requestData);
                    break;

                case 'comments':
                    // handle tac comments upload request;
                    return $this->handleUploadComments($requestData);
                    break;

                case 'filemaker':
                default:
                    // handle tac filemaker upload request;
                    return $this->handleUploadFilemaker($requestData);
                    break;
            }
        } else {
            // Export request
            switch ($request) {
                case 'filemaker':
                default:
                    // handle tac export request;
                    return $this->handleExportFilemaker($requestData);
                    break;
            }
        }
    }

    private function handleUploadResults(
        array $uploadData,
        ?Uploader $uploader = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadResults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        try {
            // instantiate upload manager
            $uploader = $uploader ?? new Uploader($this->debug);
            // Pass the uploaded file information to the tac upload manager
            return $uploader->handleUpload($uploadData, 'results');
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac results: " . $e->getMessage());
        }
    }

    private function handleUploadComments(
        array $uploadData,
        ?Uploader $uploader = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadComments");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        try {
            // instantiate upload manager
            $uploader = $uploader ?? new Uploader($this->debug);
            // Pass the uploaded file information to the tac upload manager
            return $uploader->handleUpload($uploadData, 'comments');
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac comments: " . $e->getMessage());
        }
    }

    private function handleUploadFilemaker(
        array $uploadData,
        ?Uploader $uploader = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadFilemaker");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($uploadData, "{$debugHeading} -- uploadData");

        try {
            // instantiate upload manager
            $uploader = $uploader ?? new Uploader($this->debug);
            // Pass the uploaded file information to the tac upload manager
            return $uploader->handleUpload($uploadData, 'filemaker');
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error uploading the tac filemaker: " . $e->getMessage());
        }
    }

    private function handleExportFilemaker(
        array $exportData,
        ?Exporter $exporter = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleUploadFilemaker");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($exportData, "{$debugHeading} -- exportData");

        try {
            // instantiate upload manager
            $exporter = $exporter ?? new Exporter($this->debug);
            // Pass the uploaded file information to the tac upload manager
            return $exporter->handleExport($exportData, 'filemaker');
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac upload
            $this->debug->fail("Error exporting the tac filemaker: " . $e->getMessage());
        }
    }
}
