<?php

namespace App\legacy;

// Include the files containing render, process, and helper functions, etc.
// (contains the procedural proposal forms' functions)
require_once '/home/proposal/public_html/inc/helper.inc';
require_once '/home/proposal/public_html/inc/html_form.inc';
require_once '/home/proposal/public_html/inc/process_data.inc';

class Proposals
{

    ############################################################################
    #
    # HTML FUNCTIONS
    #
    ############################################################################


    public function generateScheduleFormPage($debug, $title)
    {
        return generateScheduleFormPage($debug, $title);
    }

    public function generateTacResultsFormPage($debug, $title)
    {
        return generateTacResultsFormPage($debug, $title);
    }

    public function generateEditDatabaseFormPage($debug, $title)
    {
        return generateEditDatabaseFormPage($debug, $title);
    }

    public function generateEditInstrumentsDatabaseFormPage($debug, $title)
    {
        return generateEditInstrumentsDatabaseFormPage($debug, $title);
    }

    public function generateEditOperatorsDatabaseFormPage($debug, $title)
    {
        return generateEditOperatorsDatabaseFormPage($debug, $title);
    }

    public function generateEditSupportDatabaseFormPage($debug, $title)
    {
        return generateEditSupportDatabaseFormPage($debug, $title);
    }

    public function generateEngProgPage($debug, $title)
    {
        return generateEngProgPage($debug, $title);
    }

    public function generateEngProgEditorPage($debug, $title, $engdata)
    {
        return generateEngProgEditorPage($debug, $title, $engdata);
    }

    public function generateLoadTacMembersFormPage($debug, $title)
    {
        return generateLoadTacMembersFormPage($debug, $title);
    }

    public function generateLoadTacMembersSemesterPage($debug, $title, $year, $sem)
    {
        return generateLoadTacMembersSemesterPage($debug, $title, $year, $sem);
    }

    public function generateLoadTacEmailsFormPage($debug, $title)
    {
        return generateLoadTacEmailsFormPage($debug, $title);
    }

    public function generateLoadTacEmailsSemesterPage($debug, $title, $year, $sem)
    {
        return generateLoadTacEmailsSemesterPage($debug, $title, $year, $sem);
    }

    public function generateAssignTacMembersFormPage($debug, $title)
    {
        return generateAssignTacMembersFormPage($debug, $title);
    }

    public function generatePrepStaffTacAreasFormPage($debug, $title)
    {
        return generatePrepStaffTacAreasFormPage($debug, $title);
    }

    public function generateStaffTACPdfsFormPage($debug, $title)
    {
        return generateStaffTACPdfsFormPage($debug, $title);
    }

    public function generateStaffTACZipFormPage($debug, $title)
    {
        return generateStaffTACZipFormPage($debug, $title);
    }

    public function generateProposalCountsFormPage($debug, $title)
    {
        return generateProposalCountsFormPage($debug, $title);
    }

    public function generateProposalInstrCountsFormPage($debug, $title)
    {
        return generateProposalInstrCountsFormPage($debug, $title);
    }

    public function generateInstrumentHoursFormPage($debug, $title)
    {
        return generateInstrumentHoursFormPage($debug, $title);
    }

    public function generateEditApplicationsFormPage($debug, $title)
    {
        return generateEditApplicationsFormPage($debug, $title);
    }

    public function generateEditApplicationsSemesterPage($debug, $title, $year, $sem, $appurl)
    {
        return generateEditApplicationsSemesterPage($debug, $title, $year, $sem, $appurl);
    }

    public function generateNukeApplicationsFormPage($debug, $title)
    {
        return generateNukeApplicationsFormPage($debug, $title);
    }

    public function generateNukeApplicationsSemesterPage($debug, $title, $year, $sem)
    {
        return generateNukeApplicationsSemesterPage($debug, $title, $year, $sem);
    }

    public function generateNukeApplicationConfirmPage($debug, $title, $data)
    {
        return generateNukeApplicationConfirmPage($debug, $title, $data);
    }

    public function generateMailingListObsPage($debug, $title)
    {
        return generateMailingListObsPage($debug, $title);
    }

    public function generateMailingListUsersPage($debug, $title)
    {
        return generateMailingListUsersPage($debug, $title);
    }

    public function generateProgramExportPage($debug, $title)
    {
        return generateProgramExportPage($debug, $title);
    }

    public function generateDataRestoreFormPage($debug, $title)
    {
        return generateDataRestoreFormPage($debug, $title);
    }

    public function generateDataRequestFormPage($debug, $title)
    {
        return generateDataRequestFormPage($debug, $title);
    }

    public function generateDataRequestFormPageDIV($debug, $title)
    {
        return generateDataRequestFormPageDIV($debug, $title);
    }

    public function generateScienceHighlightFormPage($debug, $title)
    {
        return generateScienceHighlightFormPage($debug, $title);
    }

    public function generateErrorPage($debug, $title, $error)
    {
        return generateErrorPage($debug, $title, $error);
    }

    public function generateResultsPage($debug, $title, $message)
    {
        return generateResultsPage($debug, $title, $message);
    }

    public function myHeader($debug, $title, $form)
    {
        return myHeader($debug, $title, $form);
    }

    public function myFooter($file, $form)
    {
        return myFooter($file, $form);
    }

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

    public function processTACResultsFMP($debug, $title, $tfile)
    {
        return processTACResultsFMP($debug, $title, $tfile);
    }

    public function processFastTrack($debug, $title, $cvsfile, $loadtype, $access)
    {
        return processFastTrack($debug, $title, $cvsfile, $loadtype, $access);
    }

    public function processSchedule($debug, $title, $semester, $type, $access, $export)
    {
        return processSchedule($debug, $title, $semester, $type, $access, $export);
    }

    public function processEditDatabase($debug, $title)
    {
        return processEditDatabase($debug, $title);
    }

    public function processEditInstrumentsDatabase($debug, $title)
    {
        return processEditInstrumentsDatabase($debug, $title);
    }

    public function processEditOperatorsDatabase($debug, $title)
    {
        return processEditOperatorsDatabase($debug, $title);
    }

    public function processEditSupportDatabase($debug, $title)
    {
        return processEditSupportDatabase($debug, $title);
    }

    public function processEngineeringList($debug, $title)
    {
        return processEngineeringList($debug, $title);
    }

    public function processLoadTacMembers($debug, $title, $get)
    {
        return processLoadTacMembers($debug, $title, $get);
    }

    public function processLoadTacMembersSemester($debug, $title, $post)
    {
        return processLoadTacMembersSemester($debug, $title, $post);
    }

    public function processLoadTacEmails($debug, $title, $get)
    {
        return processLoadTacEmails($debug, $title, $get);
    }

    public function processLoadTacEmailsSemester($debug, $title, $post)
    {
        return processLoadTacEmailsSemester($debug, $title, $post);
    }

    public function listTacMembers($debug, $title)
    {
        return listTacMembers($debug, $title);
    }

    public function processAssignTacMembers($debug, $title, $get)
    {
        return processAssignTacMembers($debug, $title, $get);
    }

    public function processPrepStaffTacAreas($debug, $title)
    {
        return processPrepStaffTacAreas($debug, $title);
    }

    public function processStaffPdfs($debug, $title)
    {
        return processStaffPdfs($debug, $title);
    }

    public function processTacPdfs($debug, $title)
    {
        return processTacPdfs($debug, $title);
    }

    public function processStaffZip($debug, $title)
    {
        return processStaffZip($debug, $title);
    }

    public function processTacZip($debug, $title)
    {
        return processTacZip($debug, $title);
    }

    public function processProposalCounts($debug, $title)
    {
        return processProposalCounts($debug, $title);
    }

    public function listProposalCounts($debug, $title)
    {
        return listProposalCounts($debug, $title);
    }

    public function processProposalInstrCounts($debug, $title)
    {
        return processProposalInstrCounts($debug, $title);
    }

    public function processInstrumentHours($debug, $title)
    {
        return processInstrumentHours($debug, $title);
    }

    public function processProposalAck($debug, $title, $get)
    {
        return processProposalAck($debug, $title, $get);
    }

    public function processProgramExport($debug, $title, $year, $semester)
    {
        return processProgramExport($debug, $title, $year, $semester);
    }

    public function processMailingListObs($debug, $title, $export, $type)
    {
        return processMailingListObs($debug, $title, $export, $type);
    }

    public function processMailingListUsers($debug, $title, $export, $type)
    {
        return processMailingListUsers($debug, $title, $export, $type);
    }

    public function processNukeApplicationsSubmit($debug, $title, $get)
    {
        return processNukeApplicationsSubmit($debug, $title, $get);
    }

    public function processNukeApplicationConfirm($debug, $title, $data)
    {
        return processNukeApplicationConfirm($debug, $title, $data);
    }

    public function processEditApplications($debug, $title, $get)
    {
        return processEditApplications($debug, $title, $get);
    }

    public function processDataRestore($debug, $sendemails, $title)
    {
        return processDataRestore($debug, $sendemails, $title);
    }

    public function processDataRequest($debug, $sendemails, $data, $title)
    {
        return processDataRequest($debug, $sendemails, $data, $title);
    }

    public function processScienceHighlight($debug, $sendemails, $data, $title)
    {
        return processScienceHighlight($debug, $sendemails, $data, $title);
    }

    ############################################################################
    #
    # END OF PROCESSING FUNCTIONS
    #
    ############################################################################


    ############################################################################
    #
    # HELPER FUNCTIONS
    #
    ############################################################################

    public function retrieveAcctData($debug, $semyear, $semcode, $guest)
    {
        return retrieveAcctData($debug, $semyear, $semcode, $guest);
    }

    public function getInstrumentListProposal($debug, $ins)
    {
        return getInstrumentListProposal($debug, $ins);
    }

    public function getProgramListProposal($debug, $sem, $yr)
    {
        return getProgramListProposal($debug, $sem, $yr);
    }

    public function getEngineeringList($debug, $semyear, $semcode)
    {
        return getEngineeringList($debug, $semyear, $semcode);
    }

    public function getPulldownCategory($name, $option, $pad)
    {
        return getPulldownCategory($name, $option, $pad);
    }

    public function returnCategory($type = "")
    {
        return returnCategory($type = "");
    }

    public function returnFastTrackLines($debug, $ftfile)
    {
        return returnFastTrackLines($debug, $ftfile);
    }

    public function returnFastTrackSQL($debug, $lt, $sm, $td, $ft, $cm)
    {
        return returnFastTrackSQL($debug, $lt, $sm, $td, $ft, $cm);
    }

    public function loadFastTrackDB($debug, $sql)
    {
        return loadFastTrackDB($debug, $sql);
    }

    public function getScheduleDB($debug, $sem)
    {
        return getScheduleDB($debug, $sem);
    }

    public function getHolidayList($debug, $year)
    {
        return getHolidayList($debug, $year);
    }

    public function getHolidayList2($debug, $year)
    {
        return getHolidayList2($debug, $year);
    }

    public function getFacilityLine($debug, $date, $shutdownstr, $cm)
    {
        return getFacilityLine($debug, $date, $shutdownstr, $cm);
    }

    public function getCommentsList($debug)
    {
        return getCommentsList($debug);
    }

    public function returnTextSchedule($debug, $schedule, $defaultdays = true, $defaultbreaks = true)
    {
        return returnTextSchedule($debug, $schedule, $defaultdays = true, $defaultbreaks = true);
    }

    public function returnScheduleFooter($debug, $comflags, $type)
    {
        return returnScheduleFooter($debug, $comflags, $type);
    }

    public function getProgramScheduleDB($debug, $program)
    {
        return getProgramScheduleDB($debug, $program);
    }

    public function getNextWeekScheduleDB($debug)
    {
        return getNextWeekScheduleDB($debug);
    }

    public function returnMiniSchedule($debug, $schedule, $defaultdays = true)
    {
        return returnMiniSchedule($debug, $schedule, $defaultdays = true);
    }

    public function returnPDFSchedule($debug, $schedule, $defaultdays = true, $defaultbreaks = true)
    {
        return returnPDFSchedule($debug, $schedule, $defaultdays = true, $defaultbreaks = true);
    }

    public function writeScheduleFile($debug, $type, $access, $export, $text)
    {
        return writeScheduleFile($debug, $type, $access, $export, $text);
    }

    public function generateScheduleEmail($debug, $title, $msg)
    {
        return generateScheduleEmail($debug, $title, $msg);
    }

    public function writeDataRequestFile($debug, $filename, $text)
    {
        return writeDataRequestFile($debug, $filename, $text);
    }

    public function generateDataRequestEmail($debug, $title, $data)
    {
        return generateDataRequestEmail($debug, $title, $data);
    }

    public function prepIrtfstaffDir($debug, $tacdir)
    {
        return prepIrtfstaffDir($debug, $tacdir);
    }

    public function prepHtpasswd($debug, $confdir, $previous, $current)
    {
        return prepHtpasswd($debug, $confdir, $previous, $current);
    }

    public function prepStaffDir($debug, $tacdir, $semester)
    {
        return prepStaffDir($debug, $tacdir, $semester);
    }

    public function prepTacDir($debug, $tacdir, $semester)
    {
        return prepTacDir($debug, $tacdir, $semester);
    }

    public function prepStaffZip($debug, $tacdir, $current)
    {
        return prepStaffZip($debug, $tacdir, $current);
    }

    public function prepTacZip($debug, $tacdir, $current)
    {
        return prepTacZip($debug, $tacdir, $current);
    }

    public function returnTACMemberBlock($debug, $inx, $color, $yr1, $yr2, $tac)
    {
        return returnTACMemberBlock($debug, $inx, $color, $yr1, $yr2, $tac);
    }

    public function updateDBEmailAcknowledged($debug, $key)
    {
        return updateDBEmailAcknowledged($debug, $key);
    }

    public function postscriptColumns($debug, $left)
    {
        return postscriptColumns($debug, $left);
    }

    public function postscriptPageInfo($debug)
    {
        return postscriptPageInfo($debug);
    }

    public function postscriptPageHeader($debug, $page, $mon, $year)
    {
        return postscriptPageHeader($debug, $page, $mon, $year);
    }

    public function postscriptMonthFooter($debug, $page, $obs, $mon, $year)
    {
        return postscriptMonthFooter($debug, $page, $obs, $mon, $year);
    }

    public function postscriptScheduleHeader($debug, $page, $cols)
    {
        return postscriptScheduleHeader($debug, $page, $cols);
    }

    public function postscriptScheduleLine($debug, $page, $line, $cols, $mon, $yr)
    {
        return postscriptScheduleLine($debug, $page, $line, $cols, $mon, $yr);
    }

    public function postscriptFooter($debug, $page, $comflags)
    {
        return postscriptFooter($debug, $page, $comflags);
    }

    public function postscriptHeader($debug, $page)
    {
        return postscriptHeader($debug, $page);
    }

    ############################################################################
    #
    # END OF HELPER FUNCTIONS
    #
    ############################################################################

}
