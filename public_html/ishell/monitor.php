<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug                   as Debug;
use App\controllers\ishell\TemperaturesController as Controller;

/**
 * /home/webdev2024/public_html/ishell/monitor.php
 *
 * The iSHELL monitor temperature controller page.
 *
 * @category iSHELL
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire application
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode ?? false, $debugLevel); // entry point

// Enable html formatting for the entire page
//$formatHtml = $debugMode;

// Log the start of module operation
$debug->log("Monitor: Entry point debug|log mode is ON.");

// Initialize the monitor controller
$controller = new Controller('monitor', $debug); // entry point

// Process and render the monitor temperature display.
$controller->handleRequest();
