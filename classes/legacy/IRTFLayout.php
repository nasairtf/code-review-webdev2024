<?php

namespace App\legacy;

// Include the file containing sidebar, footer functions, etc.
// (contains the procedural header/footer generation functions)
require_once '/htdocs/inc/auxFuncs.inc';

class IRTFLayout
{
    public function renderExpandMenu($menuHeader)
    {
        return expandMenu($menuHeader);
    }

    public function renderHeader($title, $style_code, $javascript_code, $form_type, $form_action, $javascript_bool, $sidebar_bool)
    {
        return getHeader($title, $style_code, $javascript_code, $form_type, $form_action, $javascript_bool, $sidebar_bool);
    }

    public function renderFooter($filename, $adminname, $adminemail, $form_bool, $sidebar_bool)
    {
        return getFooter($filename, $adminname, $adminemail, $form_bool, $sidebar_bool);
    }

    public function renderIRTFFullHeader()
    {
        return getIRTFFullHeader();
    }

    public function renderIRTFHeaderHeaderDiv()
    {
        return getIRTFHeaderHeaderDiv();
    }

    public function renderIRTFHeaderNavbarDiv()
    {
        return getIRTFHeaderNavbarDiv();
    }

    public function renderIRTFFooter()
    {
        return getIRTFFooter();
    }

    public function renderIRTFModeButton()
    {
        return getIRTFModeButton();
    }

    public function renderIRTFSidebar()
    {
        return getIRTFSidebar();
    }

    public function renderIRTFSidebarMenuContents()
    {
        return getIRTFSidebarMenuContents();
    }

    public function renderHeader2007($title, $style_code, $javascript_code, $form_type, $form_action, $javascript_bool, $sidebar_bool)
    {
        return ggetHeader2007($title, $style_code, $javascript_code, $form_type, $form_action, $javascript_bool, $sidebar_bool);
    }

    public function renderFooter2007($filename, $adminname, $adminemail, $form_bool, $sidebar_bool)
    {
        return getFooter2007($filename, $adminname, $adminemail, $form_bool, $sidebar_bool);
    }

    public function renderIRTFFooter2007()
    {
        return getIRTFFooter2007();
    }

    public function renderIRTFFullHeader2007()
    {
        return getIRTFFullHeader2007();
    }

    public function renderIRTFSmallHeader()
    {
        return getIRTFSmallHeader();
    }

    public function renderIRTFHeaderP1()
    {
        return getIRTFHeaderP1();
    }

    public function renderIRTFHeaderP2()
    {
        return getIRTFHeaderP2();
    }

    public function renderIRTFHeaderP3()
    {
        return getIRTFHeaderP3();
    }

    public function renderIRTFSmallFooter()
    {
        return getIRTFSmallFooter();
    }

    public function renderIRTFSidebar2007()
    {
        return getIRTFSidebar2007();
    }

    public function getIRTFStyleStandard($printstyle)
    {
        return getIRTFStyleStandard($printstyle);
    }

    public function getIRTFStyleSimple($printstyle)
    {
        return getIRTFStyleSimple($printstyle);
    }

    public function getIRTFStyleBordersMainDiv()
    {
        return getIRTFStyleBordersMainDiv();
    }

    public function getIRTFStyleNavbarDiv()
    {
        return getIRTFStyleNavbarDiv();
    }

    public function getIRTFStyleHeaderDiv()
    {
        return getIRTFStyleHeaderDiv();
    }

    public function getIRTFStyleIndexDiv()
    {
        return getIRTFStyleIndexDiv();
    }

    public function getIRTFStyleMediaPrint($printstyle)
    {
        return getIRTFStyleMediaPrint($printstyle);
    }

    public function getIRTFStyleIndexLister()
    {
        return getIRTFStyleIndexLister();
    }

    public function renderFullPage($content)
    {
        // Assemble the full page structure
        $html = $this->renderHeader();
        $html .= $this->renderNavbar();
        $html .= $this->renderSidebar();
        $html .= $content;  // Main content of the page
        $html .= $this->renderFooter();

        return $html;
    }



    ############################################################################
    #
    # Generates an error page
    #
    #---------------------------------------------------------------------------
    #
    public function renderErrorPage( $debug, $title, $error ) {

       $isForm = false;
       $code  = "";
       $code .= myHeader( $debug, $title, $isForm );
       $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
       $code .= getHorizontalLine( 0, 0, "FFFFFF" );
       $code .= "  <tr height='30'>\n";
       $code .= "    <td>\n";
       $code .= "      <div align='center'>\n";
       $code .= "{$error}\n";
       $code .= "      Please click Back and try again.\n";
       $code .= "      </div>\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";
       $code .= getHorizontalLine( 0, 0, "FFFFFF" );
       $code .= "</table>\n";
       $code .= myFooter( __FILE__, $isForm );
       return $code;

    }

    #---------------------------------------------------------------------------
    #-- end of generateErrorPage
    ############################################################################



    ############################################################################
    #
    # Generates a results page
    #
    #---------------------------------------------------------------------------
    #
    public function renderResultsPage( $debug, $title, $message ) {

       $isForm = false;
       $code  = "";
       $code .= myHeader( $debug, $title, $isForm );
       $code .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'>\n";
       $code .= getHorizontalLine( 0, 0, "FFFFFF" );
       $code .= "  <tr height='30'>\n";
       $code .= "    <td>\n";
       $code .= "      <div align='center'>\n";
       $code .= "{$message}\n";
       $code .= "      </div>\n";
       $code .= "    </td>\n";
       $code .= "  </tr>\n";
       $code .= getHorizontalLine( 0, 0, "FFFFFF" );
       $code .= "</table>\n";
       $code .= myFooter( __FILE__, $isForm );
       return $code;

    }

    #---------------------------------------------------------------------------
    #-- end of generateResultsPage
    ############################################################################

    #---------------------------------------------------------------------------
    # Produces the header for the page
    #
    public function myHeader( $debug, $title, $form ) {

       #-----------------------------------------------
       #-- define the HTML header
       $code  = "";
       if ( $debug ) { $code .= "</div>\n"; }
       $code .= getHeader( $title, "", "", "post", $form, true, true );
       $code .= "\n\n<!-- page contents container -->\n";
       $code .= "<div style='margin: 0 5 0 5; width: 100vh; max-width: 100vw; min-width: 500px;'>\n\n";
       $code .= getTitle( $title );
       return $code;
    }

    #-- end of myHeader
    #---------------------------------------------------------------------------


    #---------------------------------------------------------------------------
    # Produces the footer for the page
    #
    public function myFooter( $file, $form ) {

       #-----------------------------------------------
       #-- define the HTML header
       $code  = "";
       $code .= "\n\n</div>\n";
       $code .= "<!-- end page contents container -->\n\n";
       $code .= getFooter( $file, CONTACT, "", $form, true );
       return $code;
    }

    #-- end of myFooter
    #---------------------------------------------------------------------------

}
