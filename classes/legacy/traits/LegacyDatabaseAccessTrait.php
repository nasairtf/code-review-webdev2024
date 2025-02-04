<?php

namespace App\legacy\traits;

trait LegacyDatabaseAccessTrait
{
    ############################################################################
    #
    # The following functions are helper functions to interact with databases
    #
    ############################################################################

    #---------------------------------------------------------------------------
    # Connects to the troublelog database (uses MySQLi extension)
    #
    function connectDBtroublelog($debug, $host = 4)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectDBtroublelog({$debug}, {$host})</h1>\n"; }

        switch ($host) {
            case 4:
                $dbhost = "irtfweb4";
                break;
            case 3:
                $dbhost = "irtfweb3";
                break;
            case 2:
                $dbhost = "irtfweb2";
                break;
            case 1:
            default:
                $dbhost = "irtfweb";
                break;
        }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect($dbhost, "trouble", "pa!hoa") or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, "troublelog") or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectDBtroublelog({$debug}, {$host})</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectDBtroublelog
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Connects to the personnel database (uses MySQLi extension)
    #
    function connectDBpersonnel($debug)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectDBpersonnel({$debug})</h1>\n"; }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect("irtfweb", "trouble", "pa!hoa") or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, "personnel") or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectDBpersonnel({$debug})</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectDBpersonnel
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Connects to the feedback database (uses MySQLi extension)
    #
    function connectDBfeedback($debug)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectDBfeedback({$debug})</h1>\n"; }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect("irtfweb", "feedbackuser", "changeme") or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, "feedback") or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectDBfeedback({$debug})</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectDBfeedback
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Connects to the meetings database (uses MySQLi extension)
    #
    function connectDBmeetings($debug)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectDBmeetings({$debug})</h1>\n"; }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect("irtfweb", "trouble", "pa!hoa") or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, "meetings") or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectDBmeetings({$debug})</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectDBmeetings
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Connects to the iqup database (uses MySQLi extension)
    #
    function connectDBiqup($debug)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectDBiqup({$debug})</h1>\n"; }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect("irtfweb", "iqup", "pa!hoa") or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, "iqup") or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectDBiqup({$debug})</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectDBiqup
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Connects to the database (uses MySQLi extension)
    #
    function connectMysql($debug, $host, $user, $pwd, $data)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: connectMysql({$debug}, host, user, pwd, data)</h1>\n"; }

        error_log($_SERVER['PHP_SELF']);
        $dbc = mysqli_connect($host, $user, $pwd) or die("Could not connect to MySQL: " . mysqli_connect_error());
        mysqli_select_db($dbc, $data) or die ("Could not select the database: " . mysqli_error($dbc));

        if ($debug) { echo "<h1>RETURN: connectMysql({$debug}, host, user, pwd, data)</h1>\n\n\n"; }
        return $dbc;
    }
    #-- end of connectMysql
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Disconnects from the database (uses MySQLi extension)
    #
    function disconnectMysql($debug, $dbc, $result)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: disconnectMysql({$debug}, db-object, db-result)</h1>\n"; }
        if (is_resource($result)) { mysqli_free_result($result); }
        mysqli_close($dbc);
        if ($debug) { echo "<h1>RETURN: disconnectMysql({$debug}, db-object, db-result)</h1>\n\n\n"; }
    }
    #-- end of disconnectMysql
    #---------------------------------------------------------------------------
}
