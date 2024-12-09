<?php

declare(strict_types=1);

namespace App\validators\ishell;

use Exception;
use App\core\common\Debug;
use App\core\irtf\IrtfUtilities;

/**
 * Validator for handling the ishell temperature validation logic.
 *
 * @category Validators
 * @package  IRTF
 * @version  1.0.0
 */

class TemperaturesValidator
{
    /**
     * @var Debug Debug instance for logging and debugging purposes.
     */
    private $debug;
    private $config;

    /**
     * Constructor to initialize the TemperaturesValidator with a Debug instance.
     *
     * @param Debug|null $debug Optional. An instance of Debug for logging; defaults to null.
     */
    public function __construct(
        array $config,
        ?Debug $debug = null
    ) {
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Validator", "__construct");
        $this->debug->debug($debugHeading);

        // Store the ishell configuration
        $this->config = $config;
        $this->debug->debugVariable($this->config, "{$debugHeading} -- this->config");
    }

    public function validateTemperatureData(array $dbData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        // Validate the form data and return the array for database input
        $validData = $this->validateDataFromDatabase($dbData);
        // Transform the validated data and return the array for email output
        $graphData = $this->transformDataForGraphing($validData);
        // Return array
        return $graphData;
    }

    private function validateDataFromDatabase(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "validateDataFromDatabase");
        $this->debug->debug($debugHeading);

        // Build the validated data array for transformation
        $valid = $data;

        return $valid;
    }

    private function transformDataForGraphing(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Validator", "transformDataForGraphing");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        // Initialize result array
        $graph = [
            'times' => [],
            'temps' => []
        ];
        // Transform the data
        $channelIndex = 0;
        foreach ($data as $channel => $readings) {
            // Initialize each channel in 'times' and 'temps' arrays
            $graph['times'][$channelIndex] = [];
            $graph['temps'][$channelIndex] = [];
            // Populate the timestamps and ktemps for each channel
            foreach ($readings as $reading) {
                $graph['times'][$channelIndex][] = $reading['timestamp'];
                $graph['temps'][$channelIndex][] = $reading['ktemp'];
            }
            // Increment the index for the next channel
            $channelIndex++;
        }
        return $graph;
    }
}
