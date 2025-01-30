<?php

declare(strict_types=1);

namespace App\domains\schedule\remind;

use Exception;
use App\core\common\CustomDebug as Debug;
use App\legacy\traits\LegacyProcessObsRemindersTrait;

/**
 * /home/webdev2024/classes/domains/schedule/remind/ObservingReminder.php
 *
 * A single entry point for all schedule-reminder-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObservingReminder
{
    use LegacyProcessObsRemindersTrait;

    private $debug;

    /**
     * Constructor for the ObservingReminder class.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Reminder", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Observing Reminder initialisation complete.");
    }

    public function handleObservingReminders(
        array $reminderData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleObservingReminders");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($reminderData, "{$debugHeading} -- reminderData");

        try {
            // Process the reminders
            $results = $this->generateObsReminderEmailPage(
                $this->debug->isDebugMode(), // debug
                '',                          // page title
                $reminderData,               // reminder data
                false                        // sendemails
            );
            return [$results];
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac export
            $this->debug->fail("Error generating the observing reminders: " . $e->getMessage());
        }
    }
}
