<?php

namespace App\legacy\traits;

trait LegacyProcessObsRemindersTrait
{
    ############################################################################
    #
    # Generates the observing reminder form
    #
    #---------------------------------------------------------------------------
    #
    function generateObsReminder($debug, $data)
    {
        $code  = "";
        $color = "";
        $cols  = 5;
        $units = $this->returnReminderUnit($data['units']);
        $button = 'Generate Emails';

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, $cols, "FFFFFF");

        $height = 45;
        $wid1  = 20;  # width of first column
        $wid2  = 175; # width of second column
        $wid3  = 80;  # width of third column
        $wid4  = 150; # width of fourth column
        $wid5  = 25;  # width of fifth column
        $wid6  = $wid2 + $wid3; # width of columns 2-3 (labels)
        $wid7  = $wid3 + $wid4 + $wid5; # width of columns 3-5 (radio buttons)
        if ($data['blockWindow'] > 1)   { $blk = "s"; } else { $blk = ""; }
        if ($data['emailLeadTime'] > 1) { $elt = "s"; } else { $elt = ""; }
        if ($debug) { $pldn = -10; } else { $pldn = 1; }
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td colspan='{$cols}'>\n";
        $code .= "      <strong>Requirements</strong>:<br/>
        <ul>
        <li>the schedule must be released and the time announcement emails sent <strong>at least ".OBS_LEAD_DAYS." days before the semester starts</strong>. The guest account creation script runs automatically ".OBS_LEAD_DAYS." days prior to the start of each semester.</li>
        <li>the <strong>guest accounts</strong> and the <strong>schedule</strong> must both be set up at least {$data['blockWindow']} {$units}{$blk} prior to the start of the semester.</li>
        </ul>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td colspan='{$cols}'>\n";
        $code .= "      <strong>Algorithm</strong>:<br/><br/>
        This form generates the text of the reminder emails that would be sent to observers to notify them of imminent observing. The algorithm for generation of emails is fairly straightforward:<br/>
        <ul>
        <li>a <strong>block window</strong> is a rolling {$data['blockWindow']} {$units} window of time that contains the observing times that will be examined.</li>
        <li>the <strong>email lead time</strong> is the {$data['emailLeadTime']} {$units} point when email reminders will be sent if the program's observing time meets the criteria for it.</li>
        <li>if a program has upcoming observing time on the last day of the block window but an email has not been sent, a reminder email will be sent.</li>
        <li>if a program has observing times within {$data['blockWindow']} {$units}{$blk} prior to the {$data['emailLeadTime']} {$units} email lead time and an email has already been sent, no additional email will be sent.</li>
        <li>if a program has observing times within a block window but contains the comment '{$data['serviceobscm']}', no email will be sent.</li>
        <li>no email will be sent for the 999 program. If 999 observing time has been reassigned, no reminders will be generated if the schedule has not been updated to reflect the new program numbers.</li>
        </ul>
        Specify the appropriate test values and designate whether the notification emails should be generated and then click the '<em>{$button}</em>' button to generate the reminder emails.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td width='{$wid1}'><br/></td>\n";
        $code .= "    <td width='{$wid2}'>Email lead time:</td>\n";
        $code .= "    <td width='{$wid3}'><br/></td>\n";
        $code .= "    <td>";
        $code .= getPulldownNumbers("emailLeadTime", $data['emailLeadTime'], 4, $pldn, 20);
        $code .= "</td>\n";
        $code .= "    <td><br/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td width='{$wid1}'><br/></td>\n";
        $code .= "    <td width='{$wid2}'>Block window:</td>\n";
        $code .= "    <td width='{$wid3}'><br/></td>\n";
        $code .= "    <td>";
        $code .= getPulldownNumbers("blockWindow", $data['blockWindow'], 4, $pldn, 20);
        $code .= "</td>\n";
        $code .= "    <td><br/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td width='{$wid1}'><br/></td>\n";
        $code .= "    <td width='{$wid2}'>Unit type:</td>\n";
        $code .= "    <td width='{$wid3}'><br/></td>\n";
        $code .= "    <td align='left'>\n";
        $code .= "      <table style='margin: 1 auto;'>\n";
        $code .= "        <tr>\n";
        $code .= "          <td>" . getRadioButton("units", "1", "checked", $data['units']) . "</td>\n";
        $code .= "          <td>Days</td>\n";
        #$code .= "        </tr>\n";
        #$code .= "        <tr>\n";
        $code .= "          <td>" . getRadioButton("units", "0", "checked", $data['units']) . "</td>\n";
        $code .= "          <td>Weeks</td>\n";
        $code .= "        </tr>\n";
        $code .= "      </table>\n";
        $code .= "</td>\n";
        $code .= "    <td><br/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td width='{$wid1}'><br/></td>\n";
        $code .= "    <td width='{$wid2}'>Send emails:</td>\n";
        $code .= "    <td width='{$wid3}'><br/></td>\n";
        $code .= "    <td align='left'>\n";
        $code .= "      <table style='margin: 1 auto;'>\n";
        $code .= "        <tr>\n";
        $code .= "          <td>" . getRadioButton("emails", "1", "checked", $data['emails']) . "</td>\n";
        $code .= "          <td>Yes (send real emails)</td>\n";
        $code .= "        </tr>\n";
        $code .= "        <tr>\n";
        $code .= "          <td>" . getRadioButton("emails", "0", "checked", $data['emails']) . "</td>\n";
        $code .= "          <td>No (send dummy emails)</td>\n";
        $code .= "        </tr>\n";
        $code .= "      </table>\n";
        $code .= "</td>\n";
        $code .= "    <td><br/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $td = "align='center' width='164px'";
        $in = "style='width: 156px;'";
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td colspan='{$cols}' align='center'>\n";
        $code .= "      <table border='0' cellspacing='4'>\n";
        $code .= "        <tr>\n";
        $code .= "          <td {$td}><br/></td>\n";
        $code .= "          <td {$td}><input type='reset' value='Clear Form' name='clear' {$in} /></td>\n";
        $code .= "          <td {$td}><input type='submit' value='{$button}' name='submit' {$in} /></td>\n";
        $code .= "        </tr>\n";
        $code .= "      </table>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= getHorizontalLine(0, $cols, "FFFFFF");

        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateObsReminder
    ############################################################################

    ############################################################################
    #
    # Generates the observer reminder chooser
    #
    #---------------------------------------------------------------------------
    #
    function generateObsReminderEmailPage($debug, $title, $data, $sendemails)
    {
        $isForm = false;
        $code   = "";
        $msg    = "";
        $tmp    = "";
        $color  = "";
        $cols   = 7;
        $height = 18;
        $width  = 75;
        $bwid   = 120;

        if ($debug) {
            $isdebug = "_debug";
        } else {
            $isdebug = "";
        }
        $filedate = date("Ymd-Hi");
        $filename = "/home/proposal/public_html/accounts/reminders/{$filedate}{$isdebug}_reminders.html";
        $urlpath  = "/~proposal/accounts/reminders/{$filedate}{$isdebug}_reminders.html";

        #-- process the semester and generate the email text for previewing
        $announce = $this->processObsReminderPreviewEmails($debug, $title, $data, $sendemails);

        if ($debug) {
            echo "<br/>\n{reminders} (Array: merged) = generateObsReminderEmailPage({$debug}, {$title}, data, {$sendemails})<br/>\n";
            print_r($announce);
        }

        #-- build the divider row
        $break  = "  <tr style='height: ".floor($height/2)."px; background-color: #FFFFFF;'>\n";
        $break .= "    <td colspan='{$cols}'></td>\n";
        $break .= "  </tr>\n";

        //$tmp .= myHeader(false, $title, $isForm);

        $tmp .= "<table width='100%' border='0' cellspacing='0' cellpadding='2'>\n";
        $tmp .= getHorizontalLine(0, $cols, "FFFFFF");

        foreach ($announce[1] as $key => $value) {
            #$value = str_replace("\n", "<br/>\n", $value);

            $color = getGrayShading($color);

            #-- build the proposal information rows
            $header  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $header .= "    <th width='65'>Record ".sprintf("%03d", $key+1)."</th>\n";
            $header .= "    <th width='55' align='right'>start time:</th>\n";
            $header .= "    <td width='110'>".date("Y/m/d H:i:s", $value['startTime'])."</td>\n";
            $header .= "    <th width='40' align='right'>Program:</th>\n";
            if ($value['programID'] == 0) {
                $header .= "    <td width='40' align='center'>{$value['semesterID']}".sprintf("%03d", $value['programID'])."</td>\n";
            } else {
                $header .= "    <td width='40' align='center' style='color:green'><strong>{$value['semesterID']}".sprintf("%03d", $value['programID'])."</strong></td>\n";
            }

            $header .= "    <th width='40' align='right'></th>\n";
            $header .= "    <td width='40' align='center'></td>\n";
            #$header .= "    <th width='40' align='right'>Code:</th>\n";
            #$header .= "    <td width='40' align='center'>{$value['code']}</td>\n";
            $header .= "  </tr>\n";

            #-- build the PI row
            $pirow  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $pirow .= "    <td></td>\n";
            $pirow .= "    <th align='right' valign='top'>PI:</th>\n";
            $pirow .= "    <td colspan='5'>{$value['Email1']}</td>\n";
            $pirow .= "  </tr>\n";

            #-- build the title row
            #$titlerow  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            #$titlerow .= "    <td align='center'></td>\n";
            #$titlerow .= "    <th align='right' valign='top'>Title:</th>\n";
            #$titlerow .= "    <td colspan='5'>{$value['ApplicationTitle']}</td>\n";
            #$titlerow .= "  </tr>\n";

            #-- build the first CI row
            if ($value['Email2'] != "") {
                $ci1row  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
                $ci1row .= "    <td></td>\n";
                $ci1row .= "    <th align='right' valign='top'>Co-I 1:</th>\n";
                $ci1row .= "    <td colspan='5'>{$value['Email2']}</td>\n";
                $ci1row .= "  </tr>\n";
            } else { $ci1row = ""; }

            #-- build the second CI row
            if ($value['Email3'] != "") {
                $ci2row  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
                $ci2row .= "    <td></td>\n";
                $ci2row .= "    <th align='right' valign='top'>Co-I 2:</th>\n";
                $ci2row .= "    <td colspan='5'>{$value['Email3']}</td>\n";
                $ci2row .= "  </tr>\n";
            } else { $ci2row = ""; }

            #-- build the third CI row
            if ($value['Email4'] != "") {
                $ci3row  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
                $ci3row .= "    <td></td>\n";
                $ci3row .= "    <th align='right' valign='top'>Co-I 3:</th>\n";
                $ci3row .= "    <td colspan='5'>{$value['Email4']}</td>\n";
                $ci3row .= "  </tr>\n";
            } else { $ci3row = ""; }

            #-- build the fourth CI row
            if ($value['Email5'] != "") {
                $ci4row  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
                $ci4row .= "    <td></td>\n";
                $ci4row .= "    <th align='right' valign='top'>Co-I 4:</th>\n";
                $ci4row .= "    <td colspan='5'>{$value['Email5']}</td>\n";
                $ci4row .= "  </tr>\n";
            } else { $ci4row = ""; }

            #-- build the additional CIs row
            if ($value['projectMembers'] != "") {
                $addcirow  = "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
                $addcirow .= "    <td></td>\n";
                $addcirow .= "    <th align='right' valign='top'>Observers:</th>\n";
                $addcirow .= "    <td colspan='5'>{$value['projectMembers']}</td>\n";
                $addcirow .= "  </tr>\n";
            } else { $addcirow = ""; }

            $tmp .= $header;
            $tmp .= $pirow;
            #$tmp .= $titlerow;
            if (isset($ci1row)) { $tmp .= $ci1row; }
            if (isset($ci2row)) { $tmp .= $ci2row; }
            if (isset($ci3row)) { $tmp .= $ci3row; }
            if (isset($ci4row)) { $tmp .= $ci4row; }
            if (isset($addcirow)) { $tmp .= $addcirow; }

            #-- build the email preview section
            #$tmp .= $break;
            $color = getGrayShading($color);
            $emailmessage = str_replace("<pre>", "<pre style='width: 565px; white-space:pre-wrap; overflow-wrap: break-word; word-wrap: break-word;'>", $value['message']);
            $tmp .= "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $tmp .= "    <td></td>\n";
            $tmp .= "    <th colspan='2' align='right' valign='top'>Email Preview:</th>\n";
            $tmp .= "    <td colspan='5'></td>\n";
            $tmp .= "  </tr>\n";

            $tmp .= "  <tr style='background-color: #{$color};'>\n";
            $tmp .= "    <td colspan='{$cols}'>\n";
            $tmp .= "      {$emailmessage}\n";
            $tmp .= "    </td>\n";
            $tmp .= "  </tr>\n";

            $tmp .= getHorizontalLine(0, $cols, "FFFFFF");
        }

        $tmp .= "</table>\n\n";

        //$tmp .= myFooter($debug, __FILE__, SYSAD_CONTACT, $isForm);

        #if ($debug) { echo "<h2>Line to be written: [{$tmp}]</h2>\n"; }

        #-- write the data to the people file
        $color  = "";
        if (count($announce[1]) > 0) {
            $fileid = fopen($filename, "w+");
            $bytes = fwrite($fileid, $tmp, strlen($tmp));
            if ($bytes == 0) {
                $page_error = "There was a problem writing to the obs reminder email preview file.";
                if ($debug) { echo "<h2>{$page_error}</h2>\n"; }
                #generateErrorPage($debug, $page_title, $page_error, 8, $data);
                exit;
            } else {
                if ($debug) { echo "<h1>IT WORKED! Bytes = {$bytes}</h1>\n"; }
            }
            fclose($fileid);

            $color = getGrayShading($color);
            $msg .= "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $msg .= "    <td align='center' colspan='{$cols}'>\n";
            $msg .= "      Click the link below to preview the obs reminder emails.\n";
            $msg .= "    </td>\n";
            $msg .= "  </tr>\n";

            $color = getGrayShading($color);
            $msg .= "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $msg .= "    <td align='center' colspan='{$cols}'>\n";
            $msg .= "      <a target='_blank' href='http://irtfweb.ifa.hawaii.edu{$urlpath}'>{$urlpath}</a>\n";
            $msg .= "    </td>\n";
            $msg .= "  </tr>\n";
        } else {
            $color = getGrayShading($color);
            $msg .= "  <tr style='height: {$height}px; background-color: #{$color};'>\n";
            $msg .= "    <td align='center' colspan='{$cols}'>\n";
            $msg .= "      {$announce[0]}\n";
            $msg .= "    </td>\n";
            $msg .= "  </tr>\n";
        }

        //$code .= myHeader($debug, $title, $isForm);

        #$code .= "<form enctype='multipart/form-data' target='_blank' action='{$_SERVER['PHP_SELF']}' method='get'>\n\n";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, $cols, "FFFFFF");

        $code .= $msg;

        $color = getGrayShading($color);
        $code .= getHorizontalLine(0, $cols, "FFFFFF");

        $code .= "</table>\n\n";

        #$code .= "</form>\n\n";

        //$code .= myFooter($debug, __FILE__, SYSAD_CONTACT, $isForm);
        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateObsReminderEmailPage
    ############################################################################

    #---------------------------------------------------------------------------
    # Generates and writes to file the preview observer reminder emails
    #
    function processObsReminderPreviewEmails($debug, $title, $data, $sendemails)
    {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: processObsReminderPreviewEmails({$debug}, {$title}, data, {$sendemails})</h1>\n"; }
        $message = "";
        $isForm = false;
        $proposals = 0;

        #-----------------------------------------------
        #-- retrieve schedule entries from blockwindow1 (emails should have already been sent to these programs)
        $intstart = $data['emailLeadTime'] - $data['blockWindow'];
        #$intend   = $data['emailLeadTime'];
        $intend   = $data['emailLeadTime'] + 1;
        $blockwindow1 = $this->getIntervalScheduleDB($debug, $intstart, $intend, $this->returnReminderUnit($data['units']), false);
        if ($debug) { echo "<br/>\ngetIntervalScheduleDB(blockwindow1):<br/>\n"; print_r($blockwindow1); }
        if ($debug) {
            $schedtext = array();
            $text1 = array();
            foreach ($blockwindow1 as $value) {
                $schedtext = $this->returnMiniSchedule($debug, $value, false);
                #-- schd[0] is plaintext, schd[1] is html coloured
                $text1 = array_merge($text1, $schedtext[1]);
            }
            echo "<br/>\nreturnMiniSchedule(blockwindow1):<br/>\n";
            print_r($text1);
        }

        #-----------------------------------------------
        #-- retrieve schedule entries from blockwindow2 (these are the program to which emails might need to be sent)
        $intstart = $data['emailLeadTime'];
        $blockwindow2 = $this->getIntervalScheduleDB($debug, $intstart, $intend, $this->returnReminderUnit($data['units']), true);
        if ($debug) { echo "<br/>\ngetIntervalScheduleDB(blockwindow2):<br/>\n"; print_r($blockwindow2); }
        #$text2 = $this->returnMiniSchedule($debug, $blockwindow2, false);
        #if ($debug) { echo "<br/>\nreturnMiniSchedule(blockwindow2):<br/>\n"; print_r($text2); }
        if ($debug) {
            $schedtext = array();
            $text2 = array();
            foreach ($blockwindow2 as $value) {
                $schedtext = $this->returnMiniSchedule($debug, $value, false);
                #-- schd[0] is plaintext, schd[1] is html coloured
                $text2 = array_merge($text2, $schedtext[1]);
            }
            echo "<br/>\nreturnMiniSchedule(blockwindow2):<br/>\n";
            print_r($text2);
        }

        #-----------------------------------------------
        #-- determine what programs need to have emails sent
        $curprogram = "";
        $j = 0;
        $maxj = count($blockwindow1);
        $anncemails = array();
        foreach ($blockwindow2 as $key => $value) {
            #if ($curprogram == $key) { continue; }
            if ($debug) { echo "\n\n<h1>blockwindow2[{$key}]:</h1>\n"; }
            #$curprogram = $key;

            #for (; $j < $maxj; $j++) {
            #-- check to see if blockwindow2['ProgramNumber'] exists in blockwindow1. both blockwindow arrays are
            #-- sorted on ProgramNumber so if blockwindow2's ProgramNumber is less than blockwindow1's ProgramNumber,
            #-- then the ProgramNumber isn't in blockwindow1;
            #if ($debug) { echo "({{$blockwindow1[$j]['ProgramNumber']}}blockwindow1[j={$j}]['ProgramNumber'] > {{$value['ProgramNumber']}}value['ProgramNumber']) || (({{$blockwindow1[$j]['ProgramNumber']}}blockwindow1[j={$j}]['ProgramNumber'] == {{$value['ProgramNumber']}}value['ProgramNumber']) && ({{$value['reminderEmail']}}value['reminderEmail'] != 1) && ({{$value['comments']}}value['comments'] != 'Service Obs'))<br/>\n"; }
            #if ($debug) { echo "({".(isset($blockwindow1[$key]) ? "true" : "false")."}isset(blockwindow1[{$key}]) === false) || (({".(isset($blockwindow1[$key]) ? "true" : "false")."}isset(blockwindow1[{$key}]) === true) && ({{$blockwindow1[$key][0]['reminderEmail']}}blockwindow1[$key][0]['reminderEmail'] != 1) && ({".stripos($value[0]['comments'], 'Service Obs')."}}stripos($value[0]['comments'], 'Service Obs') === false))<br/>\n"; }
            if ($debug) { echo "({".(isset($blockwindow1[$key]) ? "true" : "false")."}isset(blockwindow1[{$key}]) === false) || (({".(isset($blockwindow1[$key]) ? "true" : "false")."}isset(blockwindow1[{$key}]) === true) && ({".(isset($blockwindow1[$key]) ? "{$blockwindow1[$key][0]['reminderEmail']}" : "")."}blockwindow1[$key][0]['reminderEmail'] != 1) && ({".stripos($value[0]['comments'], 'Service Obs')."}}stripos(value[0]['comments'], 'Service Obs') === false))<br/>\n"; }
            #-- [(prog not in BW1)] OR [(prog in BW1) AND (BW1-sentemail != 1) AND (comment != 'service obs')]:
            #if (($blockwindow1[$j]['ProgramNumber'] > $value['ProgramNumber']) ||
            if ((isset($blockwindow1[$key]) === false) ||
                ((isset($blockwindow1[$key]) === true) &&
                ($blockwindow1[$key][0]['reminderEmail'] != 1) &&
                (stripos($value[0]['comments'], "Service Obs") === false))) {
                if ($data['emails'] == 1) {
                    if ($debug) { echo "if: data['emails'] == 1: {$data['emails']}<br/>\n"; }
                    #-- send real emails
                    $anncemail = $this->generateEmailMessage($debug, $sendemails, $data['emails'], $title, $value, "obsremind");
                    #-- set reminderEmail = 1;
                } elseif ($data['emails'] == 0) {
                    if ($debug) { echo "elseif: data['emails'] == 0: {$data['emails']}<br/>\n"; }
                    #-- send dummy emails
                    $anncemail = $this->generateEmailMessage($debug, $sendemails, $data['emails'], $title, $value, "obsremind");
                    #-- set reminderEmail = 2;
                } else {
                    if ($debug) { echo "foreach inner else: no emails sent<br/>\n"; }
                    #-- send no emails at all
                    #-- leave reminderEmail = 0;
                }
                if (!is_array($anncemail)) {
                    $message .= "<p align='center' style='color:red'>There was a problem generating the observing reminder email for record ".($key + 1)." (Program {$value['ProgramNumber']}).</p>\n";
                    #$proposals -= 1;
                } else {
                    $proposals += 1;
                }
                $value[0]['message'] = $anncemail['message'][1];
                $anncemails[] = $value[0];
                #break;
                continue;
            } else {
                if ($debug) { echo "foreach outer else: no emails sent<br/>\n"; }
                #-- send no emails at all
                #-- leave reminderEmail = 0;
            }
            #}
        }

        #-----------------------------------------------
        #-- update status to page
        if ($proposals == 1) {
            $message .= "<p align='center'>{$proposals} observing reminder email was added to the preview list.</p>\n";
        } else {
            $message .= "<p align='center'>{$proposals} observing reminder emails were added to the preview list.</p>\n";
        }
        if ($debug) { echo "<h1>RETURN: processObsReminderPreviewEmails({$debug}, {$title}, data, {$sendemails})</h1>\n\n\n"; }
        return array($message, $anncemails);
    }
    #-- end of processObsReminderPreviewEmails
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Return the schedule select results from the database
    #
    #function getIntervalScheduleDB($debug, $emailleadtime, $blockwindow, $unittype) {
    function getIntervalScheduleDB($debug, $schedintstart, $schedintend, $unittype, $addemail = false)
    {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: getIntervalScheduleDB({$debug}, {$schedintstart}, {$schedintend}, {$unittype}, {$addemail})</h1>\n"; }

        $schedule = array();
        #$schedintstart = $emailleadtime - $blockwindow;
        #$schedintend = $emailleadtime;
        $schedinttype = strtoupper($unittype);

        if ($addemail) {
            #$stx1 = "\n   ObsReminders.reminderEmail <> '1' AND";
            $stx1 = "";
            $stx2 = "NULL AS ";
            $from = "ScheduleObs, DailyInstrument, Program, GuestAccts";
            $where = "";
        } else {
            $stx1 = "";
            $stx2 = "ObsReminders.";
            $from = "ScheduleObs, ObsReminders, DailyInstrument, Program, GuestAccts";
            $where = "\n   ScheduleObs.semesterID = ObsReminders.semesterID AND\n   ScheduleObs.programID = ObsReminders.programID AND\n   ScheduleObs.startTime = ObsReminders.startTime AND";
        }

        $sql1 = "
SELECT DISTINCT
    ScheduleObs.semesterID,
    ScheduleObs.logID,
    ScheduleObs.startTime,
    ScheduleObs.endTime,
    (SELECT GROUP_CONCAT(operatorCode ORDER BY overlap ASC SEPARATOR ',')
     FROM DailyOperator, Operator
     WHERE DailyOperator.startTime = ScheduleObs.startTime
     AND DailyOperator.operatorID = Operator.operatorID
     GROUP BY DailyOperator.startTime
  ) AS Operators,
    ScheduleObs.remoteObs,
    ScheduleObs.programID,
    (SELECT GROUP_CONCAT(itemName SEPARATOR '/')
     FROM DailyInstrument, Hardware
     WHERE DailyInstrument.startTime = ScheduleObs.startTime
     AND DailyInstrument.hardwareID = Hardware.hardwareID
     GROUP BY DailyInstrument.startTime
  ) AS Instruments,
    ScheduleObs.SupportAstronomerID,
    ScheduleObs.daytimeObs,
    ScheduleObs.firstTime,
    ScheduleObs.comments,
    {$stx2}reminderEmail,
    {$stx2}reminderDate,
    Program.projectMembers,
    GuestAccts.username AS ProgramNumber,
    GuestAccts.defaultpwd AS code,";

        $sql2tac = "
    Program.projectPI,
    IF
      (
         (ObsApp.InvFirstName1 <=> '') OR
         (ObsApp.InvLastName1 <=> '') OR
         (ObsApp.Email1 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName1,' ',ObsApp.InvLastName1,' <',ObsApp.Email1,'>')
    ) AS Email1,
    IF
      (
         (ObsApp.InvFirstName2 <=> '') OR
         (ObsApp.InvLastName2 <=> '') OR
         (ObsApp.Email2 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName2,' ',ObsApp.InvLastName2,' <',ObsApp.Email2,'>')
    ) AS Email2,
    IF
      (
         (ObsApp.InvFirstName3 <=> '') OR
         (ObsApp.InvLastName3 <=> '') OR
         (ObsApp.Email3 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName3,' ',ObsApp.InvLastName3,' <',ObsApp.Email3,'>')
    ) AS Email3,
    IF
      (
         (ObsApp.InvFirstName4 <=> '') OR
         (ObsApp.InvLastName4 <=> '') OR
         (ObsApp.Email4 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName4,' ',ObsApp.InvLastName4,' <',ObsApp.Email4,'>')
    ) AS Email4,
    IF
      (
         (ObsApp.InvFirstName5 <=> '') OR
         (ObsApp.InvLastName5 <=> '') OR
         (ObsApp.Email5 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName5,' ',ObsApp.InvLastName5,' <',ObsApp.Email5,'>')
    ) AS Email5

FROM
   {$from}, ObsApp
WHERE
   ScheduleObs.semesterID = CONCAT(ObsApp.semesterYear,ObsApp.semesterCode) AND
   ScheduleObs.programID = ObsApp.ProgramNumber AND";

        $sql2eng = "
   EngProgram.projectPI,
   IF
      (
         (EngProgram.PIName <=> '') OR
         (EngProgram.PIEmail <=> ''),
         NULL,
         CONCAT(EngProgram.PIName,' <',EngProgram.PIEmail,'>')
    ) AS Email1,
   NULL AS Email2,
   NULL AS Email3,
   NULL AS Email4,
   NULL AS Email5
FROM
   {$from}, EngProgram
WHERE
   ScheduleObs.programID <> '999' AND
   ScheduleObs.semesterID = EngProgram.semesterID AND
   ScheduleObs.programID = EngProgram.programID AND";

        $sql3 = "{$stx1}
   ScheduleObs.logID = DailyInstrument.logID AND
   ScheduleObs.programID = DailyInstrument.programID AND
   ScheduleObs.semesterID = Program.semesterID AND
   ScheduleObs.programID = Program.programID AND{$where}
   GuestAccts.username = CONCAT(ScheduleObs.semesterID,LPAD(ScheduleObs.programID,3,'0')) AND
   ScheduleObs.startTime BETWEEN UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL {$schedintstart} ${schedinttype})) AND UNIX_TIMESTAMP(DATE_ADD(NOW(),INTERVAL {$schedintend} ${schedinttype}))";

        $sql4 = "
ORDER BY
    ProgramNumber, logID, startTime;";

   #$sql = "{$sql1}{$sql2}{$sql3}{$sql4}";

        $sql = "
({$sql1}{$sql2eng}{$sql3}
)

UNION

({$sql1}{$sql2tac}{$sql3}
)
{$sql4}";

        if ($debug) {
            echo "<hr/>\n";
            echo "<h1>SQL strings for database schedule selects:</h1>\n";
            echo "<h3>Schedule select:</h3>\n";
            echo "SQL:  <br/>\n";
            echo "{$sql}<br/>\n";
            echo "<hr/>\n";
        }

        $dbc = connectDBtroublelog($debug);
        # retrieve data for this semester (returns result set)
        $result = mysqli_query($dbc, $sql) or die ("Error retrieving Schedule info from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) {
            #$schedule[$row['ProgramNumber']][] = $row;
            $schedule[$row['ProgramNumber']][] = returnQuotes(str_replace("INTERNALLINEFEEDHERE", "\n", $row));
        }
        disconnectMysql($debug, $dbc, $result);

        if ($debug && isset($schedule)) {
            $keycut = 20;
            $keycut = 50;
            echo "<hr/>\n";
            echo "<h1>Schedule information from the database:</h1>\n";
            foreach ($schedule as $i => $value) {
                echo "Line [{$i}]: ".print_r($value,true)." <br/>\n";
                if ($i > $keycut) { break; }
            }
            echo "<hr/>\n";
        }

        if ($debug) { echo "<h1>RETURN: getIntervalScheduleDB({$debug}, {$schedintstart}, {$schedintend}, {$unittype}, {$addemail})</h1>\n\n\n"; }
        return $schedule;
    }
    #-- end of getIntervalScheduleDB
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Return the constructed text schedule array for the plain schedule listing
    #
    # return[0] => schedule array in plain text
    # return[1] => schedule array in coloured html
    # return[2] => array of last obs date, next obs date
    #
    function returnMiniSchedule($debug, $schedule, $defaultdays = true, $textonly = true)
    {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: returnMiniSchedule({$debug}, schedule, {$defaultdays}, {$textonly})</h1>\n"; }
        $month = "";
        $oldday = "";
        $text1 = array(); // text schedule
        $text2 = array(); // html schedule
        $obs   = array('startTime' => -1, 'endTime' => -1); // last obs, next obs dates
        $comflags = $this->getCommentsList($debug);
        $now = time();

        foreach ($schedule as $i => $value) {
            #-- get the current month, so we can check for a new month
            #set mon  [getMonth [lindex $date 0]] (returns full month in caps)
            $mon = strtoupper(date("F", $value['startTime']));
            $year = date("Y", $value['startTime']);
            $semyr = $year;
            if ($i == 0) {
                #-- generate column header
                $text1[] = "HST Date    HST Time     TO Remote Prog & PI                Instr                SA  Comments";
                $text2[] = "HST Date    HST Time     TO Remote Prog & PI                Instr                SA  Comments";
            }

            #-- if this is a new month, generate new header
            if ($month != $mon) {
                $month = $mon;
            }

            #-- check if this is a new date
            $day2 = sprintf("%2d", date("j", $value['startTime']));
            if ($oldday != $day2 || !$defaultdays) {
                $day1 = date("M.", $value['startTime']);
                $day3 = date("D", $value['startTime']);
                if (($day3 == "Sat" || $day3 == "Sun") && $defaultdays) {
                    $day3 = "--";
                } else {
                    $day3 = substr($day3, 0, 2);
                }
                $sdate = "{$day1} {$day2} {$day3} ";
                $oldday = $day2;
            } else {
                $sdate = str_pad("", 11, " ");
            }

            #-- this is a normal observing slot
            #-- pad the start and end time column
            $stime = sprintf("%-5s", date("H:i", $value['startTime']));
            $etime = sprintf("%-5s", date("H:i", $value['endTime']));
            #-- pad the program number column
            $prog  = sprintf("%-3s", pad_num(3, $value['programID']));
            #-- pad the observer/PI column
            $pi    = sprintf("%-20s", returnQuotes($value['projectPI']));
            #-- pad the instrument column
            $ins   = sprintf("%-20s", $value['Instruments']);
            #-- pad the support astronomer column
            $sa    = sprintf("%-3s", $value['SupportAstronomerID']);
            #-- pad the comments column
            $comments = array();
            if ($value['daytimeObs'] == 1) { $comments[] = $comflags[0]; }
            if ($value['firstTime'] == 1)  { $comments[] = $comflags[1]; }
            if ($value['comments'] != "")  { $comments[] = $value['comments']; }
            $com   = implode(", ", $comments);

            #-- pad the telescope operator column
            $op    = sprintf("%-6s", $value['Operators']);
            #-- pad the remote observing flag column
            if ($value['remoteObs'] == 1) { $rem = "X"; } else { $rem = ""; }
            $remob = sprintf("%-2s", $rem);

            #-- build string to be sent to file
            if ($value['startTime'] > $now) {
                $cs = "<span style='color: blue;'>";
                $ce = "</span>";
                if ($obs['startTime'] == -1) { $obs['startTime'] = $value['startTime']; }
            } else {
                $cs = "<span style='color: grey;'>";
                $ce = "</span>";
                $obs['endTime'] = $value['endTime'];
            }
            $text1[] = "{$sdate} {$stime} {$etime}  {$op} {$remob} {$prog} {$pi} {$ins} {$sa} {$com}";
            $text2[] = "{$cs}{$sdate} {$stime} {$etime}  {$op} {$remob} {$prog} {$pi} {$ins} {$sa} {$com}{$ce}";

            #-- store the additional observer list if necessary
            if ($value['projectMembers'] != "") {
                $observers[$value['programID']] = "{$prog} ".returnQuotes($value['projectMembers']);
            }
        }

        #-- return the text schedule output
        if ($debug) { echo "<h1>RETURN: returnMiniSchedule({$debug}, schedule, {$defaultdays}, {$textonly})</h1>\n\n\n"; }
        return array($text1, $text2, $obs);
    }
    #-- end of returnMiniSchedule
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Return an array with the static comments list
    # (NOTE: this function exists in /home/proposal/public_html/inc/helpter.inc
    #        and /htdocs/observing/application/process_applications*.inc.
    #        REMEMBER to update all files!)
    #
    function getCommentsList($debug)
    {
        $comflags = array("Daylight Obs", "First Night", "Service Obs", "facility open", "facility close", "inst. change");

        if ($debug) {
            echo "<h1>COMMENTS SETUP:</h1>\n";
            print_r($comflags);
        }

        return $comflags;
    }
    #-- end of getCommentsList
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    #-- start of initialObsReminderData
    function initialObsReminderData($debug)
    {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: initialObsReminderData({$debug})</h1>\n"; }

        #-- initialize form data
        $data['emailLeadTime'] = 3;
        $data['blockWindow']   = 10;
        $data['serviceobscm']  = "Service Obs.";
        $data['units']         = 1; // 1 = DAY, 0 = WEEK
        $data['emails']        = 0; // 1 = send emails, 0 = dummy emails

        if ($debug) {
            echo "<h1>initialObsReminderData() - INITIALIZED mydata ARRAY OUTPUT:</h1>\n";
            print_r($data);
        }

        #-- return harvested array
        if ($debug) { echo "<h1>RETURN: initialObsReminderData({$debug})</h1>\n\n\n"; }
        return $data;
    }
    #-- end of initialObsReminderData
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    #-- start of harvestObsReminderData
    function harvestObsReminderData($debug, $datadst, $datasrc)
    {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: harvestObsReminderData({$debug})</h1>\n"; }

        #-- harvest form data
        $datadst['emailLeadTime'] = $datasrc['emailLeadTime'];
        $datadst['blockWindow']   = $datasrc['blockWindow'];
        #$datadst['serviceobscm']  = $datasrc['serviceobscm']; // hardcoded via initial setup
        $datadst['units']         = $datasrc['units'];
        $datadst['emails']        = $datasrc['emails'];

        if ($debug) {
            echo "<h1>harvestObsReminderData() - HARVESTED mydata ARRAY OUTPUT:</h1>\n";
            print_r($datadst);
        }

        #-- return harvested array
        if ($debug) { echo "<h1>RETURN: harvestObsReminderData({$debug})</h1>\n\n\n"; }
        return $datadst;
    }
    #-- end of harvestObsReminderData
    #---------------------------------------------------------------------------


    #---------------------------------------------------------------------------
    # Returns the strings for the scientific categories pulldown
    #
    function returnReminderUnit($num)
    {
        switch ($num) {
            case 0:
                return "week";
                break;
            case 1:
                return "day";
                break;
            default:
                return $num;
        }
    }
    #-- end of returnReminderUnit
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Returns the index for the Reminder Unit strings
    #
    function returnReminderUnitNum($str)
    {
       switch ($str) {
          case "week":
             return 0;
             break;
          case "day":
             return 1;
             break;
          default:
             return $str;
       }
    }
    #-- end of returnReminderUnitNum
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Generates the confirmation email bodies (emailflag: submit, save, confirm, etc)
    #
    # $sendemails1 - boolean variable set in ProcessApplications.php (like $debug)
    # $sendemails2 - variable that defaults to 1, on some staff forms this can be
    #                set to 1 or 0 to toggle sending real email to
    #                proposers/guestaccts/etc or sending fake email to walters/hawarden
    #
    function generateEmailMessage($debug, $sendemails1, $sendemails2, $title, $data, $emailflag)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: generateEmailMessage({$debug}, {$sendemails1}, {$sendemails2}, {$title}, data, {$emailflag})</h1>\n"; }

        if ($debug) {
            echo "\ngenerateEmailMessage() - data = \n";
            print_r($data);
        }

        #-- set header information for emails; these are the default settings that
        #-- can be changed within the individual functions for the specific email
        #-- types; Setting these here allows us to ignore them in the functions
        #-- that use the same defaults;

        #-- set system admin, admin person, scheduler, director
        #-- system administrator
        $tmp = getContactInfoAutoEmails($debug, "sysad");
        $header['sys']['ifaemail'] = $tmp['email1'];
        $header['sys']['ademail']  = $tmp['email2'];
        $header['sys']['name']     = $tmp['name'];
        $header['sys']['phone']    = $tmp['phone'];
        #-- administrator
        $tmp = getContactInfoAutoEmails($debug, "admin");
        $header['adm']['ifaemail'] = $tmp['email1'];
        $header['adm']['ademail']  = $tmp['email2'];
        $header['adm']['name']     = $tmp['name'];
        $header['adm']['phone']    = $tmp['phone'];
        #-- scheduler
        $tmp = getContactInfoAutoEmails($debug, "scheduler");
        $header['sch']['ifaemail'] = $tmp['email1'];
        $header['sch']['ademail']  = $tmp['email2'];
        $header['sch']['name']     = $tmp['name'];
        $header['sch']['phone']    = $tmp['phone'];
        #-- director
        $tmp = getContactInfoAutoEmails($debug, "director");
        $header['dir']['ifaemail'] = $tmp['email1'];
        $header['dir']['ademail']  = $tmp['email2'];
        $header['dir']['name']     = $tmp['name'];
        $header['dir']['phone']    = $tmp['phone'];

        #-- set sender
        if ($debug) {
            #$header['fromaddr'] = "{$header['adm']['ademail']}";
            $header['fromaddr'] = "{$header['adm']['ifaemail']}";
            $header['fromname'] = "{$header['adm']['name']}";
            #$header['replyto'] = "{$header['sys']['name']} <{$header['sys']['ifaemail']}>";
            $header['replyto']  = "{$header['sys']['name']} <{$header['sys']['ademail']}>";
        } else {
            #$header['fromaddr'] = "{$header['adm']['ademail']}";
            $header['fromaddr'] = "{$header['adm']['ifaemail']}";
            $header['fromname'] = "{$header['adm']['name']}";
            #$header['replyto']  = "{$header['adm']['name']} <{$header['adm']['ademail']}>, {$header['sys']['name']} <{$header['sys']['ademail']}>";
            #$header['replyto']  = "{$header['adm']['name']} <{$header['adm']['ifaemail']}>, {$header['sys']['name']} <{$header['sys']['ademail']}>";
            #$header['replyto']  = "{$header['adm']['name']} <{$header['adm']['ifaemail']}>, {$header['sys']['name']} <{$header['sys']['ifaemail']}>";
            $header['replyto'] = "{$header['sys']['name']} <{$header['sys']['ademail']}>";
        }

        $header['url'] = "http://irtfweb.ifa.hawaii.edu/~proposal/";
        $header['editprop'] = "http://irtfweb.ifa.hawaii.edu{$_SERVER['PHP_SELF']}";

        $header['footer1'] = "NASA Infrared Telescope Facility
        Institute for Astronomy
        640 North A`ohoku Place
        Hilo, HI 96720
        Phone:  {$header['adm']['phone']}
        Fax:  (808) 933-0737
        E-mail:  {$header['adm']['ifaemail']}";

        $header['footer2'] = "<p>\nNASA Infrared Telescope Facility<br/>
        Institute for Astronomy<br/>
        640 North A`ohoku Place<br/>
        Hilo, HI 96720<br/>
        Phone:  {$header['adm']['phone']}<br/>
        Fax:  (808) 933-0737<br/>
        E-mail:  <a href='mailto:{$header['adm']['ifaemail']}'>{$header['adm']['ifaemail']}</a><br/>
        </p>";

        #-- empty values that are set in the various functions below
        $header['subject']   = "";
        $header['addressee'] = "";
        $header['ccto']      = "";
        $header['msgbody1']  = "";
        $header['msgbody2']  = "";
        $header['pdfdir']    = "";
        $header['urlkey']    = "";

        #-- set the values that differ for the various emails
        switch ($emailflag) {
            case "obsremind":
                #------------------------------------------------------
                #-- reminder information about imminent observing time (sent from miranda)
                $tmp = $this->generateEmailObserverReminder($debug, $sendemails1, $sendemails2, $header, $data);
                $header = $tmp['headers'];
                $data   = $tmp['data'];
                break;
        } #-- end of email switch;

        $boundary = '-----=' . md5(rand());

        $headers  = "From: {$header['fromname']} <{$header['fromaddr']}>\n";
        $headers .= "Reply-To: {$header['replyto']}\n";
        $headers .= "Date: ".date('r')."\n";
        $headers .= "Message-ID: <".time()."-{$header['fromaddr']}>\n";
        $headers .= "X-Mailer: PHP v".phpversion()."\n";
        $headers .= "MIME-Version: 1.0\n";
        #$headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\n\n";

        $message1  = "--{$boundary}\n";
        $message1 .= "Content-Type: text/plain; charset=utf-7\n";
        $message1 .= "Content-Transfer-Encoding: 7bit\n\n";

        $message2  = "--{$boundary}\n";
        $message2 .= "Content-Type: text/html; charset=ISO-8859-1\n";
        $message2 .= "Content-Transfer-Encoding: 7bit\n\n";

        #-- construct the To section
        if ($emailflag != "accepted" && $emailflag != "rejected" &&
            isset($data['PIName']) && $data['PIName'] != "") {
            $piname1 = "TO: " . replaceDoubleQuotes($data['PIName']) . "\n";
            $piname2 = "<p><strong>TO</strong>: " . replaceDoubleQuotes($data['PIName']) . "</p>\n";
        } else { $piname1 = ""; $piname2 = ""; }

        #-- construct the PIEmail section
        if ($emailflag != "accepted" && $emailflag != "rejected" &&
            $emailflag != "confirm" && $emailflag != "guestacct" &&
            $emailflag != "obsremind" && $emailflag != "feedremind" &&
            isset($data['PIEmail']) && $data['PIEmail'] != "" ) {
            $piemail1 = "\nPI EMAIL: {$data['PIEmail']}\n";
            $piemail2 = "\n<p><strong>PI EMAIL</strong>: {$data['PIEmail']}</p>\n";
        } else { $piemail1 = ""; $piemail2 = ""; }

        #-- construct the Title section
        if ($emailflag != "accepted" && $emailflag != "rejected" &&
            isset($data['ApplicationTitle']) && $data['ApplicationTitle'] != "") {
            $apptitle1 = "\nTITLE: " . trim(html_entity_decode(replaceDoubleQuotes($data['ApplicationTitle']), ENT_QUOTES, "UTF-8")) . "\n";
            $apptitle2 = "\n<p><strong>TITLE</strong>: " . replaceDoubleQuotes($data['ApplicationTitle']) . "</p>\n";
        } else { $apptitle1 = ""; $apptitle2 = ""; }

        #-- if this is not a proposal submission/confirmation email, skip the pdf stuff
        if ($emailflag == "submit" || $emailflag == "save" || $emailflag == "confirm") {
            #-- construct the SessionID/ProgramNum section
            if ($emailflag == "confirm") {
                if (isset($data['ProgramNumber']) && $data['ProgramNumber'] != "" ) {
                    $code1 = "\nPROPOSAL NO.: {$data['semesterYear']}{$data['semesterCode']}".sprintf("%03d", $data['ProgramNumber'])."\n";
                    $code2 = "\n<p><strong>PROPOSAL NO.</strong>: {$data['semesterYear']}{$data['semesterCode']}".sprintf("%03d", $data['ProgramNumber'])."</p>\n";
                } else { $code1 = ""; $code2 = ""; }
            } else {
                if (isset($data['code']) && $data['code'] != "") {
                    $code1 = "\nSESSION CODE: {$data['code']}\n";
                    $code2 = "\n<p><strong>SESSION CODE</strong>: {$data['code']}</p>\n";
                } else { $code1 = ""; $code2 = ""; }
            }

            #-- construct the proposal pdf section
            if (isset($data['ProposalFileName']) && $data['ProposalFileName'] != "") {
                $username  = normalUnixString($data['InvLastName1']) . substr($data['InvFirstName1'], 0, 1);
                $username  = "{$data['code']}_" . strtolower($username);
                $password = "{$data['code']}";
                $pdfdirpath = $data['FilePathName'];
                $pdffilelink = "{$header['url']}{$header['pdfdir']}{$pdfdirpath}/" . basename($data['ProposalFileName']);
                $pdffileemail1 = "\nPROPOSAL FILE:\n{$pdffilelink}\n";
                $pdffileemail2 = "\n<p><strong>PROPOSAL FILE</strong>:\n<a target='_blank' href='{$pdffilelink}'>{$pdffilelink}</a></p>\n";
                $userpwd1 = "\nUSERNAME: {$username}\n\nPASSWORD: {$password}\n";
                $userpwd2 = "\n<p><strong>USERNAME</strong>: {$username}</p>\n\n<p><strong>PASSWORD</strong>: {$password}</p>\n";
            } else { $pdffileemail1 = ""; $pdffileemail2 = ""; $pdffilelink = ""; $userpwd1 = ""; $userpwd2 = ""; }
        } elseif ($emailflag == "guestacct") {
            #-- construct the SessionID/ProgramNum section
            if (isset($data['ProgramNumber']) && $data['ProgramNumber'] != "" ) {
                $code1 = "\nPROPOSAL NO.: {$data['semesterYear']}{$data['semesterCode']}".sprintf("%03d", $data['ProgramNumber'])."\n";
                $code2 = "\n<p><strong>PROPOSAL NO.</strong>: {$data['semesterYear']}{$data['semesterCode']}".sprintf("%03d", $data['ProgramNumber'])."</p>\n";
            } else { $code = ""; }
            if (isset($data['code']) && $data['code'] != "") {
                $code1 .= "\nSESSION CODE: {$data['code']}\n";
                $code2 .= "\n<p><strong>SESSION CODE</strong>: {$data['code']}</p>\n";
            }
            $pdffileemail1 = "";
            $pdffileemail2 = "";
            $pdffilelink = "";
            $userpwd1 = "";
            $userpwd2 = "";
        } else {
            $code1 = "";
            $code2 = "";
            $pdffileemail1 = "";
            $pdffileemail2 = "";
            $pdffilelink = "";
            $userpwd1 = "";
            $userpwd2 = "";
        }

        $header['msgbody1'] = wordwrap($header['msgbody1'], 75, "\n");

        $message[] = "\n{$piname1}{$piemail1}{$code1}{$apptitle1}{$pdffileemail1}{$userpwd1}\n{$header['msgbody1']}\n{$header['footer1']}\n";
        $message[] = "\n{$piname2}{$piemail2}{$code2}{$apptitle2}{$pdffileemail2}{$userpwd2}\n{$header['msgbody2']}\n{$header['footer2']}\n";

        #$body = $message1 . $message . "\n";
        #$body = $message1 . $message . "\n--" . $boundary . "--\n\n";
        $body = $message1 . $message[0] . $message2 . $message[1] . "\n--" . $boundary . "--\n\n";

        if ($debug) {
            #echo "\n\n\n<pre>\nraw email text:\n\nheaders:\n".htmlentities($headers,ENT_QUOTES, "UTF-8")."\n\naddressee:\n".htmlentities($header['addressee'],ENT_QUOTES, "UTF-8")."\n\nsubject:\n".htmlentities($header['subject'],ENT_QUOTES, "UTF-8")."\n\nbody:\n".htmlentities(wordwrap($body, 75, "\n"),ENT_QUOTES, "UTF-8")."\n</pre>\n\n\n";
            echo "\n\n\n<pre>\nraw email text:\n\nheaders:\n".htmlentities($headers,ENT_QUOTES, "UTF-8")."\n\naddressee:\n".htmlentities($header['addressee'],ENT_QUOTES, "UTF-8")."\n\nsubject:\n".htmlentities($header['subject'],ENT_QUOTES, "UTF-8")."\n\nbody:\n".htmlentities($body,ENT_QUOTES, "UTF-8")."\n</pre>\n\n\n";
        }

        if ($sendemails1 && $sendemails2 == 1) {
            if (!mail($header['addressee'], $header['subject'], $body, $headers)) {
                if ($emailflag != "confirm") {
                    $error = "There was a problem sending the email.";
                    echo generateErrorPage($debug, $title, $error, 8, $data);
                }
                return 0;
            } elseif ($emailflag == "obsremind") {
                #-- mark this program/schedule as real-obsremind-emailed
                $this->updateDBObsReminderEmail($debug, $data, 1);
            }
        } elseif ($sendemails1 == 2 && $sendemails2 == 2) {
            #-- this email request is coded as "dummy" and no emails should
            #-- be sent, but the message should be returned for previewing
            #if ($emailflag == "obsremind") {
                #-- mark this program/schedule as not-obsremind-emailed
                #$this->updateDBObsReminderEmail($debug, $data, 0);
            #}
        } else {
            $header['addressee'] = "{$header['replyto']}";
            if (!mail($header['addressee'], $header['subject'], $body, $headers)) {
                if ($emailflag != "confirm") {
                    $error = "There was a problem sending the email in debug mode.";
                    echo generateErrorPage($debug, $title, $error, 8, $data);
                }
                return 0;
            } elseif ($emailflag == "obsremind") {
                #-- mark this program/schedule as dummy-obsremind-emailed
                $this->updateDBObsReminderEmail($debug, $data, 2);
            }
        }

        $emaildata['piemail']   = $data['PIEmail'];
        $emaildata['code']      = $data['code'];
        $emaildata['pdfemail']  = $pdffilelink;
        $emaildata['pdflink']   = $pdffilelink;
        $emaildata['addressee'] = $header['addressee'];
        $emaildata['sender']    = $header['fromaddr'];
        $emaildata['subject']   = $header['subject'];
        $emaildata['message']   = $message;

        if ($debug) { echo "<h1>RETURN: generateEmailMessage({$debug}, {$sendemails1}, {$sendemails2}, {$title}, data, {$emailflag})</h1>\n\n\n"; }
        return $emaildata;
    }
    #-- end of generateEmailMessage
    #---------------------------------------------------------------------------

#---------------------------------------------------------------------------
# Generates the observer reminder specific email parts
#
function generateEmailObserverReminder( $debug, $sendemails1, $sendemails2, $header, $data ) {

   if ( $debug ) { echo "<br/>\n\n\n<h1>START: generateEmailObserverReminder( {$debug}, {$sendemails1}, {$sendemails2}, header, data )</h1>\n"; }

   if ( $debug ) {
      echo "\ngenerateEmailObserverReminder() - headers = \n";
      print_r( $header );
      echo "\ngenerateEmailObserverReminder() - data = \n";
      print_r( $data );
   }

   #------------------------------------------------------
   #-- reminder information about imminent observing time (sent from miranda)

   #-- retrieve schedule information for this program
   $progsched = $this->getProgramScheduleDB( $debug, $data[0]['ProgramNumber'], true );
   if ( count( $progsched ) > 0 ) {
      $schedtext = $this->returnMiniSchedule( $debug, $progsched, false );
   } else {
      $schedtext = array( "--- no entries currently present on schedule ---" );
   }
   #-- schd[0] is plaintext, schd[1] is html coloured
   $schdbody1 = implode( "\n", $schedtext[0] );
   $schdbody2 = implode( "\n", $schedtext[1] );
   #$interval  = floor(($data[0]['logID'] - time()) / 86400);
   $interval  = date("D, M d Y H:i:s T", $data[0]['startTime']);
   $removal   = date("D, M d Y", $data[0]['startTime'] + (DATA_GRACE_DAYS * 86400));

   #-- set from address, since different from default
   if ( $debug ) {
      $header['fromaddr'] = "{$header['sys']['ademail']}";
      $header['fromname'] = "{$header['sys']['name']}";
      $header['replyto']  = "{$header['sys']['name']} <{$header['sys']['ademail']}>";
   } else {
      $header['fromaddr'] = "{$header['sys']['ademail']}";
      $header['fromname'] = "{$header['sys']['name']}";
      $header['ccto']     = "{$header['sch']['name']} <{$header['sch']['ademail']}>, Mike Connelley <msconnelley@gmail.com>";
      #$header['replyto']  = "{$header['replyto']}, {$header['sch']['name']} <{$header['sch']['ademail']}>";
      $header['replyto']  = "{$header['replyto']}, {$header['ccto']}";
   }

   #-- make sure that emails are always sent unless $sendemails1 is false.
   #-- make sure that emails are never sent (still testing).
   #$sendemails1 = 2;
   #$sendemails2 = 2;

   #-- set addressee
   if ( $data[0]['Email1'] != "" && !$debug ) {
      $header['addressee'] = replaceDoubleQuotes( $data[0]['Email1'] );
      for ( $i = 2; $i <= 5; $i++ ) {
         $inx = "Email{$i}";
         if ( isset($data[0][$inx]) && $data[0][$inx] != "" ) { $header['addressee'] = "{$header['addressee']}, " . replaceDoubleQuotes( $data[0][$inx] ); }
      }
      $header['addressee'] = "{$header['addressee']}, {$header['replyto']}";
   } else { $header['addressee'] = $header['replyto']; }
   $data['PIEmail'] = $data[0]['Email1'];
   $data['code']    = $data[0]['code'];

   #-- set subject
   #$header['subject'] = "Observing time reminder for IRTF Program {$data[0]['ProgramNumber']} (" . replaceDoubleQuotes( $data[0]['projectPI'] ) . ")";
   #$header['subject'] = "IRTF Observing Reminder (Program {$data[0]['ProgramNumber']} " . replaceDoubleQuotes( $data[0]['projectPI'] ) . ")";
   #$header['subject'] = "IRTF Observing Reminder ({$data[0]['ProgramNumber']} " . replaceDoubleQuotes( $data[0]['projectPI'] ) . ")";
   $header['subject'] = "IRTF Observing and ORF Reminder ({$data[0]['ProgramNumber']} " . replaceDoubleQuotes( $data[0]['projectPI'] ) . ")";

   #-- set pdf link
   $header['pdfdir'] = "proposals/";

   #-- set message body
   $header['msgbody1'] = "This automated email has been sent by the IRTF system to remind you that Program {$data[0]['ProgramNumber']} has observing time scheduled on the IRTF starting {$interval}.

ORFs are required for all programs. If you are observing remotely and have not yet completed your ORF, please do so as soon as possible using the form here: http://irtfweb.ifa.hawaii.edu/observing/orf

Here is the schedule excerpt listing all {$data[0]['semesterID']} time for this program. Blue indicates future observing time:
{$schdbody1}

If you are remote observing with the IRTF, please refer to the vnc page ( http://irtfweb.ifa.hawaii.edu/observing/computer/vnc.php#1.2) and pay particular attention to the requirements.

If you have forgotten or do not know how to operate your scheduled instrument, please contact your support astronomer.

Also, please be aware that data files remain in /scrs1 for ".DATA_GRACE_DAYS." days before they are removed by the automatic cleanup script. Be sure to download your data prior to its removal around {$removal}.

Please feel free to contact me or your support astronomer should you have any questions.\n";
   $header['msgbody2'] = "<p style='text-align: justify;'>
This automated email has been sent by the IRTF system to remind you that Program {$data[0]['ProgramNumber']} has observing time scheduled on the IRTF starting <span style='color: blue;'>{$interval}</span>.
</p>

<p style='text-align: justify; font-weight: bold; font-size: 115%; color: maroon;'>
ORFs are required for all programs. If you are observing remotely and have not yet completed your ORF, please do so as soon as possible using the form here: <a href='http://irtfweb.ifa.hawaii.edu/observing/orf'>http://irtfweb.ifa.hawaii.edu/observing/orf</a>
</p>

<p style='text-align: justify;'>
Here is the schedule excerpt listing all {$data[0]['semesterID']} time for this program. Blue indicates future observing time:
</p>

<table>
   <tr>
      <td>
<pre>
{$schdbody2}
</pre>
      </td>
   </tr>
</table>

<p style='text-align: justify;'>
If you are remote observing with the IRTF, please refer to the vnc page (<a href='http://irtfweb.ifa.hawaii.edu/observing/computer/vnc.php#1.2'>http://irtfweb.ifa.hawaii.edu/observing/computer/vnc.php#1.2</a>) and pay particular attention to the requirements.
</p>

<p style='text-align: justify;'>
If you have forgotten or do not know how to operate your scheduled instrument, please contact your support astronomer.
</p>

<p style='text-align: justify;'>
Also, please be aware that data files remain in /scrs1 for ".DATA_GRACE_DAYS." days before they are removed by the automatic cleanup script. Be sure to download your data prior to its removal around <span style='color: blue;'>{$removal}</span>.
</p>

<p style='text-align: justify;'>
Please feel free to contact me or your support astronomer should you have any questions.
</p>\n";

   $header['footer1'] = "Thanks!
Miranda

Miranda Hawarden-Ogata | (808) 640-7694 (cell)
Network Engineer | NASA IRTF, MKOCN
Institute for Astronomy | hawarden@hawaii.edu
640 North Aohoku Place; Hilo, HI 96720
";
   $header['footer2'] = "<p>\nThanks!<br/>
Miranda</p>
<p>
Miranda Hawarden-Ogata | (808) 640-7694 (cell)<br/>
Network Engineer | NASA IRTF, MKOCN<br/>
Institute for Astronomy | <a href='mailto:hawarden@hawaii.edu'>hawarden@hawaii.edu</a><br/>
640 North Aohoku Place; Hilo, HI 96720<br/>
</p>
";

   if ( $debug ) { echo "<h1>RETURN: generateEmailObserverReminder( {$debug}, {$sendemails1}, {$sendemails2}, header, data )</h1>\n\n\n"; }

   return array( 'headers' => $header, 'data' => $data );
}
#-- end of generateEmailObserverReminder
#---------------------------------------------------------------------------

#---------------------------------------------------------------------------
# Return the schedule select results from the database
#
function getProgramScheduleDB( $debug, $program, $getOp = false ) {

   if ( $debug ) { echo "<br/>\n<hr/>\n\n\n<h1>START: getProgramScheduleDB( {$debug}, {$program}, {$getOp} )</h1>\n"; }

   $schedule = array();
   $operator = "NULL AS Operators";
   if ( $getOp !== false ) {
      $operator = "(SELECT GROUP_CONCAT(operatorCode ORDER BY overlap ASC SEPARATOR ',')
    FROM DailyOperator, Operator
    WHERE DailyOperator.startTime = ScheduleObs.startTime
    AND DailyOperator.operatorID = Operator.operatorID
    GROUP BY DailyOperator.startTime
   ) AS Operators";
   }
#   ScheduleObs.reminderEmail,
   $sql1 = "
SELECT DISTINCT
   ScheduleObs.semesterID,
   ScheduleObs.logID,
   ScheduleObs.startTime,
   ScheduleObs.endTime,
   {$operator},
   ScheduleObs.remoteObs,
   ScheduleObs.programID,
   (SELECT GROUP_CONCAT(itemName SEPARATOR '/')
    FROM DailyInstrument, Hardware
    WHERE DailyInstrument.startTime = ScheduleObs.startTime
    AND DailyInstrument.hardwareID = Hardware.hardwareID
    GROUP BY DailyInstrument.startTime
   ) AS Instruments,
   ScheduleObs.SupportAstronomerID,
   ScheduleObs.daytimeObs,
   ScheduleObs.firstTime,
   ScheduleObs.comments,
   Program.projectMembers,
   GuestAccts.username AS ProgramNumber,
   GuestAccts.defaultpwd AS code,";
   $sql2tac = "
   Program.projectPI,
   IF
      (
         (ObsApp.InvFirstName1 <=> '') OR
         (ObsApp.InvLastName1 <=> '') OR
         (ObsApp.Email1 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName1,' ',ObsApp.InvLastName1,' <',ObsApp.Email1,'>')
      ) AS Email1,
   IF
      (
         (ObsApp.InvFirstName2 <=> '') OR
         (ObsApp.InvLastName2 <=> '') OR
         (ObsApp.Email2 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName2,' ',ObsApp.InvLastName2,' <',ObsApp.Email2,'>')
      ) AS Email2,
   IF
      (
         (ObsApp.InvFirstName3 <=> '') OR
         (ObsApp.InvLastName3 <=> '') OR
         (ObsApp.Email3 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName3,' ',ObsApp.InvLastName3,' <',ObsApp.Email3,'>')
      ) AS Email3,
   IF
      (
         (ObsApp.InvFirstName4 <=> '') OR
         (ObsApp.InvLastName4 <=> '') OR
         (ObsApp.Email4 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName4,' ',ObsApp.InvLastName4,' <',ObsApp.Email4,'>')
      ) AS Email4,
   IF
      (
         (ObsApp.InvFirstName5 <=> '') OR
         (ObsApp.InvLastName5 <=> '') OR
         (ObsApp.Email5 <=> ''),
         NULL,
         CONCAT(ObsApp.InvFirstName5,' ',ObsApp.InvLastName5,' <',ObsApp.Email5,'>')
      ) AS Email5

FROM
   ScheduleObs, DailyInstrument, Program, GuestAccts, ObsApp
WHERE
   ScheduleObs.semesterID = CONCAT(ObsApp.semesterYear,ObsApp.semesterCode) AND
   ScheduleObs.programID = ObsApp.ProgramNumber AND";
   $sql2eng = "
   EngProgram.projectPI,
   IF
      (
         (EngProgram.PIName <=> '') OR
         (EngProgram.PIEmail <=> ''),
         NULL,
         CONCAT(EngProgram.PIName,' <',EngProgram.PIEmail,'>')
      ) AS Email1,
   NULL AS Email2,
   NULL AS Email3,
   NULL AS Email4,
   NULL AS Email5
FROM
   ScheduleObs, DailyInstrument, Program, GuestAccts, EngProgram
WHERE
   ScheduleObs.semesterID = EngProgram.semesterID AND
   ScheduleObs.programID = EngProgram.programID AND";
   $sql3 = "
   ScheduleObs.logID = DailyInstrument.logID AND
   ScheduleObs.programID = DailyInstrument.programID AND
   ScheduleObs.semesterID = Program.semesterID AND
   ScheduleObs.programID = Program.programID AND
   GuestAccts.username = CONCAT(ScheduleObs.semesterID,LPAD(ScheduleObs.programID,3,'0')) AND
   GuestAccts.username = '{$program}'";
   $sql4 = "
ORDER BY
   ProgramNumber, logID, startTime;";

   #$sql = "{$sql1}{$sql2tac}{$sql3}{$sql4}";

   $sql = "
({$sql1}{$sql2eng}{$sql3}
)

UNION

({$sql1}{$sql2tac}{$sql3}
)
{$sql4}";


   if ( $debug ) {
      echo "<hr/>\n";
      echo "<h1>SQL strings for database schedule selects:</h1>\n";
      echo "<h3>Schedule select:</h3>\n";
      echo "SQL:  <br/>\n";
      echo "{$sql}<br/>\n";
      echo "<hr/>\n";
   }

   $dbc = connectDBtroublelog( $debug );
   # retrieve data for this semester (returns result set)
   $result = mysqli_query( $dbc, $sql ) or die ( "Error retrieving Schedule info from the database: " . mysqli_error( $dbc ) );
   while ( $row = mysqli_fetch_assoc( $result ) ) {
      $schedule[] = returnQuotes( str_replace( "INTERNALLINEFEEDHERE", "\n", $row ) );
   }
   disconnectMysql( $debug, $dbc, $result );

   if ( $debug && isset( $schedule ) ) {
      $keycut = 20;
      $keycut = 50;
      echo "<hr/>\n";
      echo "<h1>Schedule information from the database:</h1>\n";
      foreach ( $schedule as $i => $value ) {
         echo "Line [{$i}]: ".print_r($value,true)." <br/>\n";
         if ( $i > $keycut ) { break; }
      }
      echo "<hr/>\n";
   }

   if ( $debug ) { echo "<h1>RETURN: getProgramScheduleDB( {$debug}, {$program}, {$getOp} )</h1>\n\n\n"; }
   return $schedule;
}

#-- end of getProgramScheduleDB
#---------------------------------------------------------------------------


#---------------------------------------------------------------------------
# Marks the given schedule slots as having had the observing reminder email sent
#
function updateDBObsReminderEmail( $debug, $data, $key )
{
   if ( $debug ) { echo "<br/>\n\n\n<h1>START: updateDBObsReminderEmail( {$debug}, data, {$key} )</h1>\n"; }

   #-- mark this proposal as obsremind-emailed (0 = no-email, 1 = real-email, 2 = dummy-email)
   foreach ( $data as $inx => $value ) {
      if ( !is_numeric($inx) ) { continue; }
      #if ( $debug ) { echo "\ndata[{$inx}]: \n".print_r($value,true)."<br/>\n"; }
      #$sql[] = "UPDATE ScheduleObs SET reminderEmail = {$key} WHERE startTime = '{$value['startTime']}' AND programID = '{$value['programID']}' AND semesterID = '{$value['semesterID']}';";
      #$sql[] = "UPDATE ObsReminders SET reminderEmail = {$key}, reminderDate = ".time()." WHERE startTime = '{$value['startTime']}' AND programID = '{$value['programID']}' AND semesterID = '{$value['semesterID']}' AND reminderEmail <> '1';";
      if ( $key == 2 ) {
         $str = "logID = '{$value['logID']}', programID = '{$value['programID']}', semesterID = '{$value['semesterID']}', startTime = '{$value['startTime']}', endTime = '{$value['endTime']}', reminderEmail = {$key}";
         $sql[] = "INSERT INTO ObsReminders SET {$str}, reminderDate = ".time()." ON DUPLICATE KEY UPDATE {$str};";
      } else {
         $str = "logID = '{$value['logID']}', programID = '{$value['programID']}', semesterID = '{$value['semesterID']}', startTime = '{$value['startTime']}', endTime = '{$value['endTime']}', reminderEmail = {$key}, reminderDate = ".time();
         $sql[] = "INSERT INTO ObsReminders SET {$str} ON DUPLICATE KEY UPDATE {$str};";
      }
   }
   if ( $debug ) { echo "\n<p>sql:\n".print_r($sql,true)."\n</p>\n\n\n"; }
   $result = "";
   $dbc = connectDBtroublelog( $debug );
   foreach ( $sql as $inx => $value ) {
      if ( !is_numeric($inx) ) { continue; }
      if ( $debug ) { echo "{$value}<br/>\n"; }
      $result = mysqli_query( $dbc, $value ) or die ( "Error marking observing time slot as emailed in the database: " . mysqli_error() );
      if ( $debug ) { echo "res: [{$result}]<br/>\n"; }
   }
   disconnectMysql( $debug, $dbc, $result );

   if ( $debug ) { echo "<h1>RETURN: updateDBObsReminderEmail( {$debug}, data, {$key} )</h1>\n\n\n<br/>\n"; }
}
#-- end of updateDBObsReminderEmail
#---------------------------------------------------------------------------

}
