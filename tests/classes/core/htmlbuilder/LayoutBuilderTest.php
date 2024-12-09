<?php

declare(strict_types=1);

namespace Tests\classes\core\htmlbuilder;

use PHPUnit\Framework\TestCase;
use App\core\htmlbuilder\LayoutBuilder;
use App\core\htmlbuilder\HtmlBuilder;
use App\core\htmlbuilder\FormElementsBuilder;
use App\core\htmlbuilder\TableLayoutBuilder;

/**
 * Unit tests for the LayoutBuilder class.
 *
 * @covers \App\core\htmlbuilder\LayoutBuilder
 */
class LayoutBuilderTest extends TestCase
{
    /**
     * Instance of LayoutBuilder for testing.
     *
     * @var LayoutBuilder
     */
    private $layoutBuilder;

    /**
     * Sets up the test environment by initializing the LayoutBuilder instance.
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
        $tableLayoutBuilder = new TableLayoutBuilder(true, $htmlBuilder, $formElementsBuilder);

        $this->layoutBuilder = new LayoutBuilder(
            true,
            $htmlBuilder,
            $formElementsBuilder,
            $tableLayoutBuilder
        );
    }

    /**
     * Tests the LayoutBuilder constructor to ensure it uses default values when
     * no dependencies are injected.
     *
     * Verifies that:
     * - The default dependencies are correctly initialized.
     * - The `buildResultsPage` method works as expected with default settings.
     * - The `formatOutput` flag is respected (e.g., no additional formatting is applied).
     *
     * Assertions:
     * - Checks that the specified HTML attributes (e.g., `class="default-builder"`) are present.
     * - Ensures the output contains the provided message (`No dependency injection.`).
     * - Confirms that structural elements like `<hr/>` are included in the generated HTML.
     * - Validates that no unnecessary formatting (e.g., extra whitespace or line breaks) is added.
     *
     * @return void
     */
    public function testConstructorUsesDefaultValues(): void
    {
        $layoutBuilder = new LayoutBuilder(false);
        $html = $layoutBuilder->buildResultsPage(
            'No dependency injection.',
            ['class' => 'default-builder'],
            2
        );

        $this->assertStringContainsString('class="default-builder"', $html);
        $this->assertStringContainsString('No dependency injection.', $html);
        $this->assertStringContainsString('<hr/>', $html);
        // Check for absence of formatting (no unnecessary whitespace or line breaks)
        $this->assertStringNotContainsString("\n  ", $html); // Example check for formatted indentation
    }

    /**
     * Tests the buildResultsPage method for generating a results page table.
     *
     * Spot-checks for key components such as the message and table structure.
     *
     * @return void
     */
    public function testBuildResultsPage(): void
    {
        $html = $this->layoutBuilder->buildResultsPage(
            'Operation successful.',
            ['class' => 'results-table'],
            2
        );

        $this->assertStringContainsString('class="results-table"', $html);
        $this->assertStringContainsString('Operation successful.', $html);
        $this->assertStringContainsString('<hr/>', $html);
    }

    /**
     * Tests the buildResultsBlockPage method for generating a detailed results block table.
     *
     * Spot-checks for the message and table structure.
     *
     * @return void
     */
    public function testBuildResultsBlockPage(): void
    {
        $html = $this->layoutBuilder->buildResultsBlockPage(
            'Here is a detailed message.',
            ['class' => 'block-table'],
            2
        );

        $this->assertStringContainsString('class="block-table"', $html);
        $this->assertStringContainsString('Here is a detailed message.', $html);
        $this->assertStringContainsString('<hr/>', $html);
    }

    /**
     * Tests the buildErrorPage method for generating an error page table.
     *
     * Spot-checks for the error message and table structure.
     *
     * @return void
     */
    public function testBuildErrorPage(): void
    {
        $html = $this->layoutBuilder->buildErrorPage(
            'An error occurred.',
            ['class' => 'error-table'],
            2
        );

        $this->assertStringContainsString('class="error-table"', $html);
        $this->assertStringContainsString('An error occurred.', $html);
        $this->assertStringContainsString('<hr/>', $html);
    }

    /**
     * Tests the buildSemesterChooserForm method for generating a semester chooser form.
     *
     * Spot-checks for form structure and key elements such as pulldown menus.
     *
     * @return void
     */
    public function testBuildSemesterChooserForm(): void
    {
        $html = $this->layoutBuilder->buildSemesterChooserForm(
            '/semester',
            'Please choose a semester.',
            ['class' => 'chooser-table'],
            2
        );

        $this->assertStringContainsString(
            '<form enctype="multipart/form-data" target="_blank" action="/semester" method="get">',
            $html
        );
        $this->assertStringContainsString('class="chooser-table"', $html);
        $this->assertStringContainsString('Please choose a semester.', $html);
        $this->assertStringContainsString('<select name="y">', $html);
        $this->assertStringContainsString('<option value="2024" selected>2024</option>', $html);
        $this->assertStringContainsString('<button type="submit"', $html);
    }

    /**
     * Tests the buildSemesterProposalListForm method for generating a proposal list form.
     *
     * Spot-checks for proposal details and table structure.
     *
     * @return void
     */
    public function testBuildSemesterProposalListForm(): void
    {
        $proposals = [
            [
                'ObsApp_id' => '1',
                'code' => 'PR001',
                'semesterYear' => '2024',
                'semesterCode' => 'FA',
                'ProgramNumber' => '100',
                'InvLastName1' => 'Smith',
            ],
            [
                'ObsApp_id' => '2',
                'code' => 'PR002',
                'semesterYear' => '2024',
                'semesterCode' => 'SP',
                'ProgramNumber' => '101',
                'InvLastName1' => 'Johnson',
            ],
        ];

        $html = $this->layoutBuilder->buildSemesterProposalListForm(
            '/proposals',
            'Available Proposals:',
            $proposals,
            ['class' => 'proposal-list'],
            2
        );

        $this->assertStringContainsString('class="proposal-list"', $html);
        $this->assertStringContainsString('Available Proposals:', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="1" />', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="2" />', $html);
        $this->assertStringContainsString('(Smith)</td>', $html);
        $this->assertStringContainsString('(Johnson)</td>', $html);
    }

    /**
     * Tests the buildProposalUpdateConfirmationForm method for generating a confirmation form.
     *
     * Spot-checks for form structure, proposal details, and input field.
     *
     * @return void
     */
    public function testBuildProposalUpdateConfirmationForm(): void
    {
        $proposal = [
            'ObsApp_id' => '12345',
            'code' => 'PR123',
            'semesterYear' => '2024',
            'semesterCode' => 'FA',
            'ProgramNumber' => '101',
            'InvLastName1' => 'Doe',
        ];

        $inputField = '<input type="text" name="proposal_name" value="Proposal X" />';

        $html = $this->layoutBuilder->buildProposalUpdateConfirmationForm(
            '/confirm',
            'Confirm Update:',
            $proposal,
            $inputField,
            ['class' => 'confirm-table'],
            2
        );

        $this->assertStringContainsString('action="/confirm"', $html);
        $this->assertStringContainsString('class="confirm-table"', $html);
        $this->assertStringContainsString('Confirm Update:</td>', $html);
        $this->assertStringContainsString('<input type="hidden" name="i" value="12345" />', $html);
        $this->assertStringContainsString('(Doe)</td>', $html);
        $this->assertStringContainsString($inputField, $html);
    }
}
