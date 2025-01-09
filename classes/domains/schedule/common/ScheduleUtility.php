<?php

declare(strict_types=1);

namespace App\domains\schedule\common;

use Exception;

/**
 * /home/webdev2024/classes/domains/schedule/common/ScheduleUtility.php
 *
 * This class provides static methods to handle IRTF-specific tasks.
 *
 * @category Utilities
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleUtility
{
    public static function getCommentsList(): array
    {
        /**
         * Note that Service Obs is in the list but usually indicated manually
         * by the scheduler.
         */
        return [
            "Daylight Obs",
            "First Night",
            "Service Obs",
            "facility open",
            "facility close",
            "inst. change",
            "Facility",
            "Facility Shutdown",
            "Christmas Eve",
            "Christmas",
            "New Year's Eve"
        ];
    }

    public static function getSemester(
        string $semesterString,
        string $stringType = 'program'
    ): string {
        switch ($stringType) {
            case 'program':
                // Extract the semester from a program number
                return substr($semesterString, 0, 5);

            // Future cases can be added here for different string formats
            default:
                throw new Exception("Invalid string type provided: {$stringType}");
        }
    }

    public static function getFacilityLine(
        string $shutdownstr,
        string $cm
    ): array {
        $stime = sprintf("%-5s", "-----");
        $sp    = sprintf("%-11s", "");
        $noday = sprintf("%-11s", "");

        return [
            'sdate' => "",
            'stime' => $stime,
            'etime' => $stime,
            'op'    => $sp,
            'remob' => "",
            'prog'  => "",
            'pi'    => $shutdownstr,
            'ins'   => $sp,
            'sa'    => "",
            'com'   => $cm,
        ];
    }

    public static function getHolidayList(int $year): array
    {
        $stime = str_pad("-----", 5); // Placeholder for times
        $sp = str_pad("", 13);        // Placeholder for spacing
        $noday = str_pad("", 11);     // Placeholder for "no date"

        // Define holidays with offsets
        $holidays = [
            ['name' => "Christmas Eve", 'offset' => 0],
            ['name' => "Christmas", 'offset' => 1],
            ['name' => "New Year's Eve", 'offset' => 7]
        ];

        $holidayList = [];
        foreach ($holidays as $holiday) {
            $date = mktime(18, 0, 0, 12, 24 + $holiday['offset'], $year);
            $holidayList[] = self::generateHolidayData($date, $holiday['name'], $stime, $sp, $noday);
        }

        return $holidayList;
    }

    /**
     * Generates the structured holiday data.
     *
     * @param int    $date   The Unix timestamp for the holiday.
     * @param string $name   The name of the holiday.
     * @param string $stime  Placeholder for times.
     * @param string $sp     Placeholder for spacing.
     * @param string $noday  Placeholder for "no date".
     *
     * @return array Structured holiday data.
     */
    private static function generateHolidayData(
        int $date,
        string $name,
        string $stime,
        string $sp,
        string $noday
    ): array {
        $day = substr(date("D", $date), 0, 2);
        $dateinfo = sprintf("%s %2d %s", date("M.", $date), date("j", $date), $day);

        return [
            'date' => $date,
            'txt' => "{$dateinfo}  {$stime} {$stime}  {$sp} {$name}",
            'noday' => "{$noday} {$stime} {$stime}  {$sp} {$name}",
            'line' => [
                "date"  => $dateinfo,
                "time"  => $stime,
                "time2" => $stime,
                "op"    => "",
                "rem"   => "",
                "prog"  => "",
                "pi"    => $name,
                "ins"   => "",
                "sa"    => "",
                "com"   => ""
            ],
            'lnoday' => [
                "date"  => "",
                "time"  => $stime,
                "time2" => $stime,
                "op"    => "",
                "rem"   => "",
                "prog"  => "",
                "pi"    => $name,
                "ins"   => "",
                "sa"    => "",
                "com"   => ""
            ]
        ];
    }

    public static function generateHeaderMap(array $fileHeaders): array
    {
        // Define required headers and their DB counterparts
        $headerMappings = [
            'Program'           => ['db' => 'programID'],
            'PI'                => ['db' => 'projectPI'],
            'Instrument'        => ['db' => 'instrumentIDs'],
            'Start Date'        => ['db' => 'startTime'],
            'Start Time'        => ['db' => 'startTime'],
            'Finish Date'       => ['db' => 'endTime'],
            'Finish Time'       => ['db' => 'endTime'],
            'DayTime'           => ['db' => 'daytimeObs'],
            'Remote'            => ['db' => 'remoteObs'],
            'Facility Open'     => ['db' => 'facilityOpen'],
            'Facility Close'    => ['db' => 'facilityClose'],
            'Instrument Change' => ['db' => 'insChange'],
            'Shutdown'          => ['db' => 'facilityShutD'],
            'TO'                => ['db' => 'operatorIDs'],
            'SA'                => ['db' => 'supportID'],
            'FirstNight'        => ['db' => 'firstTime'],
            'Comments'          => ['db' => 'comments'],
        ];

        // Additional derived fields not present in CSV headers
        $derivedFields = [
            'logID'          => 'logID',
            'semesterID'     => 'semesterID',
            'PIEmail'        => 'PIEmail',
            'PIName'         => 'PIName',
            'otherInfo'      => 'otherInfo',
            'projectMembers' => 'projectMembers',
        ];

        // Map CSV headers to column indexes and add derived fields
        foreach ($headerMappings as $header => &$mapping) {
            $mapping['csv'] = array_search($header, $fileHeaders); // -1 or null if not found
        }

        // Add derived fields (not present in CSV headers)
        foreach ($derivedFields as $field => $db) {
            $headerMappings[$field] = ['db' => $db, 'csv' => null];
        }

        return $headerMappings;
    }

    public static function generateCSVHeaderMap(array $fileHeaders): array
    {
        // Construct header mapping
        $requiredFileHeaders = [
            'Program',
            'PI',
            'Instrument',
            //'Description',
            //'Day',
            'Start Date',
            'Start Time',
            'Finish Date',
            'Finish Time',
            //'Nights',
            //'SolSys?',
            'DayTime',
            'Remote',
            'Facility Open',
            'Facility Close',
            'Instrument Change',
            'Shutdown',
            //'SumNights',
            'TO',
            'SA',
            'FirstNight',
            'Comments',
        ];
        $headerMap = [];
        foreach ($requiredFileHeaders as $key) {
            $headerMap[$key] = array_search($key, $fileHeaders);
        }
        return $headerMap;
    }


    public static function escape(
        string $string
    ): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
