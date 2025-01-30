<?php

declare(strict_types=1);

namespace App\domains\schedule\upload;

use App\core\common\CustomDebug                     as Debug;
use App\domains\schedule\common\ScheduleUtility;
use App\domains\schedule\upload\ScheduleUploadModel as Model;

/**
 * /home/webdev2024/classes/domains/schedule/upload/ScheduleIngester.php
 *
 * @category Processor
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleIngester
{
    private $debug;
    private $model;

    /**
     * Constructor for the ScheduleIngester class.
     *
     * @param Debug|null $debug Optional Debug instance for logging and debugging.
     * @param Model|null $model Optional Model instance for data handling.
     */
    public function __construct(
        ?Debug $debug = null,
        ?Model $model = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Ingester", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this processor
        $this->model = $model ?? new Model($this->debug);
        $this->debug->debug("{$debugHeading} -- Model class successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Ingester initialisation complete.");
    }

    public function ingestSchedule(array $scheduleData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Ingester", "ingestSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");

        // Step 1: Determine whether doing INFILE or explicit SQL inserts
        $fileLoad = $scheduleData['fileload'];
        $deleteSQL = $scheduleData['delete'];
        $insertSQL = $fileLoad
            ? $scheduleData['files']
            : $scheduleData['sql'];
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        $this->debug->debugVariable($insertSQL, "{$debugHeading} -- insertSQL");

        // Step 2: Delete the necessary rows
        $deleteResults = $this->deleteScheduleRows($deleteSQL);
        $this->debug->debugVariable($deleteResults, "{$debugHeading} -- deleteResults");

        // Step 3: Insert the appropriate data
        $insertResults = $this->insertScheduleRows($insertSQL, $fileLoad);
        $this->debug->debugVariable($insertResults, "{$debugHeading} -- insertResults");

        // Step 4: Format the output
        $ingestResults = [];
        foreach ($deleteResults as $key => $value) {
            $ingestResults[] = $deleteResults[$key];
            $ingestResults[] = $insertResults[$key];
        }

        // Step 5: Return the results
        return $ingestResults;
    }

    private function deleteScheduleRows(array $deleteSQL): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Ingester", "deleteScheduleRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");

        // Send the delete SQL to the model
        $deleteResults = [
            'schedule' => $this->model->deleteScheduleRows($deleteSQL['schedule']),
            'instrument' => $this->model->deleteInstrumentRows($deleteSQL['instrument']),
            'operator' => $this->model->deleteOperatorRows($deleteSQL['operator']),
            'program' => $this->model->deleteProgramRows($deleteSQL['program']),
            'engprogram' => $this->model->deleteEngProgramRows($deleteSQL['engprogram']),
        ];
        $this->debug->debugVariable($deleteResults, "{$debugHeading} -- deleteResults");

        // Return result message
        return $deleteResults;
    }

    private function insertScheduleRows(array $insertSQL, bool $fileLoad): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Ingester", "insertScheduleRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($insertSQL, "{$debugHeading} -- insertSQL");
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");

        // Send the delete SQL to the model
        $insertResults = $fileLoad
            ? $this->ingestInfile($insertSQL)
            : $this->ingestSQL($insertSQL);
        $this->debug->debugVariable($insertResults, "{$debugHeading} -- insertResults");

        // Return result message
        return $insertResults;
    }

    private function ingestInfile(array $infile): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Ingester", "ingestInfile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");

        // Collect file statistics and SQL
        $fileStats = [];
        $insertSQL = [];
        foreach ($infile as $key => $values) {
            $insertSQL[$key] = $values['sql'];
            $filePath = $values['file'] ?? null;
            if ($filePath && file_exists($filePath)) {
                $fileStats[$key] = $this->getFileStats($filePath);
            } else {
                $fileStats[$key] = "File not found: {$filePath}";
            }
        }
        $this->debug->debugVariable($fileStats, "{$debugHeading} -- fileStats");
        $this->debug->debugVariable($insertSQL, "{$debugHeading} -- insertSQL");

        // Execute the database load
        $insertResults = [
            'schedule' => $this->model->insertScheduleRows($insertSQL['schedule']),
            'instrument' => $this->model->insertInstrumentRows($insertSQL['instrument']),
            'operator' => $this->model->insertOperatorRows($insertSQL['operator']),
            'program' => $this->model->insertProgramRows($insertSQL['program']),
            'engprogram' => $this->model->insertEngProgramRows($insertSQL['engprogram']),
        ];

        // Return result message
        return $insertResults;
    }

    private function ingestSQL(array $sql): array
    {
        // explicit SQL upload
        // Return result message
        $results = [];
        return $results;
    }

    /**
     * Gather statistics for a given file.
     *
     * @param string $filePath
     * @return array|string
     */
    private function getFileStats(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return "File not found";
        }

        $stat = stat($filePath);

        return [
            'file'      => $filePath,
            'size'      => filesize($filePath), // File size in bytes
            'modified'  => date('Y-m-d H:i:s', $stat['mtime']), // Last modified time
            'created'   => date('Y-m-d H:i:s', $stat['ctime']), // Creation time
            'owner'     => $stat['uid'], // File owner (UID)
            'group'     => $stat['gid'], // File group (GID)
            'permissions' => substr(sprintf('%o', fileperms($filePath)), -4) // Permissions
        ];
    }
}
