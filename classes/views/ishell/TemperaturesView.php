<?php

declare(strict_types=1);

namespace App\views\ishell;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug as Debug;
use App\core\irtf\IrtfUtilities;

/**
 * Class TemperaturesView
 *
 * Responsible for rendering the iSHELL temperature display, including the HTML structure
 * and layout. This class prepares sanitized data for safe output and renders the temperature
 * and image information for the temperature monitoring pages.
 *
 * @category Views
 * @package  IRTF
 * @version 1.0.0
 */

class TemperaturesView
{
    /**
     * @var Debug Debugging utility instance for logging and debugging operations.
     */
    private $debug;
    private $config;

    /**
     * @var string Path to the template file for rendering.
     */
    private $template;
    private $pageInfo;

    /**
     * Initializes the TemperaturesView with core configurations.
     *
     * @param string $template The path to the temperature display template.
     * @param Debug|null $debug Optional. Debugging utility instance.
     */
    public function __construct(
        array $config,
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);

        // Store the ishell configuration
        $this->config = $config;
        $this->debug->debugVariable($this->config, "{$debugHeading} -- this->config");
        $type = $this->config['controller'];

        // Store the template path
        $this->template = __DIR__ . '/' . ($this->config['template']['name'] ?? 'temperature_template.php');
        $this->debug->debugVariable($this->template, "{$debugHeading} -- this->template");

        // Store the page setup
        $this->pageInfo = $this->config[$type];
        $this->debug->debugVariable($this->pageInfo, "{$debugHeading} -- this->pageInfo");
    }

    /**
     * Renders the temperature display page.
     *
     * Prepares data for display, sanitizes the inputs, and includes the template for rendering.
     *
     * @param array $pageInfo Metadata for the page, including title, URL, and other labels.
     * @param array $temperatures Array of temperature data, where each entry contains 'channel',
     *                            'temperature', and 'timestamp' values.
     * @param array $images Array of image metadata, where each entry may include 'sp_count',
     *                      'ignore_entries', 'path', and 'alt' values.
     *
     * @return void
     */
    public function renderPage(
        array $temperatures,
        array $images
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "renderPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($temperatures, "{$debugHeading} -- temperatures");
        $this->debug->debugVariable($images, "{$debugHeading} -- images");

        // Use provided pageInfo or default to the config values
        $pageInfo = $this->pageInfo;

        // Prepare the template array
        $template = [
            'title' => IrtfUtilities::escape($pageInfo['title']),
            'url'   => IrtfUtilities::escape($pageInfo['url']),
            'head'  => IrtfUtilities::escape($pageInfo['head']),
            'cur'   => IrtfUtilities::escape($pageInfo['cur']),
            'cols'  => (int) $pageInfo['cols'],
        ];

        // Sanitize temperature and image data for safe output
        $template['images'] = $this->prepareImageData($images);
        $template['temps'] = $this->prepareTemperatureData($temperatures, $template['cols']);

        // Include the template file
        include $this->template;
        return;
    }

    private function prepareTemperatureData(array $data, int $columns): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "prepareTemperatureData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($columns, "{$debugHeading} -- columns");
        // Sanitize the temperature variables
        $temps = $this->sanitizeTemperatures($data);
        $temperatures = $this->arrangeDataByColumns($temps[$this->config['controller']], $columns);
        $this->debug->debugVariable($temperatures, "{$debugHeading} -- temperatures");
        // place here any additional method calls needed to prepare the temperatures for display
        return $temperatures;
    }

    private function prepareImageData(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "prepareImageData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        // Sanitize the image variables
        $images = $this->sanitizeImages($data);
        // place here any additional method calls needed to prepare the images for display
        $this->debug->debugVariable($images, "{$debugHeading} -- images");
        return $images;
    }

    /**
     * Sanitizes the temperature data array for safe HTML output.
     *
     * Ensures all values are properly formatted and escaped, converting temperature to a
     * consistent three-decimal format.
     *
     * @param array $temperatures Array of raw temperature data, where each entry contains
     *                            'channel', 'temperature', and 'timestamp' keys.
     *
     * @return array Sanitized temperature data ready for display.
     */
    private function sanitizeTemperatures(array $temperatures): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "sanitizeTemperatures");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($temperatures, "{$debugHeading} -- temperatures");
        // Sanitize the temperature variables
        $templateTemperatures = [];
        foreach ($temperatures as $section => $temp) {
            $tempTemp = [];
            foreach ($temp as $key => $sensor) {
                $tempTemp['channel'] = IrtfUtilities::escape($key);
                if (isset($sensor['ktemp'])) {
                    $tempTemp['temperature'] = sprintf('%.3f', $sensor['ktemp']);
                }
                if (isset($sensor['timestamp'])) {
                    $tempTemp['timestamp'] = IrtfUtilities::escape($sensor['timestamp']);
                }
                $templateTemperatures[$section][] = $tempTemp;
            }
        }
        return $templateTemperatures;
    }

    /**
     * Sanitizes the image data array for safe HTML output.
     *
     * Converts numerical fields to integers and ensures all text fields are properly escaped.
     *
     * @param array $images Array of raw image data, where each entry may contain 'sp_count',
     *                      'ignore_entries', 'path', and 'alt' keys.
     *
     * @return array Sanitized image data ready for display.
     */
    private function sanitizeImages(array $images): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "sanitizeImages");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($images, "{$debugHeading} -- images");
        // Sanitize the image variables
        $templateImages = [];
        foreach ($images as $image) {
            $templateImage = [];
            if (isset($image['sp_count'])) {
                $templateImage['sp_count'] = (int) $image['sp_count'];
            }
            if (isset($image['ignore_entries'])) {
                $templateImage['ignore_entries'] = (int) $image['ignore_entries'];
            }
            if (isset($image['path'])) {
                $templateImage['path'] = IrtfUtilities::escape($image['path']);
            }
            if (isset($image['alt'])) {
                $templateImage['alt'] = IrtfUtilities::escape($image['alt']);
            }
            $templateImages[] = $templateImage;
        }
        return $templateImages;
    }

    private function arrangeDataByColumns(array $data, int $columns = 2): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "arrangeDataByColumns");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        // Determine the number of rows needed based on columns and total items
        $rows = ceil(count($data) / $columns);
        $arranged = [];
        // Loop over the number of rows and slice data accordingly
        $k = 0;
        for ($i = 0; $i < $columns; $i++) {
            for ($j = 0; $j < $rows; $j++) {
                if (isset($data[$k])) {
                    $arranged[$j][$i] = $data[$k];
                    $k++;
                }
            }
        }
        return $arranged;
    }
}
