<?php

namespace App\legacy\traits;

define('GUEST_ACCTS', 0);

trait LegacyAddguestTrait
{
    ############################################################################
    #
    # Generates the form to upload the TAC results file exported from FMP
    #
    #---------------------------------------------------------------------------
    #
    function generateAddguestFormPage(bool $debug, array $data)
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $fwid = 25;
        $bwid = 120;
        $alc = "align='center'";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Fill in the fields and click the button to manually run the adduser script.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the account username: <input type='text' name='username' value='{$data['username']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the label for the account: <input type='text' name='acctname' value='{$data['acctname']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the uid: <input type='text' name='uid' value='{$data['uid']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the default gid, for guest accts this should be uid: <input type='text' name='gid' value='{$data['gid']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the shell: <input type='text' name='shell' value='{$data['shell']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the password for the account, escape necessary characters: <input type='text' name='passwd' value='{$data['passwd']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";

        # see /home/addguest/src/semesteraccts.php for reference:
        #echo "                 (".GUEST_ACCTS." guests, 1 techstaff, 2 daycrew, 3 operators,\n";
        #echo "                  4 supastr, 5 nightattend, 6 projobs, 7 projfac1,\n";
        #echo "                  8 projfac2, 9 students, 10 misc)\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        #$code .= "    <td {$alc}>the type of account (0 staff, 1 project, 2 student, 3 misc, 4 guest): <input type='text' name='accttype' size={$fwid} ></td>\n";
        $code .= "    <td {$alc}>the type of account (0 guests, 1 techstaff, 2 daycrew, 3 operators, 4 supastr, 5 nightattend, 6 projobs, 7 projfac1, 8 projfac2, 9 students, 10 misc):";
        $code .= getPulldownNumbers("accttype", $data['accttype'], 4, 0, 10);
        #$code .= "      <input type='text' name='accttype' size={$fwid} >\n";
        $code .= "\n    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the unix timestamp date or time in days to expire the account (only used if account type is guest): <input type='text' name='expiredays' value='{$data['expiredays']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' >\n";
        $code .= "      <input type='submit' name='submit' value='Addguest' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateAddguestFormPage
    ############################################################################

    ############################################################################
    #
    # Generates the Clearguest form
    #
    #---------------------------------------------------------------------------
    #
    function generateClearguestFormPage(bool $debug, array $data)
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Click the button to manually run the clearguest script.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      <input type='submit' name='submit' value='Clearguest' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateClearguestFormPage
    ############################################################################


    ############################################################################
    #
    # Generates the Createguest form
    #
    #---------------------------------------------------------------------------
    #
    function generateCreateguestFormPage(bool $debug, array $data)
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $bwid = 120;
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Click the button to manually run the createguest script.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      <input type='submit' name='submit' value='Createguest' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateCreateguestFormPage
    ############################################################################

    ############################################################################
    #
    # Generates the form to upload the TAC results file exported from FMP
    #
    #---------------------------------------------------------------------------
    #
    function generateExtendguestFormPage(bool $debug, array $data)
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $fwid = 25;
        $bwid = 120;
        $alc = "align='center'";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Fill in the fields and click the button to manually run the extenduser script.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the account username: <input type='text' name='username' value='{$data['username']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the time in days to expire the account: <input type='text' name='expiredays' value='{$data['expiredays']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' >\n";
        $code .= "      <input type='submit' name='submit' value='Extendguest' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateExtendguestFormPage
    ############################################################################

    ############################################################################
    #
    # Generates the form to upload the TAC results file exported from FMP
    #
    #---------------------------------------------------------------------------
    #
    function generateRemoveguestFormPage(bool $debug, array $data)
    {
        $code  = "";
        $color = "";

        $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='6'>\n";
        $code .= getHorizontalLine(0, 0, "FFFFFF");

        $height = 45;
        $width = 75;
        $fwid = 25;
        $bwid = 120;
        $alc = "align='center'";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center'>\n";
        $code .= "      Fill in the fields and click the button to manually run the remuser script.\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the account username: <input type='text' name='username' value='{$data['username']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        $code .= "    <td {$alc}>the uid: <input type='text' name='uid' value='{$data['uid']}' size={$fwid} ></td>\n";
        $code .= "  </tr>\n";
        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}'>\n";
        #$code .= "    <td {$alc}>the type of account (0 staff, 1 project, 2 student, 3 misc, 4 guest): <input type='text' name='accttype' size={$fwid} ></td>\n";
        #$code .= "    <td {$alc}>the type of account (0 staff, 1 project, 2 student, 3 misc, 4 guest):";
        $code .= "    <td {$alc}>the type of account (0 guests, 1 techstaff, 2 daycrew, 3 operators, 4 supastr, 5 nightattend, 6 projobs, 7 projfac1, 8 projfac2, 9 students, 10 misc):";
        $code .= getPulldownNumbers("accttype", $data['accttype'], 4, 0, 10);
        #$code .= getPulldownNumbers("accttype", 4, 4, 0, 4);
        #$code .= "      <input type='text' name='accttype' size={$fwid} >\n";
        $code .= "\n    </td>\n";
        $code .= "  </tr>\n";

        $color = getGrayShading($color);
        $code .= "  <tr bgcolor='#{$color}' height='{$height}'>\n";
        $code .= "    <td align='center' >\n";
        $code .= "      <input type='submit' name='submit' value='Removeguest' style='width: {$bwid}px;'/>\n";
        $code .= "    </td>\n";
        $code .= "  </tr>\n";

        $code .= getHorizontalLine(0, 0, "FFFFFF");
        $code .= "</table>\n";

        return $code;
    }
    #---------------------------------------------------------------------------
    #-- end of generateRemoveguestFormPage
    ############################################################################
}
