<?php

namespace App\legacy\traits;

trait LegacyExportTACFilemakerTrait
{
    ############################################################################
    #
    # Generates the tac results export semester chooser
    #
    #---------------------------------------------------------------------------
    #
    function generateExportTACResultsPage(): string
    {
        $code  = "";
        $color = "";
        $first = 2015;
        $year  = date("Y", time());

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "     <td align='center'>Select the semester to export the TAC results file.</td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Year:&nbsp;\n";
        $code .= getPulldownNumbers("y", $year, 4, $first, $year + 1);
        $code .= "      &nbsp;&nbsp;&nbsp;\n";
        $code .= "      Semester:&nbsp;\n";
        $code .= getPulldownSemesters("s", "", 4);
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      <input type='reset'  name='clear'  value='Clear'         style='width: {$bwid}px;'/>\n";
        $code .= "      <input type='submit' name='submit' value='Generate File' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateExportTACResultsPage
    ############################################################################
}
