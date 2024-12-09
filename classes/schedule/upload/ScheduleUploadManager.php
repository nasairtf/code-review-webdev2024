<?php

declare(strict_types=1);

namespace App\schedule\upload;

use Exception;
use App\core\common\Debug;
use App\services\files\FileParser as Parser;
use App\schedule\common\ScheduleUtility;
use App\schedule\upload\ScheduleProcessor as Processor;
use App\schedule\upload\ScheduleIngester as Ingester;

/**
 * /home/webdev2024/classes/schedule/upload/ScheduleUploadManager.php
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleUploadManager
{
    private $debug;
    private $parser;
    private $processor;
    private $ingester;

    /**
     * Constructor for the ScheduleUploadManager class.
     */
    public function __construct(
        ?Debug $debug = null,
        ?Parser $parser = null,
        ?Processor $processor = null,
        ?Ingester $ingester = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this manager
        $this->parser = $parser ?? new Parser('schedule', null, $this->debug->isDebugMode());
        $this->processor = $processor ?? new Processor($this->debug);
        $this->ingester = $ingester ?? new Ingester($this->debug);
        $this->debug->log("{$debugHeading} -- Parser, Processor, Ingester classes successfully initialised.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Manager initialisation complete.");
    }

    public function handleSchedule(array $scheduleData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");
        // Step 1: parse the csv file
        $fileLoad = $scheduleData['fileload'];
        try {
            // Pass the CSV file information to the parser
            $scheduleData['csv'] = $this->parser->parseFile($scheduleData['file']);
            $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");
        } catch (Exception $e) {
            // Rethrow any errors generated during the parsing phase
            $this->debug->fail("Error parsing the uploaded file: " . $e->getMessage());
        }
        // Step 2: process the schedule data for ingestion
        try {
            // Pass the file array to the processer
            $processedSchedule = $this->processor->processSchedule($scheduleData);
            $this->debug->debugVariable($processedSchedule, "{$debugHeading} -- processedSchedule");
        } catch (Exception $e) {
            // Rethrow any errors generated during the processing phase
            $this->debug->fail("Error processing the schedule data: " . $e->getMessage());
        }
        // Step 3: ingest the schedule to the database
        try {
            // Pass the prepared data to the ingester
            $result = $this->ingester->ingestSchedule($processedSchedule);
            $this->debug->debugVariable($result, "{$debugHeading} -- result");
        } catch (Exception $e) {
            // Rethrow any errors generated during the ingesting phase
            $this->debug->fail("Error ingesting the schedule data: " . $e->getMessage());
        }
        // Step 4: return the result message.
        return $result;
    }
}
