<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\DebugFactory;
use App\controllers\ishell\TemperaturesController as Controller;

/**
 * /home/webdev2024/public_html/ishell/spectrograph_controller.php
 *
 * The iSHELL spectrograph temperature controller page.
 *
 * @category iSHELL
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire application
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = DebugFactory::create('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
//$formatHtml = $debugMode;

// Output the start of debug logging
$debug->log("Spectrograph: Entry point debug|log mode is ON.");

// Initialize the spectrograph controller
$controller = new Controller('spectrograph', $debug);

// Process and render the spectrograph temperature display.
$controller->handleRequest();
