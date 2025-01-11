<?php

declare(strict_types=1);

namespace App\domains\schedule\upload;

use Exception;
use App\core\common\Debug;
use App\domains\schedule\common\ScheduleUtility;
use App\services\database\troublelog\read\EngProgramService as EngProgramRead;
use App\services\database\troublelog\read\HardwareService as HardwareRead;
use App\services\database\troublelog\read\ObsAppService as ObsAppRead;
use App\services\database\troublelog\read\OperatorService as OperatorRead;
use App\services\database\troublelog\write\DailyInstrumentService as HardwareWrite;
use App\services\database\troublelog\write\DailyOperatorService as OperatorWrite;
use App\services\database\troublelog\write\EngProgramService as EngProgramWrite;
use App\services\database\troublelog\write\ProgramService as ProgramWrite;
use App\services\database\troublelog\write\ScheduleObsService as ScheduleWrite;

/**
 * /home/webdev2024/classes/domains/schedule/upload/ScheduleUploadModel.php
 *
 * @category Model
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ScheduleUploadModel
{
    private $debug;
    private $dbEngPrgRead;
    private $dbObsAppRead;
    private $dbInstruRead;
    private $dbOperatRead;
    private $dbEngPrgWrite;
    private $dbProgrmWrite;
    private $dbScheduWrite;
    private $dbInstruWrite;
    private $dbOperatWrite;

    /**
     * Constructor for the ScheduleUploadModel class.
     */
    public function __construct(
        ?Debug $debug = null,
        ?EngProgramRead $dbEngPrgRead = null,
        ?ObsAppRead $dbObsAppRead = null,
        ?HardwareRead $dbInstruRead = null,
        ?OperatorRead $dbOperatRead = null,
        ?EngProgramWrite $dbEngPrgWrite = null,
        ?ProgramWrite $dbProgrmWrite = null,
        ?ScheduleWrite $dbScheduWrite = null,
        ?HardwareWrite $dbInstruWrite = null,
        ?OperatorWrite $dbOperatWrite = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('schedule', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this manager
        $this->dbEngPrgRead = $dbEngPrgRead ?? new EngProgramRead($this->debug->isDebugMode());
        $this->dbObsAppRead = $dbObsAppRead ?? new ObsAppRead($this->debug->isDebugMode());
        $this->dbInstruRead = $dbInstruRead ?? new HardwareRead($this->debug->isDebugMode());
        $this->dbOperatRead = $dbOperatRead ?? new OperatorRead($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- DB read service classes successfully initialised.");
        $this->dbEngPrgWrite = $dbEngPrgWrite ?? new EngProgramWrite($this->debug->isDebugMode());
        $this->dbProgrmWrite = $dbProgrmWrite ?? new ProgramWrite($this->debug->isDebugMode());
        $this->dbScheduWrite = $dbScheduWrite ?? new ScheduleWrite($this->debug->isDebugMode());
        $this->dbInstruWrite = $dbInstruWrite ?? new HardwareWrite($this->debug->isDebugMode());
        $this->dbOperatWrite = $dbOperatWrite ?? new OperatorWrite($this->debug->isDebugMode());
        $this->debug->log("{$debugHeading} -- DB write service classes successfully initialised.");
    }

    public function deleteEngProgramRows(string $deleteSQL): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "deleteEngProgramRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        // Execute updates
        $deleteRows = $this->dbEngPrgWrite->deleteEngPrograms($deleteSQL);
        // Validate results
        if (!is_numeric($deleteRows)) {
            $this->debug->log(
                "Error: deleteEngPrograms returned a non-numeric value: " . json_encode($deleteRows)
            );
            $deleteRows = -1; // Default to -1 if invalid
        }
        // Format results
        $deleteResult = ($deleteRows === 1)
            ? "{$deleteRows} record was deleted from EngProgram table."
            : "{$deleteRows} records were deleted from EngProgram table.";
        return $deleteResult;
    }

    public function deleteProgramRows(string $deleteSQL): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "deleteProgramRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        // Execute updates
        $deleteRows = $this->dbProgrmWrite->deletePrograms($deleteSQL);
        // Validate results
        if (!is_numeric($deleteRows)) {
            $this->debug->log(
                "Error: deletePrograms returned a non-numeric value: " . json_encode($deleteRows)
            );
            $deleteRows = -1; // Default to -1 if invalid
        }
        // Format results
        $deleteResult = ($deleteRows === 1)
            ? "{$deleteRows} record was deleted from Program table."
            : "{$deleteRows} records were deleted from Program table.";
        return $deleteResult;
    }

    public function deleteScheduleRows(string $deleteSQL): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "deleteScheduleRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        // Execute updates
        $deleteRows = $this->dbScheduWrite->deleteSchedule($deleteSQL);
        // Validate results
        if (!is_numeric($deleteRows)) {
            $this->debug->log(
                "Error: deleteSchedule returned a non-numeric value: " . json_encode($deleteRows)
            );
            $deleteRows = -1; // Default to -1 if invalid
        }
        // Format results
        $deleteResult = ($deleteRows === 1)
            ? "{$deleteRows} record was deleted from ScheduleObs table."
            : "{$deleteRows} records were deleted from ScheduleObs table.";
        return $deleteResult;
    }

    public function deleteInstrumentRows(string $deleteSQL): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "deleteInstrumentRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        // Execute updates
        $deleteRows = $this->dbInstruWrite->deleteInstruments($deleteSQL);
        // Validate results
        if (!is_numeric($deleteRows)) {
            $this->debug->log(
                "Error: deleteInstruments returned a non-numeric value: " . json_encode($deleteRows)
            );
            $deleteRows = -1; // Default to -1 if invalid
        }
        // Format results
        $deleteResult = ($deleteRows === 1)
            ? "{$deleteRows} record was deleted from ScheduleObs table."
            : "{$deleteRows} records were deleted from DailyInstrument table.";
        return $deleteResult;
    }

    public function deleteOperatorRows(string $deleteSQL): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "deleteOperatorRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($deleteSQL, "{$debugHeading} -- deleteSQL");
        // Execute updates
        $deleteRows = $this->dbOperatWrite->deleteOperators($deleteSQL);
        // Validate results
        if (!is_numeric($deleteRows)) {
            $this->debug->log(
                "Error: deleteOperators returned a non-numeric value: " . json_encode($deleteRows)
            );
            $deleteRows = -1; // Default to -1 if invalid
        }
        // Format results
        $deleteResult = ($deleteRows === 1)
            ? "{$deleteRows} record was deleted from DailyOperator table."
            : "{$deleteRows} records were deleted from DailyOperator table.";
        return $deleteResult;
    }

    public function insertEngProgramRows(string $infile): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "insertEngProgramRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");
        // Execute updates
        $insertRows = $this->dbEngPrgWrite->updateEngProgramsInfile($infile);
        // Validate results
        if (!is_numeric($insertRows)) {
            $this->debug->log(
                "Error: updateEngProgramsInfile returned a non-numeric value: " . json_encode($updateRows)
            );
            $insertRows = -1; // Default to -1 if invalid
        }
        // Format results
        $insertResult = ($insertRows === 1)
            ? "{$insertRows} record was inserted into EngProgram table."
            : "{$insertRows} records were inserted into EngProgram table.";
        return $insertResult;
    }

    public function insertProgramRows(string $infile): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "insertProgramRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");
        // Execute updates
        $insertRows = $this->dbProgrmWrite->updateProgramsInfile($infile);
        // Validate results
        if (!is_numeric($insertRows)) {
            $this->debug->log(
                "Error: updateProgramsInfile returned a non-numeric value: " . json_encode($updateRows)
            );
            $insertRows = -1; // Default to -1 if invalid
        }
        // Format results
        $insertResult = ($insertRows === 1)
            ? "{$insertRows} record was inserted into Program table."
            : "{$insertRows} records were inserted into Program table.";
        return $insertResult;
    }

    public function insertScheduleRows(string $infile): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "insertScheduleRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");
        // Execute updates
        $insertRows = $this->dbScheduWrite->updateScheduleInfile($infile);
        // Validate results
        if (!is_numeric($insertRows)) {
            $this->debug->log(
                "Error: updateScheduleInfile returned a non-numeric value: " . json_encode($updateRows)
            );
            $insertRows = -1; // Default to -1 if invalid
        }
        // Format results
        $insertResult = ($insertRows === 1)
            ? "{$insertRows} record was inserted into ScheduleObs table."
            : "{$insertRows} records were inserted into ScheduleObs table.";
        return $insertResult;
    }

    public function insertInstrumentRows(string $infile): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "insertInstrumentRows");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");
        // Execute updates
        $insertRows = $this->dbInstruWrite->updateInstrumentsInfile($infile);
        // Validate results
        if (!is_numeric($insertRows)) {
            $this->debug->log(
                "Error: updateInstrumentsInfile returned a non-numeric value: " . json_encode($updateRows)
            );
            $insertRows = -1; // Default to -1 if invalid
        }
        // Format results
        $insertResult = ($insertRows === 1)
            ? "{$insertRows} record was inserted into DailyInstrument table."
            : "{$insertRows} records were inserted into DailyInstrument table.";
        return $insertResult;
    }

    public function insertOperatorRows(string $infile): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "updateOperators");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($infile, "{$debugHeading} -- infile");
        // Execute updates
        $insertRows = $this->dbOperatWrite->updateOperatorsInfile($infile);
        // Validate results
        if (!is_numeric($insertRows)) {
            $this->debug->log(
                "Error: updateOperatorsInfile returned a non-numeric value: " . json_encode($updateRows)
            );
            $insertRows = -1; // Default to -1 if invalid
        }
        // Format results
        $insertResult = ($insertRows === 1)
            ? "{$insertRows} record was inserted into DailyOperator table."
            : "{$insertRows} records were inserted into DailyOperator table.";
        return $insertResult;
    }

    public function fetchInstrumentList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchInstrumentList");
        $this->debug->debug($debugHeading);
        // Fetch list from database
        $rawList = $this->dbInstruRead->fetchFullNotObsoleteInstrumentsList();
        $instruments = [
            'hardwareID' => [],
            'itemName' => [],
            'type' => [],
            //'description' => [],
            'notes' => [],
            //'notAvailableStart' => [],
            //'notAvailableEnd' => [],
            //'pulldownIndex' => [],
        ];
        foreach ($rawList as $row) {
            $instruments['hardwareID'][] = $row['hardwareID'];
            $instruments['itemName'][] = strtoupper($row['itemName']);
            $instruments['type'][] = $row['type'];
            $instruments['notes'][] = $row['notes'];
        }
        return $instruments;
    }

    public function fetchOperatorList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchOperatorList");
        $this->debug->debug($debugHeading);
        // Fetch list from database
        $rawList = $this->dbOperatRead->fetchOperatorData();
        // Format and structure the data
        $operators = [
            'operatorID' => [],
            'lastName' => [],
            'firstName' => [],
            'operatorCode' => [],
            //'nightAttend' => []
        ];
        foreach ($rawList as $row) {
            $operators['operatorID'][] = $row['operatorID'];
            $operators['lastName'][] = $row['lastName'];
            $operators['firstName'][] = $row['firstName'];
            $operators['operatorCode'][] = $row['operatorCode'];
            //$operators['nightAttend'][] = $row['nightAttend'];
        }
        return $operators;
    }

    public function fetchEngProgramList(int $year, string $semester): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchEngProgramList");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        // Fetch list from database
        $rawList = $this->dbEngPrgRead->fetchScheduleSemesterEngProgramList($year . $semester);
        // Format and structure the data
        $engprograms = [];
        foreach ($rawList as $row) {
            $programID = $row['programID'];
            $engprograms[$programID] = [
                'semesterID'       => $row['semesterID'],
                'projectPI'        => ScheduleUtility::escape($row['projectPI']),
                'otherInfo'        => ScheduleUtility::escape($row['otherInfo'] ?? ''),
                'programID'        => $programID,
                'PIEmail'          => $row['PIEmail'],
                'PIName'           => ScheduleUtility::escape($row['PIName']),
                'projectMembers'   => ScheduleUtility::escape($row['projectMembers'] ?? ''),
                'SciCategory'      => $row['SciCategory'],
                'SciCategoryText'  => ScheduleUtility::escape($row['SciCategoryText'] ?? ''),
                'ApplicationTitle' => ScheduleUtility::escape($row['ApplicationTitle'] ?? ''),
                'Abstract'         => ScheduleUtility::escape(trim($row['Abstract'] ?? '')),
            ];
        }
        return $engprograms;
    }

    public function fetchProgramList(int $year, string $semester): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchProgramList");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");
        // Fetch list from database
        $rawList = $this->dbObsAppRead->fetchScheduleSemesterProgramList($year, $semester);
        // Format and structure the data
        $programs = [];
        foreach ($rawList as $row) {
            $programID = $row['programID'];
            $programs[$programID] = [
                'semesterID' => $row['semesterID'],
                'projectPI' => ScheduleUtility::escape($row['projectPI']),
                'otherInfo' => ScheduleUtility::escape($row['otherInfo'] ?? ''),
                'programID' => $programID,
                'PIEmail' => $row['PIEmail'],
                'PIName' => ScheduleUtility::escape($row['PIName']),
                'projectMembers' => $this->formatProgramMembers($row),
            ];
        }
        return $programs;
    }

    private function formatProgramMembers(array $row): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "formatProgramMembers");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($row, "{$debugHeading} -- row");
        // Format the program members
        $members = [];
        for ($i = 1; $i <= 5; $i++) {
            $key = "projectMembers{$i}";
            if (!empty(trim($row[$key] ?? ''))) {
                $members[] = ScheduleUtility::escape(trim($row[$key]));
            }
        }
        if (!empty(trim($row['projectMembers6'] ?? ''))) {
            $members[] = ScheduleUtility::escape(trim($row['projectMembers6']));
        }
        return implode(', ', $members);
    }
}
