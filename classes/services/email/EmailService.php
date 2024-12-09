<?php

declare(strict_types=1);

namespace App\services\email;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\core\common\Debug;

/**
 * EmailService class that provides core functionality for all email services.
 *
 * Manages SMTP configurations and email sending functionalities using PHPMailer.
 * Allows setting recipients, subjects, bodies, and attachments.
 *
 * @category Services
 * @package  IRTF
 * @version  1.0.0
 */

class EmailService
{
    /**
     * @var Debug $debug Debug instance for logging and output.
     */
    protected $debug;

    /**
     * @var PHPMailer $mailer PHPMailer instance for email handling
     */
    protected $mailer;

    /**
     * Constructs a new EmailService instance.
     *
     * @param string       $smtpName  Key to select which email configuration to use.
     * @param bool|null    $debugMode Whether to enable debug mode (default: false).
     * @param PHPMailer|null $mailer  Optional PHPMailer instance for email handling.
     */
    public function __construct(
        string $smtpName,
        ?bool $debugMode = null,
        ?PHPMailer $mailer = null
    ) {
        // Debug output
        $this->debug = new Debug('email', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class
        $debugHeading = $this->debug->debugHeading("Service", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($smtpName, "{$debugHeading} -- smtpName");

        // Fetch the SMTP config from Config
        $config = Config::get('smtp_config', $smtpName);
        $this->debug->log("{$debugHeading} -- Config successfully fetched.");

        // Establish the SMTP setup
        $this->mailer = $mailer ?? new PHPMailer(true); // Enable exceptions for error handling
        $this->applyMailerDebug();                      // Set PHPMailer debug levels
        $this->applySmtpSettings($config);              // Apply the chosen SMTP configuration
        $this->debug->log("{$debugHeading} -- PHPMailer class successfully initialised.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- EmailService initialisation complete.");
    }

    /**
     * Applies SMTP settings to the PHPMailer instance.
     *
     * @param array $config Configuration array for the selected SMTP settings.
     */
    private function applySmtpSettings(array $config): void
    {
        if ($config['relay'] ?? false) {
            $this->mailer->isMail();                           // Use local sendmail for relay
        } else {
            $this->mailer->isSMTP();                           // Direct SMTP configuration
            $this->mailer->Host = $config['host'];             // Set the SMTP server
            $this->mailer->SMTPAuth = true;                    // Enable SMTP authentication
            $this->mailer->Username = $config['username'];     // SMTP username
            $this->mailer->Password = $config['password'];     // SMTP password
            $this->mailer->SMTPSecure = $config['encryption']; // Enable TLS encryption
            $this->mailer->Port = $config['port'];             // TCP port for TLS
        }
        // Set default sender email/name based on config
        $this->setFromAddress(
            $config['username'] ?? 'no-reply@ifa.hawaii.edu',
            $config['sendername'] ?? 'IRTF Support'
        );
        $this->mailer->isHTML(true);                           // Set email format to HTML
    }

    /**
     * Configures debugging levels for PHPMailer.
     */
    private function applyMailerDebug(): void
    {
        if ($this->debug->isDebugMode()) {
            // set the level of PHPMailer debug here
            $this->mailer->SMTPDebug = 2;
            //$this->mailer->SMTPDebug = 3;
            $this->mailer->Debugoutput = 'error_log';
        } else {
            // Disable PHPMailer debug
            $this->mailer->SMTPDebug = 0;
            $this->mailer->Debugoutput = null;
        }
    }

    /**
     * Sets the 'From' address for the email.
     *
     * @param string $email The email address to set as the 'From' address.
     * @param string $name  The name associated with the 'From' address.
     *
     * @return self
     */
    public function setFromAddress(string $email, string $name = 'IRTF Support'): self
    {
        $this->mailer->setFrom($email, $name);
        return $this;
    }

    /**
     * Sets the 'From' address using the first entry in an array of emails.
     *
     * @param array $emails Associative array of emails, with email as the key and name as the value.
     *
     * @return self
     */
    public function setFromAddressFromArray(array $emails): self
    {
        if (!empty($emails)) {
            $email = key($emails);
            $name = $emails[$email];
            $this->setFromAddress($email, $name);
        }
        return $this;
    }

    /**
     * Sets a single recipient for the email.
     *
     * @param string $email Email address of the recipient.
     * @param string $name  Name of the recipient.
     *
     * @return self
     */
    public function setRecipient(string $email, string $name = ''): self
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    /**
     * Sets multiple recipients for the email.
     *
     * @param array $recipients Associative array of recipients, with email as the key and name as the value.
     *
     * @return self
     */
    public function setRecipients(array $recipients): self
    {
        foreach ($recipients as $email => $name) {
            $this->mailer->addAddress($email, $name);
        }
        return $this;
    }

    /**
     * Sets the reply-to address for the email.
     *
     * @param string $email Reply-to email address.
     * @param string $name  Optional name for the reply-to address.
     *
     * @return self
     */
    public function setReplyTo(string $email, string $name = ''): self
    {
        $this->mailer->addReplyTo($email, $name);
        return $this;
    }

    /**
     * Sets multiple reply-to addresses for the email.
     *
     * @param array $replyTos Associative array of reply-to addresses, with email as the key and name as the value.
     *
     * @return self
     */
    public function setReplyTos(array $replyTos): self
    {
        foreach ($replyTos as $email => $name) {
            $this->mailer->addReplyTo($email, $name);
        }
        return $this;
    }

    /**
     * Sets the subject for the email.
     *
     * @param string $subject Subject line of the email.
     *
     * @return self
     */
    public function setSubject(string $subject): self
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    /**
     * Sets the HTML and plain text body for the email, with optional HTML minification.
     *
     * HTML is minified if $minify is true or if debug mode is disabled, providing
     * compact output for production emails. If in debug mode and $minify is false,
     * HTML will be preserved as-is.
     *
     * @param string $htmlBody HTML content for the email body.
     * @param string $textBody Optional plain text fallback content for the email body.
     * @param bool   $minify   Optional flag to minify the HTML content. Default is true.
     *
     * @return self
     */
    public function setBody(
        string $htmlBody,
        string $textBody = '',
        bool $minify = true
    ): self {
        $this->mailer->Body = ($minify || !$this->debug->isDebugMode())
            ? $this->minifyHtml($htmlBody)
            : $htmlBody;
        if ($textBody) {
            $this->mailer->AltBody = $textBody; // Fallback text version
        }
        return $this;
    }

    /**
     * Adds an attachment to the email.
     *
     * @param string $filePath File path of the attachment to add.
     *
     * @return self
     */
    public function addAttachment(string $filePath): self
    {
        $this->mailer->addAttachment($filePath);
        return $this;
    }

    /**
     * Sends the email with the current settings.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public function send(): bool
    {
        try {
            $success = $this->mailer->send();
            $this->debug->log('Email sent successfully.');
            return $success;
        } catch (PHPMailerException $e) {
            $this->debug->log("Error sending email: {$e->getMessage()}", 'red');
            return false;
        } catch (Exception $e) {
            $this->debug->log("Non-mailer error sending email: {$e->getMessage()}", 'red');
            return false;
        }
    }

    /**
     * Minifies the provided HTML by removing unnecessary whitespace, line breaks, and tabs.
     * If a third-party minifier is available, it will use that instead.
     *
     * @param string $html The HTML content to be minified.
     * @return string The minified HTML content.
     */
    public function minifyHtml(string $html): string
    {
        // Check if a third-party minifier is available
        if (class_exists('SomeHtmlMinifierLibrary')) {
            return (new \SomeHtmlMinifierLibrary())->minify($html);
        }

        // Fallback to internal regex-based minification
        return preg_replace(['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'], ['>', '<', '\\1'], $html);
    }
}
