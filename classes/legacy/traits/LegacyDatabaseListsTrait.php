<?php

namespace App\legacy\traits;

trait LegacyDatabaseListsTrait
{
    ############################################################################
    #
    # The following functions are helper functions to interact with databases
    #
    ############################################################################

    #---------------------------------------------------------------------------
    # Return the operator list from the database
    #
    function getOperatorList($debug, $op, $mode = "")
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: getOperatorList({$debug}, {$op}, {$mode})</h1>\n"; }

        $whr = "";
        if ($op == true) { $whr = " WHERE nightAttend = '0'"; }
        $sql = "SELECT * FROM Operator{$whr} ORDER BY lastName";
        if ($debug) { echo "<p>sql: {$sql}</p>\n\n\n"; }

        $dbc = connectDBtroublelog($debug);
        $result = mysqli_query($dbc, $sql) or die ("Error retrieving Operator list from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) { $tmp[] = $row; }
        disconnectMysql($debug, $dbc, $result);

        switch ($mode) {
            case "trouble":
                $operators = array(1 => '');
                foreach ($tmp as $key => $value) { $operators[] = $value['lastName']; }
                $operators[] = "Other";
                break;
            case "proposal":
                foreach ($tmp as $key => $value) {
                    $operators['operatorID'][] = $value['operatorID'];
                    $operators['lastName'][] = $value['lastName'];
                    $operators['firstName'][] = $value['firstName'];
                    $operators['operatorCode'][] = $value['operatorCode'];
                    $operators['nightAttend'][] = $value['nightAttend'];
                }
                break;
            case "feedback":
                $operators = array(0 => '');
                foreach ($tmp as $key => $value) { $operators[] = $value; }
                break;
            default:
                $operators = $tmp;
                break;
        }

        if ($debug) { echo "<h1>RETURN: getOperatorList({$debug}, {$op}, {$mode})</h1>\n\n\n"; }
        return $operators;
    }
    #-- end of getOperatorList
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Return the support astronomer list from the database
    #
    function getSupportList($debug, $sa, $mode = "")
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: getSupportList({$debug}, {$sa}, {$mode})</h1>\n"; }

        $whr = "";
        if ($sa == true) { $whr = " WHERE status = '1'"; }
        $sql = "SELECT * FROM SupportAstronomer{$whr} ORDER BY lastName";
        if ($debug) { echo "<p>sql: {$sql}</p>\n\n\n"; }

        $dbc = connectDBtroublelog($debug);
        $result = mysqli_query($dbc, $sql) or die ("Error retrieving Support Astronomer list from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) { $tmp[] = $row; }
        disconnectMysql($debug, $dbc, $result);

        switch ($mode) {
            case "feedback":
                $support = array(0 => '');
                foreach ($tmp as $key => $value) { $support[] = $value; }
                break;
            default:
                $support = $tmp;
                break;
        }

        if ($debug) { echo "<h1>RETURN: getSupportList({$debug}, {$sa}, {$mode})</h1>\n\n\n"; }
        return $support;
    }
    #-- end of getSupportList
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Return the instrument list from the database
    #
    # $sort: true = sort ASC, false = sort DESC;
    # $mode:
    #
    #
    function getInstrumentList($debug, $order, $mode = "")
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: getInstrumentList({$debug}, {$order}, {$mode})</h1>\n"; }

        $unk = "AND hardwareID <> 'unk' AND hardwareID <> 'ic'";
        if ($order === true) { $srt = "ASC"; } else { $srt = "DESC"; }

        #-- mode = "all" returns the instrument list with all the entries in the Hardware table;
        if ($mode == "all") {
            $sql = "SELECT * FROM Hardware ORDER BY itemName {$srt}";
        }

        #-- mode = "secon" returns the instrument list with all the active secondary entries in the Hardware table;
        if ($mode == "secon") {
            $sql = "SELECT * FROM Hardware WHERE notes = 'active' AND type = 'secon' {$unk} ORDER BY itemName {$srt}";
        }

        #-- mode = "instru+vis" returns the instrument list with all the active instrument entries in the Hardware table;
        #-- mode = "instru" returns the instrument list with all the active instrument entries in the Hardware table;
        if ($mode == "instru+vis" || $mode == "instru" || $mode == "feedback") {
            $sql = "SELECT * FROM Hardware WHERE notes = 'active' AND type = 'instr' {$unk} ORDER BY pulldownIndex {$srt}";
        }

        #-- mode = "active-instru" returns the instrument list with all the active instruments entries in the Hardware table;
        if ($mode == "active-instru") {
            $sql = "SELECT * FROM Hardware WHERE notes IN ('active','visitor') AND type = 'instr' {$unk} ORDER BY pulldownIndex {$srt}";
        }

        #-- mode = "trouble" returns the instrument list with all the active instruments entries in the Hardware table;
        if ($mode == "trouble") {
            $sql = "SELECT * FROM Hardware WHERE notes IN ('active','visitor') AND type = 'instr' {$unk} ORDER BY itemName {$srt}";
        }
        if ($debug) { echo "<p>sql: {$sql}</p>\n\n\n"; }

        $dbc = connectDBtroublelog($debug);
        $result = mysqli_query($dbc, $sql) or die ("Error retrieving Instrument list from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) { $tmp[] = $row; }
        disconnectMysql($debug, $dbc, $result);

        switch ($mode) {
            case "secon":
                $instruments = array(0 => '');
                foreach ($tmp as $key => $value) { $instruments[] = $value['itemName']; }
                break;
            case "trouble":
                $instruments = array(1 => '');
                foreach ($tmp as $key => $value) { $instruments[] = $value['itemName']; }
                $instruments[] = "Other";
                break;
            case "feedback":
                $instruments = array(0 => '');
                foreach ($tmp as $key => $value) { $instruments[] = $value; }
                $instruments[] = array('itemName' => "Visitor");
                break;
            default:
                $instruments = $tmp;
                break;
        }

        if ($debug) { echo "<h1>RETURN: getInstrumentList({$debug}, {$order}, {$mode})</h1>\n\n\n"; }
        return $instruments;
    }
    #-- end of getInstrumentList
    #---------------------------------------------------------------------------
}
