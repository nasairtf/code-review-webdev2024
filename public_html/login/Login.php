<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug;
use App\controllers\login\LoginController as Controller;

/**
 * /home/webdev2024/public_html/Login.php
 *
 * Mocks up the Login Form.
 *
 * Created:
 *  2024/10/27 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2024/10/27 - Miranda Hawarden-Ogata
 *
 * @category Login
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire page
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = new CustomDebug('login', $debugMode, $debugLevel); // entry point

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Log the start of login module operation
$debug->log("Login: Entry point debug|log mode is ON.");

// Initialize and process the login controller
$controller = new Controller($formatHtml, $debug); // entry point

// Process and render the login form based on request type.
$controller->handleRequest();
