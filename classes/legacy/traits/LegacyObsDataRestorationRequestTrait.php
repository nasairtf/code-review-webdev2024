<?php

namespace App\legacy\traits;

trait LegacyObsDataRestorationRequestTrait
{
    ############################################################################
    #
    # Generates the form to take input for requesting a data restore
    #
    #---------------------------------------------------------------------------
    #
    function generateDataRequestFormPage(bool $debug, array $data): string
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        #$code .= getHorizontalLine(0, 0, "FFFFFF");

        $cols = 5;
        $first = 2016;
        $year = date("Y", time());
        $height = 45;
        $width = 100;
        $bwid = 120;
        $style[1] = "style='text-align:left; width:" . ($width * 1) . "px;'";
        $style[2] = "style='text-align:left; width:" . ($width * 2) . "px;'";
        $style[3] = "style='text-align:left; width:" . ($width * 3) . "px;'";
        $style[4] = "style='text-align:left; width:" . ($width * 4) . "px;'";
        $style[5] = "style='text-align:left; width:" . ($width * 5) . "px;'";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left' colspan='{$cols}'>\n";
        $code .= "      Enter the information below in order to submit a data restoration request. The more details that can be provided, the easier it will be to locate and restore the files.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center' colspan='{$cols}'><hr/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}' {$style[5]}>\n";
        $code .= "    <td align='left' colspan='" . ($cols-4) . "'>Requestor name:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-1) . "'><input type='text' name='reqname' value='{$data['reqname']}' size='50' {$style[4]}></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}' {$style[5]}>\n";
        $code .= "    <td align='left' colspan='" . ($cols-4) . "'>Requestor email:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-1) . "'><input type='text' name='reqemail' value='{$data['reqemail']}' size='50' {$style[4]}></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center' colspan='{$cols}'><hr/></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left' colspan='" . ($cols-2) . "'>Select the semester the data were taken:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-3) . "'>\n";
        $code .= getPulldownNumbers("y", $year, 4, $first, $year);
        $code .= "      &nbsp;&nbsp;&nbsp;\n";
        $code .= getPulldownSemesters("s", "", 4);
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        #$color = getGrayShading($color);
        #$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        #$code .= "    <td align='left' colspan='" . ($cols-2) . "'>Date range of the observations:</td>\n";
        #$code .= "    <td align='center' colspan='" . ($cols-3) . "'><input type='text' name='obsdates' value='{$data['obsdates']}' size='20' {$style[2]}></td>\n";
        #$code .= "  </tr>\n";

        #$color = getGrayShading($color);
        #$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        #$code .= "    <td align='left' colspan='" . ($cols-2) . "'>Guest account used to take the data (if known):</td>\n";
        #$code .= "    <td align='center' colspan='" . ($cols-3) . "'><input type='text' name='srcuser' value='{$data['srcuser']}' size='20' {$style[2]}></td>\n";
        #$code .= "  </tr>\n";

        #$color = getGrayShading($color);
        #$code .= "  <tr bgcolor='#{$color}'>\n";
        #$code .= "    <td align='left' colspan='{$cols}'>Storage path of the files (if known):</td>\n";
        #$code .= "  </tr>\n";
        #$code .= "  <tr bgcolor='#{$color}'>\n";
        #$code .= "    <td align='center' colspan='{$cols}'><textarea name='datapath' rows='2' cols='60' {$style[5]} >{$data['datapath']}</textarea></td>\n";
        #$code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left' colspan='" . ($cols-2) . "'>Program the data were taken under:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-3) . "'><input type='text' name='srcprogram' value='{$data['srcprogram']}' size='20' {$style[2]}></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left' colspan='" . ($cols-2) . "'>PI of the program:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-3) . "'><input type='text' name='piprogram' value='{$data['piprogram']}' size='20' {$style[2]}></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left' colspan='" . ($cols-2) . "'>Instruments used to take the data:</td>\n";
        $code .= "    <td align='center' colspan='" . ($cols-3) . "'><input type='text' name='obsinstr' value='{$data['obsinstr']}' size='20' {$style[2]}></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td align='left' colspan='{$cols}'>Any other details that might be relevant or helpful:</td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td align='center' colspan='{$cols}'><textarea name='reldetails' rows='4' cols='60' {$style[5]}>{$data['reldetails']}</textarea></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' colspan='{$cols}'>\n";
        $code .= "      <input type='reset'  name='reset' value='Clear' style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Request restore' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        #$code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateDataRequestFormPage
    ############################################################################

    #---------------------------------------------------------------------------
    # Write out the data request log
    #
    function writeDataRequestFile($debug, $filename, $text) {

        #-- merge together the data for the saved session file
        if (is_array($text)) {
            $line = implode("\n", $text);
        } else {
            $line = $text;
        }

        if ($debug) {
            echo "<hr/>\n";
            echo "<h1>writeDataRequestFile( {$debug}, {$filename}, text-below )</h1>\n";
            echo "<h1>Lines to be written to data request file:</h1>\n";
            echo "{$line}\n";
            echo "<hr/>\n";
        }

        #-- write the data to the schedule file
        $fileid = fopen($filename, "w");
        $bytes = fwrite($fileid, $line, strlen($line));
        if ($bytes == 0) {
            #$title = "There was a problem writing to the schedule file.";
            #echo generateErrorPage($debug, $title, $msg);
            #exit;
        } elseif ($debug) {
            echo "<h1>IT WORKED! Bytes written to file: {$bytes}</h1>\n";
            echo "<h2>(filename: {$filename})</h2>\n";
        }
        fclose($fileid);

        return;

    }

    #-- end of writeDataRequestFile
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Generates a confirmation email when the schedule is updated
    #
    function generateDataRequestEmail($debug, $data) {

        #-- set sender
        if ($debug) {
            $from    = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
            $replyto = "Miranda Hawarden-Ogata <hawarden@hawaii.edu>";
        } else {
            $from    = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
            $replyto = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
        }

        #-- set addressee
        $addressee = "{$replyto}, {$data['reqname']} <{$data['reqemail']}>";

        #-- set subject
        $subject  = "IRTF data restore request";

        #-- set message body
        $msg1 = "
This is an automated acknowledgement email sent to the IRTF data restore staff.

Requested by:     {$data['reqname']} ({$data['reqemail']})
Program:          {$data['srcprogram']}
Program PI:       {$data['piprogram']}
Instrument(s):    {$data['obsinstr']}
Relevant details: {$data['reldetails']}

Please expect a followup email within a few days containing the details to access your restored data.
\n\n";
$msg2 = "
<p>This is an automated acknowledgement email sent to the IRTF data restore staff.</p>
<br/>
<table border='0'>
<tr><td>Requested by:</td>    <td>{$data['reqname']} ({$data['reqemail']})</td></tr>
<tr><td>Program:</td>         <td>{$data['srcprogram']}</td></tr>
<tr><td>Program PI:</td>      <td>{$data['piprogram']}</td></tr>
<tr><td>Instrument(s):</td>   <td>{$data['obsinstr']}</td></tr>
<tr><td>Relevant details:</td><td>{$data['reldetails']}</td></tr>
</table>
<br/>
<p>Please expect a followup email within a few days containing the details to access your restored data.</p>
\n\n";

        $this->writeDataRequestFile($debug, "/home/proposal/datarestores/request_{$data['srcprogram']}." . date("Ymd-His") . ".log", $msg1);

        $boundary = '-----=' . md5(rand());

        $headers  = "From: {$from}\n";
        $headers .= "Reply-To: {$replyto}\n";
        $headers .= "Date: " . date('r') . "\n";
        $headers .= "Message-ID: <" . time() . "-{$from}>\n";
        $headers .= "X-Mailer: PHP v" . phpversion() . "\n";
        $headers .= "MIME-Version: 1.0\n";
        #$headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\n\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\n\n";

        $message1  = "--{$boundary}\n";
        $message1 .= "Content-Type: text/plain; charset=utf-7\n";
        $message1 .= "Content-Transfer-Encoding: 7bit\n\n";

        $message2  = "--{$boundary}\n";
        $message2 .= "Content-Type: text/html; charset=ISO-8859-1\n";
        $message2 .= "Content-Transfer-Encoding: 7bit\n\n";

        #$body = $message1 . $message . "\n";
        #$body = $message1 . $message . "\n--" . $boundary . "--\n\n";
        $body = $message1 . $msg1 . $message2 . $msg2 . "\n--" . $boundary . "--\n\n";

        if (!mail($addressee, $subject, $body, $headers)) {
            $error = "There was a problem sending the email.";
            throw new Exception($error);
        }

        return "The data restoration request email has been sent. Please check your inbox for your copy.";
    }
    #-- end of generateDataRequestEmail
    #---------------------------------------------------------------------------


    ############################################################################
    #
    # ORIGINAL METHODS TAKEN FROM PROCEDURAL CODEBASE
    #
    ############################################################################



    ############################################################################
    #
    # Generates the form to take input for requesting a data restore
    #
    #---------------------------------------------------------------------------
    #
    function ORIGINAL_generateDataRequestFormPage( $debug, $title ) {

       $isForm = true;
       $code  = "";
       $color = "";
       $cols = 5;
       #$first = 2000;
       $first = 2016;
       $year = date( "Y", time() );
       $height = 45;
       #$width = 75;
       $width = 100;
       $bwid = 120;
       $style[1] = "style='text-align:left; width:".($width * 1)."px;'";
       $style[2] = "style='text-align:left; width:".($width * 2)."px;'";
       $style[3] = "style='text-align:left; width:".($width * 3)."px;'";
       $style[4] = "style='text-align:left; width:".($width * 4)."px;'";
       $style[5] = "style='text-align:left; width:".($width * 5)."px;'";

       $code .= myHeader( $debug, $title, $isForm );

       $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
       #$code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='left' colspan='{$cols}'>\n";
       $code .= "      Enter the information below in order to submit a data restoration request. The more details that can be provided, the easier it will be to locate and restore the files.\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' >\n";
       $code .= "    <td align='center' colspan='{$cols}'><hr/></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}' {$style[5]}>\n";
       $code .= "    <td align='left' colspan='".($cols-4)."'>Requestor name:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-1)."'><input type='text' name='reqname' value='' size='50' {$style[4]}></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}' {$style[5]}>\n";
       $code .= "    <td align='left' colspan='".($cols-4)."'>Requestor email:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-1)."'><input type='text' name='reqemail' value='' size='50' {$style[4]}></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' >\n";
       $code .= "    <td align='center' colspan='{$cols}'><hr/></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='left' colspan='".($cols-2)."'>Select the semester the data were taken:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-3)."'>\n";
       $code .= getPulldownNumbers( "y", $year, 4, $first, $year );
       $code .= "      &nbsp;&nbsp;&nbsp;\n";
       $code .= getPulldownSemesters( "s", "", 4 );
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       #$color = getGrayShading( $color );
       #$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       #$code .= "    <td align='left' colspan='".($cols-2)."'>Date range of the observations:</td>\n";
       #$code .= "    <td align='center' colspan='".($cols-3)."'><input type='text' name='obsdates' value='' size='20' {$style[2]}></td>\n";
       #$code .= "  </tr>\n";

       #$color = getGrayShading( $color );
       #$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       #$code .= "    <td align='left' colspan='".($cols-2)."'>Guest account used to take the data (if known):</td>\n";
       #$code .= "    <td align='center' colspan='".($cols-3)."'><input type='text' name='srcuser' value='' size='20' {$style[2]}></td>\n";
       #$code .= "  </tr>\n";

       #$color = getGrayShading( $color );
       #$code .= "  <tr bgcolor='#{$color}'>\n";
       #$code .= "    <td align='left' colspan='{$cols}'>Storage path of the files (if known):</td>\n";
       #$code .= "  </tr>\n";
       #$code .= "  <tr bgcolor='#{$color}'>\n";
       #$code .= "    <td align='center' colspan='{$cols}'><textarea name='datapath' rows='2' cols='60' {$style[5]} ></textarea></td>\n";
       #$code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='left' colspan='".($cols-2)."'>Program the data were taken under:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-3)."'><input type='text' name='srcprogram' value='' size='20' {$style[2]}></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='left' colspan='".($cols-2)."'>PI of the program:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-3)."'><input type='text' name='piprogram' value='' size='20' {$style[2]}></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='left' colspan='".($cols-2)."'>Instruments used to take the data:</td>\n";
       $code .= "    <td align='center' colspan='".($cols-3)."'><input type='text' name='obsinstr' value='' size='20' {$style[2]}></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}'>\n";
       $code .= "    <td align='left' colspan='{$cols}'>Any other details that might be relevant or helpful:</td>\n";
       $code .= "  </tr>\n";
       $code .= "  <tr bgcolor='#{$color}'>\n";
       $code .= "    <td align='center' colspan='{$cols}'><textarea name='reldetails' rows='4' cols='60' {$style[5]}></textarea></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='center' colspan='{$cols}'>\n";
       $code .= "      <input type='reset'  name='reset' value='Clear' style='width: {$bwid}px;'/>\n";
       $code .= "      <input type='submit' name='submit' value='Request restore' style='width: {$bwid}px;'/>\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       #$code .= getHorizontalLine( 0, $cols, "FFFFFF" );
       $code .= "</table>\n";

       $code .= myFooter( __FILE__, $isForm );
       return $code;
    }

    #---------------------------------------------------------------------------
    #-- end of ORIGINAL_generateDataRequestFormPage
    ############################################################################


    ############################################################################
    #
    # Generates the form to take input for requesting a data restore
    #
    #---------------------------------------------------------------------------
    #
    function ORIGINAL_generateDataRequestFormPageDIV( $debug, $title ) {

       $isForm = true;
       $code  = "";
       $color = "";
       $cols = 5;
       $first = 2000;
       $year = date( "Y", time() );
       $height = 45;
       #$width = 75;
       $width = 100;
       $bwid = 120;
       $style[1] = "style='text-align:left; width:".($width * 1)."px;'";
       $style[2] = "style='text-align:left; width:".($width * 2)."px;'";
       $style[3] = "style='text-align:left; width:".($width * 3)."px;'";
       $style[4] = "style='text-align:left; width:".($width * 4)."px;'";
       $style[5] = "style='text-align:left; width:".($width * 5)."px;'";

       $code .= myHeader( $debug, $title, $isForm );

       $color = getGrayShading( $color );
       $code .= "<div id='pageform' style='background-color: {$color};'>\n";
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "      Enter the information below in order to submit a data restoration request. The more details that can be provided, the easier it will be to locate and restore the files.\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: left;'>\n";
       $code .= "  Select the semester the data were taken:\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: right;'>\n";
       $code .= getPulldownNumbers( "y", $year, 4, $first, $year );
       $code .= "      &nbsp;&nbsp;&nbsp;\n";
       $code .= getPulldownSemesters( "s", "", 4 );
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: left;'>\n";
       $code .= "  Program the data were taken under:\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: right;'>\n";
       $code .= "  <input type='text' name='srcprogram' value='' size='20' {$style[1]}>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: left;'>\n";
       $code .= "  Date range of the observations:\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: right;'>\n";
       $code .= "  <input type='text' name='obsdates' value='' size='20' {$style[1]}>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: left;'>\n";
       $code .= "  Guest account used to take the data (if known):\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: right;'>\n";
       $code .= "  <input type='text' name='srcuser' value='' size='20' {$style[1]}>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 100%; height: auto;'>\n";
       $code .= "  <p>Storage path of the files (if known):</p>\n";
       $code .= "  <p style='text-align: center;'><textarea name='datapath' rows='2' cols='60' {$style[5]} ></textarea></p>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 100%; height: auto;'>\n";
       $code .= "  <p>Any other details that might be relevant or helpful:</p>\n";
       $code .= "  <p style='text-align: center;'><textarea name='reldetails' rows='4' cols='60' {$style[5]}></textarea></p>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 25%; height: auto; float: left;'>\n";
       $code .= "  <p>Requestor name:</p>\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 75%; height: auto; float: right;'>\n";
       $code .= "  <p style='text-align: center;'><input type='text' name='reqname' value='' size='50' {$style[4]}></p>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       #$color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 25%; height: auto; float: left;'>\n";
       $code .= "  Requestor email:\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 75%; height: auto; float: right;'>\n";
       $code .= "  <input type='text' name='reqemail' value='' size='50' {$style[4]}>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $color = getGrayShading( $color );
       $code .= "<div style='background-color: {$color}; max-width: 100%; min-height: {$height}px;'>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: left;'>\n";
       $code .= "      <input type='reset'  name='reset' value='Clear' style='width: {$bwid}px;'/>\n";
       $code .= "  </span>\n";
       $code .= "  <span style='background-color: {$color}; max-width: 50%; height: auto; float: right;'>\n";
       $code .= "      <input type='submit' name='submit' value='Restore' style='width: {$bwid}px;'/>\n";
       $code .= "  </span>\n";
       $code .= "</div>\n";

       $color = getGrayShading( $color );
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $code .= "</div>\n";

       $code .= myFooter( __FILE__, $isForm );
       return $code;
    }

    #---------------------------------------------------------------------------
    #-- end of ORIGINAL_generateDataRequestFormPageDIV
    ############################################################################

    ############################################################################
    #
    # Handles generating a data restore request to irtf-data-restore@lists.hawaii.edu
    #
    #---------------------------------------------------------------------------
    #
    function ORIGINAL_processDataRequest( $debug, $sendemails, $data, $title ) {

       $code = "";

       if ( $debug ) {
          echo "<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n";
          echo "<h2>DATA ARRAY OUTPUT:</h2>\n";
          print_r( $data );
       }

       if ( strlen($data['srcprogram']) <= 3 ) {
          $data['srcprogram'] = sprintf( "%d%s%03d", $data['y'], $data['s'], $data['srcprogram'] );
       }

       if ( $debug ) {
          echo "<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n<br/>\n";
          echo "<h2>DATA ARRAY OUTPUT:</h2>\n";
          print_r( $data );
       }

       $message = generateDataRequestEmail( $debug, $title, $data );

       if ( $debug ) { echo "</div>\n"; }

       $code .= generateResultsPage( $debug, $title, $message );
       return $code;

    }
    #---------------------------------------------------------------------------
    #-- end of ORIGINAL_processDataRequest
    ############################################################################

    #---------------------------------------------------------------------------
    # Return the constructed pdf schedule array
    #
    function ORIGINAL_writeDataRequestFile( $debug, $filename, $text ) {

       #-- merge together the data for the saved session file
       if ( is_array($text) ) { $line = implode( "\n", $text ); } else { $line = $text; }

       if ( $debug ) {
          echo "<hr/>\n";
          echo "<h1>writeDataRequestFile( {$debug}, {$filename}, text-below )</h1>\n";
          echo "<h1>Lines to be written to data request file:</h1>\n";
          echo "{$line}\n";
          echo "<hr/>\n";
       }

       #-- write the data to the schedule file
       $fileid = fopen( $filename, "w" );
       $bytes = fwrite( $fileid, $line, strlen($line) );
       if ( $bytes == 0 ) {
          #$title = "There was a problem writing to the schedule file.";
          #echo generateErrorPage( $debug, $title, $msg );
          #exit;
       } elseif ( $debug ) {
          echo "<h1>IT WORKED! Bytes written to file: {$bytes}</h1>\n";
          echo "<h2>(filename: {$filename})</h2>\n";
       }
       fclose( $fileid );

       return;

    }

    #-- end of ORIGINAL_writeDataRequestFile
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Generates a confirmation email when the schedule is updated
    #
    function ORIGINAL_generateDataRequestEmail( $debug, $title, $data ) {

       #-- set sender
       if ( $debug ) {
          $from    = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
          $replyto = "Miranda Hawarden-Ogata <hawarden@hawaii.edu>";
       } else {
          $from    = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
          $replyto = "IRTF Data Restore <irtf-data-restore@lists.hawaii.edu>";
       }

       #-- set addressee
       $addressee = "{$replyto}, {$data['reqname']} <{$data['reqemail']}>";

       #-- set subject
       $subject  = "IRTF data restore request";

       #-- set message body
       $msg1 = "
    This is an automated acknowledgement email sent to the IRTF data restore staff.

    Requested by:     {$data['reqname']} ({$data['reqemail']})
    Program:          {$data['srcprogram']}
    Program PI:       {$data['piprogram']}
    Instrument(s):    {$data['obsinstr']}
    Relevant details: {$data['reldetails']}

    Please expect a followup email within a few days containing the details to access your restored data.
    \n\n";
       $msg2 = "
    <p>This is an automated acknowledgement email sent to the IRTF data restore staff.</p>
    <br/>
    <table border='0'>
       <tr><td>Requested by:</td>    <td>{$data['reqname']} ({$data['reqemail']})</td></tr>
       <tr><td>Program:</td>         <td>{$data['srcprogram']}</td></tr>
       <tr><td>Program PI:</td>      <td>{$data['piprogram']}</td></tr>
       <tr><td>Instrument(s):</td>   <td>{$data['obsinstr']}</td></tr>
       <tr><td>Relevant details:</td><td>{$data['reldetails']}</td></tr>
    </table>
    <br/>
    <p>Please expect a followup email within a few days containing the details to access your restored data.</p>
    \n\n";

       writeDataRequestFile( $debug, "/home/proposal/datarestores/request_{$data['srcprogram']}.".date("Ymd-His").".log", $msg1 );

       $boundary = '-----=' . md5( rand( ) );

       $headers  = "From: {$from}\n";
       $headers .= "Reply-To: {$replyto}\n";
       $headers .= "Date: ".date('r')."\n";
       $headers .= "Message-ID: <".time()."-{$from}>\n";
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

       #$body = $message1 . $message . "\n";
       #$body = $message1 . $message . "\n--" . $boundary . "--\n\n";
       $body = $message1 . $msg1 . $message2 . $msg2 . "\n--" . $boundary . "--\n\n";

       if ( !mail( $addressee, $subject, $body, $headers ) ) {
          $error = "There was a problem sending the email.";
          echo generateErrorPage( $debug, $title, $error );
          return 0;
       }

       return $msg2;
    }

    #-- end of ORIGINAL_generateDataRequestEmail
    #---------------------------------------------------------------------------
}
