<?php

declare(strict_types=1);

namespace App\domains\schedule;

use Exception;
use App\core\common\CustomDebug                          as Debug;
use App\domains\schedule\build\ScheduleBuildManager      as Builder;
use App\domains\schedule\upload\ScheduleUploadManager    as Uploader;
use App\domains\schedule\remind\ScheduleRemindersManager as Reminder;

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
        $this->debug->debug("{$debugHeading} -- Schedule Manager initialisation complete.");
    }

    /**
     * Handles incoming requests.
     *
     * @return void
     */
    public function handleRequest(
        array $requestData,
        ?string $request = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($requestData, "{$debugHeading} -- requestData");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        // Identify the request type
        $request = $request ?? 'observing';

        // Handle the request
        switch ($request) {
            case 'upload':
                // handle tac scores/allocation upload request;
                return $this->uploadSchedule($requestData);
                break;

            case 'build':
                // handle tac comments upload request;
                return $this->buildSchedule($requestData);
                break;

            case 'feedback':
            case 'observing':
            default:
                // handle observing reminders request;
                return $this->handleReminders($requestData, $request);
                break;
        }
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
    protected function uploadSchedule(
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
    protected function buildSchedule(
        array $scheduleData,
        ?Builder $builder = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "buildSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");

        try {
            // instantiate build manager
            $builder = $builder ?? new Builder($this->debug);
            // Pass the schedule information to the schedule build manager
            return $builder->handleSchedule($scheduleData);
        } catch (Exception $e) {
            // Rethrow any errors generated during the schedule generation
            $this->debug->fail("Error generating the schedule: " . $e->getMessage());
        }
    }

    /**
     * Handle schedule reminder tasks.
     *
     * @param array $scheduleData Data related to the schedule generation.
     *
     * @return array The generated schedule.
     *
     * @throws Exception If an error occurs during the generation process.
     */
    protected function handleReminders(
        array $reminderData,
        ?string $request = null,
        ?Reminder $reminder = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleReminders");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($reminderData, "{$debugHeading} -- reminderData");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        try {
            // instantiate reminder manager
            $reminder = $reminder ?? new Reminder($this->debug);
            // Pass the reminder information to the observing reminder generation manager
            return $reminder->handleReminders($reminderData, $request);
        } catch (Exception $e) {
            // Rethrow any errors generated during the observing reminder generation
            $this->debug->fail("Error generating the reminder emails: " . $e->getMessage());
        }
    }
}
