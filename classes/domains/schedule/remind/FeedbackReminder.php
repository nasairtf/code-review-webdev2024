<?php

declare(strict_types=1);

namespace App\domains\schedule\remind;

use Exception;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug as Debug;
use App\legacy\traits\LegacyProcessFeedRemindersTrait;

/**
 * /home/webdev2024/classes/domains/schedule/remind/FeedbackReminder.php
 *
 * A single entry point for all schedule-reminder-related tasks.
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackReminder
{
    use LegacyProcessFeedRemindersTrait;

    private $debug;

    /**
     * Constructor for the FeedbackReminder class.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Reminder", "__construct");
        $this->debug->debug($debugHeading);

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Feedback Reminder initialisation complete.");
    }

    public function handleFeedbackReminders(
        array $reminderData = []
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "handleFeedbackReminders");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($reminderData, "{$debugHeading} -- reminderData");

        try {
            // Process the reminders
            $results = $this->generateFeedReminderEmailPage(
                $this->debug->isDebugMode(), // debug
                '',                          // page title
                $reminderData,               // reminder data
                false                        // sendemails
            );
            return [$results];
        } catch (Exception $e) {
            // Rethrow any errors generated during the tac export
            $this->debug->fail("Error generating the feedback reminders: " . $e->getMessage());
        }
    }
}
