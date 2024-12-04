<?php

namespace App\legacy;

// Include the files containing render, process, and helper functions, etc.
// (contains the procedural application forms' functions)
require_once '/htdocs/observing/application/helper.inc';
require_once '/htdocs/observing/application/html_form.inc';
require_once '/htdocs/observing/application/process_data.inc';
require_once '/htdocs/observing/application/process_applications.inc';

class Applications
{

    ############################################################################
    #
    # HTML FUNCTIONS
    #
    ############################################################################

    public function generateLoginPage($debug, $title, $data)
    {
        return generateLoginPage($debug, $title, $data);
    }

    public function generateStaffPage($debug, $title, $data)
    {
        return generateStaffPage($debug, $title, $data);
    }

    public function generateFormPage($debug, $title, $values)
    {
        return generateFormPage($debug, $title, $values);
    }

    public function generatePreviewPage($debug, $sendemails, $title, $form, $data)
    {
        return generatePreviewPage($debug, $sendemails, $title, $form, $data);
    }

    public function generateErrorPage($debug, $title, $error, $formtype, $data)
    {
        return generateErrorPage($debug, $title, $error, $formtype, $data);
    }

    public function generateSavePage($debug, $sendemails, $title, $data)
    {
        return generateSavePage($debug, $sendemails, $title, $data);
    }

    public function generateSubmitPage($debug, $sendemails, $title, $data, $htmllink)
    {
        return generateSubmitPage($debug, $sendemails, $title, $data, $htmllink);
    }


############################################################################
#
# SECTION FUNCTIONS
#
############################################################################



    public function myHeader($debug, $title, $form, $width = "90")
    {
        return myHeader($debug, $title, $form, $width = "90");
    }

    public function myFooter($debug, $file, $contact, $form)
    {
        return myFooter($debug, $file, $contact, $form);
    }

    public function getPreamble($debug, $data)
    {
        return getPreamble($debug, $data);
    }

    public function getApplicationButtons($debug, $bwid)
    {
        return getApplicationButtons($debug, $bwid);
    }

    public function getPreviewButtons($debug, $bwid)
    {
        return getPreviewButtons($debug, $bwid);
    }

    public function getSecurity($debug, $data, $bwid)
    {
        return getSecurity($debug, $data, $bwid);
    }

    public function getBody($debug, $data, $form, $bwid, $numinv, $numrun)
    {
        return getBody($debug, $data, $form, $bwid, $numinv, $numrun);
    }

    public function getBodyNumInvRuns($debug, $data, $form, $bwid, $color, $numinv, $numrun)
    {
        return getBodyNumInvRuns($debug, $data, $form, $bwid, $color, $numinv, $numrun);
    }

    public function getBodyInvestigators($debug, $data, $form, $bwid, $color, $numinv, $numrun)
    {
        return getBodyInvestigators($debug, $data, $form, $bwid, $color, $numinv, $numrun);
    }

    public function getBodyThesisInfo($debug, $data, $form, $bwid, $color)
    {
        return getBodyThesisInfo($debug, $data, $form, $bwid, $color);
    }

    public function getBodyTitleInformation($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyTitleInformation($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyShortTitle($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyShortTitle($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyRunInformation($debug, $data, $form, $bwid, $color, $item, $numinv, $numrun)
    {
        return getBodyRunInformation($debug, $data, $form, $bwid, $color, $item, $numinv, $numrun);
    }

    public function getBodyRunDates($debug, $data, $form, $bwid, $color, $item, $numinv, $numrun)
    {
        return getBodyRunDates($debug, $data, $form, $bwid, $color, $item, $numinv, $numrun);
    }

    public function getBodyScienceCategory($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyScienceCategory($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyMoonPhase($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyMoonPhase($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyInstruments($debug, $data, $form, $bwid, $color, $item, $instruments)
    {
        return getBodyInstruments($debug, $data, $form, $bwid, $color, $item, $instruments);
    }

    public function getBodyUserInformation($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyUserInformation($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyRemoteObs($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyRemoteObs($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyRequirements($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyRequirements($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyProjectCompletion($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyProjectCompletion($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyAbstract($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyAbstract($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyPreviousObs($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyPreviousObs($debug, $data, $form, $bwid, $color, $item);
    }

    public function getBodyAddendum($debug, $data, $form, $bwid, $color, $item)
    {
        return getBodyAddendum($debug, $data, $form, $bwid, $color, $item);
    }

    public function getTrailer($debug, $title, $form)
    {
        return getTrailer($debug, $title, $form);
    }

    ############################################################################
    #
    # END OF SECTION FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # END OF HTML FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # PROCESSING FUNCTIONS
    #
    ############################################################################

    public function initialData($debug)
    {
        return initialData($debug);
    }

    public function harvestData($debug, $datadst, $datasrc)
    {
        return harvestData($debug, $datadst, $datasrc);
    }

    public function retrieveSavedData($debug, $piemail, $sessionID, $semyear, $semcode)
    {
        return retrieveSavedData($debug, $piemail, $sessionID, $semyear, $semcode);
    }

    public function exportApplication($debug, $data, $filename, $appnum)
    {
        return exportApplication($debug, $data, $filename, $appnum);
    }

    public function exportSavedSession($debug, $data)
    {
        return exportSavedSession($debug, $data);
    }

    public function pdfWrite($debug, $data, $filename, $appnum, $title, $creator, $addendum, $mode = "app")
    {
        return pdfWrite($debug, $data, $filename, $appnum, $title, $creator, $addendum, $mode = "app");
    }


    ############################################################################
    #
    # END OF PROCESSING FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # APPLICATIONS PROCESSING FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # END OF APPLICATIONS PROCESSING FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # HELPER FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # END OF HELPER FUNCTIONS
    #
    ############################################################################

}
