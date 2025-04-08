<?php

declare(strict_types=1);

namespace App\models\proposals;

use Exception;
use App\core\common\CustomDebug as Debug;

/**
 * Model for handling the List Application Pdfs logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class DownloadApplicationPdfsModel
{
    private $debug;
    private $pdfpath;

    public function __construct(
        string $pdfpath,
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($pdfpath, "{$debugHeading} -- pdfpath");

        // Store the file path
        $this->pdfpath = $pdfpath ?? '';

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    public function resolveDownloadPath(
        array $tokenData
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "resolveDownloadPath");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($tokenData, "{$debugHeading} -- tokenData");

        // Return reconstructed file path
        return sprintf(
            '%s/%s/%s',
            $this->pdfpath,
            $tokenData['code'],
            $tokenData['file']
        );
    }

    public function verifyRequestedFile(
        string $filepath
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "verifyRequestedFile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($filepath, "{$debugHeading} -- filepath");

        // Verify requested file name makes sense
        $filename = basename($filepath);
        $this->debug->debugVariable($filename, "{$debugHeading} -- filename");
        if (!preg_match('/^[\w\-\.]+\.pdf$/', $filename)) {
            throw new Exception("Invalid filename format.");
        }

        // Ensure reconstructed path is going where it should
        $realRoot = realpath($this->pdfpath);
        $realFile = realpath($filepath);
        $this->debug->debugVariable($realRoot, "{$debugHeading} -- realRoot");
        $this->debug->debugVariable($realFile, "{$debugHeading} -- realFile");
        if (!$realFile || strpos($realFile, $realRoot) !== 0) {
            throw new Exception("Requested file path is invalid.");
        }

        // Ensure requested file exists
        if (!file_exists($filepath)) {
            throw new Exception("Requested file does not exist.");
        }
    }

    public function serveRequestedFile(
        string $filepath
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "serveRequestedFile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($filepath, "{$debugHeading} -- filepath");

        /*
         * IMPORTANT!
         *
         * If you need to debug this form, the code below _will render as html_
         * the contents of the file. That is perfectly normal: the headers are
         * not being received _first_ so the file's stream is being interpreted
         * as part of the page. As soon as the problem you're debugging is resolved
         * and you set debug to false again, the stream and the download will function
         * as intended.
         */

        // Ship it!
        $filename = basename($filepath);

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}
