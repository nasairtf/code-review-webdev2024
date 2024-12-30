<?php

declare(strict_types=1);

namespace App\schedule\upload;

use App\core\common\Debug;
use App\services\files\FileWriter as Writer;
use App\schedule\common\ScheduleUtility;
use App\schedule\upload\ScheduleUploadModel as Model;

/**
 * /home/webdev2024/classes/schedule/upload/ScheduleProcessor.php
 *
 * @category Processor
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleProcessor
{
    private $debug;
    private $writer;
    private $model;

    /**
     * Constructor for the ScheduleProcessor class.
     */
    public function __construct(
        ?Debug $debug = null,
        ?Writer $writer = null,
        ?Model $model = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Processor", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this processor
        $this->model = $model ?? new Model($this->debug);
        $this->writer = $writer ?? new Writer('infilesql', null, $this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- Model, Writer classes successfully initialised.");

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- Processor initialisation complete.");
    }

    public function processSchedule(array $scheduleData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "processSchedule");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($scheduleData, "{$debugHeading} -- scheduleData");

        // Step 1: Get the list of comments, the logID date, the semester, etc
        $prep = $this->prepareForProcessing(
            $scheduleData['csv']['lines'][0][0],
            $scheduleData['csv']['header'],
            $scheduleData['loadtype'],
            $scheduleData['access'],
            $scheduleData['fileload']
        );
        $this->debug->debugVariable($prep, "{$debugHeading} -- prep");

        // Step 2: Parse the schedule file's rows
        $parsedRows = $this->parseRows(
            $scheduleData['csv']['lines'],
            $prep
        );
        $this->debug->debugVariable($parsedRows, "{$debugHeading} -- parsedRows");

        // Step 3: Prepare DELETE SQL
        $deleteSQL = $this->prepareDeletes(
            $prep['type'],
            $prep['semester'],
            $prep['logID']
        );
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");

        // Step 4: Prepare INSERT SQL
        $insertSQL = $this->prepareInserts(
            $parsedRows,
            $prep
        );
        $this->debug->debugVariable($insertSQL, "{$debugHeading} -- insertSQL");

        // Step 5: Prepare the SQL files
        $insertFiles = $scheduleData['fileload']
            ? $this->prepareFiles(
                $insertSQL,
                $prep
            )
            : [];
        $this->debug->debugVariable($insertFiles, "{$debugHeading} -- insertFiles");

        // Step 6: Return the SQL/file paths.
        $processResults = [
            'fileload' => $scheduleData['fileload'],
            'delete' => $deleteSQL,
        ];
        // Add either 'files' or 'sql' based on 'fileload'
        $processResults += $scheduleData['fileload']
            ? ['files' => $insertFiles]
            : ['sql' => $insertSQL];
        $this->debug->debugVariable($processResults, "{$debugHeading} -- processResults");
        return $processResults;
    }

    private function prepareFiles(
        array $sql,
        array $prep
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "prepareFiles");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($sql, "{$debugHeading} -- sql");
        $this->debug->debugVariable($prep, "{$debugHeading} -- prep");
        // Retrieve file headers
        $fileHeaders = $this->prepareHeaders();
        // Prepare file mapping
        $fileMapping = [
            'program'     => [
                'filename' => "/home/webdev2024/data/schedule/infile.program.sql.cvs",
                'table'    => 'Program',
                'fields'   => $fileHeaders['program'],
                'sqldata'  => $sql['tacprogram'],
            ],
            'schedule'    => [
                'filename' => "/home/webdev2024/data/schedule/infile.schedule.sql.cvs",
                'table'    => 'ScheduleObs',
                'fields'   => $fileHeaders['schedule'],
                'sqldata'  => $sql['schedule'],
            ],
            'instrument'  => [
                'filename' => "/home/webdev2024/data/schedule/infile.instrument.sql.cvs",
                'table'    => 'DailyInstrument',
                'fields'   => $fileHeaders['instrument'],
                'sqldata'  => $sql['instrument'],
            ],
            'operator'    => [
                'filename' => "/home/webdev2024/data/schedule/infile.operator.sql.cvs",
                'table'    => 'DailyOperator',
                'fields'   => $fileHeaders['operator'],
                'sqldata'  => $sql['operator'],
            ],
        ];
        // use the file writer class to write the files
        $sqlLoad = [
            'program'    => [],
            'schedule'   => [],
            'instrument' => [],
            'operator'   => [],
        ];
        foreach ($fileMapping as $type => $details) {
            $data = array_merge([$details['fields']], $details['sqldata']);
            if ($this->writer->writeFile($data, $details['filename'])) {
                $this->debug->debug("File was successfully written at {$details['filename']}.");
                $sqlLoad[$type]['file'] = $details['filename'];
                $sqlLoad[$type]['sql'] = "
LOAD DATA INFILE '{$details['filename']}' INTO TABLE {$details['table']}
FIELDS TERMINATED BY ';'
ENCLOSED BY '\"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(" . implode(', ', $details['fields']) . ")";
                $this->debug->debugVariable($sqlLoad[$type], "{$debugHeading} -- sqlLoad[$type]");
                $filestats = $this->getFileStats($sqlLoad[$type]['file']);
                $this->debug->debugVariable($filestats, "{$debugHeading} -- filestats");
            }
        }
        $this->debug->debugVariable($sqlLoad, "{$debugHeading} -- sqlLoad");
        return $sqlLoad;
    }


    /**
     * Gather statistics for a given file.
     *
     * @param string $filePath
     * @return array|string
     */
    private function getFileStats(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return "File not found";
        }

        $stat = stat($filePath);

        return [
            'file'      => $filePath,
            'size'      => filesize($filePath), // File size in bytes
            'modified'  => date('Y-m-d H:i:s', $stat['mtime']), // Last modified time
            'created'   => date('Y-m-d H:i:s', $stat['ctime']), // Creation time
            'owner'     => $stat['uid'], // File owner (UID)
            'group'     => $stat['gid'], // File group (GID)
            'permissions' => substr(sprintf('%o', fileperms($filePath)), -4) // Permissions
        ];
    }

    private function prepareInserts(
        array $rows,
        array $prep
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "prepareInserts");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rows, "{$debugHeading} -- rows");
        $this->debug->debugVariable($prep, "{$debugHeading} -- prep");
        // Prepare the lists and filemode
        $instruments = $prep['instruments'];
        $operators   = $prep['operators'];
        $filemode    = $prep['fileload'];
        // Prepare the SQL arrays
        $tacProgramSQL = [];
        $engProgramSQL = [];
        $scheduleSQL = [];
        $instrumentSQL = [];
        $operatorSQL = [];
        // Prepare the INSERT statements
        foreach ($rows as $key => $row) {
            // Build program SQL (always processed, no way to know when the program runs)
            $this->buildProgramSQL($row, $tacProgramSQL, $engProgramSQL, $filemode);
            // Skip processing irrelevant rows for partial loads
            if ($this->skipRow($row, $prep)) {
                continue;
            }
            // Build SQL for schedule, instruments, and operators
            $this->buildScheduleSQL($row, $scheduleSQL, $filemode);
            $this->buildInstrumentSQL($row, $instruments, $instrumentSQL, $filemode);
            $this->buildOperatorSQL($row, $operators, $operatorSQL, $filemode);
        }
        // sort some of the data
        sort($operatorSQL);
        ksort($tacProgramSQL);
        ksort($engProgramSQL);

        return [
            'tacprogram' => $tacProgramSQL,
            'engprogram' => $engProgramSQL,
            'schedule'   => $scheduleSQL,
            'instrument' => $instrumentSQL,
            'operator'   => $operatorSQL,
        ];
    }

    private function skipRow(array $row, array $prep): bool
    {
        // Skip rows in partial loads where logID is earlier than the cutoff
        return $prep['type'] === 'partial' && $row['logID'] < $prep['logID'];
    }

    private function buildScheduleSQL(
        array $row,
        array &$scheduleInserts,
        bool $fileLoad = true
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "buildScheduleSQL");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        //$this->debug->debugVariable($scheduleInserts, "{$debugHeading} -- &scheduleInserts");
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");

        // Prepare the fields
        $logID         = $row['logID'];
        $startTime     = $row['startTime'];
        $endTime       = $row['endTime'];
        $semesterID    = $row['semesterID'];
        $programID     = $row['programID'];
        $remoteObs     = $row['remoteObs'];
        $daytimeObs    = $row['daytimeObs'];
        $firstTime     = $row['firstTime'];
        $facilityOpen  = $row['facilityOpen'];
        $facilityClose = $row['facilityClose'];
        $insChange     = $row['insChange'];
        $facilityShutD = $row['facilityShutD'];
        $supAstroID    = $row['supportID'];
        $comments      = $this->formatComments($row['comments'], $fileLoad);

        // Build the SQL
        $sql = $fileLoad
            ? [
                $logID,
                $startTime,
                $semesterID,
                $endTime,
                $remoteObs,
                $daytimeObs,
                $firstTime,
                $facilityOpen,
                $facilityClose,
                $insChange,
                $facilityShutD,
                $supAstroID,
                $programID,
                $comments
            ]
            : sprintf(
                "INSERT INTO `ScheduleObs` SET logID=%d, startTime=%d, semesterID='%s', endTime=%d, remoteObs=%d, "
                    . "daytimeObs=%d, firstTime=%d, facilityOpen=%d, facilityClose=%d, instrumentChange=%d, "
                    . "facilityShutdown=%d, supportAstronomerID='%s', programID=%d%s;",
                $logID,
                $startTime,
                $semesterID,
                $endTime,
                $remoteObs,
                $daytimeObs,
                $firstTime,
                $facilityOpen,
                $facilityClose,
                $insChange,
                $facilityShutD,
                $supAstroID,
                $programID,
                $comments
            );

        // Add to the schedule inserts list, avoiding duplicates
        if (!in_array($sql, $scheduleInserts, true)) {
            $scheduleInserts[] = $sql;
        } else {
            $this->debug->debug("Duplicate schedule row skipped: " . ($fileLoad ? json_encode($sql) : $sql));
        }
    }

    private function formatComments(?string $comments, bool $fileLoad = true): string
    {
        if (empty($comments)) {
            return '';
        }
        return $fileLoad
            ? $comments
            : sprintf(", comments='%s'", addslashes($comments));
    }

    private function buildInstrumentSQL(
        array $row,
        array $instrumentList,
        array &$instrumentInserts,
        bool $fileLoad = true
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "buildInstrumentSQL");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        $this->debug->debugVariable($instrumentList, "{$debugHeading} -- instrumentList");
        //$this->debug->debugVariable($instrumentInserts, "{$debugHeading} -- &instrumentInserts");
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");

        // Prepare the fields
        $logID      = $row['logID'];
        $startTime  = $row['startTime'];
        $semesterID = $row['semesterID'];
        $programID  = $row['programID'];

        // Process the instruments for this row
        foreach ($row['instrumentIDs'] as $key => $instrumentID) {
            // Find the instrument in the provided list or default to "TBD"
            $index = array_search(strtoupper($instrumentID), $instrumentList['itemName']);
            if ($index === false) {
                $index = array_search("TBD", $instrumentList['itemName']);
            }
            // Skip if no valid instrument found, even "TBD"
            if ($index === false) {
                $this->debug->debug("Instrument ID '{$instrumentID}' not found in instrumentList -- skipping.");
                continue;
            }
            $hardwareID = $instrumentList['hardwareID'][$index];

            // Rank is equal to $key for all instruments, starting at 0
            $rank = $key;

            // Build the SQL
            $sql = $fileLoad
                ? [
                    $logID,
                    $startTime,
                    $semesterID,
                    $programID,
                    $hardwareID,
                    $rank
                ]
                : sprintf(
                    "INSERT INTO `DailyInstrument` SET logID=%d, startTime=%d, semesterID='%s', programID=%d, "
                        . "hardwareID='%s', rank=%d;",
                    $logID,
                    $startTime,
                    $semesterID,
                    $programID,
                    $hardwareID,
                    $rank
                );

            // Add to inserts list, avoiding duplicates
            if (!in_array($sql, $instrumentInserts, true)) {
                $instrumentInserts[] = $sql;
            } else {
                $this->debug->debug("Duplicate instrument entry skipped: " . ($fileLoad ? json_encode($sql) : $sql));
            }
        }
    }

    private function buildOperatorSQL(
        array $row,
        array $operatorList,
        array &$operatorInserts,
        bool $fileLoad = true
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "buildOperatorSQL");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        $this->debug->debugVariable($operatorList, "{$debugHeading} -- operatorList");
        //$this->debug->debugVariable($operatorInserts, "{$debugHeading} -- &operatorInserts");
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");

        // Prepare the fields
        $logID      = $row['logID'];
        $startTime  = $row['startTime'];
        $semesterID = $row['semesterID'];
        $programID  = $row['programID'];
        $arrive     = -1;
        $depart     = -1;

        // Process the operators for this row
        foreach ($row['operatorIDs'] as $key => $operatorID) {
            // Skip empty operator IDs
            if (empty($operatorID)) {
                continue;
            }

            // Find the operator in the provided list
            $operatorIndex = array_search($operatorID, $operatorList['operatorID']);
            // Skip if operator not found in the list
            if ($operatorIndex === false) {
                $this->debug->debug("Operator ID {$operatorID} not found in operatorList -- skipping.");
                continue;
            }
            $operator = $operatorList['operatorID'][$operatorIndex];

            // Determine overlap (1 for secondary operators, 0 for primary operator)
            $overlap = $key > 0 ? 1 : 0;

            // Build the SQL
            $sql = $fileLoad
                ? [
                    $logID,
                    $startTime,
                    $semesterID,
                    $programID,
                    $operator,
                    $arrive,
                    $arrive,
                    $overlap
                ]
                : sprintf(
                    "INSERT INTO `DailyOperator` SET logID=%d, startTime=%d, semesterID='%s', programID=%d, "
                        . "operatorID='%s', arrive=%d, depart=%d, overlap=%d;",
                    $logID,
                    $startTime,
                    $semesterID,
                    $programID,
                    $operator,
                    $arrive,
                    $arrive,
                    $overlap
                );

            // Add to inserts list, avoiding duplicates
            if (!in_array($sql, $operatorInserts, true)) {
                $operatorInserts[] = $sql;
            } else {
                $this->debug->debug("Duplicate operator entry skipped: " . ($fileLoad ? json_encode($sql) : $sql));
            }
        }
    }

    private function buildProgramSQL(
        array $row,
        array &$programInserts,
        array &$engProgramInserts,
        bool $fileLoad = true
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "buildProgramSQL");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        //$this->debug->debugVariable($programInserts, "{$debugHeading} -- &programInserts");
        //$this->debug->debugVariable($engProgramInserts, "{$debugHeading} -- &engProgramInserts");
        $this->debug->debugVariable($fileLoad, "{$debugHeading} -- fileLoad");

        // Prepare the fields
        $programID      = $row['programID'];
        $semesterID     = $row['semesterID'];
        $projectPI      = $row['projectPI'];
        $projectMembers = $row['projectMembers'];
        $otherInfo      = $row['otherInfo'];
        $PIName         = $row['PIName'];
        $PIEmail        = $row['PIEmail'];

        // Skip if programID is already in the inserts list
        if (array_key_exists($programID, $programInserts)) {
            $this->debug->debug("Program ID {$programID} already in programInserts -- skipping.");
            return;
        }

        // Handle NULL or quoted strings for nullable fields
        $formattedProjectMembers = $this->formatNullableValue($projectMembers, $fileLoad);
        $formattedOtherInfo = $this->formatNullableValue($otherInfo, $fileLoad);
        $formattedPIName = $this->formatNullableValue($PIName, $fileLoad);
        $formattedPIEmail = $this->formatNullableValue($PIEmail, $fileLoad);

        // Build the SQL
        $programSQL = $fileLoad
            ? [
                $programID,
                $semesterID,
                $projectPI,
                $formattedProjectMembers,
                $formattedOtherInfo,
                $formattedPIName,
                $formattedPIEmail
            ]
            : sprintf(
                "INSERT INTO `Program` SET programID=%d, semesterID='%s', projectPI='%s', projectMembers=%s, "
                    . "otherInfo=%s, PIName=%s, PIEmail=%s;",
                $programID,
                $semesterID,
                $projectPI,
                $formattedProjectMembers,
                $formattedOtherInfo,
                $formattedPIName,
                $formattedPIEmail
            );
        $programInserts[$programID] = $programSQL;

        // Handle Engineering Program if programID is in the specific range
        if ($programID >= 900 && $programID < 1000) {
            $engProgramSQL = $fileLoad
                ? [
                    $programID,
                    $semesterID,
                    $projectPI,
                    $projectPI
                ]
                : sprintf(
                    "INSERT INTO `EngProgram` SET programID=%d, semesterID='%s', projectPI='%s' "
                        . "ON DUPLICATE KEY UPDATE projectPI='%s';",
                    $programID,
                    $semesterID,
                    $projectPI,
                    $projectPI
                );
            $engProgramInserts[$programID] = $engProgramSQL;
        }
    }

    private function formatNullableValue(?string $value, bool $fileLoad = true): string
    {
        if (empty($value)) {
            return $fileLoad
                ? ''
                : 'NULL';
        }
        return $fileLoad
            ? $value
            : sprintf("'%s'", addslashes($value));
    }

    private function prepareDeletes(
        string $loadType,
        string $semester,
        int $today
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "prepareDeletes");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($loadType, "{$debugHeading} -- loadType");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        $this->debug->debugVariable($today, "{$debugHeading} -- today");
        // Prepare the DELETE statements
        if (strtoupper($loadType) === 'FULL') {
            return [
                'schedule'   => "DELETE FROM `ScheduleObs` WHERE semesterID = '{$semester}';",
                'instrument' => "DELETE FROM `DailyInstrument` WHERE semesterID = '{$semester}';",
                'operator'   => "DELETE FROM `DailyOperator` WHERE semesterID = '{$semester}';",
                'program'    => "DELETE FROM `Program` WHERE semesterID = '{$semester}';",
            ];
        } else {
            return [
                'schedule'   => "DELETE FROM `ScheduleObs` WHERE semesterID = '{$semester}' AND logID >= {$today};",
                'instrument' => "DELETE FROM `DailyInstrument` WHERE semesterID = '{$semester}' AND logID >= {$today};",
                'operator'   => "DELETE FROM `DailyOperator` WHERE semesterID = '{$semester}' AND logID >= {$today};",
                'program'    => "DELETE FROM `Program` WHERE semesterID = '{$semester}';",
            ];
        }
    }

    private function prepareHeaders(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "prepareHeaders");
        $this->debug->debug($debugHeading);
        return [
            'program'    => [
                'programID',
                'semesterID',
                'projectPI',
                'projectMembers',
                'otherInfo',
                'PIName',
                'PIEmail'
            ],
            'schedule'   => [
                'logID',
                'startTime',
                'semesterID',
                'endTime',
                'remoteObs',
                'daytimeObs',
                'firstTime',
                'facilityOpen',
                'facilityClose',
                'instrumentChange',
                'facilityShutdown',
                'supportAstronomerID',
                'programID',
                'comments'
            ],
            'instrument' => [
                'logID',
                'startTime',
                'semesterID',
                'programID',
                'hardwareID',
                'rank'
            ],
            'operator'   => [
                'logID',
                'startTime',
                'semesterID',
                'programID',
                'operatorID',
                'arrive',
                'depart',
                'overlap'
            ],
            'engprogram' => [
                'programID',
                'semesterID',
                'projectPI'
            ],
        ];
    }

    private function prepareForProcessing(
        string $program,
        array $headers,
        string $loadType,
        string $access,
        bool $fileLoad
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "prepareForProcessing");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "{$debugHeading} -- program");
        $this->debug->debugVariable($headers, "{$debugHeading} -- headers");
        $this->debug->debugVariable($loadType, "{$debugHeading} -- loadType");
        $this->debug->debugVariable($access, "{$debugHeading} -- access");
        return [
            // The SQL loading mode: fileload ? infile : explicit SQL
            'fileload'    => $fileLoad,
            //-- The schedule load type
            'type'        => strtolower($loadType),
            //-- The schedule status (private or public)
            'access'      => strtolower($access),
            //-- The logID timestamp (unix timestamp of midnight today)
            //-- Capture the time now, will be used for checking thru-out script.
            //-- $today is the logID value of tonight's observing, since the observing
            //-- that occured in the AM of the current day has the logID of yesterday's
            //-- observing. logID <> start_time
            //$today = [clock scan [clock format time(), -format "%m/%d/%Y"]];
            //$today = date_format( date_create( date( "m/d/Y", time() ) ), "U" );
            'logID'       => strtotime('today'),
            //-- The semester tag from the first data line's program field
            'semester'    => ScheduleUtility::getSemester($program, 'program'),
            //-- The list of schedule comments
            'comments'    => ScheduleUtility::getCommentsList(),
            //-- The header fields-to-index mapping
            'headmap'     => ScheduleUtility::generateHeaderMap($headers),
            //-- The list of active instruments from the database
            'instruments' => $this->fetchInstrumentList(),
            //-- The list of active operators from the database
            'operators'   => $this->fetchOperatorList(),
            //-- The list of programs from the database
            'programs'    => $this->fetchProgramList($program),
        ];
    }

    private function parseRows(
        array $rows,
        array $prep
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "parseRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rows, "{$debugHeading} -- rows");
        $this->debug->debugVariable($prep, "{$debugHeading} -- prep");
        // Parse the rows
        $parsedRows = [];
        foreach ($rows as $key => $row) {
            // Parse the row
            $parsedRow = $this->parseRow(
                $row,
                $prep['headmap'],
                $prep['programs'],
                $prep['semester'],
                $prep['logID'],
                $prep['comments']
            );
            $this->debug->debugVariable($parsedRow, "{$debugHeading} -- parsedRow[{$key}]");
            $parsedRows[] = $parsedRow;
        }
        return $parsedRows;
    }

    private function parseRow(
        array $row,
        array $headerMap,
        array $programList,
        string $semester,
        int $today,
        array $commentList
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "parseRow");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        $this->debug->debugVariable($headerMap, "{$debugHeading} -- headerMap");
        //$this->debug->debugVariable($programList, "{$debugHeading} -- programList");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        $this->debug->debugVariable($today, "{$debugHeading} -- today");
        $this->debug->debugVariable($commentList, "{$debugHeading} -- commentList");
        // Parse the row fields
        $programID = $this->extractProgramID($row[$headerMap['Program']['csv']]);
        $projectPI = ScheduleUtility::escape($row[$headerMap['PI']['csv']]);
        $instrumentID = explode('/', $row[$headerMap['Instrument']['csv']]);
        $startTime = $this->calculateUnixTime(
            $row[$headerMap['Start Date']['csv']],
            $row[$headerMap['Start Time']['csv']]
        );
        $endTime = $this->calculateUnixTime(
            $row[$headerMap['Finish Date']['csv']],
            $row[$headerMap['Finish Time']['csv']]
        );
        $daytimeObs = $this->parseBoolean($row[$headerMap['DayTime']['csv']]);
        $remoteObs = $this->parseBoolean($row[$headerMap['Remote']['csv']]);
        $facilityOpen = $this->parseBoolean($row[$headerMap['Facility Open']['csv']]);
        $facilityClose = $this->parseBoolean($row[$headerMap['Facility Close']['csv']]);
        $insChange = $this->parseBoolean($row[$headerMap['Instrument Change']['csv']]);
        $facilityShutD = $this->parseBoolean($row[$headerMap['Shutdown']['csv']]);
        $operatorID = explode(',', $row[$headerMap['TO']['csv']]);
        $supportID = $row[$headerMap['SA']['csv']];
        $firstTime = $this->parseBoolean($row[$headerMap['FirstNight']['csv']]);
        $comments = $row[$headerMap['Comments']['csv']] ?? '';
        $logID = $this->calculateLogID($startTime);
        if ($facilityOpen === 1 || $facilityClose === 1 || $insChange === 1 || $facilityShutD === 1) {
            $projectPI = $commentList[6];
        }
        $programData = $this->parseProgram($programID, $programList);
        $projectMembers = $programData['projectMembers'];
        $otherInfo = $programData['otherInfo'];
        $PIName = $programData['PIName'];
        $PIEmail = $programData['PIEmail'];

        return [
            $headerMap['logID']['db']             => $logID,
            $headerMap['Start Date']['db']        => $startTime,
            $headerMap['Finish Date']['db']       => $endTime,
            $headerMap['Program']['db']           => $programID,
            $headerMap['semesterID']['db']        => $semester,
            $headerMap['Remote']['db']            => $remoteObs,
            $headerMap['DayTime']['db']           => $daytimeObs,
            $headerMap['FirstNight']['db']        => $firstTime,
            $headerMap['Comments']['db']          => $comments,
            $headerMap['Instrument']['db']        => $instrumentID,
            $headerMap['TO']['db']                => $operatorID,
            $headerMap['Facility Open']['db']     => $facilityOpen,
            $headerMap['Facility Close']['db']    => $facilityClose,
            $headerMap['Instrument Change']['db'] => $insChange,
            $headerMap['Shutdown']['db']          => $facilityShutD,
            $headerMap['SA']['db']                => $supportID,
            $headerMap['PI']['db']                => $projectPI,
            $headerMap['PIEmail']['db']           => $PIEmail,
            $headerMap['PIName']['db']            => $PIName,
            $headerMap['otherInfo']['db']         => $otherInfo,
            $headerMap['projectMembers']['db']    => $projectMembers,
        ];
    }

    private function parseProgram(string $programID, array $programList): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "parseProgram");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($programID, "{$debugHeading} -- programID");
        //$this->debug->debugVariable($programList, "{$debugHeading} -- programList");

        // Use the null coalescing operator (??) to check if the programID exists in the list.
        // If it exists, return the corresponding data; otherwise, return default values.
        return $programList[$programID] ?? [
            'projectMembers' => '',
            'otherInfo' => '',
            'PIName' => '',
            'PIEmail' => '',
        ];
    }

    private function parseBoolean(string $value): int
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "parseBoolean");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($value, "{$debugHeading} -- value");
        // Convert the 'X' to numeric value
        return strtoupper($value) === 'X' ? 1 : 0;
    }

    private function extractProgramID(string $program): int
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "extractProgramID");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "{$debugHeading} -- program");
        // Calculate the value for programID
        $id = ltrim(substr($program, 5, 3), '0');
        return $id === '' || $id === '0' ? 0 : (int) $id;
    }

    private function calculateLogID(int $startTime): int
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "calculateLogID");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($startTime, "{$debugHeading} -- startTime");
        // Calculate the value for logID
        $cutoffTime = mktime(6, 0, 0, date('n', $startTime), date('j', $startTime), date('Y', $startTime));
        // if the start is < the cutoff time, the logID is the previous day's date
        // if the start is > the cutoff time, the logID is the current day's date
        return $startTime <= $cutoffTime
            ? strtotime('yesterday', $cutoffTime)
            : strtotime('today', $cutoffTime);
    }

    private function calculateUnixTime(string $date, string $time): int
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "calculateUnixTime");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($date, "{$debugHeading} -- date");
        $this->debug->debugVariable($time, "{$debugHeading} -- time");
        // Calculate Unix timestamp
        $dateParts = explode('/', $date); // Assumes "YYYY/MM/DD"
        $timeParts = explode(':', str_replace('hr', '', $time)); // Assumes "HH:mmhr"
        return mktime(
            (int) $timeParts[0], // HH   | date('H', $time)
            (int) $timeParts[1], // mm   | date('i', $time)
            0,                   // ss   | date('s', $time)
            (int) $dateParts[1], // MM   | date('n', $date)
            (int) $dateParts[2], // dd   | date('j', $date)
            (int) $dateParts[0]  // YYYY | date('Y', $date)
        );
    }

    private function fetchInstrumentList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "fetchInstrumentList");
        $this->debug->debug($debugHeading);
        // Fetch instrument information from database
        return $this->model->fetchInstrumentList();
    }

    private function fetchOperatorList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "fetchOperatorList");
        $this->debug->debug($debugHeading);
        // Fetch operator information from database
        return $this->model->fetchOperatorList();
    }

    private function fetchProgramList(string $program): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Processor", "fetchProgramList");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "{$debugHeading} -- program");
        // Fetch program information from database
        $year = (int) substr($program, 0, 4);
        $semester = substr($program, 4, 1);
        return $this->model->fetchProgramList($year, $semester);
    }

    private function filterRows(
        array $rows,
        int $today,
        string $loadType
    ): array {
        if (strtoupper($loadType) === 'FULL') {
            return $rows; // No filtering
        }

        return array_filter($rows, function ($row) use ($today) {
            return $row['logID'] >= $today;
        });
    }
}
