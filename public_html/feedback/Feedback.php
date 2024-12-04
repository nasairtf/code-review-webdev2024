<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\Debug;

use App\controllers\feedback\FeedbackController as Controller;

/**
 * /home/webdev2024/public_html/Feedback.php
 *
 * The observers enter feedback for the observing run they have just completed.
 * The information from the form is emailed to the director, who forwards it to
 * the appropriate people to handle any issues that may have arisen.
 *
 * Created:
 *  2006/04/19 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2006/11/09 - Miranda Hawarden-Ogata
 *      - Moved from /userSupport to /observing.
 *      - Changed appearance to match homepage style.
 *  2007/01/23 - Miranda Hawarden-Ogata
 *      - changed calls to standard header and footer function to use new format
 *  2010/11/03 - Miranda Hawarden-Ogata
 *      - split file into helper, html_form, and process_data inc files.
 *  2024/10/14 - Miranda Hawarden-Ogata
 *      - Refactored into MVC OO methodology.
 *  2024/10/21 - Miranda Hawarden-Ogata
 *      - Set up composer and autoloading.
 *
 * @category Feedback
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// define legacy constant
define('CONTACT', "hawarden at hawaii dot edu");

// Enable debug mode for the entire page
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode ?? false, $debugLevel); // entry point

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Output the start of debug logging
$debug->log("Feedback: Entry point debug|log mode is ON.");

// Start the session for the feedback form
if (session_status() === PHP_SESSION_NONE) {
    $debug->log("Feedback: Starting the session.");
    session_start();
}
// Output the SESSION values
$debug->debugVariable($_SESSION, "_SESSION");

// Initialize the feedback form controller
$controller = new Controller($formatHtml, $debug); // entry point

// Process and render the feedback form.
$controller->handleRequest();
