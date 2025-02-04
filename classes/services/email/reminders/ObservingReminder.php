<?php

declare(strict_types=1);

namespace App\services\email\reminders;

use App\core\irtf\IrtfUtilities;
use App\services\email\EmailService as BaseService;

/**
 * ObservingReminder class that provides email for the cron-generated observing reminder email.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObservingReminder extends BaseService
{
    public function __construct(bool $debugMode = false)
    {
        parent::__construct('leavitt_relay', $debugMode);
    }

    public function prepareReminderEmail(array $data): self
    {
        $irtfEmails = $this->irtfEmails();
        $this->setFromAddressFromArray($irtfEmails)
             ->setReplyTos($irtfEmails)
             ->setRecipients($irtfEmails)
             ->setRecipient($data['email'], $data['name'])
             ->setSubject("IRTF Observing Reminder ({$data['program']} {$data['name']})")
             ->setBody($this->generateHtmlBody($data), $this->generateTextBody($data));
        return $this;
    }

    private function generateHtmlBody(array $data): string
    {
        // Build HTML body here
        return "
            <h1>IRTF Observing Reminder</h1>
            <table>
                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>IRTF Observing Reminder Email</h2></td></tr>
                <tr><td colspan='2'><hr /></td></tr>
            </table>
        ";
    }

    private function generateTextBody(array $data): string
    {
        // Build plain text body here
        $msgbody = "--------------------------------
IRTF Observing Reminder
--------------------------------
IRTF Observing Reminder Email
--------------------------------\n";

        return $msgbody;
    }

    private function irtfEmails(): array
    {
        $sysad = $this->contacts['sysad'];
        $sched = $this->contacts['scheduler'];
        $suppt = $this->contacts['support'];
        return $this->debug->isDebugMode()
            ? [
                $sysad['email1'] => $sysad['name'],
              ]
            : [
                $sysad['email1'] => $sysad['name'],
                $sched['email1'] => $sched['name'],
                $suppt['email1'] => $suppt['name'],
              ];
    }
}
