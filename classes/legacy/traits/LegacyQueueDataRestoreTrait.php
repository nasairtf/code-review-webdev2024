<?php

namespace App\legacy\traits;

trait LegacyQueueDataRestoreTrait
{
    ############################################################################
    #
    # Generates the form to take input for restoring data to scrh1_restore
    #
    #---------------------------------------------------------------------------
    #
    function generateDataRestoreFormPage(bool $debug, array $data): string
    {
        $code  = "";
        $color = "";
        $cols = 4;

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, $cols, "FFFFFF");

        $first = 2016;
        $year = date("Y", time());
        $height = 45;
        $width = 75;
        $bwid = 120;

        $test['on']  = $data['test'] === 1 ? 'checked' : '';
        $test['off'] = $data['test'] === 0 ? 'checked' : '';
        $del['on']   = $data['delete'] === 1 ? 'checked' : '';
        $del['off']  = $data['delete'] === 0 ? 'checked' : '';

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' colspan='{$cols}'>\n";
        $code .= "      Enter the guest account username to restore data for:\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center' colspan='2'>Source Program</td>\n";
        $code .= "    <td align='center' colspan='2'>Destination Program</td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='right'>Username:</td>\n";
        $code .= "    <td align='left'><input type='text' name='usersrc' value='{$data['usersrc']}' size='20'></td>\n";
        $code .= "    <td align='right'>Username:</td>\n";
        $code .= "    <td align='left'><input type='text' name='userdst' value='{$data['userdst']}' size='20'></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='right'>Session code:</td>\n";
        $code .= "    <td align='left'><input type='text' name='codesrc' value='{$data['codesrc']}' size='20'></td>\n";
        $code .= "    <td align='right'>Session code:</td>\n";
        $code .= "    <td align='left'><input type='text' name='codedst' value='{$data['codedst']}' size='20'></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' colspan='{$cols}'>\n";
        $code .= "      <table>\n";
        $code .= "        <tr>\n";
        $code .= "          <td>Restore data in:</td>\n";
        $code .= "          <td>\n";
        $code .= "            <table>\n";
        $code .= "              <tr>\n";
        $code .= "                <td>test mode</td>\n";
        $code .= "                <td><br/></td>\n";
        $code .= "                <td>delete mode</td>\n";
        $code .= "              </tr>\n";
        $code .= "              <tr>\n";
        $code .= "                <td>\n";
        $code .= "                  <input type='radio' name='test' value='0' {$test['off']}/>off\n";
        $code .= "                  <input type='radio' name='test' value='1' {$test['on']}/>on\n";
        $code .= "                </td>\n";
        $code .= "                <td><br/></td>\n";
        $code .= "                <td>\n";
        $code .= "                  <input type='radio' name='delete' value='0' {$del['off']}/>off\n";
        $code .= "                  <input type='radio' name='delete' value='1' {$del['on']}/>on\n";
        $code .= "                </td>\n";
        $code .= "              </tr>\n";
        $code .= "            </table>\n";
        $code .= "          </td>\n";
        $code .= "        </tr>\n";
        $code .= "      </table>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' colspan='{$cols}'>\n";
        $code .= "      <input type='reset'  name='reset' value='Clear' style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Queue restore' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, $cols, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateDataRequestFormPage
    ############################################################################


    ############################################################################
    #
    # ORIGINAL METHODS TAKEN FROM PROCEDURAL CODEBASE
    #
    ############################################################################


    ############################################################################
    #
    # Generates the form to take input for restoring data to scrh1_restore
    #
    #---------------------------------------------------------------------------
    #
    function ORIGINAL_generateDataRestoreFormPage( $debug, $title ) {

       $isForm = true;
       $code  = "";
       $color = "";
       $cols = 4;
       $year = date( "Y", time() );

       $myscrpt = basename( pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME ) );
       if ( $myscrpt == "datarestore" ) {
          $codetext = "staff restore";
       } else {
          $codetext = "";
       }

       $code .= myHeader( $debug, $title, $isForm );

       $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );

       $height = 45;
       $width = 75;
       $bwid = 120;

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='center' colspan='{$cols}'>\n";
       #$code .= "      Enter the guest account username and session code to restore data for:\n";
       $code .= "      Enter the guest account username to restore data for:\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' >\n";
       $code .= "    <td align='center' colspan='2'>Source Program</td>\n";
       $code .= "    <td align='center' colspan='2'>Destination Program</td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' >\n";
       $code .= "    <td align='right'>Username:</td>\n";
       $code .= "    <td align='left'><input type='text' name='usersrc' value='' size='20'></td>\n";
       $code .= "    <td align='right'>Username:</td>\n";
       $code .= "    <td align='left'><input type='text' name='userdst' value='' size='20'></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' >\n";
       $code .= "    <td align='right'>Session code:</td>\n";
       $code .= "    <td align='left'><input type='text' name='codesrc' value='{$codetext}' size='20'></td>\n";
       $code .= "    <td align='right'>Session code:</td>\n";
       $code .= "    <td align='left'><input type='text' name='codedst' value='{$codetext}' size='20'></td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='center' colspan='{$cols}'>\n";
       $code .= "      <table>\n";
       $code .= "        <tr>\n";
       $code .= "          <td>Restore data in:</td>\n";
       $code .= "          <td><input type='checkbox' name='test' value='yes' /></td>\n";
       $code .= "          <td>test mode</td>\n";
       $code .= "          <td><br/></td>\n";
       $code .= "          <td><input type='checkbox' name='delete' value='yes' /></td>\n";
       $code .= "          <td>delete mode</td>\n";
       $code .= "        </tr>\n";
       $code .= "      </table>\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       $color = getGrayShading( $color );
       $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
       $code .= "    <td align='center' colspan='{$cols}'>\n";
       $code .= "      <input type='reset'  name='reset' value='Clear' style='width: {$bwid}px;'/>\n";
       $code .= "      <input type='submit' name='submit' value='Restore' style='width: {$bwid}px;'/>\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";

       $code .= getHorizontalLine( 0, $cols, "FFFFFF" );
       $code .= "</table>\n";

       $code .= myFooter( __FILE__, $isForm );
       return $code;
    }

    #---------------------------------------------------------------------------
    #-- end of ORIGINAL_generateDataRestoreFormPage
    ############################################################################


    ############################################################################
    #
    # Handles queuing restore of the requested data to scrh1_restore
    #
    #---------------------------------------------------------------------------
    #
    function ORIGINAL_processDataRestore( $debug, $sendemails, $title ) {

       $code = "";
       $message = "";
       $test = "";
       $del = "";
       $dsthost = "basking";
       $pathleg = "/netdisks2/idals";
       $pathdst = "/netdisks/scrh1/scrs1_restore";
       $scripthome = "/home/proposal/bin/restore_data";

       $myscrpt = basename( pathinfo( $_SERVER['PHP_SELF'], PATHINFO_DIRNAME ) );
       $usersrc = $_POST['usersrc'];
       $userdst = $_POST['userdst'];
       if ( $myscrpt == "datarestore" && ($_POST['codesrc'] == "staff restore" || $_POST['codedst'] == "staff restore") ) {
          $datasrc = retrieveAcctData( $debug, "", "", $usersrc );
          if ( !isset($datasrc[0]) ) {
             $title = "Error";
             $msg   = "<br/>\n The specified source username is not valid.<br/>\n";
             $msg  .= "<br/>\n Please contact the IRTF system administrator for assistance.<br/>\n<br/>\nOr<br/>\n<br/>\n";
             echo generateErrorPage( $debug, $title, $msg );
             exit;
          }
          $datadst = retrieveAcctData( $debug, "", "", $userdst );
          if ( !isset($datadst[0]) ) {
             $title = "Error";
             $msg   = "<br/>\n The specified destination username is not valid.<br/>\n";
             $msg  .= "<br/>\n Please contact the IRTF system administrator for assistance.<br/>\n<br/>\nOr<br/>\n<br/>\n";
             echo generateErrorPage( $debug, $title, $msg );
             exit;
          }
          $codesrc = $datasrc[0]['defaultpwd'];
          $codedst = $datadst[0]['defaultpwd'];
       } else {
          $codesrc = $_POST['codesrc'];
          $codedst = $_POST['codedst'];
       }
       if ( isset($_POST['test']) )   { $test = "-t"; }
       if ( isset($_POST['delete']) ) { $del = "-r"; }
       if ( $debug ) { echo "{$myscrpt} {$usersrc} {$codesrc} {$userdst} {$codedst} {$test} {$del}<br/>\n"; }

       #-- verify the source user name/code provided
       $out = array();
       $sys = "/home/proposal/bin/verify_user -u {$usersrc} -p {$codesrc}";
       $tmp = exec( $sys, $out );
       if ( count($out) != 0 ) {
          $out = implode( "\n", $out );
          $out = explode( ":", $out );
       }
       if ( $debug ) { echo print_r( $out, true ) . "<br/>\n"; }

       #-- generate error notice for source user name/code pair if code doesn't match or user not in db
       if ( $out[0] == 2 || $out[0] == 4 ) {
          $title = "Error";
          $msg   = $out[1];
          $msg  .= "<br/>\n Please contact the IRTF system administrator for assistance.<br/>\n<br/>\nOr<br/>\n<br/>\n";
          echo generateErrorPage( $debug, $title, $msg );
          exit;
       }

       #-- verify the destination user name/code provided
       $out = array();
       $sys = "/home/proposal/bin/verify_user -u {$userdst} -p {$codedst}";
       $tmp = exec( $sys, $out );
       if ( count($out) != 0 ) {
          $out = implode( "\n", $out );
          $out = explode( ":", $out );
       }
       if ( $debug ) { echo print_r( $out, true ) . "<br/>\n"; }

       #-- generate error notice for destination user name/code pair
       if ( $out[0] != 1 ) {
          $title = "Error";
          $msg   = $out[1];
          $msg  .= "<br/>\n Please contact the IRTF system administrator for assistance.<br/>\n<br/>\nOr<br/>\n<br/>\n";
          echo generateErrorPage( $debug, $title, $msg );
          exit;
       }

       #-- run the script
       $out = array();
       #$sys = "{$scripthome}/run_restore_on_webserver -m {$scripthome} -k {$dsthost} -s {$pathleg} -d {$pathdst} -o {$usersrc} -p {$userdst} {$test} {$del}";
       #echo "Running run_restore_on_webserver:"
       #/home/proposal/bin/run_restore_on_webserver -q -x -k "stefan" -o "2025A037" -p "2025A037" -s "/netdisks2/idals" -d "/netdisks/scrh1/scrs1_restore"
       $sys = "/home/proposal/bin/run_restore_on_webserver -q -x -k {$dsthost} -o {$usersrc} -p {$userdst} -s {$pathleg} -d {$pathdst} {$test} {$del}";
       if ( $debug ) { echo "{$sys}<br/>\n"; }
       $tmp = exec( $sys, $out );
       if ( count($out) != 0 ) {
          $out = implode( "\n", $out );
          $out = explode( ":", $out );
       }
       if ( $debug ) { echo print_r( $out, true ) . "<br/>\n"; }

       #-- generate error notice for run_restore_on_webserver
       #if ( $out[0] != 1 ) {
       #   $title = "Error";
       #   $msg   = $out[1];
       #   $msg  .= "<br/>\n Please contact the IRTF system administrator for assistance.<br/>\n<br/>\nOr<br/>\n<br/>\n";
       #   echo generateErrorPage( $debug, $title, $msg );
       #   exit;
       #}

       $message = "";
       $message .= "<p style='text-align: left;'>The requested data files should be available soon at:</p>";
       $message .= "<p style='text-align: left;'>stefan.ifa.hawaii.edu:/scrh1/scrs1_restore/{$userdst}/</p>";
       $message .= "<p style='text-align: left;'>The files can be downloaded using {$userdst}'s credentials.</p>";
       $message .= "<p></p>";
       $message .= "<p style='text-align: left;'>Please note that the restore might take a while if there are a lot of files in the request.</p>";
       $message .= "<p></p>";
       $message .= "<p style='text-align: left;'>Also, not all data can be restored using this form. If you get an error, please contact the IRTF system administrator for further assistance.</p>";

       if ( $debug ) { echo "</div>\n"; }

       $code .= generateResultsPage( $debug, $title, $message );
       return $code;

    }

    #---------------------------------------------------------------------------
    #-- end of ORIGINAL_processDataRestore
    ############################################################################

    #---------------------------------------------------------------------------
    # Retrieves the saved data for the entire semester from the database
    #
    function ORIGINAL_retrieveAcctData( $debug, $semyear, $semcode, $guest ) {

       if ( $debug ) { echo "<br/>\n<hr/>\n\n\n<h1>START: retrieveAcctData( {$debug}, {$semyear}, {$semcode}, {$guest} )</h1>\n"; }

       #-----------------------------------------------
       #-- get the information from the database
       #-- to populate the data array

       #-- get the form information
       if ( $guest != "" ) {
          $where = "username = '{$guest}'";
       } else {
          $where = "SUBSTRING(username, 1, 5) = '{$semyear}{$semcode}'";
       }
       $sql = "
    SELECT
       *
    FROM
       GuestAccts
    WHERE
       {$where}
    ORDER BY
       uid ASC;";

       if ( $debug ) { echo "\n<p>sql: {$sql}</p>\n\n\n"; }

       #-- connect to the database
       $dbc = connectDBtroublelog( $debug );

       $result = mysqli_query( $dbc, $sql ) or die ( "Error importing session from the database: " . mysqli_error( $dbc ) );
       while ( $row = mysqli_fetch_assoc( $result ) ) {
          $merged[] = returnQuotes( str_replace( "INTERNALLINEFEEDHERE", "\n", $row ) );
       }
       if ( !isset( $merged ) ) { $merged = array( ); }

       if ( $debug ) {
          echo "\nretrieveAcctData() - mysqli_fetch_assoc = \n";
          print_r( $merged );
       }

       #-- disconenct from the database
       disconnectMysql( $debug, $dbc, $result );

       #-- return harvested array
       if ( $debug ) { echo "<h1>RETURN: retrieveAcctData( {$debug}, {$semyear}, {$semcode}, {$guest} )</h1>\n\n\n"; }
       return $merged;
    }
    #-- end of ORIGINAL_retrieveAcctData
    #---------------------------------------------------------------------------
}
