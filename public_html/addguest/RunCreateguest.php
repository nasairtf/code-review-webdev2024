<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug                   as Debug;
use App\controllers\addguest\GuestAcctsController as Controller;

/**
 * /home/webdev2024/public_html/addguest/RunCreateguest.php
 *
 * The Createguest form used to queue the createguest command.
 *
 * @category Accounts
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire application
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Log the start of module operation
$debug->log("RunCreateguest: Entry point debug|log mode is ON.");

// Initialize the form controller
$controller = new Controller($formatHtml, $debug);

// Process and render the form.
$controller->handleRequest('createguest');
