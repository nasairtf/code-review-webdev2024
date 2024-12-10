<?php

declare(strict_types=1);

namespace Tests\classes\services\files;

use Exception;
use PHPUnit\Framework\TestCase;
use App\services\files\FileParser;
use Tests\utilities\PrivatePropertyTrait;

/**
 * Unit tests for the FileParser class.
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
     * Sets up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
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
        if (is_dir($this->tempDir)) {
            array_map('unlink', glob($this->tempDir . '/*'));
            rmdir($this->tempDir);
        }
    }

    /**
     * Tests the constructor and default configuration of the FileParser class.
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
     * Tests the parseFile method for a CSV file.
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
     * @param string $content  The content to write to the file.
     * @param string $fileType The file extension.
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
     * Accesses a private or protected property of an object for testing purposes.
     *
     * @param object $object   The object instance.
     * @param string $property The name of the property to access.
     *
     * @return mixed The value of the property.
     */
    private function getPrivateProperty(object $object, string $property)
    {
        $reflection = new \ReflectionClass($object);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);
        return $propertyReflection->getValue($object);
    }
}
