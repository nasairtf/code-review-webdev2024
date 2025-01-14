<?php

declare(strict_types=1);

namespace App\domains\tac\export;

use Exception;
use App\core\common\CustomDebug          as Debug;
use App\domains\tac\export\ExportResults as Results;

/**
 * /home/webdev2024/classes/domains/tac/export/ExportManager.php
 *
 * A single entry point for all tac-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ExportManager
{
    private $debug;

    /**
     * Constructor for the ExportManager class.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Export Manager initialisation complete.");
    }

    public function handleExport(
        array $exportData = [],
        ?string $request = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleExport");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($exportData, "{$debugHeading} -- exportData");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        try {
            // instantiate export manager
            $exporter = $exporter ?? new Results($this->debug);
            // Pass the exported file information to the tac export manager
            switch ($request) {
                case 'filemaker':
                    // handle tac filemaker export request;
                    return $exporter->handleExportingTACFilemaker($exportData);
                    break;
            }
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac export
            $this->debug->fail("Error exporting the tac results: " . $e->getMessage());
        }
    }
}
