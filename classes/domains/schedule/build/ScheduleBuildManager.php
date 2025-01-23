<?php

declare(strict_types=1);

namespace App\domains\schedule\build;

use App\core\common\CustomDebug as Debug;

/**
 * /home/webdev2024/classes/domains/schedule/build/ScheduleBuildManager.php
 *
 * @category Manager
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleBuildManager
{
    private $debug;

    /**
     * Constructor for the ScheduleBuildManager class.
     */
    public function __construct(?Debug $debug = null)
    {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Manager", "__construct");
        $this->debug->debug($debugHeading);
    }

    public function ingestSchedule(array $validData): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Manager", "ingestSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");
        // Step 1: parse the csv file
        try {
            // Pass the CSV file information to the parser
            $parser = new Parser('schedule', $validData['file'], false);
            $csvSchedule = $parser->parseFile();
            $this->debug->debugVariable($csvSchedule, "{$debugHeading} -- csvSchedule");
        } catch (Exception $e) {
            // Handle any errors during the parsing phase
            return "Error parsing the uploaded file: " . $e->getMessage();
        }
        // Step 2: process the schedule data for ingestion
        try {
            // Pass the file array to the processer
            $processor = new Processor($csvSchedule);
            $processedSchedule = $processor->processSchedule();
            $this->debug->debugVariable($processedSchedule, "{$debugHeading} -- processedSchedule");
            // ORIGINAL Step 2: get the list of comments, the date, the semester
            //$comflags = ScheduleUtility::getCommentsList();
            //$this->debug->debugVariable($comflags, "{$debugHeading} -- comflags");
            //-- Capture the time now, will be used for checking thru-out script.
            //-- $today is the logID value of tonight's observing, since the observing
            //-- that occured in the AM of the current day has the logID of yesterday's
            //-- observing. logID <> start_time
            //$today = [clock scan [clock format time(), -format "%m/%d/%Y"]];
            //$today = date_format( date_create( date( "m/d/Y", time() ) ), "U" );
            //$today = strtotime('today');
            //$this->debug->debugVariable($today, "{$debugHeading} -- today");
            //$semester = ScheduleUtility::getSemester($csvSchedule['lines'][0][0]);
            //$this->debug->debugVariable($semester, "{$debugHeading} -- semester");
            // ORIGINAL Step 3: generate the SQL to be sent to the database
            //$sql = returnFastTrackSQL( $debug, $loadtype, $semester, $today, $fasttrack, $comflags );
        } catch (Exception $e) {
            // Handle any errors during the processing phase
            return "Error processing the schedule data: " . $e->getMessage();
        }
        // Step 3: ingest the schedule to the database
        try {
            // Pass the prepared data to the ingestor
            $ingestor = new Ingestor();
            $result = $ingestor->ingestSchedule($processedSchedule);
            $this->debug->debugVariable($result, "{$debugHeading} -- result");
            // ORIGINAL Step 4: execute the database load
            //$code = loadFastTrackDB( $debug, $sql );
            //$result = "ingestSchedule test";
        } catch (Exception $e) {
            // Handle any errors during the parsing phase
            return "Error parsing the uploaded file: " . $e->getMessage();
        }
        // Step 5: return the result message.
        return $result;
        //processFastTrack: 91 lnes of code
        //-- returnFastTrackLines: 47 lnes of code --> DONE
        //-- getCommentsList: 32 lnes of code --> DONE
        //-- returnFastTrackSQL: 429 lnes of code
        //---- getInstrumentListProposal: 115 lnes of code
        //---- getOperatorList: 47 lnes of code
        //---- getProgramListProposal: 63 lnes of code
        //-- loadFastTrackDB: 178 lnes of code
    }
}
