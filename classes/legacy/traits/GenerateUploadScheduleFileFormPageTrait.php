<?php

namespace App\legacy\traits;

trait GenerateUploadScheduleFileFormPageTrait
{
    ############################################################################
    #
    # Generates the form to upload the FastTrack export file
    #
    #---------------------------------------------------------------------------
    #
    function generateFastTrackFormPage(): string
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Select the FastTrack time allocation file for upload\n";
        $code .= "      <input type='hidden' name='MAX_FILE_SIZE' value='60000000' >\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "    <td align='center'><input type='file' name='file' /></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td colspan='3' align='center'>If a full schedule load is needed, select 'Yes' below.</td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td align='center'>\n";
        $code .= "        <input type='radio' name='loadtype' value='partial' checked />No\n";
        $code .= "        &nbsp;\n";
        $code .= "        <input type='radio' name='loadtype' value='full' />Yes\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";
/*
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td colspan='3' align='center'>\n";
        $code .= "       For only private txt/pdf files (no public access), click 'Private' below.\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td align='center'>\n";
        $code .= "        <input type='radio' name='access' value='public' checked />Public\n";
        $code .= "        &nbsp;\n";
        $code .= "        <input type='radio' name='access' value='private' />Private\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";
*/
        $eng = $this->htmlBuilder->getLink($this->irtfLinks->getEditEngPrograms(), 'Edit Engineering Programs', ['target' => '_blank'], 0, true);
        $ins = $this->htmlBuilder->getLink($this->irtfLinks->getEditInstruments(), 'Edit Instruments', ['target' => '_blank'], 0, true);
        $ops = $this->htmlBuilder->getLink($this->irtfLinks->getEditOperators(), 'Edit Operators', ['target' => '_blank'], 0, true);
        $sas = $this->htmlBuilder->getLink($this->irtfLinks->getEditSupportAstromers(), 'Edit Support Astromers', ['target' => '_blank'], 0, true);
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' >\n";
        $code .= "     <td>\n";
        $code .= "       <p>A full schedule load will remove all of the entries for the current semester from "
            . "the database and then load all entries in the schedule file uploaded here.</p>\n";
        $code .= "       <p>A partial schedule load will remove only entries in the future (data from "
            . date("m/d/Y") . " and later) and will leave all historical data in the database.</p>\n";
        $code .= "       <p>Generally a full schedule load should occur at the beginning of the semester and "
            . "updates done over the course of the semester can use the partial schedule load. Partial schedule "
            . "loading is the default behavior.</p>\n";
        $code .= "       <p><strong>Pease note</strong>: check the instrument and operator/night attendant listings "
            . "below and add any instruments or personnel not already listed on the "
            . "page:</p>\n";
        $code .= "       <ul>\n";
        $code .= "         <li><p>{$eng}</p></li>\n";
        $code .= "         <li><p>{$ins}</p></li>\n";
        $code .= "         <li><p>{$ops}</p></li>\n";
        $code .= "         <li><p>{$sas}</p></li>\n";
        $code .= "       </ul>\n";
        $code .= "     </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      <input type='submit' name='clear' value='Clear' style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Upload File' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateFastTrackFormPage
    ############################################################################
}
