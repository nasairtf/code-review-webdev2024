<?php

declare(strict_types=1);

namespace Tests\classes\services\files;

use Exception;
use PHPUnit\Framework\TestCase;
use App\services\files\FileWriter;
use Tests\utilities\PrivatePropertyTrait;

/**
 * Unit tests for the FileWriter class.
 *
 * @covers \App\services\files\FileWriter
 */
class FileWriterTest extends TestCase
{
    use PrivatePropertyTrait;

    /**
     * @var string Temporary directory for test files.
     */
    private $tempDir;

    /**
     * @var string Path to a temporary test file.
     */
    private $testFilePath;

    /**
     * @var FileWriter Instance of FileWriter for testing.
     */
    private $fileWriter;

    /**
     * Sets up the test environment before each test.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->tempDir = sys_get_temp_dir() . '/FileWriterTest';
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir);
        }
        $this->testFilePath = $this->tempDir . DIRECTORY_SEPARATOR . 'test_file.csv';
        $this->fileWriter = new FileWriter('csv', $this->testFilePath, false);
    }

    /**
     * Cleans up the test environment after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
        if (is_dir($this->tempDir)) {
            array_map('unlink', glob($this->tempDir . '/*'));
            rmdir($this->tempDir);
        }
    }

    /**
     * Tests the constructor and default configuration of the FileWriter class.
     *
     * @return void
     */
    public function testConstructorDefaultConfiguration(): void
    {
        $fileWriter = new FileWriter('csv');
        $this->assertSame("\t", $this->getPrivateProperty($fileWriter, 'delimiter'));
        $this->assertSame('"', $this->getPrivateProperty($fileWriter, 'encloser'));
    }

    /**
     * Tests the configureWriter method for different file types.
     *
     * @return void
     */
    public function testConfigureWriter(): void
    {
        $csvWriter = new FileWriter('csv');
        $this->assertSame("\t", $this->getPrivateProperty($csvWriter, 'delimiter'));
        $this->assertSame('"', $this->getPrivateProperty($csvWriter, 'encloser'));

        $logWriter = new FileWriter('log');
        $this->assertSame(',', $this->getPrivateProperty($logWriter, 'delimiter'));
        $this->assertSame('"', $this->getPrivateProperty($logWriter, 'encloser'));

        $sqlWriter = new FileWriter('infilesql');
        $this->assertSame(';', $this->getPrivateProperty($sqlWriter, 'delimiter'));
        $this->assertSame('"', $this->getPrivateProperty($sqlWriter, 'encloser'));
    }

    /**
     * Tests successful file writing with infile SQL data.
     *
     * @return void
     */
    public function testWriteFileWritesSqlSuccessfully(): void
    {
        $fileWriter = new FileWriter('infilesql', $this->testFilePath);
        $data = [
            ['Field1', 'Field2', 'Field3'],
            ['RecordAField1', 'RecordAField2', 'RecordAField3'],
            ['RecordBField1', 'RecordBField2', 'RecordBField3'],
        ];

        $result = $fileWriter->writeFile($data);

        $this->assertTrue($result);
        $this->assertFileExists($this->testFilePath);

        $fileContents = file_get_contents($this->testFilePath);
        $expectedContents = "Field1;Field2;Field3\n"
            . "RecordAField1;RecordAField2;RecordAField3\n"
            . "RecordBField1;RecordBField2;RecordBField3\n";
        $this->assertSame($expectedContents, $fileContents);
    }

    /**
     * Tests successful file writing with CSV data.
     *
     * @return void
     */
    public function testWriteFileWritesCsvSuccessfully(): void
    {
        $data = [
            ['Header1', 'Header2', 'Header3'],
            ['Row1Col1', 'Row1Col2', 'Row1Col3'],
            ['Row2Col1', 'Row2Col2', 'Row2Col3'],
        ];

        $result = $this->fileWriter->writeFile($data);

        $this->assertTrue($result);
        $this->assertFileExists($this->testFilePath);

        $fileContents = file_get_contents($this->testFilePath);
        $expectedContents = "Header1\tHeader2\tHeader3\n"
            . "Row1Col1\tRow1Col2\tRow1Col3\n"
            . "Row2Col1\tRow2Col2\tRow2Col3\n";
        $this->assertSame($expectedContents, $fileContents);
    }

    /**
     * Tests writing a log file by delegating to the CSV writer.
     *
     * @return void
     */
    public function testWriteLogFileDelegatesToCsv(): void
    {
        $fileWriter = new FileWriter('log', $this->testFilePath, false);
        $data = [
            ['Log1', 'Info1', 'Data1'],
            ['Log2', 'Info2', 'Data2'],
        ];

        $result = $fileWriter->writeFile($data);

        $this->assertTrue($result);
        $this->assertFileExists($this->testFilePath);

        $fileContents = file_get_contents($this->testFilePath);
        $expectedContents = "Log1,Info1,Data1\n"
            . "Log2,Info2,Data2\n";
        $this->assertSame($expectedContents, $fileContents);
    }

    /**
     * Tests the writeFile method when an invalid file path is provided.
     *
     * @return void
     */
    public function testWriteFileThrowsExceptionForInvalidPath(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("File path must be provided.");

        $fileWriter = new FileWriter('csv', null, false);
        $fileWriter->writeFile([['Invalid']]);
    }

    /**
     * Tests the writeFile method when an invalid file handle is used.
     *
     * @return void
     */
    public function testWriteFileThrowsExceptionForInvalidHandle(): void
    {
        $invalidPath = '/invalid/path/test.csv';
        $this->expectException(\Exception::class);
        //$this->expectExceptionMessage("Unable to open file: " . $invalidPath);
        $this->expectExceptionMessage("The directory does not exist: " . dirname($invalidPath) . ".");

        $fileWriter = new FileWriter('csv', $invalidPath, false);
        $fileWriter->writeFile([['Invalid']]);
    }

    /**
     * Tests the writeLines method with invalid data.
     *
     * @return void
     */
    public function testWriteLinesFailsWithInvalidData(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Expected string for line data, got: integer");

        $data = ['Valid line', 123]; // Invalid data type
        $handle = fopen($this->testFilePath, 'w');

        $reflection = new \ReflectionClass(FileWriter::class);
        $method = $reflection->getMethod('writeLines');
        $method->setAccessible(true);
        $method->invoke($this->fileWriter, $handle, $data);

        fclose($handle);
    }

    /**
     * Tests the writeCSV method with invalid data.
     *
     * @return void
     */
    public function testWriteCsvFailsWithInvalidData(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Expected an array of CSV data, got: string");

        $data = [['Valid row'], 'Invalid row']; // Invalid data type
        $handle = fopen($this->testFilePath, 'w');

        $reflection = new \ReflectionClass(FileWriter::class);
        $method = $reflection->getMethod('writeCSV');
        $method->setAccessible(true);
        $method->invoke($this->fileWriter, $handle, $data);

        fclose($handle);
    }
}
