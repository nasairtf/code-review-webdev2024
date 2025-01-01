<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug                 as Debug;
use App\controllers\feedback\FeedbackController as Controller;

/**
 * /home/webdev2024/public_html/feedback/Feedback.php
 *
 * The Feedback Form.
 *
 * @category Feedback
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire application
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode, $debugLevel); // entry point

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Log the start of module operation
$debug->log("Feedback: Entry point debug|log mode is ON.");

// Initialize the feedback form controller
$controller = new Controller($formatHtml, $debug); // entry point

// Process and render the feedback form.
$controller->handleRequest();
