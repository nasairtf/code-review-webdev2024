<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug;

use App\controllers\proposals\UpdateApplicationDateController as Controller;

/**
 * /home/webdev2024/public_html/proposals/UpdateApplicationDate.php
 *
 * Allows staff changing of proposals' date for a semester.
 *
 * Created:
 *  2024/10/11 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2024/10/20 - Miranda Hawarden-Ogata
 *      - Updated DebugUtility:: calls to use Debug class instance calls.
 *  2024/10/21 - Miranda Hawarden-Ogata
 *      - Set up composer and autoloading.
 *
 * @category Application
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire application
//$debugMode = isset($_GET['debug']) && $_GET['debug'] === 'true';  // Enable via a query param for example
//$debugLevel = $debugMode ? 1 : 0;  // Set debug level based on query param or default
//$debug = new Debug('default', $debugMode, $debugLevel);
//$debug = new Debug('default', true, 1);
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new CustomDebug('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
//$formatHtml = isset($_GET['formatHtml']) && $_GET['formatHtml'] === 'true';  // Optional flag for formatting
$formatHtml = false;

$debug->log("UpdateApplicationDate: Starting refactored code.");
$debug->log("UpdateApplicationDate: Entry point debug|log mode is ON.");
$controller = new Controller($formatHtml, $debug);
$controller->handleRequest();
