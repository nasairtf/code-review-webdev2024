<?php

declare(strict_types=1);

namespace App\services\files;

use Exception;
use App\core\common\Debug;

/**
 * FileWriter Service
 *
 * Provides functionality for writing data to various file formats, including CSV,
 * log, and schedule files. Supports configurable delimiters and enclosures
 * depending on the file type.
 *
 * @category Services
 * @package  IRTF
 * @version  1.0.0
 */

class FileWriter
{
    /**
     * @var Debug Debugging utility for logging and error reporting.
     */
    protected $debug;

    /**
     * @var string The type of file to be written (e.g., 'csv', 'log', 'schedulesql').
     */
    protected $fileType;

    /**
     * @var string The delimiter used to separate fields in the file.
     */
    protected $delimiter;

    /**
     * @var string The encloser used to wrap fields in the file.
     */
    protected $encloser;

    /**
     * @var string|null The path to the file to be written.
     */
    protected $filePath;

    /**
     * Constructs a new FileWriter instance.
     *
     * @param string      $fileType  The type of file to be written (e.g., 'csv', 'log').
     * @param string|null $filePath  Optional file path to use for writing data.
     * @param bool|null   $debugMode Optional flag to enable debug mode.
     */
    public function __construct(
        string $fileType,
        ?string $filePath = null,
        ?bool $debugMode = null
    ) {
        // Debug output
        $this->debug = new Debug('file', $debugMode ?? false, $debugMode ? 1 : 0); // base-level service class
        $debugHeading = $this->debug->debugHeading("Service", "__construct");
        $this->debug->debug($debugHeading);

        // Set the type of file to be parsed
        $this->fileType = $fileType ?? 'csv';

        // Set the file path if provided
        $this->filePath = $filePath;

        // Configure parser based on file type
        $this->configureWriter();
    }

    /**
     * Configures the writer based on the file type.
     *
     * Sets the delimiter and enclosure characters used when writing files.
     *
     * @return void
     */
    protected function configureWriter(): void
    {
        switch ($this->fileType) {
            case 'schedulesql':
                $this->delimiter = ";";
                $this->encloser = '"';
                break;

            case 'log':
                $this->delimiter = ',';
                $this->encloser = '"';
                break;

            case 'csv':
            default:
                $this->delimiter = "\t";
                $this->encloser = '"';
                $this->fileType = 'csv';
                break;
        }
    }

    /**
     * Writes an array of lines to a file.
     *
     * This method writes structured data to the specified file path using the appropriate
     * format for the configured file type. Supports CSV, log, and schedule files.
     *
     * @param array       $data     The data to write (array of arrays or strings).
     * @param string|null $filePath Optional path to write the file. Defaults to the
     *                              constructor-defined path.
     *
     * @return bool True if the file was successfully written, false otherwise.
     *
     * @throws Exception If the file path is invalid or writing fails.
     */
    public function writeFile(array $data, ?string $filePath = null): bool
    {
        // Use the provided file path or fallback to the one set in the constructor
        $this->filePath = $filePath ?? $this->filePath;

        // Ensure the file path is set
        if (!$this->filePath) {
            $this->debug->fail("File path must be provided.");
        }

        // Check if file can be opened in write mode
        $handle = fopen($this->filePath, 'w');
        if (!$handle) {
            $this->debug->fail("Unable to open file: {$this->filePath}.");
        }

        // Use a try-finally block to ensure the file handle is properly closed,
        // even if an exception occurs during writing.
        try {
            // Write the file using the appropriate method
            switch (strtolower($this->fileType)) {
                case 'log':
                    $this->writeLog($handle, $data);
                    break;

                case 'schedulesql':
                    $this->writeCSV($handle, $data);
                    break;

                case 'csv':
                    $this->writeCSV($handle, $data);
                    break;
            }
            return true;
        } catch (Exception $e) {
            $this->debug->fail("Error writing file: " . $e->getMessage());
            return false;
        // 'finally' clause executes even if return is reached in the 'try' clause
        } finally {
            // Close the file
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }

    /**
     * Writes raw lines of data to a file.
     *
     * @param resource $handle File handle to write to.
     * @param array    $data   Array of strings to write as lines.
     *
     * @return void
     *
     * @throws Exception If writing fails.
     */
    protected function writeLines($handle, array $data): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Service", "writeLines");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->filePath, "{$debugHeading} -- handle");
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        // Ensure file handle is valid
        if (!is_resource($handle)) {
            $this->debug->fail("Invalid file handle provided to writeLines.");
        }

        // Write the lines to the file
        foreach ($data as $line) {
            if (!is_string($line)) {
                $this->debug->fail("Expected string for line data, got: " . gettype($line));
            }

            if (fwrite($handle, $line . PHP_EOL) === false) {
                $this->debug->fail("Failed to write line to file: " . json_encode($line));
            }
        }
    }

    /**
     * Writes structured data to a CSV file.
     *
     * @param resource $handle File handle to write to.
     * @param array    $data   Array of arrays to write as rows.
     *
     * @return void
     *
     * @throws Exception If writing fails.
     */
    protected function writeCSV($handle, array $data): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Service", "writeCSV");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->filePath, "{$debugHeading} -- handle");
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        // Ensure file handle is valid
        if (!is_resource($handle)) {
            $this->debug->fail("Invalid file handle provided to writeCSV.");
        }

        // Write the lines to the CSV file
        foreach ($data as $line) {
            if (!is_array($line)) {
                $this->debug->fail("Expected an array of CSV data, got: " . gettype($line));
            }

            if (!fputcsv($handle, $line, $this->delimiter, $this->encloser)) {
                $this->debug->fail("Failed to write CSV line to file: " . json_encode($line));
            }
        }
    }

    /**
     * Writes log data to a file.
     *
     * Currently, this method uses the CSV writer as a placeholder.
     *
     * @param resource $handle File handle to write to.
     * @param array    $data   Array of arrays to write as rows.
     *
     * @return void
     */
    protected function writeLog($handle, array $data): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Service", "writeLog");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->filePath, "{$debugHeading} -- handle");
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        // for now, use writeCSV. Expand this method once log file format is determined.
        $this->writeCSV($handle, $data);
    }
}
