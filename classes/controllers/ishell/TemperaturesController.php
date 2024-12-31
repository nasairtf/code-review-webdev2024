<?php

declare(strict_types=1);

namespace App\controllers\ishell;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\Config;
use App\core\common\CustomDebug                 as Debug;
use App\services\graphs\GraphService            as Graph;
use App\models\ishell\TemperaturesModel         as Model;
use App\views\ishell\TemperaturesView           as View;
use App\validators\ishell\TemperaturesValidator as Validator;

/**
 * Controller for handling the ishell temperature display logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class TemperaturesController
{
    private $debug;
    private $type;
    private $config;
    private $model;
    private $view;
    private $valid;
    private $graph;

    /**
     * Initializes the controller with specified type and optional debugging.
     *
     * @param string     $ishell_type The specific type of configuration to load (e.g., 'monitor')
     * @param Debug|null $debug       Optional. Debugging utility instance.
     */
    public function __construct(
        string $ishell_type = 'monitor',
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', true, 1);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Load the config for ishell temperatures
        $this->config = Config::get('ishelltemps_config');
        $this->debug->debugVariable($this->config, "{$debugHeading} -- this->config");

        // Check if the requested controller exists in the config
        if (!isset($this->config[$ishell_type])) {
            $this->debug->fail("Configuration for '{$ishell_type}' not found.");
        }

        // Check if the requested controller's temps field exists in the config
        if (!isset($this->config[$ishell_type]['temps'])) {
            $this->debug->fail("Configuration for '{$ishell_type}['temps']' not found.");
        } else {
            // Check if the requested temps exists in the config
            foreach ($this->config[$ishell_type]['temps'] as $tempName) {
                if (!isset($this->config['temps'][$tempName])) {
                    $this->debug->fail("Configuration for '{$tempName}' not found.");
                }
            }
        }

        // Store the specified controller
        $this->type = $ishell_type;
        $this->debug->debugVariable($this->type, "{$debugHeading} -- this->type");
        $this->config['controller'] = $this->type;

        // Initialise the model and view with their config info
        $this->model = new Model($this->config, $this->debug);
        $this->view  = new View($this->config, $this->debug);
        $this->valid = new Validator($this->config, $this->debug);
        $this->graph = new Graph($this->debug->isDebugMode());

        $this->debug->log("{$debugHeading} -- Classes are successfully initialised.");
    }

    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);

        // retrieve data set
        $tempData = $this->fetchTemperatureData();
        $this->debug->debug("{$debugHeading} -- Temperature data retrieved from database.");

        // Validate the data set
        $cleanData = $this->validateTemperatureData($tempData['all']);
        $this->debug->debug("{$debugHeading} -- Validation checks completed.");

        // If validation passes, proceed to processing the temperature data
        $graphs = $this->processTemperatureData($cleanData);
        $this->debug->debug("{$debugHeading} -- Data processing completed.");

        $this->renderDisplayPage(['temps' => $tempData['cur'], 'graphs' => $graphs]);
    }

    private function fetchTemperatureData(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "fetchTemperatureData");
        $this->debug->debug($debugHeading);
        try {
            // retrieve data set
            $temperatureData = $this->model->fetchTemperatureData();
            $this->debug->debugVariable($temperatureData, "{$debugHeading} -- temperatureData");
            return $temperatureData;
        } catch (Exception $e) {
            // Handle any errors during the data saving process
            $this->renderErrorPage('Error retrieving temperature data', $e->getMessage());
        }
    }

    private function validateTemperatureData(array $tempData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "validateTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($tempData, "{$debugHeading} -- tempData");
        try {
            // clean up data set
            $cleanedData = $this->valid->validateTemperatureData($tempData[$this->config['controller']]);
            $this->debug->debugVariable($cleanedData, "{$debugHeading} -- cleanData");
            return $cleanedData;
        } catch (Exception $e) {
            // Handle any errors during the data saving process
            $this->renderErrorPage('Error validating temperature data', $e->getMessage());
        }
    }

    private function processTemperatureData(array $graphData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($graphData, "{$debugHeading} -- graphData");
        $controller = $this->config['controller'];
        $subdir = '/ishell/graphs/';
        $intervals = [120, 1440, 10080];
        $graphs = [];
        $images = [];
        foreach ($intervals as $delta) {
            $graphs[$delta] = [
                'ttl' => "{$controller} temperatures, last {$delta} minutes",
                'alt' => "{$controller} {$delta} minute plot",
                'url' => BASE_URL . "{$subdir}{$controller}_{$delta}_min.jpg",
                'pth' => BASE_PATH . "/public_html{$subdir}{$controller}_{$delta}_min.jpg",
            ];
        }
        $this->debug->debugVariable($graphs, "{$debugHeading} -- graphs");
        try {
            // graph data set
            foreach ($intervals as $delta) {
                $results[$delta] = $this->graph->generateLinePlot2(
                    $graphs[$delta]['pth'],
                    $graphData['temps'],
                    $graphData['times'],
                    $delta,
                    $graphs[$delta]['ttl'],
                    true
                );
            }
            foreach ($results as $delta => $graph) {
                if ($graph) {
                    $images[] = [
                        'alt'  => $graphs[$delta]['alt'],
                        'path' => $graphs[$delta]['url'],
                        //'sp_count'  => 0,
                        //'ignore_entries' => 0,
                    ];
                }
            }
            $this->debug->debugVariable($images, "{$debugHeading} -- images");
            return $images;
        } catch (Exception $e) {
            // Handle any errors during the data saving process
            $this->renderErrorPage('Error graphing temperature data', $e->getMessage());
        }
    }

    private function renderDisplayPage(array $tempData): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderDisplayPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($tempData, "{$debugHeading} -- tempData");
        // Render the display page
        $code = $this->view->renderPage(
            $tempData['temps'],
            $tempData['graphs']
        );
        echo $code;
    }
}
