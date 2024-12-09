<?php

declare(strict_types=1);

namespace App\services\files;

use Exception;
use App\core\common\Debug;

/**
 * Class FileParser
 *
 * Provides functionality for parsing files of various types (e.g., CSV, log, schedule).
 * This service supports different file formats and configurations, ensuring structured
 * data extraction while offering robust error handling and debugging capabilities.
 *
 * @category Services
 * @package  App\services\files
 * @version  1.0.0
 */

class FileParser
{
    /**
     * Debugging utility instance.
     *
     * @var Debug
     */
    protected $debug;

    /**
     * Type of the file being parsed (e.g., csv, log, schedule).
     *
     * @var string
     */
    protected $fileType;

    /**
     * Delimiter used for parsing the file.
     *
     * @var string
     */
    protected $delimiter;

    /**
     * Maximum length of each line to be parsed.
     *
     * @var int
     */
    protected $lineLength;

    /**
     * File path of the file to be parsed.
     *
     * @var string|null
     */
    protected $filePath;

    /**
     * Constructs a new FileParser instance.
     *
     * @param string      $fileType  The type of file to parse (e.g., csv, log, schedule).
     * @param string|null $filePath  [optional] The path to the file. Can be set later via methods.
     * @param bool|null   $debugMode [optional] Whether to enable debug mode. Default is false.
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
        $this->configureParser();
    }

    /**
     * Configures the parser based on the file type.
     *
     * Sets appropriate delimiter and line length depending on the file type.
     *
     * @return void
     */
    protected function configureParser(): void
    {
        switch ($this->fileType) {
            case 'schedule':
                $this->delimiter = "\t";
                $this->lineLength = 0;
                break;

            case 'log':
                $this->delimiter = ',';
                $this->lineLength = 1000;
                break;

            case 'csv':
            default:
                $this->delimiter = "\t";
                $this->lineLength = 0;
                $this->fileType = 'csv';
                break;
        }
    }

    /**
     * Parses the file and returns structured data.
     *
     * @param string|null $filePath [optional] Path to the file. If null, uses the pre-set file path.
     *
     * @return array Parsed data including 'header' and 'lines' arrays.
     *
     * @throws Exception If the file path is invalid, the file cannot be read, or parsing fails.
     */
    public function parseFile(?string $filePath = null): array
    {
        // Use the provided file path or fallback to the one set in the constructor
        $this->filePath = $filePath ?? $this->filePath;

        // Ensure the file path is set
        if (!$this->filePath) {
            $this->debug->fail("File path must be provided.");
        }

        // Check if file exists
        if (!file_exists($this->filePath)) {
            $this->debug->fail("File not found: {$this->filePath}.");
        }

        // Check if file is readable
        if (!is_readable($this->filePath)) {
            $this->debug->fail("File {$this->filePath} is not readable.");
        }

        // Check if file can be opened
        $handle = fopen($this->filePath, 'r');
        if (!$handle) {
            $this->debug->fail("Unable to open file: {$this->filePath}.");
        }

        // Use a try-finally block to ensure the file handle is properly closed,
        // even if an exception occurs during parsing.
        try {
            // Enable compatibility with macOS line endings
            ini_set('auto_detect_line_endings', true);

            // Parse the file using the appropriate method
            switch (strtolower($this->fileType)) {
                case 'log':
                    return $this->parseLogFile($handle);
                    break;

                case 'schedule':
                case 'csv':
                    return $this->parseCSVFile($handle);
                    break;
            }
        // 'finally' clause executes even if return is reached in the 'try' clause
        } finally {
            // Disable macOS line ending compatibility
            ini_set('auto_detect_line_endings', false);

            // Close the file
            if (is_resource($handle)) {
                fclose($handle);
            }
        }
    }

    /**
     * Parses a CSV file and extracts data.
     *
     * @param resource $handle The file handle for reading the file.
     *
     * @return array An array with 'header' and 'lines' extracted from the CSV.
     *
     * @throws Exception If parsing fails or the file does not have a valid header.
     */
    protected function parseCSVFile($handle): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Service", "parseCSVFile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->filePath, "{$debugHeading} -- handle");

        // Ensure file handle is valid
        if (!is_resource($handle)) {
            $this->debug->fail("Invalid file handle provided to parseCSVFile().");
        }

        // Prepare to parse the CSV file
        $header = [];
        $lines = [];

        // Read the header, skipping blank lines
        while (($header = fgetcsv($handle, $this->lineLength, $this->delimiter)) !== false) {
            if (!empty($header) && $this->hasNonEmptyValues($header)) {
                break; // Found a valid header line
            }
        }
        if ($header === false) {
            $this->debug->fail("Failed to find a valid header in the CSV file: {$this->filePath}.");
        }

        // Read the lines
        while (($row = fgetcsv($handle, $this->lineLength, $this->delimiter)) !== false) {
            // Check for non-empty rows
            if (empty($row) || !$this->hasNonEmptyValues($row)) {
                continue; // Skip blank lines
            }
            $lines[] = $row;
        }

        // Debug output
        $this->debug->debugVariable($header, "{$debugHeading} -- header");
        $this->debug->debugVariable($lines, "{$debugHeading} -- lines");

        // Return the header and lines arrays
        return ['header' => $header, 'lines' => $lines];
    }

    /**
     * Parses a log file and extracts data.
     *
     * @param resource $handle The file handle for reading the file.
     *
     * @return array An array with 'header' (empty) and 'lines' extracted from the log file.
     */
    protected function parseLogFile($handle): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Service", "parseLogFile");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($this->filePath, "{$debugHeading} -- handle");

        // Ensure file handle is valid
        if (!is_resource($handle)) {
            $this->debug->fail("Invalid file handle provided to parseLogFile().");
        }

        // Placeholder for log file parsing logic.
        // Prepare to parse the log file
        $lines = [];

        // Debug output
        $this->debug->debugVariable($lines, "{$debugHeading} -- lines");

        // Return the header and lines arrays
        return ['header' => [], 'lines' => $lines];
    }

    /**
     * Checks if an array contains any non-empty, non-whitespace values.
     *
     * @param array $array The array to evaluate.
     *
     * @return bool True if at least one non-empty value exists; false otherwise.
     */
    protected function hasNonEmptyValues(array $array): bool
    {
        // Equivalency:
        // !array_filter($array, function ($value) { return trim($value) !== ''; })
        // !array_filter($array, fn($value) => trim($value) !== '')
        foreach ($array as $value) {
            if (trim($value) !== '') {
                return true;
            }
        }
        return false;
    }
}
