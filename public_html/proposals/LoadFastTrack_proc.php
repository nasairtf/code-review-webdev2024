<?php

require_once '/aux1/htdocs/inc/bootstrap.php';

#use App\controllers\proposals\UpdateApplicationDateController as Controller;
// DEBUG core
#use App\core\common\Debug;
use App\legacy\Proposals;

/**
 * /home/webdev2024/public_html/proposals/LoadFastTrack.php
 *
 * Allows staff changing of proposals' date for a semester.
 *
 * Created:
 *  2024/10/11 - Miranda Hawarden-Ogata
 *
 * Modified:
 *  2024/10/20 - Miranda Hawarden-Ogata
 *      - Updated DebugUtility:: calls to use Debug class instance calls.
 *  2024/10/21 - Miranda Hawarden-Ogata
 *      - Set up composer and autoloading.
 *
 * @category Application
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

// define legacy constant
define('CONTACT', "hawarden at hawaii dot edu");

// Enable debug mode for the entire application
#$debugMode = true;
#$debugLevel = $debugMode ? 1 : 0;
#$debug = new Debug('default', $debugMode, $debugLevel);

// Enable html formatting for the entire page
#$formatHtml = $debugMode;

#$debug->log("UpdateApplicationDate: Starting refactored code.");
#$debug->log("UpdateApplicationDate: Entry point debug|log mode is ON.");
#$controller = new Controller($formatHtml, $debug);
#$controller->handleRequest();


############################################################################
#
# PORTED FROM LEGACY ENTRY POINT
#
############################################################################

// Initialize the wrapper
$proposals = new Proposals();

$debug = true;
define("HOMEPATH", "/home/proposal");
ini_set("memory_limit", "100M");
require_once "/htdocs/inc/auxFuncs.inc";

########################################################
#--
#-- start debug here
#--
if ($debug) {
    echo "<div style='color: green'>\n";
}

########################################################
#--
#-- handle submit
#--
if (isset($_POST['upload'])) {
    #-- get the type of load, partial or full
    if (isset($_POST['loadtype']) && $_POST['loadtype'] == "full") {
        $loadtype = "FULL";
    } else {
        $loadtype = "PARTIAL";
    }

    #-- get the type of load, partial or full
    if (isset($_POST['access']) && $_POST['access'] != "") {
        $access = $_POST['access'];
    } else {
        $access = "public";
    }

    #-- Check if can move the uploaded files to ~/schedule
    $schfile = HOMEPATH . "/schedule/{$_FILES['fasttrack']['name']}";
    if (!move_uploaded_file($_FILES['fasttrack']['tmp_name'], $schfile)) {
        $page_title = "Error";
        $error = "<p style='font-size:120%; font-weight:bold;'>\n";
        $error .= "A possible file upload attack has occurred.<br/><br/>\n";
        $error .= "File '{$_FILES['fasttrack']['name']}' does not appear to have been uploaded properly.<br/>\n";
        $error .= "Check '{$_FILES['fasttrack']['tmp_name']}' to verify if this is an attempted attack.\n";
        $error .= "</p>\n";
        echo $proposals->generateErrorPage($debug, $page_title, $error);
        exit;
    }

    #-- process the uploaded files
    $page_title = "Uploading FastTrack schedule";
    $msg = $proposals->processFastTrack($debug, $page_title, $schfile, $loadtype, $access);

    #-- Rename the uploaded file
    $newfile = HOMEPATH . "/schedule/sch{$msg[2]}{$msg[3]}." . date("Ymd.Hi", $msg[1]) . ".txt";
    if (!rename($schfile, $newfile)) {
        $page_title = "Error";
        $error  = "<p style='font-size:120%; font-weight:bold;'>\n";
        $error .= "There was a problem backing up the uploaded file.\n";
        $error .= "</p>\n";
        echo $proposals->generateErrorPage($debug, $page_title, $error);
        exit;
    }

    #-- temp page just to output A page...
    $code = $proposals->generateResultsPage($debug, $page_title, $msg[0]);

    #-- finished handling upload, exit.
    echo $code;
    exit;
}

########################################################
#--
#-- display fresh page
#--
if (true) {
    $page_title = "Upload FastTrack Schedule";
    $code = $proposals->generateFastTrackFormPage($debug, $page_title);

    #-- finished handling new page, exit.
    echo $code;
    exit;
}
