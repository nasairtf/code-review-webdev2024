<?php

declare(strict_types=1);

namespace Tests\classes\services\files;

use Exception;
use PHPUnit\Framework\TestCase;
use Tests\utilities\PrivatePropertyTrait;
use App\services\files\FileParser;

/**
 * Unit tests for the FileParser class.
 *
 * This test suite validates the behavior of the FileParser class, ensuring
 * it handles file parsing correctly for various scenarios. It covers
 * constructor behavior, file parsing logic, and exception handling.
 *
 * @covers \App\services\files\FileParser
 */
class FileParserTest extends TestCase
{
    use PrivatePropertyTrait;

    /**
     * @var string Temporary directory for test files.
     */
    private $tempDir;

    /**
     * Tests the constructor and default configuration of the FileParser class.
     *
     * @covers \App\services\files\FileParser::__construct
     *
     * @return void
     */
    public function testConstructorDefaultConfiguration(): void
    {
        $fileParser = new FileParser('csv');
        $this->assertSame("\t", $this->getPrivateProperty($fileParser, 'delimiter'));
        $this->assertSame(0, $this->getPrivateProperty($fileParser, 'lineLength'));
    }

    /**
     * Tests the configureParser method for different file types.
     *
     * @covers \App\services\files\FileParser::configureParser
     *
     * @return void
     */
    public function testConfigureParser(): void
    {
        $csvParser = new FileParser('csv');
        $this->assertSame("\t", $this->getPrivateProperty($csvParser, 'delimiter'));
        $this->assertSame(0, $this->getPrivateProperty($csvParser, 'lineLength'));

        $logParser = new FileParser('log');
        $this->assertSame(',', $this->getPrivateProperty($logParser, 'delimiter'));
        $this->assertSame(1000, $this->getPrivateProperty($logParser, 'lineLength'));

        $scheduleParser = new FileParser('schedule');
        $this->assertSame("\t", $this->getPrivateProperty($scheduleParser, 'delimiter'));
        $this->assertSame(0, $this->getPrivateProperty($scheduleParser, 'lineLength'));
    }

    /**
     * Tests the parseFile, parseCSVFile methods for a CSV file.
     *
     * @covers \App\services\files\FileParser::parseFile
     * @covers \App\services\files\FileParser::parseCSVFile
     *
     * @return void
     */
    public function testParseFileCsv(): void
    {
        $filePath = $this->createTestFile("header1,header2\nvalue1,value2\nvalue3,value4", 'csv');
        $fileParser = new FileParser('csv', $filePath);

        // Parse the test file
        $result = $fileParser->parseFile();

        // Assert that the header was parsed correctly
        $this->assertSame(['header1,header2'], $result['header']);

        // Assert that all rows were included in the 'lines' array
        $this->assertCount(2, $result['lines']);
        $this->assertSame(['value1,value2'], $result['lines'][0]);
        $this->assertSame(['value3,value4'], $result['lines'][1]);
    }

    /**
     * Tests the parseFile method when the file is missing.
     *
     * @covers \App\services\files\FileParser::parseFile
     *
     * @return void
     */
    public function testParseFileMissingFile(): void
    {
        $fileParser = new FileParser('csv', '/nonexistent/path/file.csv');
        $this->expectException(\Exception::class);
        $fileParser->parseFile();
    }

    /**
     * Tests the parseFile method for an empty file.
     *
     * @covers \App\services\files\FileParser::parseFile
     *
     * @return void
     */
    public function testParseFileEmptyFile(): void
    {
        $filePath = $this->createTestFile("", 'csv');
        $fileParser = new FileParser('csv', $filePath);

        $this->expectException(\Exception::class);
        $fileParser->parseFile();
    }

    /**
     * Tests the parseCSVFile method for a file with blank rows.
     *
     * @covers \App\services\files\FileParser::parseFile
     * @covers \App\services\files\FileParser::parseCSVFile
     *
     * @return void
     */
    public function testParseCSVFileWithBlankRows(): void
    {
        $filePath = $this->createTestFile("header1,header2\n\nvalue1,value2\n\nvalue3,value4", 'csv');
        $fileParser = new FileParser('csv', $filePath);

        // Parse the test file
        $result = $fileParser->parseFile();

        // Assert that the header was parsed correctly
        $this->assertSame(['header1,header2'], $result['header']);

        // Assert that all non-blank rows were included in the 'lines' array
        $this->assertCount(2, $result['lines']);
        $this->assertSame(['value1,value2'], $result['lines'][0]);
        $this->assertSame(['value3,value4'], $result['lines'][1]);
    }

    /**
     * Creates a temporary test file with the given content.
     *
     * This helper method writes the specified content to a file with the provided
     * extension in the temporary directory set up for testing.
     *
     * @param string $content  The content to write to the file.
     * @param string $fileType The file extension (e.g., 'csv').
     *
     * @return string The path to the created file.
     */
    private function createTestFile(string $content, string $fileType): string
    {
        $filePath = "{$this->tempDir}/testfile.{$fileType}";
        file_put_contents($filePath, $content);
        return $filePath;
    }

    /**
     * Sets up the test environment.
     *
     * Prepares a temporary directory for test files.
     *
     * @return void
     */
    protected function setUp(): void
    {
        // Ensure the parent setup runs if needed
        parent::setUp();
        // Run the setup for this class
        $this->tempDir = sys_get_temp_dir() . '/FileParserTest';
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir);
        }
    }

    /**
     * Cleans up the test environment by removing temporary files.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Run the teardown logic for this class
        if (is_dir($this->tempDir)) {
            array_map('unlink', glob($this->tempDir . '/*'));
            rmdir($this->tempDir);
        }
        // Ensure PHPUnit's teardown logic runs too
        parent::tearDown();
    }
}
