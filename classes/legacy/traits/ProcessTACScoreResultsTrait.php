<?php

namespace App\legacy\traits;

trait ProcessTACScoreResultsTrait
{
    private function processTACResultsTxt(
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
             if (count($line) < 8) { continue; }
             # remove blank lines
             if ($line[0] == "") { continue; }
             $line = str_replace($vt, "INTERNALLINEFEEDHERE", $line);
             $line = str_replace($ht, " ", $line);
             $lines[] = $line;
             if ($debug) { echo "Line: [".print_r($line, true)."]\n"; }
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

       $gottime = 0;
       $notgottime = 0;
       $txt1 = "UPDATE ObsApp";
       foreach ($lines as $key => $value) {
          if ($key == 0) {
             $myfile = dirname($tfile) . "/tacresults_{$year}{$semester}_{$tac}.txt";
             if ($tfile != $myfile) { rename($tfile, $myfile); }
          }
          if ($key < 2) { continue; }
          #-- "Time Allocated"
          #-- "Allocated Days" [10], "Alloc Nights" [11]
          if (($value[10] != "" && $value[10] != 0) || ($value[11] != "" && $value[11] != 0)) {
             $txt2 = "SET isAllocatedTime = '1'";
             $gottime++;
          } else {
             $txt2 = "SET isAllocatedTime = '0'";
             $notgottime++;
          }
          #-- "TAC Rating (1-8)" [1-4 SS; 5-8 NSS]
          #-- "Solar System: TAC Rating (1-4)"
          if ($tac == "ss") {
             if ($value[5] != "") { $txt2 .= ", TACRating1 = '{$value[5]}'"; }
             if ($value[6] != "") { $txt2 .= ", TACRating2 = '{$value[6]}'"; }
             if ($value[7] != "") { $txt2 .= ", TACRating3 = '{$value[7]}'"; }
             if ($value[8] != "") { $txt2 .= ", TACRating4 = '{$value[8]}'"; }
          }
          #-- "Non-Solar System: TAC Rating (5-8)"
          if ($tac == "nss") {
             if ($value[5] != "") { $txt2 .= ", TACRating5 = '{$value[5]}'"; }
             if ($value[6] != "") { $txt2 .= ", TACRating6 = '{$value[6]}'"; }
             if ($value[7] != "") { $txt2 .= ", TACRating7 = '{$value[7]}'"; }
             if ($value[8] != "") { $txt2 .= ", TACRating8 = '{$value[8]}'"; }
          }
          #-- "TAC Mean Rating"
          if ($value[9] != "") {
             $sum = 0;
             $num = 0;
             if ($value[5] != "") { $sum += $value[5]; $num++; }
             if ($value[6] != "") { $sum += $value[6]; $num++; }
             if ($value[7] != "") { $sum += $value[7]; $num++; }
             if ($value[8] != "") { $sum += $value[8]; $num++; }
             $ave = sprintf("%0.3f", $sum/$num);
             $txt2 .= ", TACMeanRating = '{$ave}'";
          }
          #-- "Allocated Days"
          if ($value[10] != "") {
             $txt2 .= ", allocatedTimeDay = '{$value[10]}'";
          }
          #-- "Alloc Nights"
          if ($value[11] != "") {
             $txt2 .= ", allocatedTimeNight = '{$value[11]}'";
          }

          $txt3 = "WHERE semesterYear = '{$year}' AND semesterCode = '{$semester}' AND ProgramNumber='{$value[0]}'";
          $sql[] = "{$txt1} {$txt2} {$txt3};";
       }

       #-----------------------------------------------
       #-- submit sql statements to the database

       #-- construct the mysql INSERT/UPDATE statement
       if ($debug) {

          #-- "Solar System: TAC Rating (1-4)"
          if ($tac == "ss") {
             $fmp = array("Program Number", "Primary Reviewer", "Secondary Reviewer", "Other Reviewer 1", "Other Reviewer 2", "TAC Rating 1", "TAC Rating 2", "TAC Rating 3", "TAC Rating 4", "TAC Mean Rating", "Allocated Days", "Alloc Nights");
          }
          #-- "Non-Solar System: TAC Rating (5-8)"
          if ($tac == "nss") {
             $fmp = array("Program Number", "Primary Reviewer", "Secondary Reviewer", "Other Reviewer 1", "Other Reviewer 2", "TAC Rating 5", "TAC Rating 6", "TAC Rating 7", "TAC Rating 8", "TAC Mean Rating", "Allocated Days", "Alloc Nights");
          }

          echo "Headers: ".print_r($fmp, true)."\n";
          echo "\n\n<h2>SQL to be written: \n</h2>\n";
          foreach ($sql as $key => $value) { echo "{$value}<br/>\n"; }
       }

       #-- connect to the database
       $dbc = connectDBtroublelog($debug);

       #-- upload each sql statement to the database
       $count = $gottime + $notgottime;
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
          $count = $gottime + $notgottime;
          if ($count == 1) {
             //$message .= "<p align='center'><strong>{$count} submitted {$tac} proposal was processed for {$year}{$semester}:</strong></p>\n";
             $message[] = "{$count} submitted {$tac} proposal was processed for {$year}{$semester}:";
          } else {
             //$message .= "<p align='center'><strong>{$count} submitted {$tac} proposals were processed for {$year}{$semester}:</strong></p>\n";
             $message[] = "{$count} submitted {$tac} proposals were processed for {$year}{$semester}:";
          }
          if ($gottime == 1) {
             //$message .= "<p align='center'>{$gottime} proposal got time.</p>\n";
             $message[] = "{$gottime} proposal got time.";
          } else {
             //$message .= "<p align='center'>{$gottime} proposals got time.</p>\n";
             $message[] = "{$gottime} proposals got time.";
          }
          if ($notgottime == 1) {
             //$message .= "<p align='center'>{$notgottime} proposal did not get time.</p>\n";
             $message[] = "{$notgottime} proposal did not get time.";
          } else {
             //$message .= "<p align='center'>{$notgottime} proposals did not get time.</p>\n";
             $message[] = "{$notgottime} proposals did not get time.";
          }
       } else {
          //$message .= "<p align='center'>There was a problem processing the proposal awards. {$count} submitted proposals were not processed.</p>\n";
          $message[] = "There was a problem processing the proposal awards. {$count} submitted proposals were not processed.";
       }

       if ($debug) { echo "</div>\n"; }

       return $message;
    }
}
