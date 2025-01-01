<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug           as Debug;
use App\controllers\login\LogoutController as Controller;

/**
 * /home/webdev2024/public_html/login/Logout.php
 *
 * The Logout Form.
 *
 * @category Logout
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire page
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('login', $debugMode, $debugLevel); // entry point

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Log the start of logout module operation
$debug->log("Logout: Entry point debug|log mode is ON.");

// Initialize and process the logout controller
$controller = new Controller($formatHtml, $debug); // entry point

// Process and render the logout form based on request type.
$controller->handleRequest();
