<?php

declare(strict_types=1);

namespace App\domains\tac\export;

use Exception;
use App\core\common\CustomDebug as Debug;
use App\legacy\traits\LegacyTacExportExportResultsTrait;

/**
 * /home/webdev2024/classes/domains/tac/export/ExportResults.php
 *
 * A single entry point for all tac-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ExportResults
{
    use LegacyTacExportExportResultsTrait;

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

    public function handleExportingTACFilemaker(
        array $exportData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleExportingTACFilemaker");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($exportData, "{$debugHeading} -- exportData");

        $break = [' '];
        try {
            // Process the exported file
            $results = $this->processExportTACResults(
                $this->debug->isDebugMode(),
                $exportData['year'],
                $exportData['semester']
            );
            return $results;
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac export
            $this->debug->fail("Error exporting the tac results filemaker: " . $e->getMessage());
        }
    }
}
