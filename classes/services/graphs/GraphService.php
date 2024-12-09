<?php

declare(strict_types=1);

namespace App\services\graphs;

use Exception;
use App\core\common\Debug;


/**
 * JpGraph does not support Composer, so we manually include the required files here.
 * This results in a PHPCS warning about mixing side effects with class declarations.
 * Resolving this would require additional refactoring to centralize the includes
 * (e.g., via a bootstrap file). For now, this structure is retained for simplicity.
 */
require_once __DIR__ . '/lib/jpgraph/src/jpgraph.php';
require_once __DIR__ . '/lib/jpgraph/src/jpgraph_line.php';

class GraphService
{
    private $debug;
    private $width;
    private $height;
    private $title;
    private $xAxisTitle;
    private $yAxisTitle;
    private $plotColors;
    private $channelNames;

    public function __construct(
        ?bool $debugMode = null,
        ?int $width = null,
        ?int $height = null,
        ?string $xAxisTitle = null,
        ?string $yAxisTitle = null,
        ?array $plotColors = null,
        ?array $channelNames = null
    ) {
        // Debug output
        $this->debug = new Debug('graph', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class
        $debugHeading = $this->debug->debugHeading("Service", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise properties with defaults
        $this->width = $width ?? 1200;
        $this->height = $height ?? 400;
        $this->xAxisTitle = $xAxisTitle ?? 'Timestamp';
        $this->yAxisTitle = $yAxisTitle ?? 'Degrees Kelvin';
        $this->plotColors = $plotColors ?? ["blue", "red", "pink", "yellow", "green", "orange", "brown", "black"];
        $this->channelNames = $channelNames ?? ["Ch 1", "Ch 2", "Ch 3", "Ch 4", "Ch 5", "Ch 6", "Ch 7", "Ch 8"];

        // Class initialisation complete
        $this->debug->log("{$debugHeading} -- GraphService initialisation complete.");
    }

    /**
     * Generates a line plot graph and saves it to a specified file.
     *
     * @param string $filename File path to save the graph image.
     * @param array $data Array of temperature data arrays, one for each sensor.
     * @param array $timestamps Array of timestamp arrays, one for each sensor.
     * @param int $displayCount Number of data points to display.
     * @param string $title Title of the graph.
     * @param bool $shortFormat Whether to display timestamps in short format.
     */
    public function generateLinePlot1(
        string $filename,
        array $data,
        array $timestamps,
        int $displayCount,
        string $title,
        bool $shortFormat = false
    ): void {
        // Calculate the starting index based on display count
        $dataPointCount = count($data[0]);
        $ignoreEntries = max(0, $dataPointCount - $displayCount);

        // Prepare adjusted data and timestamps
        $adjustedData = [];
        $adjustedTimestamps = [];
        for ($i = 0; $i < min($displayCount, $dataPointCount); $i++) {
            $currentIndex = $ignoreEntries + $i;
            foreach ($data as $index => $sensorData) {
                $adjustedData[$index][] = $sensorData[$currentIndex];
            }
            $adjustedTimestamps[] = $shortFormat
                ? strtotime($timestamps[0][$currentIndex])
                : $timestamps[0][$currentIndex];
        }

        // Initialize the graph
        $graph = new \Graph($this->width, $this->height, "auto");
        $graph->img->SetMargin(80, 40, 20, 50);
        $graph->SetScale("lin");
        $graph->SetShadow();
        $graph->title->Set($title);
        $graph->xaxis->title->Set($this->xAxisTitle);
        $graph->yaxis->title->Set($this->yAxisTitle);

        // Add each sensor data series as a line plot
        foreach ($adjustedData as $index => $sensorData) {
            $linePlot = $shortFormat
                ? new \LinePlot($sensorData, $adjustedTimestamps)
                : new \LinePlot($sensorData);
            $linePlot->SetColor($this->plotColors[$index % count($this->plotColors)]);
            $linePlot->SetWeight(2);
            $linePlot->SetLegend($this->channelNames[$index]);
            $graph->Add($linePlot);
        }

        // Customize x-axis based on format
        $graph->xaxis->SetLabelAngle($shortFormat ? 90 : 30);
        $graph->xgrid->Show();
        $graph->xgrid->SetLineStyle("solid");
        $graph->xgrid->SetColor('#E3E3E3');
        if ($shortFormat) {
            $graph->xaxis->scale->ticks->Set(300, 60);
        } else {
            $graph->xaxis->SetTickLabels($adjustedTimestamps);
        }

        // Render and save the graph
        $graph->Stroke($filename);
    }

    /**
     * Generates a line plot graph and saves it to the specified file path.
     *
     * @param string $filename File path to save the graph image.
     * @param array $data Array of temperature data arrays, one for each sensor.
     * @param array $timestamps Array of timestamp arrays, one for each sensor.
     * @param int $displayCount Number of data points to display.
     * @param string $title Title of the graph.
     * @param bool $shortFormat Whether to display timestamps in short format.
     * @return bool True if graph generation succeeded, false otherwise.
     */
    public function generateLinePlot2(
        string $filename,
        array $data,
        array $timestamps,
        int $displayCount,
        string $title,
        bool $shortFormat = false
    ): bool {
        try {
            // Calculate starting index based on the desired display count
            $dataPointCount = count($data[0]);
            $ignoreEntries = max(0, $dataPointCount - $displayCount);

            // Prepare adjusted data and timestamps
            $adjustedData = [];
            $adjustedTimestamps = [];
            for ($i = 0; $i < min($displayCount, $dataPointCount); $i++) {
                $currentIndex = $ignoreEntries + $i;
                foreach ($data as $index => $sensorData) {
                    $adjustedData[$index][] = $sensorData[$currentIndex];
                }
                $adjustedTimestamps[] = $shortFormat
                    ? date('H:i', strtotime($timestamps[0][$currentIndex]))
                    : $timestamps[0][$currentIndex];
            }

            // Initialize the graph
            $this->graph = new \Graph(1200, 400, "auto");
            $this->graph->img->SetMargin(80, 40, 20, 50);
            $this->graph->SetScale("lin");
            $this->graph->SetShadow();
            $this->graph->title->Set($title);
            $this->graph->xaxis->title->Set("Timestamp");
            $this->graph->yaxis->title->Set("Degrees Kelvin");

            // Add each sensor data series as a line plot
            foreach ($adjustedData as $index => $sensorData) {
                $linePlot = $shortFormat
                    ? new \LinePlot($sensorData, $adjustedTimestamps)
                    : new \LinePlot($sensorData);
                $linePlot->SetColor($this->plotColors[$index % count($this->plotColors)]);
                $linePlot->SetWeight(2);
                $linePlot->SetLegend($this->channelNames[$index]);
                $this->graph->Add($linePlot);
            }

            // Customize x-axis based on format
            $this->graph->xaxis->SetLabelAngle($shortFormat ? 90 : 30);
            $this->graph->xgrid->Show();
            $this->graph->xgrid->SetLineStyle("solid");
            $this->graph->xgrid->SetColor('#E3E3E3');
            if ($shortFormat) {
                $this->graph->xaxis->scale->ticks->Set(300, 60);
            } else {
                $this->graph->xaxis->SetTickLabels($adjustedTimestamps);
            }

            // Render and save the graph
            $this->graph->Stroke($filename);
            return true;
        } catch (Exception $e) {
            // Log any errors encountered
            $this->debug->log("Graph generation failed: " . $e->getMessage());
            return false;
        }
    }
}
