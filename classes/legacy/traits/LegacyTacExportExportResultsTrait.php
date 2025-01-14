<?php

namespace App\legacy\traits;

trait LegacyTacExportExportResultsTrait
{
    ############################################################################
    #
    # Processes export tac results request and generates the import file for FMP
    #
    #---------------------------------------------------------------------------
    #
    function processExportTACResults(
        $debug,
        $year,
        $semester
    ) {
        $code = "";

        #-- "TAC Rating" is a 8-field repeating field
        #-- filemaker uses 'group separater' ascii character to separate the items in a repeating field
        $gs = chr(29);
        #-- filemaker uses 'vertical tab' ascii character for carriage returns
        $vt = chr(11);
        #-- filemaker uses 'horizontal tab' ascii character for tabs
        $ht = chr(9);

        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: processExportTACResults({$debug}, {$year}, {$semester})</h1>\n"; }

        #-- retrieve the data from the database
        #$flags['submit'] = 1;
        $saveddata = $this->retrieveSemesterAllData($debug, $year, $semester);

        $proposals = count($saveddata);
        if ($proposals == 0) {
            return "<h2 align='center' style='color:red'>{$proposals} proposals retrieved for {$year}{$semester}</h2>\n";
        }

        #-- delete the existing tacresults_fmp.txt file
        $fmpname = "tacresults_fmp_{$year}{$semester}.txt";
        $appfile = "/home/proposal/public_html/schedule/filemaker/{$fmpname}";
        $urlfile = "/~proposal/schedule/filemaker/{$fmpname}";
        if (file_exists($appfile)) {
            $out = exec("/bin/rm {$appfile}");
        }

        #-- append a line for each proposal to Application.txt
        foreach ($saveddata as $key => $value) {
            $output = $this->exportTACResults($debug, $value, $appfile, sprintf("%03d", $value['ProgramNumber']));
            if ($debug) { echo "{$output}"; }
        }

        #-----------------------------------------------
        #-- update status to page
        //$message = "\n";
        $message = [];
        if ($debug) { echo "<h1>RETURN: processExportTACResults({$debug}, {$year}, {$semester})</h1>\n\n\n"; }
        if ($proposals == 1) {
            //$message .= "<p align='center'>{$proposals} submitted proposal was added to <a href='{$urlfile}'>{$fmpname}</a>.</p>\n";
            $message[] = "{$proposals} submitted proposal was added to <a href='{$urlfile}'>{$fmpname}</a>.";
        } else {
            //$message .= "<p align='center'>{$proposals} submitted proposals were added to <a href='{$urlfile}'>{$fmpname}</a>.</p>\n";
            $message[] = "{$proposals} submitted proposals were added to <a href='{$urlfile}'>{$fmpname}</a>.";
        }

        if ($debug) { echo "</div>\n"; }

        return $message;
    }
    #---------------------------------------------------------------------------
    #-- end of processExportTACResults
    ############################################################################


    #---------------------------------------------------------------------------
    # Retrieves the saved data for the entire semester from the database
    #
    function retrieveSemesterAllData($debug, $semyear, $semcode) {
        if ($debug) { echo "<br/>\n<hr/>\n\n\n<h1>START: retrieveSemesterAllData({$debug}, {$semyear}, {$semcode})</h1>\n"; }

        #-----------------------------------------------
        #-- get the information from the database
        #-- to populate the data array

        #-- get the form information
        $sql = "
SELECT
    *
FROM
    ObsApp
WHERE
    isSubmitted = '1' AND
    semesterYear = '{$semyear}' AND
    semesterCode = '{$semcode}'
ORDER BY
    creationDate ASC;
";

        if ($debug) { echo "\n<p>sql: {$sql}</p>\n\n\n"; }

        #-- connect to the database
        $dbc = connectDBtroublelog($debug);

        $result = mysqli_query($dbc, $sql) or die ("Error importing session from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) {
            $merged[] = returnQuotes(str_replace("INTERNALLINEFEEDHERE", "\n", $row));
        }
        if (!isset($merged)) { $merged = array(); }

        if ($debug) {
            echo "\nretrieveSemesterAllData() - mysqli_fetch_assoc = \n";
            print_r($merged);
        }

        #-- disconenct from the database
        disconnectMysql($debug, $dbc, $result);

        #-- return harvested array
        if ($debug) { echo "<h1>RETURN: retrieveSemesterAllData({$debug}, {$semyear}, {$semcode})</h1>\n\n\n"; }
        return $merged;
    }
    #-- end of retrieveSemesterAllData
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Writes out the file to be uploaded into FMP's application database
    #
    function exportTACResults($debug, $data, $filename, $appnum)
    {
        if ($debug) { echo "<br/>\n\n\n<h1>START: exportTACResults({$debug})</h1>\n"; }

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

        $header  = "";
        $line  = "";
        $max_num_invs = 5;

        #-- people information
        for ($inv = 1; $inv <= $data['NumInv']; $inv++) {
            $invlastname  = "InvLastName{$inv}";
            $invfirstname = "InvFirstName{$inv}";
            $email        = "Email{$inv}";
            $ispi         = "IsPI{$inv}";
            $isuh         = "IsUH{$inv}";
            $isgradstu    = "IsGradStu{$inv}";
            $phone        = "Phone{$inv}";
            $institute    = "Institute{$inv}";

            $header .= "\"Inv Last Name ".sprintf("%02d", $inv)."\",";
            $line .= "\"".html_entity_decode($data[$invlastname], ENT_QUOTES, "UTF-8")."\",";

            $header .= "\"Inv First Name ".sprintf("%02d", $inv)."\",";
            $line .= "\"".html_entity_decode($data[$invfirstname], ENT_QUOTES, "UTF-8")."\",";

            $header .= "\"Inv Email ".sprintf("%02d", $inv)."\",";
            $line .= "\"".html_entity_decode($data[$email], ENT_QUOTES, "UTF-8")."\",";

            $header .= "\"InvIsPI".sprintf("%02d", $inv)."\",";
            $line .= "\"".returnYesNo($data[$ispi])."\",";

            $header .= "\"InvIsGrad".sprintf("%02d", $inv)."\",";
            $line .= "\"".returnYesNo($data[$isgradstu])."\",";

            if ($inv == 1) {
                $header .= "\"Form Special Emp. Agency\",";
                if ($data[$isuh] == 1) {
                    $line .= "\"UH\",";
                } else {
                    $line .= "\"n/a\",";
                }
                $header .= "\"Inv Phone ".sprintf("%02d", $inv)."\",";
                $line .= "\"".html_entity_decode($data[$phone], ENT_QUOTES, "UTF-8")."\",";
                $header .= "\"Inv Institute ".sprintf("%02d", $inv)."\",";
                $line .= "\"".html_entity_decode($data[$institute], ENT_QUOTES, "UTF-8")."\",";
            }
        }
        if ($data['NumInv'] < $max_num_invs) {
            for ($inv = $data['NumInv'] + 1; $inv <= $max_num_invs; $inv++) {
                $invlastname  = "InvLastName{$inv}";
                $invfirstname = "InvFirstName{$inv}";
                $email        = "Email{$inv}";
                $ispi         = "IsPI{$inv}";
                $isgradstu    = "IsGradStu{$inv}";

                $header .= "\"Inv Last Name " . sprintf("%02d", $inv) . "\",\"Inv First Name " . sprintf("%02d", $inv)
                    . "\",\"Inv Email " . sprintf("%02d", $inv) . "\",\"InvIsPI" . sprintf("%02d", $inv)
                    . "\",\"InvIsGrad" . sprintf("%02d", $inv) . "\",";
                $line .= "\"\",\"\",\"\",\"\",\"\",";
            }
        }

        $header .= "\"Inv Additional Names List\",";
        $line .= "\"".html_entity_decode($data['AdditionalCoInvs'], ENT_QUOTES, "UTF-8")."\",";

        #-- tac rating information
        $tacrating[] = $data['TACRating1'];
        $tacrating[] = $data['TACRating2'];
        $tacrating[] = $data['TACRating3'];
        $tacrating[] = $data['TACRating4'];
        $tacrating[] = $data['TACRating5'];
        $tacrating[] = $data['TACRating6'];
        $tacrating[] = $data['TACRating7'];
        $tacrating[] = $data['TACRating8'];
        $rating = implode($gs, $tacrating);

        $header .= "\"TAC Rating\",";
        $line .= "\"{$rating}\",";

        $header .= "\"Alloc Nights\",";
        $line .= "\"{$data['allocatedTimeNight']}\",";

        $header .= "\"Allocated Days\",";
        $line .= "\"{$data['allocatedTimeDay']}\",";

        #-- application information
        $header .= "\"Program Date Received\",\"Program App Code\",\"Program Code\",\"Program Number\","
            . "\"Program Year\",\"Program Semester\",\"Form Thesis\",\"Program Title\",\"Program Short Title\","
            . "\"Form Scientific Category\",\"Form Planetary\",\"Req Moon\",\"Ins AO\",\"Ins CSHELL\","
            . "\"Ins iSHELL\",\"Ins Other\",\"Ins Other Type\",\"Ins NSFCAM\",\"Ins Rotating Polarimeter\","
            . "\"Ins Apogee Camera\",\"Ins Opihi\",\"Ins MIRSI\",\"Ins MOC\",\"Ins SpeX\",\"Ins MORIS\","
            . "\"Instr 1st time user\",\"Instr 1st time user Years\",\"Remote obs\",\"Remote obs phone\","
            . "\"Form Special Req\",\"Form Nights To Complete\"";
        $line .= "\"".date("m/d/Y", $data['creationDate'])."\"";
        $line .= ",\"{$data['code']}\"";
        $line .= ",\"{$data['semesterYear']}{$data['semesterCode']}{$appnum}\"";
        $line .= ",\"{$appnum}\"";
        $line .= ",\"{$data['semesterYear']}\"";
        $line .= ",\"{$data['semesterCode']}\"";
        $line .= ",\"".returnYesNo($data['ThesisYesNo'])."\"";
        $line .= ",\"".html_entity_decode($data['ApplicationTitle'], ENT_QUOTES, "UTF-8")."\"";
        $line .= ",\"".html_entity_decode($data['ShortTitle'], ENT_QUOTES, "UTF-8")."\"";
        $line .= ",\"".$this->returnCategory($data['SciCategory'])."\"";
        $line .= ",\"".strtolower(returnYesNo($this->returnPlanetary($data['SciCategory'])))."\"";
        $line .= ",\"".html_entity_decode($data['MoonPhase'], ENT_QUOTES, "UTF-8")."\"";
        $line .= ",\"No\"";
        if ($data['InstrumentCshell'] == "" || $data['InstrumentCshell'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentIshell'] == "" || $data['InstrumentIshell'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentOtherEquip'] == "" || $data['InstrumentOtherEquip'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentVisitor'] != 1) {
            $line .= ",\"".$this->returnOtherInstr($data['InstrumentVisitor'])."\"";
        } else {
            $line .= ",\"".html_entity_decode($data['InstruOtherEquipName'], ENT_QUOTES, "UTF-8")."\"";
        }
        if ($data['InstrumentNsfcam'] == "" || $data['InstrumentNsfcam'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentPolarizer'] == "" || $data['InstrumentPolarizer'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentApogee'] == "" || $data['InstrumentApogee'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentOpihi'] == "" || $data['InstrumentOpihi'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentMirsi'] == "" || $data['InstrumentMirsi'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentMoc'] == "" || $data['InstrumentMoc'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentSpex'] == "" || $data['InstrumentSpex'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        if ($data['InstrumentMoris'] == "" || $data['InstrumentMoris'] == "off") {
            $line .= ",\"No\"";
        } else {
            $line .= ",\"Yes\"";
        }
        $line .= ",\"".returnYesNo($data['FirstTime'])."\"";
        $line .= ",\"{$data['YearsAgo']}\"";
        $line .= ",\"".returnYesNo($data['RemoteObs'])."\"";
        $line .= ",\"".html_entity_decode($data['RemoteObsPhone'], ENT_QUOTES, "UTF-8")."\"";
        $line .= ",\"".html_entity_decode($data['SpecialRequirements'], ENT_QUOTES, "UTF-8")."\"";
        $line .= ",\"".html_entity_decode($data['CompleteProj'], ENT_QUOTES, "UTF-8")."\"";
        #$line .= ",\"{$data['Abstract']}\"";

        #-- other information

        #-- observing run information
        for ($run = 1; $run <= $data['MaxRuns']; $run++) {
            $startmonth  = "StartMonth{$run}";
            $startday    = "StartDay{$run}";
            $startyear   = "StartYear{$run}";
            $endmonth    = "EndMonth{$run}";
            $endday      = "EndDay{$run}";
            $endyear     = "EndYear{$run}";
            $runnights   = "RunNights{$run}";
            $rundays     = "RunDays{$run}";
            $runnighthrs = "RunNightHours{$run}";
            $rundayhrs   = "RunDayHours{$run}";
            $remarks     = "Remarks{$run}";
            $runstr = sprintf("%02d", $run);
            $header .= ",\"Req Start Date {$runstr}\",\"Req End Date {$runstr}\",\"Req Nights {$runstr}\","
                . "\"Req Days {$runstr}\",\"Req Ngt Length {$runstr}\",\"Req Day Length {$runstr}\","
                . "\"Req Remarks {$runstr}\"";

            if ($run > $data['NumRuns']) {
                $line .= ",\"\",\"\",\"\",\"\",\"\",\"\",\"\"";
            } else {
                $line .= ",\"{$data[$startmonth]}/{$data[$startday]}/{$data[$startyear]}\"";
                $line .= ",\"{$data[$endmonth]}/{$data[$endday]}/{$data[$endyear]}\"";
                $line .= ",\"{$data[$runnights]}\"";
                $line .= ",\"{$data[$rundays]}\"";
                $line .= ",\"".$this->returnHoursForApp($data[$runnighthrs])."\"";
                $line .= ",\"".$this->returnHoursForApp($data[$rundayhrs])."\"";
                $line .= ",\"".html_entity_decode($data[$remarks], ENT_QUOTES, "UTF-8")."\"";
            }
        }
        $header .= ",\"Form Obs Run Additional Info\"";
        $line .= ",\"".html_entity_decode($data['AddRunInfo'], ENT_QUOTES, "UTF-8")."\"";
        $header .= ",\"TAC Comments\"";
        $line .= ",\"".html_entity_decode($data['TACComments'], ENT_QUOTES, "UTF-8")."\"";

        #-- include timestamp information in case proposal needs to be regenerated
        $header .= ",\"DO NO IMPORT THIS FIELD\"";
        $line .= ",\"{$data['creationDate']}\"\n";

        $desc = "<h2>Line to be written: [{$line}]</h2>\n";

        #-- write the data to the people file
        if (!file_exists($filename)) {
            $line = "{$header}\n{$line}";
        }
        $fileid = fopen($filename, "a+");
        $bytes = fwrite($fileid, $line, strlen($line));
        if ($bytes == 0) {
            $page_error = "There was a problem writing to the TAC Results file.";
            if ($debug) { echo "<h2>{$page_error}</h2>\n"; }
            generateErrorPage($debug, "Error", $page_error, 8, $mydata);
            exit;
        } else {
            $desc .= "<h1>IT WORKED! Bytes = {$bytes}</h1>\n";
        }
        fclose($fileid);
        if ($debug) { echo "<h1>RETURN: exportTACResults({$debug})</h1>\n\n\n"; }
        return $desc;
    }
    #-- end of exportTACResults
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Returns the strings for the scientific categories pulldown
    #
    function returnCategory($num, $debug = false)
    {
        #-- get the scientific category information from the database
        $debug = false;
        $cat = $num;
        $dbc = connectDBtroublelog($debug);
        $sql = "SELECT SciCategoryText FROM SciCategory WHERE SciCategory = '{$num}'";
        if ($debug) { echo "\n<p>sql: {$sql}</p>\n\n\n"; }
        $result = mysqli_query($dbc, $sql)
            or die ("Error retrieving SciCategory list from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) { $cat = $row['SciCategoryText']; }
        disconnectMysql($debug, $dbc, $result);
        return $cat;
    }
    #-- end of returnCategory
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Returns the strings for the scientific categories pulldown
    #
    function returnPlanetary($num, $debug = false)
    {
        #-- get the scientific category information from the database
        $debug = false;
        $cat = $num;
        $dbc = connectDBtroublelog($debug);
        $sql = "SELECT planetary FROM SciCategory WHERE SciCategory = '{$num}'";
        if ($debug) { echo "\n<p>sql: {$sql}</p>\n\n\n"; }
        $result = mysqli_query($dbc, $sql)
            or die ("Error retrieving planetary list from the database: " . mysqli_error($dbc));
        while ($row = mysqli_fetch_assoc($result)) { $cat = $row['planetary']; }
        disconnectMysql($debug, $dbc, $result);
        return $cat;
    }
    #-- end of returnPlanetary
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Returns the strings for the dorms pulldown
    #
    function returnHoursForApp($num)
    {
        switch ($num) {
            case 0:
                return "0";
                break;
            case 1:
                return round(((15/60)/12), 3);
                break;
            case 2:
                return round(((20/60)/12), 3);
                break;
            case 3:
                return round(((30/60)/12), 3);
                break;
            case 4:
                return round(((45/60)/12), 3);
                break;
            case 5:
                return round((1/12), 3);
                break;
            case 6:
                return round((1.5/12), 3);
                break;
            case 7:
                return round((2/12), 3);
                break;
            case 8:
                return round((2.5/12), 3);
                break;
            case 9:
                return round((3/12), 3);
                break;
            case 10:
                return round((3.5/12), 3);
                break;
            case 11:
                return round((4/12), 3);
                break;
            case 12:
                return round((4.5/12), 3);
                break;
            case 13:
                return round((5/12), 3);
                break;
            case 14:
                return round((5.5/12), 3);
                break;
            case 15:
                return round((6/12), 3);
                break;
            case 16:
                return round((7/12), 3);
                break;
            case 17:
                return round((8/12), 3);
                break;
            case 18:
                return round((9/12), 3);
                break;
            case 19:
                return round((10/12), 3);
                break;
            case 20:
                return round((11/12), 3);
                break;
            case 21:
            case 22:
                return round((12/12), 3);
                break;
            case 23:
                return round((6.5/12), 3);
                break;
            case 24:
                return round((7.5/12), 3);
                break;
            case 25:
                return round((8.5/12), 3);
                break;
            case 26:
                return round((9.5/12), 3);
                break;
            case 27:
                return round((10.5/12), 3);
                break;
            case 28:
                return round((11.5/12), 3);
                break;
            default:
                return $num;
        }
    }
    #-- end of returnHoursForApp
    #---------------------------------------------------------------------------

    #---------------------------------------------------------------------------
    # Returns the strings for the other instrument pulldown
    #
    function returnOtherInstr($num)
    {
        switch ($num) {
            case 1:
                return "Other";
                break;
            case 2:
                return "BASS";
                break;
            case 3:
                return "CELESTE";
                break;
            case 4:
                return "HIFOG";
                break;
            case 5:
                return "HIPWAC";
                break;
            case 6:
                return "TEXES";
                break;
            default:
                return $num;
        }
    }
    #-- end of returnOtherInstr
    #---------------------------------------------------------------------------
}
