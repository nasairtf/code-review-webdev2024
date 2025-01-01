<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug                   as Debug;
use App\controllers\ishell\TemperaturesController as Controller;

/**
 * /home/webdev2024/public_html/ishell/guider_controller.php
 *
 * The iSHELL guider temperature controller page.
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
$debug->log("Guider: Entry point debug|log mode is ON.");

// Initialize the guider controller
$controller = new Controller('guider', $debug); // entry point

// Process and render the guider temperature display.
$controller->handleRequest();
