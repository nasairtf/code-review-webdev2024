<?php

namespace App\legacy\traits;

trait ProcessTACResultsCommentsTrait
{
    ############################################################################
    #
    # Processes TAC comments csv file and uploads to the database
    #
    #---------------------------------------------------------------------------
    #
    function processTACResultsComments(
        $debug,     // boolean for debugging; to be replaced with $this->debug methods
        $title,     // page title
        $year,      // the 4-digit year
        $semester,  // 'A' or 'B' to indicate which semester
        $tac,       // 'ss' or 'nss' to indicate which TAC
        $tfile      // the full path to the csv file
    ) {
        $code = "";

        #-- "TAC Rating" is a 8-field repeating field
        #-- filemaker uses 'group separater' ascii character to separate the items in a repeating field
        $gs = chr(29);
        #-- filemaker uses 'vertical tab' ascii character for carriage returns
        $vt = chr(11);
        #-- filemaker uses 'horizontal tab' ascii character for tabs
        $ht = chr(9);
        if (($fileid = fopen($tfile, "r")) !== false) {
            while (($line = fgetcsv($fileid, 0, ",")) !== false) {
                # remove short lines
                if (count($line) < 5) { continue; }
                # remove blank lines
                if ($line[0] == "" ) { continue; }
                $line = str_replace($vt, "INTERNALLINEFEEDHERE", $line);
                $line = str_replace($ht, " ", $line);
                $line = str_replace("'", "&#39;", $line);
                $lines[] = $line;
                if ($debug) { echo "Line: [".print_r($line, true)."]\n"; }
            }
            if ($debug) { echo "END-WHILE: ".print_r($line, true)."\n"; }
        } else {
            $error = "Opening the csv file failed.\n";
            $code .= generateErrorPage($debug, $title, $error);
            return $code;
        }
        fclose($fileid);

        if ($debug) {
            foreach ($lines as $key => $value) {
                echo "Line #{$key}: ".print_r($value, true)."\n";
            }
        }

        $txt1 = "UPDATE ObsApp";
        foreach ($lines as $key => $value) {
            if ($key == 0) {
                $myfile = dirname($tfile) . "/taccomments_{$year}{$semester}_{$tac}.txt";
                if ($tfile != $myfile) { rename($tfile, $myfile); }
                $fmp = $value;
            }
            if ($key < 1) { continue; }
            #-- "TAC Comments"
            $comments = "";
            #-- "[4] - Feedback to PI (primary reviewer composes this)"
            if ($value[4] != "") {
                $value[4] = normalAsciiString($value[4]);
                $comments .= "{$value[4]}INTERNALLINEFEEDHEREINTERNALLINEFEEDHERE";
            }
            #-- "[5] - Secondary Reviewer input" per Adwin these are not to be included.
            #if ($value[5] != "") {
            #   $value[5] = normalAsciiString($value[5]);
            #   $comments .= "{$value[5]}INTERNALLINEFEEDHEREINTERNALLINEFEEDHERE";
            #}
            #-- "[6] - Other reviewers' comments 1" per Adwin these are not to be included.
            #if ($value[6] != "") {
            #   $value[6] = normalAsciiString($value[6]);
            #   $comments .= "{$value[6]}INTERNALLINEFEEDHEREINTERNALLINEFEEDHERE";
            #}
            #-- "[7] - Other reviewers' comments 2" per Adwin these are not to be included.
            #if ($value[7] != "") {
            #   $value[7] = normalAsciiString($value[7]);
            #   $comments .= "{$value[7]}INTERNALLINEFEEDHEREINTERNALLINEFEEDHERE";
            #}
            #-- combined comments
            $comments = str_replace("\n\r", "INTERNALLINEFEEDHERE", $comments);
            $comments = str_replace("\r\n", "INTERNALLINEFEEDHERE", $comments);
            $comments = str_replace("\n", "INTERNALLINEFEEDHERE", $comments);
            $comments = str_replace("'", "&#39;", $comments);
            $txt2 = "SET TACComments = '{$comments}'";

            $txt3 = "WHERE semesterYear = '{$year}' AND semesterCode = '{$semester}' AND ProgramNumber='{$value[0]}'";
            $sql[] = "{$txt1} {$txt2} {$txt3};";
        }

        #-----------------------------------------------
        #-- submit sql statements to the database

        #-- construct the mysql INSERT/UPDATE statement
        if ($debug) {
            echo "Headers: ".print_r($fmp, true)."\n";
            echo "\n\n<h2>SQL to be written: \n</h2>\n";
            foreach ($sql as $key => $value) { echo "{$value}<br/>\n"; }
        }

        #-- connect to the database
        $dbc = connectDBtroublelog($debug);

        #-- upload each sql statement to the database
        $count = count($sql);
        foreach ($sql as $key => $value) {
            if ($debug) { echo "\n<p>sql: {$value}</p>\n\n\n"; }

            $result = mysqli_query($dbc, $value) or die ("Error updating proposal in the database: " . mysqli_error($dbc));
            if ($result) { $count--; }
        }

        #-- disconnect from the database
        disconnectMysql($debug, $dbc, $result);

        //$message = "\n";
        $message = [];
        if ($count == 0) {
            $count = count($sql);
            if ($count == 1) {
                //$message .= "<p align='center'><strong>{$count} submitted {$tac} proposal was processed for {$year}{$semester}</strong></p>\n";
                $message[] = "{$count} submitted {$tac} proposal was processed for {$year}{$semester}";
            } else {
                //$message .= "<p align='center'><strong>{$count} submitted {$tac} proposals were processed for {$year}{$semester}</strong></p>\n";
                $message[] = "{$count} submitted {$tac} proposals were processed for {$year}{$semester}";
            }
        } else {
            //$message .= "<p align='center'>There was a problem processing the proposal awards. {$count} submitted proposals were not processed.</p>\n";
            $message[] = "There was a problem processing the proposal awards. {$count} submitted proposals were not processed.";
        }

        if ($debug) { echo "</div>\n"; }

        return $message;
    }
    #---------------------------------------------------------------------------
    #-- end of processTACResultsComments
    ############################################################################
}
