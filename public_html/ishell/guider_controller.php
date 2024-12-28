<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug;

use App\controllers\ishell\TemperaturesController as Controller;

/**
 * /home/webdev2024/public_html/ishell/guider_controller.php
 *
 * @category iSHELL
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire page
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new CustomDebug('default', $debugMode ?? false, $debugLevel); // entry point

// Output the start of debug logging
$debug->log("Guider: Entry point debug|log mode is ON.");

// Initialize the guider controller
$controller = new Controller('guider', $debug); // entry point

// Process and render the guider temperature display.
$controller->handleRequest();
