<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\CustomDebug;

use App\controllers\ishell\TemperaturesController as Controller;

/**
 * /home/webdev2024/public_html/ishell/spectrograph_controller.php
 *
 * @category iSHELL
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// Enable debug mode for the entire page
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = new CustomDebug('default', $debugMode, $debugLevel);

// Output the start of debug logging
$debug->log("Spectrograph: Entry point debug|log mode is ON.");

// Initialize the spectrograph controller
$controller = new Controller('spectrograph', $debug);

// Process and render the spectrograph temperature display.
$controller->handleRequest();
