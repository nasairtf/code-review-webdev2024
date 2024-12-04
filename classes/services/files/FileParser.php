<?php

namespace App\services\files;

use Exception;

use App\core\common\Debug;

/**
 * @category Services
 * @package  IRTF
 * @version  1.0.0
 */

class FileParser
{
    protected $debug;
    protected $fileType;
    protected $delimiter;
    protected $lineLength;
    protected $filePath;

    /**
     * Constructs a new FileParserService instance.
     *
     * @param bool   $debugMode Whether to enable debug mode.
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
     * @param string|null $filePath Optional file path to parse.
     *
     * @return array Parsed data with 'header' and 'lines'.
     *
     * @throws Exception If the file path is not provided or valid.
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
     * Parses a CSV file and returns structured data.
     *
     * @param resource $handle Open file handle to parse.
     *
     * @return array Parsed data with 'header' and 'lines'.
     *
     * @throws Exception If an error occurs during parsing.
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
     * PLACEHOLDER
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
     * @param array $array The array to check.
     *
     * @return bool True if the array has at least one non-empty, non-whitespace value; false otherwise.
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
