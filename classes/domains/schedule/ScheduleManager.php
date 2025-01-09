<?php

declare(strict_types=1);

namespace App\schedule;

use Exception;
use App\core\common\Debug;
use App\domains\schedule\build\ScheduleBuildManager   as Builder;
use App\domains\schedule\upload\ScheduleUploadManager as Uploader;

/**
 * /home/webdev2024/classes/domains/schedule/ScheduleManager.php
 *
 * A single entry point for all schedule-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleManager
{
    private $debug;

    /**
     * Constructor for the ScheduleManager class.
     */
    public function __construct(
        ?bool $debugMode = null
    ) {
        // Debug output
        $this->debug = new Debug('schedule', $debugMode ?? false, $debugMode ? 1 : 0); // base-level domain class
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Schedule Manager initialisation complete.");
    }

    /**
     * Handle schedule upload tasks.
     *
     * @param array $scheduleData Data related to the schedule upload.
     *
     * @return string The result of the upload process.
     *
     * @throws Exception If an error occurs during the upload process.
     */
    public function uploadSchedule(
        array $scheduleData,
        ?Uploader $uploader = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "uploadSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");

        try {
            // instantiate upload manager
            $uploader = $uploader ?? new Uploader($this->debug);
            // Pass the uploaded file information to the schedule upload manager
            return $uploader->handleSchedule($scheduleData);
        } catch (Exception $e) {
            // Rethrow any errors generated during the schedule upload
            $this->debug->fail("Error uploading the schedule: " . $e->getMessage());
        }
    }

    /**
     * Handle schedule generation tasks.
     *
     * @param array $scheduleData Data related to the schedule generation.
     *
     * @return array The generated schedule.
     *
     * @throws Exception If an error occurs during the generation process.
     */
    public function generateSchedule(
        array $scheduleData,
        ?Builder $builder = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "generateSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");

        try {
            // instantiate build manager
            $builder = $builder ?? new Builder($this->debug);
            // Pass the schedule information to the schedule generation manager
            return $builder->handleSchedule($scheduleData);
        } catch (Exception $e) {
            // Rethrow any errors generated during the schedule generation
            $this->debug->fail("Error generating the schedule: " . $e->getMessage());
        }
    }
}
