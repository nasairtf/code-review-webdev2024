<?php

declare(strict_types=1);

namespace App\models\ishell;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                         as Debug;
use App\core\irtf\IrtfUtilities;
use App\services\database\ishell\read\TemperaturesService as DbRead;

/**
 * Model for the ishell temperature display.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class TemperaturesModel
{
    private $debug;
    private $dbRead;
    private $config;
    private $tempConfig;

    public function __construct(
        array $config = [],
        ?Debug $debug = null,
        ?DbRead $dbRead = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);

        // Store the ishell configuration
        $this->config = $config;
        $this->debug->debugVariable($this->config, "{$debugHeading} -- this->config");
        $type = $this->config['controller'];

        // Store the temps setup
        $this->tempConfig = $this->config[$type]['temps'];
        $this->debug->debugVariable($this->tempConfig, "{$debugHeading} -- this->tempConfig");

        // READ services
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
    }

    public function fetchTemperatureData(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->config['controller'], "{$debugHeading} -- this->config['controller']");
        $this->debug->debugVariable($this->tempConfig, "{$debugHeading} -- this->tempConfig");

        $data = [];
        foreach ($this->tempConfig as $tempName) {
            //$this->debug->debugVariable($tempName, "{$debugHeading} -- tempName");
            switch ($tempName) {
                case 'spectrograph':
                case 'guider':
                    $data[$tempName] = $this->fetchControllerTemps($this->config['temps'][$tempName]);
                    break;

                case 'monitor':
                    $data[$tempName] = $this->fetchMonitorTemps($this->config['temps'][$tempName]);
                    break;

                default:
                    $this->debug->fail("Unknown temperature config: {$tempName}");
                    break;
            }
            //$this->debug->debugVariable($data[$tempName], "{$debugHeading} -- data[$tempName]");
        }
        $currentdata = $this->getCurrentTemperatureData($data);
        //$this->debug->debugVariable($currentdata, "{$debugHeading} -- currentdata");
        $historicdata = $this->getHistoricTemperatureData($data);
        //$this->debug->debugVariable($historicdata, "{$debugHeading} -- historicdata");

        return ['cur' => $currentdata, 'all' => $historicdata];
    }

    private function fetchControllerTemps(array $config): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchControllerTemps");
        $this->debug->debug($debugHeading);
        //$this->debug->debugVariable($config, "{$debugHeading} -- config");
        // Return the data
        $temps = [];
        foreach ($config['sensors'] as $sensor) {
            //$this->debug->debugVariable($sensor, "{$debugHeading} -- sensor");
            $temps[$sensor['id']] = [];
            $temps[$sensor['id']]['cur'] = $this->fetchCurrentTemperature(
                $sensor['id'],
                $config['system']
            )[0];
            //$this->debug->debugVariable($temps, "{$debugHeading} -- temps");
            $temps[$sensor['id']]['all'] = $this->fetchTemperatureRange(
                $sensor['id'],
                $config['system'],
                $config['timestamp']
            );
            //$this->debug->debugVariable($temps, "{$debugHeading} -- temps");
        }
        return $temps;
    }

    private function fetchMonitorTemps(array $config): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchMonitorTemps");
        $this->debug->debug($debugHeading);
        //$this->debug->debugVariable($config, "{$debugHeading} -- config");
        // Return the data
        $temps = [];
        foreach ($config['sensors'] as $sensor) {
            //$this->debug->debugVariable($sensor, "{$debugHeading} -- sensor");
            $temps[$sensor['id']] = [];
            $temps[$sensor['id']]['cur'] = $this->fetchCurrentTemperature(
                $sensor['id'],
                null
            )[0];
            //$this->debug->debugVariable($temps, "{$debugHeading} -- temps");
            $temps[$sensor['id']]['all'] = $this->fetchTemperatureRange(
                $sensor['id'],
                null,
                $config['timestamp']
            );
            //$this->debug->debugVariable($temps, "{$debugHeading} -- temps");
        }
        return $temps;
    }

    private function fetchCurrentTemperature(string $sensor_id, ?string $system = null): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchCurrentTemperature");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($sensor_id, "{$debugHeading} -- sensor_id");
        $this->debug->debugVariable($system, "{$debugHeading} -- system");
        // Return the data
        return $this->dbRead->fetchTemperatureData($sensor_id, $system, null, true, false);
    }

    private function fetchTemperatureRange(string $sensor_id, ?string $system = null, ?int $timestamp): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchTemperatureRange");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($sensor_id, "{$debugHeading} -- sensor_id");
        $this->debug->debugVariable($system, "{$debugHeading} -- system");
        $this->debug->debugVariable($timestamp, "{$debugHeading} -- timestamp");
        // Return the data
        return $this->dbRead->fetchTemperatureData($sensor_id, $system, $timestamp, false, true);
    }

    private function transformData(
        array $data,
        string $keyField,
        string $valueField
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "transformData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($keyField, "{$debugHeading} -- keyField");
        $this->debug->debugVariable($valueField, "{$debugHeading} -- valueField");
        // Return the data
        $list = [];
        foreach ($data as $item) {
            $list[$item[$keyField]] = $item[$valueField];
        }
        return $list;
    }

    private function getCurrentTemperatureData(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "getCurrentTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        // Load the temps config
        $tempsConfig = $this->config['temps'];
        $result = [];
        // Iterate over each main section (e.g., 'spectrograph', 'guider', 'monitor')
        foreach ($data as $section => $channels) {
            $result[$section] = [];
            // Get the sensors configuration for the current section
            $sensorsConfig = isset($tempsConfig[$section]['sensors'])
                ? $tempsConfig[$section]['sensors']
                : [];
            // Map sensor IDs to labels for quick lookup
            $sensorLabels = [];
            foreach ($sensorsConfig as $sensor) {
                $sensorLabels[$sensor['id']] = $sensor['channel'];
            }
            // Iterate over each channel within the section
            foreach ($channels as $channel => $channelData) {
                // Get the label for this channel
                $label = isset($sensorLabels[$channel])
                    ? $sensorLabels[$channel]
                    : $channel;
                // Only include the 'cur' data for each channel
                $result[$section][$label] = $channelData['cur'];
            }
        }
        //$this->debug->debugVariable($result, "{$debugHeading} -- result");
        return $result;
    }

    private function getHistoricTemperatureData(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "getHistoricTemperatureData");
        $this->debug->debug($debugHeading);
        //$this->debug->debugVariable($data, "{$debugHeading} -- data");
        // Iterate over each main section (e.g., 'spectrograph', 'guider', 'monitor')
        $result = [];
        foreach ($data as $section => $channels) {
            $result[$section] = [];
            // Iterate over each channel within the section
            foreach ($channels as $channel => $channelData) {
                // Only include the 'all' data for each channel
                $result[$section][$channel] = $channelData['all'];
            }
        }
        //$this->debug->debugVariable($result, "{$debugHeading} -- result");
        return $result;
    }
}
