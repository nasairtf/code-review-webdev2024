<?php

namespace App\legacy\traits;

trait ProcessTACResultsFMPTrait
{
    ############################################################################
    #
    # Processes TAC results csv file and uploads to the database
    #
    #---------------------------------------------------------------------------
    #
    function processTACResultsFMP(
        $debug,     // boolean for debugging; to be replaced with $this->debug methods
        $title,     // page title
        $tfile      // the full path to the csv file
   ) {
        $code = "";

        /********************************************************************
        ** filemaker characters reference urls:
        ** http://www.filemaker.com/help/html/import_export.16.27.html
        ** http://help.filemaker.com/app/answers/detail/a_id/949/~/expected-embedded-characters-in-filemaker-pro-export-files

        text from expected-embedded:
        Expected Embedded Characters in FileMaker Pro Export Files

        If you are trying to reconstruct data from a recovered file, it might be helpful to know which characters are 'legal' in FileMaker Pro data. Knowing this, you may be better able to clean up your data by purging the text file of bad characters.

        Following is a list of expected embedded characters in FileMaker Pro text exports. Be aware that any of these listed characters may be a problem if they are not used correctly in your data file. Rebuilding a file is labor intensive and generally a trial-and-error process.

        Expected embedded characters in FileMaker Pro text exports:

        1. ASCII 29 for Repeating fields.

        2. Repetitions are separated by Group Separator character $1D (decimal 29) when exported.

        3. Embedded return $0D (decimal 13) or $0A (decimal 10) (hard wrap, not soft wrap) is exported as Vertical Tab character $0B (decimal 11).

        4. If you are performing an export to Tab Separated text, the embedded tab $09 (decimal 9) is exported as Space character $20 (decimal 32.) For all other export formats, the tab character is exported as itself (Horizontal Tab $09 (decimal 9).)

        5. Records are separated by EndOfLine character(s) usual for the platform:
        CR $0D(decimal 13) for Macintosh
        CRLF $0D$0A (decimal 13 10, concatenated) for PC, or
        LF $0A (decimal 10) for Unix.

        6. No other control characters (<=$1F (less than or equal to decimal 31)) are generated during export, but embedded control characters are exported as themselves excepting as specified in #2 and #3 above.

        7. Accented characters are exported as themselves without remapping from the platform's normal character set: Œ $8C (140) is exported as $8C (decimal 140) on the Mac - it is NOT remapped to $86 (decimal 134) which is the equivalent ASCII character.

        Created: May 03, 2005 07:08 PM PDT
        Last Updated: Sep 10, 2008 12:30 PM PDT
        */

        #$lines = file($tfile);
        #$lines[] = array("Program Code", "Program Year", "Program Semester", "Program Number", "Program App Code", "TAC Aproved", "TAC Mean Rating", "Alloc Nights", "Allocated Days", "TAC Comments", "TAC Rating", "TAC Assignment", "TAC Members ID", "TAC Members First", "TAC Members Last");
        #-- "TAC Rating" is a 8-field repeating field
        #-- filemaker uses 'group separater' ascii character to separate the items in a repeating field
        $gs = chr(29);
        #-- filemaker uses 'vertical tab' ascii character for carriage returns
        $vt = chr(11);
        #-- filemaker uses 'horizontal tab' ascii character for tabs
        $ht = chr(9);
        if (($fileid = fopen($tfile, "r")) !== false) {
            while (($line = fgetcsv($fileid, 1000, ",")) !== false) {
                if (count($line) < 8) { continue; }
                $line = str_replace($vt, "INTERNALLINEFEEDHERE", $line);
                $line = str_replace($ht, " ", $line);
                $rating = explode($gs, $line[10]);
                foreach ($rating as $key => $value) {
                    $line[10+$key] = $value;
                }
                #$line = str_replace("\"", "&quot;", $line);
                $lines[] = $line;
            }
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

        #   $sqltest = "UPDATE ObsApp SET isApproved = 1 WHERE semesterYear = '2013' AND semesterCode = 'B' AND ProgramNumber='1' AND code = 'XXXYYY';";

        #-- there are other characters (~6) returned for the value[5] yes/no
        #-- quantity. THe characters of "Yes" and "No" do not appear to be
        #-- adjacent as matching on 'Yes' and 'No' fails. Workaround appears
        #-- to be to search for the specific character 'Y' (or 'N') and
        #-- assume "Yes" if "Y" is present.
        $semester = "";
        $award = 0;
        $notaward = 0;
        $gottime = 0;
        $notgottime = 0;
        $txt1 = "UPDATE ObsApp";
        foreach ($lines as $key => $value) {
            if ($key == 0) {
                $semester = "{$value[1]}{$value[2]}";
                $myfile = dirname($tfile) . "/tac{$semester}.txt";
                #         echo "rename({$tfile}, {$myfile})";
                if ($tfile != $myfile) { rename($tfile, $myfile); }
            }
            #-- "TAC Aproved"
            if ($value[5] == "Yes") {
                $txt2 = "SET isApproved = '1'";
                $award++;
            } else {
                $txt2 = "SET isApproved = '0'";
                $notaward++;
            }
            #-- "TAC Rating (1-8)"
            if ($value[10] != "") { $txt2 .= ", TACRating1 = '{$value[10]}'"; }
            if ($value[11] != "") { $txt2 .= ", TACRating2 = '{$value[11]}'"; }
            if ($value[12] != "") { $txt2 .= ", TACRating3 = '{$value[12]}'"; }
            if ($value[13] != "") { $txt2 .= ", TACRating4 = '{$value[13]}'"; }
            if ($value[14] != "") { $txt2 .= ", TACRating5 = '{$value[14]}'"; }
            if ($value[15] != "") { $txt2 .= ", TACRating6 = '{$value[15]}'"; }
            if ($value[16] != "") { $txt2 .= ", TACRating7 = '{$value[16]}'"; }
            if ($value[17] != "") { $txt2 .= ", TACRating8 = '{$value[17]}'"; }
            #-- "TAC Mean Rating"
            if ($value[6] != "") {
                $sum = 0;
                $num = 0;
                if ($value[10] != "") { $sum += $value[10]; $num++; }
                if ($value[11] != "") { $sum += $value[11]; $num++; }
                if ($value[12] != "") { $sum += $value[12]; $num++; }
                if ($value[13] != "") { $sum += $value[13]; $num++; }
                if ($value[14] != "") { $sum += $value[14]; $num++; }
                if ($value[15] != "") { $sum += $value[15]; $num++; }
                if ($value[16] != "") { $sum += $value[16]; $num++; }
                if ($value[17] != "") { $sum += $value[17]; $num++; }
                $ave = sprintf("%0.3f", $sum/$num);
                $txt2 .= ", TACMeanRating = '{$ave}'";
            }
            #-- "TAC Comments"
            if ($value[9] != "") {
                $value[9] = normalAsciiString($value[9]);
                $value[9] = htmlentities($value[9],ENT_QUOTES);
                #$value[9] = trim(replaceParentheses(replaceDoubleQuotes($value[9])));
                #-- replace extended characters in data set
                #$value[9] = str_replace("\'", "'", $value[9]);
                #$value[9] = str_replace("'", "\'", $value[9]);
                #$value[9] = str_replace("\'", "'", $value[9]);
                #$value[9] = str_replace("\"", "'", $value[9]);
                $txt2 .= ", TACComments = '{$value[9]}'";
            }
            #-- "Time Allocated"
            if (($value[7] != "" && $value[7] != 0) || ($value[8] != "" && $value[8] != 0)) {
                $txt2 .= ", isAllocatedTime = '1'";
                $gottime++;
            } else {
                $txt2 .= ", isAllocatedTime = '0'";
                $notgottime++;
            }

            $txt3 = "WHERE semesterYear = '{$value[1]}' AND semesterCode = '{$value[2]}' AND ProgramNumber='{$value[3]}' AND code = '{$value[4]}'";
            $txt3 = "WHERE semesterYear = '{$value[1]}' AND semesterCode = '{$value[2]}' AND ProgramNumber='{$value[3]}'";
            $sql[] = "{$txt1} {$txt2} {$txt3};";
        }

        #-----------------------------------------------
        #-- submit sql statements to the database

        #-- construct the mysql INSERT/UPDATE statement
        #   $sql = implode(" ", $sql);
        if ($debug) {
            $fmp = array("Program Code", "Program Year", "Program Semester", "Program Number", "Program App Code", "TAC Approved", "TAC Mean Rating", "Alloc Nights", "Allocated Days", "TAC Comments", "TAC Rating 1", "TAC Rating 2", "TAC Rating 3", "TAC Rating 4", "TAC Rating 5", "TAC Rating 6");
            echo "Headers: ".print_r($fmp, true)."\n";
            echo "\n\n<h2>SQL to be written: \n</h2>\n";
            #      echo "{$sql}<br/>\n";
            foreach ($sql as $key => $value) { echo "{$value}<br/>\n"; }
        }

        #-- connect to the database
        $dbc = connectDBtroublelog($debug);

        #-- upload each sql statement to the database
        $count = $award + $notaward;
        foreach ($sql as $key => $value) {
            if ($debug) { echo "\n<p>sql: {$value}</p>\n\n\n"; }

            $result = mysqli_query($dbc, $value) or die ("Error updating proposal in the database: " . mysqli_error($dbc));
            if ($result) { $count--; }
        }

        #-- disconnect from the database
        disconnectMysql($debug, $dbc, $result);

        $message = "\n";
        if ($count == 0) {
            $count = $award + $notaward;
            if ($count == 1) {
                $message .= "<p align='center'><strong>{$count} submitted proposal was processed for {$semester}:</strong></p>\n";
            } else {
                $message .= "<p align='center'><strong>{$count} submitted proposals were processed for {$semester}:</strong></p>\n";
            }
            if ($award == 1) {
                $message .= "<p align='center'>{$award} proposal was marked as awarded.</p>\n";
            } else {
                $message .= "<p align='center'>{$award} proposals were marked as awarded.</p>\n";
            }
            if ($notaward == 1) {
                $message .= "<p align='center'>{$notaward} proposal was marked as not awarded.</p>\n";
            } else {
                $message .= "<p align='center'>{$notaward} proposals were marked as not awarded.</p>\n";
            }
        } else {
            $message .= "<p align='center'>There was a problem processing the proposal awards. {$count} submitted proposals were not processed.</p>\n";
        }


        if ($debug) { echo "</div>\n"; }

        $code .= generateResultsPage($debug, $title, $message);
        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of processTACResultsFMP
    ############################################################################
}
