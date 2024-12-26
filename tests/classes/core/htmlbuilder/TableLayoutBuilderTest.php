<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\TableLayoutBuilder;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\FormElementsBuilder;

/**
 * Unit tests for the TableLayoutBuilder class.
 *
 * @covers \App\core\htmlbuilder\TableLayoutBuilder
 */
class TableLayoutBuilderTest extends TestCase
{
    /**
     * Instance of TableLayoutBuilder for testing.
     *
     * @var TableLayoutBuilder
     */
    private $tableLayoutBuilder;

    /**
     * Tests the buildMessagePageTable method for generating a message page table.
     *
     * Verifies the exact structure of the generated table.
     *
     * @return void
     */
    public function testBuildMessagePageTable(): void
    {
        $html = $this->tableLayoutBuilder->buildMessagePageTable(
            'Test message',
            true,
            ['class' => 'message-table'],
            2
        );

        $expected = '  <table width="100%" border="0" cellspacing="0" cellpadding="6" class="message-table">' . PHP_EOL
            . '<tr bgcolor="#FFFFFF"><td colspan="1" align="center"><hr/></td></tr>' . PHP_EOL
            . '    <tr style="height: 45px;" align="center">' . PHP_EOL
            . '      <td>Test message</td>' . PHP_EOL
            . '    </tr>' . PHP_EOL
            . '<tr bgcolor="#FFFFFF"><td colspan="1" align="center"><hr/></td></tr>' . PHP_EOL
            . '  </table>';

        $this->assertSame($expected, $html);
    }

    /**
     * Tests the buildMessagesPageTable method for generating a table with multiple messages.
     *
     * Spot-checks key components of the output for correctness.
     *
     * @return void
     */
    public function testBuildMessagesPageTable(): void
    {
        $html = $this->tableLayoutBuilder->buildMessagesPageTable(
            '<strong>Important:</strong> Details follow.',
            false,
            ['class' => 'error-table'],
            2
        );

        $this->assertStringContainsString('class="error-table">', $html);
        $this->assertStringContainsString('<strong>Important:</strong> Details follow.', $html);
        $this->assertStringContainsString('<hr/>', $html);
    }

    /**
     * Tests the buildSemesterChooserTable method for generating a semester chooser table.
     *
     * Verifies key elements such as pulldown menus and action buttons.
     *
     * @return void
     */
    public function testBuildSemesterChooserTable(): void
    {
        $html = $this->tableLayoutBuilder->buildSemesterChooserTable(
            'Choose your semester:',
            ['class' => 'chooser-table'],
            2
        );

        $this->assertStringContainsString('class="chooser-table"', $html);
        $this->assertStringContainsString('Choose your semester:', $html);
        $this->assertStringContainsString('Year:', $html);
        $this->assertStringContainsString('Semester:', $html);
        $this->assertStringContainsString('Reset</button>', $html);
        $this->assertStringContainsString('Generate</button>', $html);
    }

    /**
     * Tests the buildSemesterProposalListTable method for generating a proposal list table.
     *
     * Spot-checks that proposals are correctly rendered in the table.
     *
     * @return void
     */
    public function testBuildSemesterProposalListTable(): void
    {
        $proposals = [
            [
                'ObsApp_id' => '12345',
                'code' => 'P001',
                'ProgramNumber' => 1,
                'InvLastName1' => 'Smith',
                'semesterYear' => '2024',
                'semesterCode' => 'FA',
            ],
            [
                'ObsApp_id' => '67890',
                'code' => 'P002',
                'ProgramNumber' => 2,
                'InvLastName1' => 'Johnson',
                'semesterYear' => '2024',
                'semesterCode' => 'SP',
            ],
        ];

        $html = $this->tableLayoutBuilder->buildSemesterProposalListTable(
            '/submit-proposal',
            'Available Proposals:',
            $proposals,
            ['class' => 'proposal-table'],
            2
        );

        $this->assertStringContainsString('class="proposal-table"', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="12345" />', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="67890" />', $html);
        $this->assertStringContainsString('(Smith)</td>', $html);
        $this->assertStringContainsString('(Johnson)</td>', $html);
    }

    /**
     * Tests the buildProposalUpdateConfirmationTable method for generating a confirmation table.
     *
     * Verifies that the proposal details and input field are included.
     *
     * @return void
     */
    public function testBuildProposalUpdateConfirmationTable(): void
    {
        $proposal = [
            'ObsApp_id' => '12345',
            'code' => 'P001',
            'ProgramNumber' => 1,
            'InvLastName1' => 'Smith',
            'semesterYear' => '2024',
            'semesterCode' => 'FA',
        ];

        $inputField = '<input type="text" name="proposal_name" value="Proposal A" />';

        $html = $this->tableLayoutBuilder->buildProposalUpdateConfirmationTable(
            'Confirm your proposal update:',
            $proposal,
            $inputField,
            ['class' => 'confirmation-table'],
            2
        );

        $this->assertStringContainsString('class="confirmation-table"', $html);
        $this->assertStringContainsString('Confirm your proposal update:</td>', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="12345" />', $html);
        $this->assertStringContainsString('(Smith)</td>', $html);
        $this->assertStringContainsString($inputField, $html);
    }

    /**
     * Tests the buildTextareaTable method for generating a textarea table.
     *
     * @return void
     */
    public function testBuildTextareaTable(): void
    {
        $html = $this->tableLayoutBuilder->buildTextareaTable(
            'comments',
            'Your Comments:',
            'Initial text here...',
            '#FFFFFF',
            'Optional Note.',
            2
        );

        $this->assertStringContainsString('<textarea name="comments"', $html);
        $this->assertStringContainsString('Your Comments:', $html);
        $this->assertStringContainsString('Optional Note.', $html);
        $this->assertStringContainsString('Initial text here...', $html);
    }

    /**
     * Tests the buildLabeledElementTable method for generating a table with a label and content.
     *
     * @return void
     */
    public function testBuildLabeledElementTable(): void
    {
        $html = $this->tableLayoutBuilder->buildLabeledElementTable(
            'Label:',
            '<input type="text" value="Test Content" />',
            '#FFFFFF',
            true,
            false,
            true,
            2
        );

        $this->assertStringContainsString('<table', $html);
        $this->assertStringContainsString('Label:', $html);
        $this->assertStringContainsString('<input type="text" value="Test Content"', $html);
        $this->assertStringContainsString('bgcolor="#FFFFFF"', $html);
    }

    /**
     * Tests the buildLabeledRemoteObsTable method for generating a remote/onsite radio button table.
     *
     * @return void
     */
    public function testBuildLabeledRemoteObsTable(): void
    {
        $html = $this->tableLayoutBuilder->buildLabeledRemoteObsTable(
            'location',
            'Observation Location:',
            '0',
            '#FFFFFF',
            true,
            2
        );

        $this->assertStringContainsString('Observation Location:', $html);
        $this->assertStringContainsString('Remote', $html);
        $this->assertStringContainsString('Onsite', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the buildLabeledRatingTable method for generating a rating table.
     *
     * @return void
     */
    public function testBuildLabeledRatingTable(): void
    {
        $html = $this->tableLayoutBuilder->buildLabeledRatingTable(
            'rating',
            'Please rate:',
            '4',
            '#FFFFFF',
            true,
            true,
            2
        );

        $this->assertStringContainsString('Please rate:', $html);
        $this->assertStringContainsString('Excellent', $html);
        $this->assertStringContainsString('N/A', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the buildLabeledCheckboxTable method for generating a table with labeled checkboxes.
     *
     * @return void
     */
    public function testBuildLabeledCheckboxTable(): void
    {
        $options = ['opt1' => 'Option 1', 'opt2' => 'Option 2'];
        $html = $this->tableLayoutBuilder->buildLabeledCheckboxTable(
            'choices',
            $options,
            ['opt1'],
            'Choose your options:',
            '#FFFFFF',
            true,
            2
        );

        $this->assertStringContainsString('Choose your options:', $html);
        $this->assertStringContainsString('Option 1', $html);
        $this->assertStringContainsString('Option 2', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the buildCheckboxTable method for generating a table with checkboxes only.
     *
     * @return void
     */
    public function testBuildCheckboxTable(): void
    {
        $options = ['opt1' => 'Option 1', 'opt2' => 'Option 2'];
        $html = $this->tableLayoutBuilder->buildCheckboxTable(
            'choices',
            $options,
            ['opt2'],
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('Option 1', $html);
        $this->assertStringContainsString('Option 2', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the buildInstrumentCheckboxPulldownTable method for generating a table with checkboxes and pulldowns.
     *
     * @return void
     */
    public function testBuildInstrumentCheckboxPulldownTable(): void
    {
        $names = ['facility' => 'facility_name', 'visitor' => 'visitor_name'];
        $options = [
            'facility' => ['fac1' => 'Facility 1', 'fac2' => 'Facility 2'],
            'visitor' => ['vis1' => 'Visitor 1', 'vis2' => 'Visitor 2'],
        ];
        $selectedOptions = [
            'facility' => ['fac1'],
            'visitor' => 'vis2',
        ];
        $html = $this->tableLayoutBuilder->buildInstrumentCheckboxPulldownTable(
            $names,
            $options,
            $selectedOptions,
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('Facility 1', $html);
        $this->assertStringContainsString('Visitor 2', $html);
        $this->assertStringContainsString('checked', $html);
    }

    /**
     * Tests the buildDatePulldownsTable method for generating a date pulldown table.
     *
     * @return void
     */
    public function testBuildDatePulldownsTable(): void
    {
        $names = ['year' => 'year', 'month' => 'month', 'day' => 'day'];
        $options = ['year' => '2024', 'month' => '04', 'day' => '15'];
        $html = $this->tableLayoutBuilder->buildDatePulldownsTable(
            $names,
            'Select a date:',
            $options,
            2020,
            2025,
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('Select a date:', $html);
        $this->assertStringContainsString('<select name="year"', $html);
        $this->assertStringContainsString('<option value="2024" selected', $html);
    }

    /**
     * Tests the buildProgramsListPulldownTable method for generating a table with program list pulldowns.
     *
     * @return void
     */
    public function testBuildProgramsListPulldownTable(): void
    {
        $programs = ['PR1' => 'Program 1', 'PR2' => 'Program 2'];
        $html = $this->tableLayoutBuilder->buildProgramsListPulldownTable(
            'programs',
            'Select a program:',
            $programs,
            'PR2',
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('Select a program:', $html);
        $this->assertStringContainsString('Program 1', $html);
        $this->assertStringContainsString('Program 2', $html);
        $this->assertStringContainsString('selected', $html);
    }

    /**
     * Tests the buildProgramPulldownPINameTable method for generating a program and PI name entry table.
     *
     * @return void
     */
    public function testBuildProgramPulldownPINameTable(): void
    {
        $names = [
            'semester' => 'semester',
            'programs' => 'programs',
            'pi' => 'pi',
            'pulldowns' => ['pulldown1', 'pulldown2', 'pulldown3']
        ];
        $labels = [
            'semester' => 'Semester:',
            'programs' => 'Programs:',
            'pi' => 'Principal Investigator:',
            'pulldowns' => 'Pulldown Program:',
        ];
        $programs = ['PR1' => 'Program 1', 'PR2' => 'Program 2'];
        $options = [
            'semester' => '2024B',
            'programs' => 'PR2',
            'pi' => 'Dr. Jane Smith',
            'pulldowns' => [0, 0, 0],
        ];
        $html = $this->tableLayoutBuilder->buildProgramPulldownPINameTable(
            $names,
            $labels,
            $programs,
            $options,
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('Semester:', $html);
        $this->assertStringContainsString('Programs:', $html);
        $this->assertStringContainsString('Dr. Jane Smith', $html);
    }

    /**
     * Tests the buildSingleProposalTable method for generating a single proposal entry table.
     *
     * @return void
     */
    public function testBuildSingleProposalTable(): void
    {
        $proposal = 'PR001';
        $program = [
            'a' => '2024B123',
            'i' => '123',
            'n' => 'Smith',
            's' => '2024B',
        ];
        $html = $this->tableLayoutBuilder->buildSingleProposalTable(
            $proposal,
            $program,
            '#FFFFFF',
            2
        );

        $this->assertStringContainsString('<td>PR001 (Smith)</td>', $html);
        $this->assertStringContainsString('<input type="hidden" name="a" value="2024B123" />', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="123" />', $html);
        $this->assertStringContainsString('<input type="hidden" name="n" value="Smith" />', $html);
        $this->assertStringContainsString('<input type="hidden" name="s" value="2024B" />', $html);
    }

    /**
     * Sets up the test environment by initializing the TableLayoutBuilder instance.
     *
     * Enables formatted output for the generated HTML elements.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $htmlBuilder = new HtmlBuilder(true);
        $formElementsBuilder = new FormElementsBuilder(true, $htmlBuilder);

        $this->tableLayoutBuilder = new TableLayoutBuilder(
            true,
            $htmlBuilder,
            $formElementsBuilder
        );
    }
}
