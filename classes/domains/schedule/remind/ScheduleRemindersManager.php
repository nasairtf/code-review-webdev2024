<?php

declare(strict_types=1);

namespace App\domains\schedule\remind;

use Exception;
use App\core\common\CustomDebug                   as Debug;
use App\domains\schedule\remind\ObservingReminder as ObsReminder;
use App\domains\schedule\remind\FeedbackReminder  as FeedReminder;

/**
 * /home/webdev2024/classes/domains/schedule/remind/ScheduleRemindersManager.php
 *
 * A single entry point for all schedule-reminder-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleRemindersManager
{
    private $debug;

    /**
     * Constructor for the ScheduleRemindersManager class.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Schedule Reminders Manager initialisation complete.");
    }

    public function handleReminders(
        array $reminderData = [],
        ?string $request = null
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleReminders");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($reminderData, "{$debugHeading} -- reminderData");
        $this->debug->debugVariable($request, "{$debugHeading} -- request");

        try {
            // Pass the exported file information to the tac export manager
            switch ($request) {
                case 'feedback':
                    // instantiate feedback reminder handler
                    $reminder = $reminder ?? new FeedReminder($this->debug);
                    // handle feedback reminder request;
                    return $reminder->handleFeedbackReminders($reminderData);
                    break;
                case 'observing':
                    // instantiate observing reminder handler
                    $reminder = $reminder ?? new ObsReminder($this->debug);
                    // handle observing reminder request;
                    return $reminder->handleObservingReminders($reminderData);
                    break;
            }
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac export
            $this->debug->fail("Error generating the reminders: " . $e->getMessage());
        }
    }
}
