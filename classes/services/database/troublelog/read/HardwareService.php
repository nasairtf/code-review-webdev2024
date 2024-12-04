<?php

namespace App\services\database\troublelog\read;

use App\services\database\troublelog\TroublelogService as BaseService;

/**
 * HardwareService handles read operations for Hardware entities.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class HardwareService extends BaseService
{
    /**
     * Query methods that fetch instrumentation data
     *
     * fetchFullInstrumentData            - retrieves the full instrument list
     * fetchActiveSecondariesData         - retrieves the active secondary instrument list
     * fetchActiveFacilityInstrumentsData - retrieves the active facility instruments list
     * fetchActiveInstrumentsListData     - retrieves the full active instrument list
     * fetchActiveVisitorInstrumentsData  - retrieves the active visitor instrument list
     */

    public function fetchFullNotObsoleteInstrumentsList(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllNotObsoleteInstrumentsListByNameQuery(true),
            [],
            '',
            'No non-obsolete instruments found.'
        );
    }

    public function fetchFullInstrumentData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllInstrumentsListQuery(true),
            [],
            '',
            'No hardware found.'
        );
    }

    public function fetchSecondariesData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getActiveSecondaryInstrumentsListQuery(true),
            [],
            '',
            'No secondaries found.'
        );
    }

    public function fetchFacilityInstrumentsData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllActiveFacilityInstrumentsListByIndexQuery(true),
            [],
            '',
            'No active facility instruments found.'
        );
    }

    public function fetchInstrumentsListData(bool $byIndex = true): array
    {
        $sql = $byIndex
            ? $this->getAllActiveInstrumentsListByIndexQuery(true)
            : $this->getAllActiveInstrumentsListByNameQuery(true);
        return $this->fetchDataWithQuery(
            $sql,
            [],
            '',
            'No active instruments found.'
        );
    }

    public function fetchVisitorInstrumentsData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllActiveVisitorInstrumentListQuery(true),
            [],
            '',
            'No visitor instruments found.'
        );
    }

    /**
     * Helper methods to return the query strings
     *
     * getAllInstrumentsListQuery                      - return the full instrument list select SQL string
     * getActiveSecondaryInstrumentsListQuery          - return the active secondary instrument list select SQL string
     * getAllActiveFacilityInstrumentsListByIndexQuery - return the active facility instruments list select SQL string
     * getAllActiveInstrumentsListByIndexQuery         - return the full active instrument list select SQL string
     * getAllActiveInstrumentsListByNameQuery          - return the full active instrument list select SQL string
     * getAllActiveVisitorInstrumentListQuery          - return the active visitor instrument list select SQL string
     */

    private function getAllInstrumentsListQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "all" returns the instrument list with all the entries in the Hardware table;
        // Filter: 'notes' = 'obsolete' indicates retired instrument
        return "SELECT * FROM Hardware ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    private function getActiveSecondaryInstrumentsListQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "secon" returns the instrument list with all the active secondary entries in the Hardware table;
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'secon' indicates secondary
        return "SELECT * FROM Hardware WHERE notes = 'active' AND type = 'secon' AND hardwareID <> 'unk' AND hardwareID <> 'ic' ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    private function getAllActiveFacilityInstrumentsListByIndexQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "instru+vis" returns the instrument list with all the active instrument entries in the Hardware table;
        #-- mode = "instru" returns the instrument list with all the active instrument entries in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * FROM Hardware WHERE notes = 'active' AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' ORDER BY pulldownIndex " . $this->getSortString($sortAsc) . ";";
    }

    private function getAllActiveInstrumentsListByIndexQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "active-instru" returns the instrument list with all the active instruments entries in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * FROM Hardware WHERE notes IN ('active','visitor') AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' ORDER BY pulldownIndex " . $this->getSortString($sortAsc) . ";";
    }

    private function getAllActiveInstrumentsListByNameQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "trouble" returns the instrument list with all the active instruments entries in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * FROM Hardware WHERE notes IN ('active','visitor') AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    private function getAllNotObsoleteInstrumentsListByNameQuery(bool $sortAsc = true): string
    {
        // Filter: 'notes' <> 'obsolete' indicates active instrument, including 'ic', 'unk', etc
        return "SELECT * FROM Hardware WHERE notes <> 'obsolete' ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    private function getAllActiveVisitorInstrumentListQuery(bool $sortAsc = true): string
    {
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT hardwareID, itemName FROM Hardware WHERE notes = 'visitor' AND type = 'instr' ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }
}
