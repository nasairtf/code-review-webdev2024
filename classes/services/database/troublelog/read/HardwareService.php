<?php

declare(strict_types=1);

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
     */

    /**
     * Fetches the full list of non-obsolete instruments.
     *
     * @return array An array of instrument data.
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

    /**
     * Fetches the full list of instruments from the database.
     *
     * @return array An array of instrument data.
     */
    public function fetchFullInstrumentData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllInstrumentsListQuery(true),
            [],
            '',
            'No hardware found.'
        );
    }

    /**
     * Fetches the list of active secondary instruments.
     *
     * @return array An array of active secondary instrument data.
     */
    public function fetchSecondariesData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getActiveSecondaryInstrumentsListQuery(true),
            [],
            '',
            'No secondaries found.'
        );
    }

    /**
     * Fetches the list of active facility instruments.
     *
     * @return array An array of active facility instrument data.
     */
    public function fetchFacilityInstrumentsData(): array
    {
        return $this->fetchDataWithQuery(
            $this->getAllActiveFacilityInstrumentsListByIndexQuery(true),
            [],
            '',
            'No active facility instruments found.'
        );
    }

    /**
     * Fetches the list of active instruments, sorted by index or name.
     *
     * @param bool $byIndex Whether to sort by index (true) or by name (false).
     *
     * @return array An array of active instrument data.
     */
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

    /**
     * Fetches the list of active visitor instruments.
     *
     * @return array An array of active visitor instrument data.
     */
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
     */

    /**
     * Returns the SQL query string for fetching the full list of instruments.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllInstrumentsListQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "all" returns the instrument list with all the entries in the Hardware table;
        // Filter: 'notes' = 'obsolete' indicates retired instrument
        return "SELECT * "
            . "FROM Hardware "
            . "ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of active secondary instruments.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getActiveSecondaryInstrumentsListQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "secon" returns the instrument list with all the active secondary entries
        #--     in the Hardware table;
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'secon' indicates secondary
        return "SELECT * "
            . "FROM Hardware "
            . "WHERE notes = 'active' AND type = 'secon' AND hardwareID <> 'unk' AND hardwareID <> 'ic' "
            . "ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of active facility instruments, sorted by index.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllActiveFacilityInstrumentsListByIndexQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "instru+vis" returns the instrument list with all the active instrument entries
        #--     in the Hardware table;
        #-- mode = "instru" returns the instrument list with all the active instrument entries
        #--     in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * "
            . "FROM Hardware "
            . "WHERE notes = 'active' AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' "
            . "ORDER BY pulldownIndex " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of all active instruments, sorted by index.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllActiveInstrumentsListByIndexQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "active-instru" returns the instrument list with all the active instruments entries
        #--     in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * "
            . "FROM Hardware "
            . "WHERE notes IN ('active','visitor') AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' "
            . "ORDER BY pulldownIndex " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of all active instruments, sorted by name.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllActiveInstrumentsListByNameQuery(bool $sortAsc = true): string
    {
        #-- from irtfhelper.inc:
        #-- mode = "trouble" returns the instrument list with all the active instruments entries
        #--     in the Hardware table;
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'notes' = 'active' indicates active facility instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT * "
            . "FROM Hardware "
            . "WHERE notes IN ('active','visitor') AND type = 'instr' AND hardwareID <> 'unk' AND hardwareID <> 'ic' "
            . "ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of all non-obsolete instruments, sorted by name.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllNotObsoleteInstrumentsListByNameQuery(bool $sortAsc = true): string
    {
        // Filter: 'notes' <> 'obsolete' indicates active instrument, including 'ic', 'unk', etc
        return "SELECT * "
            . "FROM Hardware "
            . "WHERE notes <> 'obsolete' "
            . "ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }

    /**
     * Returns the SQL query string for fetching the list of active visitor instruments.
     *
     * @param bool $sortAsc Whether to sort the result in ascending order.
     *
     * @return string The SQL query string.
     */
    protected function getAllActiveVisitorInstrumentListQuery(bool $sortAsc = true): string
    {
        // Filter: 'notes' = 'visitor' indicates active visitor instrument
        // Filter: 'type' = 'instr' indicates instrument
        return "SELECT hardwareID, itemName "
            . "FROM Hardware "
            . "WHERE notes = 'visitor' AND type = 'instr' "
            . "ORDER BY itemName " . $this->getSortString($sortAsc) . ";";
    }
}
