<?php

declare(strict_types=1);

namespace App\models\feedback;

use App\core\common\Debug;
use App\core\irtf\IrtfUtilities;
use App\services\database\troublelog\read\EngProgramService as EngProgramServiceRead;
use App\services\database\troublelog\read\HardwareService as HardwareServiceRead;
use App\services\database\troublelog\read\ObsAppService as ObsAppServiceRead;
use App\services\database\troublelog\read\OperatorService as OperatorServiceRead;
use App\services\database\troublelog\read\SupportAstronomerService as SupportAstronomerServiceRead;
use App\services\database\feedback\FeedbackService as BaseServiceWrite;
use App\services\database\feedback\write\FeedbackService as FeedbackServiceWrite;
use App\services\database\feedback\write\InstrumentService as InstrumentServiceWrite;
use App\services\database\feedback\write\OperatorService as OperatorServiceWrite;
use App\services\database\feedback\write\SupportService as SupportServiceWrite;

/**
 * Model for the Feedback form.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class FeedbackModel
{
    private $debug;
    // READ services
    private $programRead;
    private $hardwareRead;
    private $operatorRead;
    private $supportRead;
    private $engProgRead;
    // WRITE services
    private $feedbackWrite;
    private $instrumentWrite;
    private $operatorWrite;
    private $supportWrite;
    private $baseWrite;

    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        // READ services
        $this->hardwareRead = new HardwareServiceRead($this->debug->isDebugMode());
        $this->operatorRead = new OperatorServiceRead($this->debug->isDebugMode());
        $this->supportRead = new SupportAstronomerServiceRead($this->debug->isDebugMode());
        $this->programRead = new ObsAppServiceRead($this->debug->isDebugMode());
        $this->engProgRead = new EngProgramServiceRead($this->debug->isDebugMode());
        // WRITE services
        $this->feedbackWrite = new FeedbackServiceWrite($this->debug->isDebugMode());
        $this->instrumentWrite = new InstrumentServiceWrite($this->debug->isDebugMode());
        $this->operatorWrite = new OperatorServiceWrite($this->debug->isDebugMode());
        $this->supportWrite = new SupportServiceWrite($this->debug->isDebugMode());
        $this->baseWrite = new BaseServiceWrite( // App\services\database\feedback\FeedbackService
            $this->debug->isDebugMode(),         // Debug mode
            $this->feedbackWrite,                // App\services\database\feedback\write\FeedbackService
            $this->instrumentWrite,              // App\services\database\feedback\write\InstrumentService
            $this->operatorWrite,                // App\services\database\feedback\write\OperatorService
            $this->supportWrite                  // App\services\database\feedback\write\SupportService
        );
    }

    public function saveFeedback(array $validData): bool
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "saveFeedback");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "validData");
        // Insert the data to the database
        return $this->baseWrite->insertFeedbackWithDependencies(
            $validData['feedback'],
            $validData['instruments'],
            $validData['operators'],
            $validData['support']
        );
    }

    /**
     * Query method that collect the lists for the form
     *
     * fetchFormLists              - retrieves the lists for the form
     */

    public function fetchFormLists(string $program): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchFormLists");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "program");
        // Return the form lists
        return [
            'program' => $this->fetchProgramData($program),
            'visitor' => $this->fetchVisitorInstrumentList(),
            'facility' => $this->fetchFacilityInstrumentList(),
            'operator' => $this->fetchOperatorList(),
            'support' => $this->fetchSupportAstronomerList(),
        ];
    }

    /**
     * Query helper methods that collect the lists for the form
     *
     * fetchSemesterProgramList    - retrieves the semester program list data
     * fetchVisitorInstrumentList  - retrieves the visitor instrument list data
     * fetchFacilityInstrumentList - retrieves the facility instrument list data
     * fetchOperatorList           - retrieves the operator list data
     * fetchSupportAstronomerList  - retrieves the support astronomer list data
     */

    /**
     * Fetches and transforms data for a specified program.
     *
     * This method parses the program identifier string into its year, semester,
     * and program number components, then uses these values to query for relevant
     * data from the database. It then transforms and returns the data in a structured array.
     *
     * @param string $program The program identifier in the format 'YYYYSNNN'.
     *                        - YYYY is the year.
     *                        - S is the semester code ('A' or 'B').
     *                        - NNN is the zero-padded program number.
     *
     * @return array The transformed program data.
     */
    private function fetchProgramData(string $program): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchProgramData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "program");
        // Return the data
        $year = substr($program, 0, 4);
        $sem = substr($program, 4, 1);
        $num = ltrim(substr($program, -3), '0');
        if ($num >= 900) {
            // Engineering programs
            $data = $this->engProgRead->fetchProposalEngProgramData($year . $sem, $num);
        } else {
            // TAC-approved programs
            $data = $this->programRead->fetchProposalProgramData($year, $sem, $num);
        }
        return $this->transformProgram($data, $program, $num);
    }

    private function fetchVisitorInstrumentList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchVisitorInstrumentList");
        $this->debug->debug($debugHeading);
        // Return the data
        $data = $this->hardwareRead->fetchVisitorInstrumentsData();
        $transformedData = $this->transformData($data, 'hardwareID', 'itemName');
        return array_merge(['none' => 'N/A'], $transformedData);
    }

    private function fetchFacilityInstrumentList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchFacilityInstrumentList");
        $this->debug->debug($debugHeading);
        // Return the data
        $data = $this->hardwareRead->fetchFacilityInstrumentsData();
        return $this->transformData($data, 'hardwareID', 'itemName');
    }

    private function fetchOperatorList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchOperatorList");
        $this->debug->debug($debugHeading);
        // Return the data
        $data = $this->operatorRead->fetchOperatorData();
        return $this->transformData($data, 'operatorID', 'lastName');
    }

    private function fetchSupportAstronomerList(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchSupportAstronomerList");
        $this->debug->debug($debugHeading);
        // Return the data
        $data = $this->supportRead->fetchSupportAstronomerData();
        return $this->transformData($data, 'supportCode', 'lastName');
    }

    private function transformData(
        array $data,
        string $keyField,
        string $valueField
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "transformData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "data");
        $this->debug->debugVariable($keyField, "keyField");
        $this->debug->debugVariable($valueField, "valueField");
        // Return the data
        $list = [];
        foreach ($data as $item) {
            $list[$item[$keyField]] = $item[$valueField];
        }
        return $list;
    }

    private function transformProgram(
        array $data,
        string $program,
        int $num
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "transformProgram");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($program, "program");
        // Return the data
        if ($num >= 900) {
            // Engineering programs
            $list = [
                'a' => $program,
                'p' => $data[0]['programID'],
                'i' => 0,
                'n' => $data[0]['projectPI'],
                's' => $data[0]['semesterID'],
            ];
        } else {
            // TAC-approved programs
            $list = [
                'a' => $program,
                'p' => $data[0]['ProgramNumber'],
                'i' => $data[0]['ObsApp_id'],
                'n' => $data[0]['InvLastName1'],
                's' => $data[0]['semesterYear'] . $data[0]['semesterCode'],
            ];
        }
        return $list;
    }

    public function initializeDefaultFormData(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "initializeDefaultFormData");
        $this->debug->debug($debugHeading);
        // Calcualte necessary fields
        $year = date('Y');
        $month = date('m');
        $day = date('j');
        $date = IrtfUtilities::returnUnixDate($month, $day, $year);
        $semester = IrtfUtilities::returnSemester($month, $day, $year);
        $program = $this->returnProgramFromLogin();
        $num = (int) ltrim(substr($program, -3), '0');
        // Return the data
        return [
            // Basic info
            'respondent' => '',                 // Respondent Name
            'email' => '',                      // E-mail Address

            // Program Information
            'semester' => $semester,            // Semester from current date
            'program' => $program,              // Program Number
            'a' => $program,                    // Program Number
            'p' => $num,                        // Proposal Number
            'i' => 0,                           // Program ObsApp_id
            'n' => '',                          // Program PI Last Name
            's' => '',                          // Program Semester

            // Observing Dates
            'startyear' => $year,               // Start Date - Year
            'startmonth' => $month,             // Start Date - Month
            'startday' => $day,                 // Start Date - Day
            'start_date' => $date,              // Start Date
            'endyear' => $year,                 // End Date - Year
            'endmonth' => $month,               // End Date - Month
            'endday' => $day,                   // End Date - Day
            'end_date' => $date,                // End Date

            // Support Staff
            'support_staff' => [],              // Support Astronomer(s)

            // Telescope Operators
            'operator_staff' => [],             // Telescope Operator(s)

            // Instruments
            'instruments' => [],                // Instruments used during run
            'visitor_instrument' => '',         // Visitor instruments used during run

            // Technical Feedback
            'location' => 0,                    // Observing Location
            'experience' => 0,                  // Experience Rating
            'technical' => '',                  // Technical Commentary

            // Personnel Feedback
            'scientificstaff' => 0,             // Scientific Staff Rating
            'operators' => 0,                   // Operators Rating
            'daycrew' => 0,                     // Daycrew Rating
            'personnel' => '',                  // Personnel Commentary

            // Scientific Results
            'scientific' => '',                 // Scientific Results Commentary

            // Suggestions
            'comments' => '',                   // Comments and Suggestions
        ];
    }

    /**
     * Retrieves the program from the SESSION data.
     */
    public function returnProgramFromLogin(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "returnProgramFromLogin");
        $this->debug->debug($debugHeading);
        return $_SESSION['login_data']['program'] ?? '';
    }

    /**
     * Retrieves the session from the SESSION data.
     */
    private function returnSessionFromLogin(): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "returnSessionFromLogin");
        $this->debug->debug($debugHeading);
        return $_SESSION['login_data']['session'] ?? '';
    }
}
