<?php

declare(strict_types=1);

namespace App\services\email\feedback;

use App\core\irtf\IrtfUtilities;
use App\services\email\EmailService as BaseService;

/**
 * FeedbackService class that provides email for the Feedback Form confirmation email.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackService extends BaseService
{
    public function __construct(bool $debugMode = false)
    {
        parent::__construct('leavitt_relay', $debugMode);
    }

    public function prepareFeedbackEmail(array $data): self
    {
        $irtfEmails = $this->irtfEmails();
        $this->setFromAddressFromArray($irtfEmails)
             ->setReplyTos($irtfEmails)
             ->setRecipients($irtfEmails)
             ->setRecipient($data['email'], $data['name'])
             ->setSubject("IRTF Feedback Form [{$data['program']}] ")
             ->setBody($this->generateHtmlBody($data), $this->generateTextBody($data));
        return $this;
    }

    private function generateHtmlBody(array $data): string
    {
        // Build HTML body here
        return "
            <h1>Feedback Form Submission</h1>
            <table>
                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>Program Information</h2></td></tr>
                <tr><td><strong>Respondent:</strong></td><td>" . IrtfUtilities::escape($data['name']) . "</td></tr>
                <tr><td><strong>Email:</strong></td><td>" . IrtfUtilities::escape($data['email']) . "</td></tr>
                <tr><td><strong>Program:</strong></td><td>{$data['program']}</td></tr>
                <tr><td><strong>Observing Dates:</strong></td><td>{$data['start_date']} - {$data['end_date']}</td></tr>
                <tr><td><strong>Support Astronomer(s):</strong></td><td>{$data['support']}</td></tr>
                <tr><td><strong>Telescope Operator(s):</strong></td><td>{$data['operators']}</td></tr>
                <tr><td><strong>Instrument(s):</strong></td><td>{$data['instruments']}</td></tr>

                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>Technical Feedback</h2></td></tr>
                <tr><td><strong>Observing Location:</strong></td><td>{$data['location']}</td></tr>
                <tr><td><strong>Experience:</strong></td><td>{$data['technical_rating']}</td></tr>
                <tr><td colspan='2'><strong>Comments:</strong></td></tr>
                <tr><td colspan='2'>" . nl2br(IrtfUtilities::escape($data['technical_comments'])) . "</td></tr>

                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>Personnel Feedback</h2></td></tr>
                <tr><td><strong>Scientific Staff:</strong></td><td>{$data['scientific_staff_rating']}</td></tr>
                <tr><td><strong>Telescope Operators:</strong></td><td>{$data['TO_rating']}</td></tr>
                <tr><td><strong>Daycrew:</strong></td><td>{$data['daycrew_rating']}</td></tr>
                <tr><td colspan='2'><strong>Personnel Comments:</strong></td></tr>
                <tr><td colspan='2'>" . nl2br(IrtfUtilities::escape($data['personnel_comment'])) . "</td></tr>

                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>Scientific Results</h2></td></tr>
                <tr><td colspan='2'>" . nl2br(IrtfUtilities::escape($data['scientific_results'])) . "</td></tr>

                <tr><td colspan='2'><hr /></td></tr>
                <tr><td colspan='2'><h2>Comments and Suggestions</h2></td></tr>
                <tr><td colspan='2'>" . nl2br(IrtfUtilities::escape($data['suggestions'])) . "</td></tr>
                <tr><td colspan='2'><hr /></td></tr>
            </table>
        ";
    }

    private function generateTextBody(array $data): string
    {
        // Build plain text body here
        $msgbody = "--------------------------------
Respondent: {$data['name']}
Email: {$data['email']}
Program: {$data['program']}
Observing Dates: {$data['start_date']} - {$data['end_date']}
Support Astronomer(s): {$data['support']}
Telescope Operator(s): {$data['operators']}
Instrument(s): {$data['instruments']}
--------------------------------
Technical Feedback
-- Observing Location: {$data['location']}
-- Overall experiene with the telescope and instrument(s): {$data['technical_rating']}
-- Technical Comments:

{$data['technical_comments']}
--------------------------------
Personnel Feedback
-- Scientific Staff: {$data['scientific_staff_rating']}
-- Telescope Operators: {$data['TO_rating']}
-- Daycrew: {$data['daycrew_rating']}
-- Personnel Comments:

{$data['personnel_comment']}
--------------------------------
Scientific Results:

{$data['scientific_results']}
--------------------------------
Comments and Suggestions:

{$data['suggestions']}
--------------------------------\n";

        return $msgbody;
    }

    private function irtfEmails(): array
    {
        return $this->debug->isDebugMode()
            ? [
                'hawarden@hawaii.edu' => 'Miranda Hawarden-Ogata',
              ]
            : [
                'jrayner@hawaii.edu' => 'John Rayner',
                'hawarden@hawaii.edu' => 'Miranda Hawarden-Ogata',
              ];
    }
}
