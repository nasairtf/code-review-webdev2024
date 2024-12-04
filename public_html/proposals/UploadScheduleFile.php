<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\Debug;

use App\controllers\proposals\UploadScheduleFileController as Controller;

/**
 * /home/webdev2024/public_html/proposals/UploadScheduleFile.php
 *
 * Uploads the schedule CSV file, parses it, and loads it to the database.
 *
 * Created:
 *  2014/04/25 - Miranda Hawarden-Ogata
 *
 * Modified:
 *   2014/07/25 - Miranda Hawarden-Ogata
 *      - Fixed comment bug;
 *   2015/02/08 - Miranda Hawarden-Ogata
 *      - Moved to /home/proposal
 *  2024/11/19 - Miranda Hawarden-Ogata
 *      - Refactoring to MVC OOP.
 *
 * @category Schedule
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// define legacy constant
define('CONTACT', "hawarden at hawaii dot edu");

// Enable debug mode for the entire application
$debugMode = false;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Output the start of debug logging
$debug->log("UploadScheduleFile: Entry point debug|log mode is ON.");

// Initialize the form controller
$controller = new Controller($formatHtml, $debug);

// Process and render the form.
$controller->handleRequest();
