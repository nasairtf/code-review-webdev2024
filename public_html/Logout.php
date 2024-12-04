<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

use App\core\common\Debug;

/**
 * /home/webdev2024/public_html/Logout.php
 *
 * A quick and dirty logout page to reset sessions.
 */

// Enable debug mode for the entire page
$debugMode = true;
$debugLevel = $debugMode ? 1 : 0;
$debug = new Debug('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
$formatHtml = $debugMode;

// Log the start of logout module operation
$debug->log("Logout: Entry point debug|log mode is ON.");

// Output the SESSION values
$debug->debugVariable($_SESSION, "_SESSION");

// Start the session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Log the current session data for debugging
$debug->debugVariable($_SESSION, "_SESSION before unset");

// Clear and destroy the session if it is active
$debug->log("Logout: Unset and destroy session.");
session_unset();
session_destroy();

// Log session status after clearing for confirmation
$debug->log("Logout: Session successfully cleared.");
