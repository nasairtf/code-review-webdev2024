<?php

declare(strict_types=1);

namespace App\views\forms\feedback;

use App\exceptions\HtmlBuilderException;
use App\core\common\CustomDebug           as Debug;
use App\views\forms\BaseFormView          as BaseView;
use App\core\htmlbuilder\HtmlBuilder      as HtmlBuilder;
use App\core\htmlbuilder\CompositeBuilder as CompBuilder;
use App\legacy\IRTFLayout                 as IrtfBuilder;
use App\core\irtf\IrtfLinks;

/**
 * View for rendering the Feedback form and its sections.
 * This class is responsible for generating HTML structure and layout for
 * the feedback form, including preamble, program information, technical and personnel
 * feedback, scientific results, and suggestions.
 *
 * @category Views
 * @package  IRTF
 * @version  1.0.0
 */

class FeedbackView extends BaseView
{
    private $irtfLinks;

    /**
     * Initializes the FeedbackView with core builders and configurations.
     *
     * @param bool|null        $formatHtml  Enable formatted HTML output. Defaults to false if not provided.
     * @param Debug|null       $debug       Debug instance for logging and debugging. Defaults to a new Debug instance.
     * @param HtmlBuilder|null $htmlBuilder Instance for constructing HTML elements. Defaults to a new HtmlBuilder.
     * @param CompBuilder|null $compBuilder Instance for composite HTML elements. Defaults to a new CompBuilder.
     * @param IrtfBuilder|null $irtfBuilder Legacy layout builder for site meta. Defaults to a new IrtfBuilder.
     * @param IrtfLinks|null   $irtfLinks   Links utiltiy getter for site.
     */
    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?HtmlBuilder $htmlBuilder = null, // Dependency injection to simplify unit testing
        ?CompBuilder $compBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfBuilder $irtfBuilder = null, // Dependency injection to simplify unit testing
        ?IrtfLinks $irtfLinks = null      // Dependency injection to simplify unit testing
    ) {
        // Use parent class' constructor
        parent::__construct($formatHtml, $debug, $htmlBuilder, $compBuilder, $irtfBuilder);
        $debugHeading = $this->debug->debugHeading("View", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Set up the links instance
        $this->irtfLinks = $irtfLinks ?? new IrtfLinks();
        $this->debug->debug("{$debugHeading} -- Links class is successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- View initialisation complete.");
    }

    // Abstract methods: getFieldLabels(), getPageContents()

    /**
     * Provides field labels for the Feedback form.
     *
     * Maps internal field names to user-friendly labels.
     *
     * @return array An associative array mapping field names to labels.
     */
    public function getFieldLabels(): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getFieldLabels");
        $this->debug->debug($debugHeading);

        // Map internal field names to user-friendly labels
        return [
            'respondent'         => 'Your Name',
            'email'              => 'E-mail Address',
            'dates'              => 'Observing Dates',
            'support_staff'      => 'Support Astronomer(s)',
            'operator_staff'     => 'Telescope Operator(s)',
            'instruments'        => 'Facility Instrument(s)',
            'visitor_instrument' => 'Visitor Instrument',
            'location'           => 'Observing Location',
            'experience'         => 'Overall Experience',
            'technical'          => 'Technical Commentary',
            'scientificstaff'    => 'Support Staff',
            'operators'          => 'Telescope Operators',
            'daycrew'            => 'Daycrew',
            'personnel'          => 'Personnel Support',
            'scientific'         => 'Scientific Results',
            'comments'           => 'Comments and Suggestions',
        ];
    }

    /**
     * Generates the main page content for the feedback form.
     *
     * This method defines the specific HTML structure for the login form,
     * using the provided database and form data. The BaseFormView parent method
     * getContentsForm() passes the contents to renderFormPage(), etc., for rendering.
     *
     * @param array $dbData   Data arrays required to populate form options. Defaults to an empty array.
     * @param array $formData Default data for form fields. Defaults to an empty array.
     * @param int   $pad      Optional padding level for formatted output. Defaults to 0.
     *
     * @return string The HTML content for the form page.
     */
    protected function getPageContents(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getPageContents");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dbData, "{$debugHeading} -- dbData");
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");
        $this->debug->debugVariable($pad, "{$debugHeading} -- pad");

        // Build the page contents
        $line = $this->htmlBuilder->getLine([], $pad);
        $htmlParts = [
            $this->getPreamble($pad),
            $line,
            //$this->getButtons($pad),
            $this->buildButtons($pad),
            $line,
            //$this->getSecurity($pad),
            //$line,
            $this->getProgramInfo($dbData, $formData, $pad),
            $line,
            $this->getTechnicalFeedback($formData, $pad),
            $line,
            $this->getPersonnelFeedback($formData, $pad),
            $line,
            $this->getScientificResults($formData, $pad),
            $line,
            $this->getSuggestions($formData, $pad),
            $line,
            $this->getButtons($pad),
            $line,
            "",
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getPreamble(
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getPreamble");
        $this->debug->debug($debugHeading);

        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $paragraphPad = $tableRowPad + 2;

        $pAttr = ['align' => 'justify'];
        $rowAttr = [];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        $contactInfo = getContactInfoAutoEmails(false, "director");
        $ack = $this->htmlBuilder->getLink(
            $this->irtfLinks->getResearchAcknowledgment(),
            'acknowledgement page',
            [],
            $pad,
            true
        );
        $email = $this->htmlBuilder->getEmailLink($contactInfo['email1'], $contactInfo['email1'], [], $pad, true);
        $paragraphs = [
            'Please take a few moments to answer the following questions about your IRTF observing run. '
                . 'Your feedback is the most valuable information we have on the service the IRTF is '
                . 'providing to its community.',
            'This Feedback Form is sent <u>ONLY</u> to ' . $contactInfo['name'] . ', the IRTF Director, who will '
                . 'review its content and remove sensitive material before distributing to appropriate members of '
                . 'the IRTF staff. Confidential comments may also be provided directly to ' . $contactInfo['name']
                . ' by telephone at ' . $contactInfo['phone'] . ' or by email at ' . $email . '.',
            'If you have an interesting result, please consider making it available as a science highlight for '
                . 'NASA Headquarters and contact ' . $contactInfo['name'] . ' to do this. Published results should '
                . 'acknowledge the IRTF and the instrument used.  See our ' . $ack . ' for more information.',
        ];

        $paragraphTags = [
            $this->htmlBuilder->getParagraph($paragraphs[0], $pAttr, $paragraphPad, true),
            $this->htmlBuilder->getParagraph($paragraphs[1], $pAttr, $paragraphPad, true),
            $this->htmlBuilder->getParagraph($paragraphs[2], $pAttr, $paragraphPad, true),
        ];
        $tableHtml = $this->htmlBuilder->getTableFromRows(
            [
                $this->htmlBuilder->getTableRowFromArray(
                    [$paragraphTags[0]],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                ),
                $this->htmlBuilder->getTableRowFromArray(
                    [$paragraphTags[1]],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                ),
                $this->htmlBuilder->getTableRowFromArray(
                    [$paragraphTags[2]],
                    false,
                    [false],
                    $rowAttr,
                    $tableRowPad,
                    true
                ),
            ],
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Preamble  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    /**
     * Builds the preamble section for the login form.
     *
     * @param array $formData The default form data for login fields.
     *
     * @return string The HTML for the preamble.
     */
    private function buildPreamble(int $pad = 0): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildPreamble");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $preamble = '';
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        // Build the section contents
        return $this->compBuilder->buildPreambleFormSection(
            $preamble,
            $rowAttr,
            $tableAttr,
            0
        );
    }

    /**
     * Builds the button section for the feedback form.
     *
     * @return string The HTML for the form buttons.
     */
    private function buildButtons(int $pad = 0): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "buildButtons");
        $this->debug->debug($debugHeading);

        // Prep the section contents
        $buttonAttr = ['style' => 'width: 135px;'];
        $rowAttr = [];
        $tableAttr = ['border' => '0', 'cellspacing' => '4'];

        $buttons = [
            $this->htmlBuilder->getResetButton('Clear Form', $buttonAttr),
            $this->htmlBuilder->getSubmitButton('submit', 'Send Form', $buttonAttr),
        ];

        // Build the section contents
        return $this->compBuilder->buildButtonsFormSection(
            $buttons,
            $rowAttr,
            $tableAttr,
            $pad
        );
    }

    //$htmlParts[] = $this->getSecurity($pad);
    //$code .= getSecurity( $debug, $title, $isForm, $data );
    private function getSecurity(
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getSecurity");
        $this->debug->debug($debugHeading);

        // Render output
        $htmlParts = [
            '',
            '<!--  Security section  -->',
            '',
            //'<center>',
            //$tableHtml,
            //'</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getProgramInfo(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getProgramInfo");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($dbData, "dbData");
        $this->debug->debugVariable($formData, "formData");

        $tablePad = $pad;
        $tableRowPad = $pad + 2;
        $labeledElementPad = 6;
        $elementPad = 12;

        $colors = ['#CCCCCC', '#C0C0C0'];
        $rowAttr = [];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        // Configurations for each section of the form, including label, element, and background color
        $sections = [
            // person filling in the form
            [
                'label' => 'Your Name:',
                'element' => $this->htmlBuilder->getTextInput(
                    'respondent',
                    $formData['respondent'],
                    65,
                    ['maxlength' => '70'],
                    0,
                    false
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => true
            ],
            // email address of person filling in the form
            [
                'label' => 'E-mail Address:',
                'element' => $this->htmlBuilder->getEmailInput(
                    'email',
                    $formData['email'],
                    65,
                    ['maxlength' => '70'],
                    0,
                    false
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => true
            ],
            // program information
            [
                'label' => 'Program Information:',
                'element' => $this->compBuilder->buildSingleProposalTable(
                    $formData['program'],
                    $dbData['program'],
                    $colors[0],
                    $elementPad - 2
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => false
            ],
            // observing dates
            [
                'label' => 'Observing Dates:',
                'element' => $this->compBuilder->buildDateRangeTable(
                    ['year' => 'startyear', 'month' => 'startmonth', 'day' => 'startday'],
                    ['year' => 'endyear', 'month' => 'endmonth', 'day' => 'endday'],
                    ['start' => 'Start Date', 'end' => 'End Date'],
                    [
                        'start' => [
                            'year' => $formData['startyear'],
                            'month' => $formData['startmonth'],
                            'day' => $formData['startday']
                        ],
                        'end' => [
                            'year' => $formData['endyear'],
                            'month' => $formData['endmonth'],
                            'day' => $formData['endday']
                        ]
                    ],
                    $colors[1],
                    $elementPad
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => false
            ],
            // support astronomers
            [
                'label' => 'Support Astronomer(s):',
                'element' => $this->compBuilder->buildCheckboxTable(
                    'support_staff',
                    $dbData['support'],
                    $formData['support_staff'],
                    $colors[0],
                    $elementPad
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => false
            ],
            // telescope operators
            [
                'label' => 'Telescope Operator(s):',
                'element' => $this->compBuilder->buildCheckboxTable(
                    'operator_staff',
                    $dbData['operator'],
                    $formData['operator_staff'],
                    $colors[1],
                    $elementPad
                ),
                'labelRow' => false,
                'inlineLabel' => true,
                'inlineContent' => false
            ],
            // instruments
            [
                'label' => 'Please select the instrument(s) you used during this run:',
                'element' => $this->compBuilder->buildInstrumentCheckboxPulldownTable(
                    ['facility' => 'instruments', 'visitor' => 'visitor_instrument'],
                    ['facility' => $dbData['facility'], 'visitor' => $dbData['visitor']],
                    ['facility' => $formData['instruments'], 'visitor' => $formData['visitor_instrument']],
                    $colors[0],
                    $elementPad
                ),
                'labelRow' => true,
                'inlineLabel' => true,
                'inlineContent' => false
            ]
        ];

        // Generate tables and rows
        $rows = [];
        foreach ($sections as $index => $section) {
            $rowColor = $colors[$index % 2];
            $rowAttr['bgcolor'] = $rowColor;
            $table = $this->compBuilder->buildLabeledElementTable(
                $section['label'],         // label
                $section['element'],       // content
                $rowColor,                 // background color for table
                $section['labelRow'],      // label as row
                $section['inlineLabel'],   // inline label
                $section['inlineContent'], // inline contents
                $labeledElementPad         // table padding
            );
            $rows[] = $this->htmlBuilder->getTableRowFromArray(
                [$table],
                false,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            );
        }

        $tableHtml = $this->htmlBuilder->getTableFromRows(
            $rows,
            $tableAttr,
            $tablePad
        );
        $htmlParts = [
            '',
            '<!--  Program information section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getTechnicalFeedback(
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getTechnicalFeedback");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData['location'], "formData['location']");
        $this->debug->debugVariable($formData['experience'], "formData['experience']");
        $this->debug->debugVariable($formData['technical'], "formData['technical']");

        $tablePad = $pad;
        $tableRowPad = $pad + 2;
        $subcellPad = 6;
        $colors = ['#C0C0C0', '#CCCCCC'];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];
        $rowAttr = [];
        $htmlParts = [];

        $heading = 'TECHNICAL FEEDBACK';
        $site = $this->compBuilder->buildLabeledRemoteObsTable(
            'location',
            'Did you use remote or onsite observing?',
            (string) $formData['location'],
            $colors[1],
            true,
            $subcellPad
        );
        $rating = $this->compBuilder->buildLabeledRatingTable(
            'experience',
            'Please rate your overall experience with the telescope and instrument(s) during this run:',
            (string) $formData['experience'],
            $colors[0],
            false,
            true,
            $subcellPad
        );
        $textarea = $this->compBuilder->buildTextareaTable(
            'technical',
            'Please comment on instrumentation, telescope, and other technical areas.',
            $formData['technical'],
            $colors[1],
            '',
            $subcellPad
        );

        $rows = [
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$heading],
                $colors[1],
                $colors,
                [true],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$site],
                $colors[0],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$rating],
                $colors[1],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$textarea],
                $colors[0],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
        ];

        $tableHtml = $this->htmlBuilder->getTableFromRows($rows, $tableAttr, $tablePad);
        $htmlParts = [
            '',
            '<!--  Technical feedback section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getPersonnelFeedback(
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getPersonnelFeedback");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData['scientificstaff'], "formData['scientificstaff']");
        $this->debug->debugVariable($formData['operators'], "formData['operators']");
        $this->debug->debugVariable($formData['daycrew'], "formData['daycrew']");
        $this->debug->debugVariable($formData['personnel'], "formData['personnel']");

        $tablePad = $pad;
        $tableRowPad = $tablePad + 2;
        $tablecellPad = $tableRowPad + 2;
        $subcellPad = 6;

        $colors = ['#C0C0C0', '#CCCCCC'];
        $rowAttr = [];
        $tableAttr = ['width' => '100%', 'border' => '0', 'cellspacing' => '0', 'cellpadding' => '6'];

        $heading = 'PERSONNEL FEEDBACK';
        $instructions = 'Please rate the support you received from our staff during this run:';
        $rateSupport = $this->compBuilder->buildLabeledRatingTable(
            'scientificstaff',
            'Support Staff',
            (string) $formData['scientificstaff'],
            $colors[0],
            true,
            false,
            $subcellPad
        );
        $rateOperator = $this->compBuilder->buildLabeledRatingTable(
            'operators',
            'Telescope Operators',
            (string) $formData['operators'],
            $colors[1],
            true,
            false,
            $subcellPad
        );
        $rateDaycrew = $this->compBuilder->buildLabeledRatingTable(
            'daycrew',
            'Daycrew',
            (string) $formData['daycrew'],
            $colors[0],
            true,
            false,
            $subcellPad
        );
        $textarea = $this->compBuilder->buildTextareaTable(
            'personnel',
            'Please comment on the support you received from the telescope operators, '
                . 'staff astronomers, and other IRTF personnel.',
            $formData['personnel'],
            $colors[1],
            '',
            $subcellPad
        );

        $rows = [
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$heading],
                $colors[1],
                $colors,
                [true],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$instructions],
                $colors[0],
                $colors,
                [true],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$rateSupport],
                $colors[1],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$rateOperator],
                $colors[0],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$rateDaycrew],
                $colors[1],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
            $this->htmlBuilder->getTableRowFromArrayWithAlternatingColor(
                [$textarea],
                $colors[0],
                $colors,
                [false],
                $rowAttr,
                $tableRowPad,
                true
            ),
        ];
        $tableHtml = $this->htmlBuilder->getTableFromRows($rows, $tableAttr, $tablePad);
        $htmlParts = [
            '',
            '<!--  Personnel feedback section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getScientificResults(
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getScientificResults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData['scientific'], "formData['scientific']");

        $tableHtml = $this->compBuilder->buildTextareaTable(
            'scientific',
            'SCIENTIFIC RESULTS',
            $formData['scientific'],
            '#C0C0C0',
            'Please describe general results and comment on whether your expectations for data were met.',
            $pad
        );
        $htmlParts = [
            '',
            '<!--  Scientific results section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }

    private function getSuggestions(
        array $formData = [],
        int $pad = 0
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("View", "getSuggestions");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData['comments'], "formData['comments']");

        $tableHtml = $this->compBuilder->buildTextareaTable(
            'comments',
            'COMMENTS AND SUGGESTIONS',
            $formData['comments'],
            '#CCCCCC',
            'Please describe what you liked during your run and also where we can improve.',
            $pad
        );
        $htmlParts = [
            '',
            '<!--  Comments and Suggestions section  -->',
            '',
            '<center>',
            $tableHtml,
            '</center>',
            '',
        ];
        return $this->htmlBuilder->formatParts($htmlParts, $this->formatHtml);
    }
}
