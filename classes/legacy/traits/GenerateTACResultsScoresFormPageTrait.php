<?php

namespace App\legacy\traits;

trait GenerateTACResultsScoresFormPageTrait
{
    ############################################################################
    #
    # Generates the form to upload the TAC results file exported from FMP
    #
    #---------------------------------------------------------------------------
    #
    private function generateTacResultsFormPage(): string
    {
        $code  = "";
        $color = "";
        $first = 2001;
        $year  = date("Y", time());

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 2, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "     <td colspan='2' align='center'>Select the semester:</td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td colspan='2' align='center'>\n";
        $code .= "      Year:&nbsp;\n";
        $code .= getPulldownNumbers("y", $year, 4, $first, $year + 1);
        $code .= "      &nbsp;&nbsp;&nbsp;\n";
        $code .= "      Semester:&nbsp;\n";
        $code .= getPulldownSemesters("s", "", 4);
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='right'>\n";
        $code .= "      Solar System TAC results/time allocation file:\n";
        $code .= "      <input type='hidden' name='MAX_FILE_SIZE' value='60000000' >\n";
        $code .= "    </td>\n";
        //$code .= "  </tr>\n";
        //$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left'><input type='file' name='tacss' /></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='right'>\n";
        $code .= "      Non-Solar System TAC results/time allocation file:\n";
        $code .= "      <input type='hidden' name='MAX_FILE_SIZE' value='60000000' >\n";
        $code .= "    </td>\n";
        //$code .= "  </tr>\n";
        //$code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='left'><input type='file' name='tacnss' /></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td colspan='2' align='center'>\n";
        $code .= "      <input type='reset'  name='clear'  value='Clear'        style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Upload Files' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 2, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateTacResultsFormPage
    ############################################################################
}
