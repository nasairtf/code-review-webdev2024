<?php

namespace Tests\classes\views\forms;

use App\views\forms\BaseFormView;

class TestFormView extends BaseFormView
{
    public function getFieldLabels(): array
    {
        return ['field1' => 'Field 1'];
    }

    protected function getPageContents(
        array $dbData = [],
        array $formData = [],
        int $pad = 0
    ): string {
        return '<p>Page Contents</p>';
    }

    /**
     * Proxy method for testing the protected renderPage method.
     *
     * @param string $title   The title of the page.
     * @param string $content The HTML content of the page.
     *
     * @return string The complete HTML of the page.
     */
    public function renderPageProxy(string $title, string $content): string
    {
        return $this->renderPage($title, $content);
    }

    /**
     * Proxy method for testing the protected getErrorsBlock method.
     *
     * @param array $errors      An array of error messages keyed by field name.
     * @param array $fieldLabels An array mapping field names to human-readable labels.
     * @param int   $pad         Optional padding level for formatted output (default: 0).
     *
     * @return string The HTML block containing formatted error messages.
     */
    public function getErrorsBlockProxy(array $errors, array $labels, int $pad = 0): string
    {
        return $this->getErrorsBlock($errors, $labels, $pad);
    }

    /**
     * Proxy method for testing the protected getResultsBlock method.
     *
     * @param array $results An array of result messages.
     * @param int   $pad     Optional padding level for formatted output (default: 0).
     *
     * @return string The HTML block containing formatted results messages.
     */
    public function getResultsBlockProxy(array $results, int $pad = 0): string
    {
        return $this->getResultsBlock($results, $pad);
    }

    /**
     * Proxy method for testing the protected getContentsForm method.
     *
     * @param string $action   The form submission URL.
     * @param array  $dbData   Data arrays required to populate form options.
     * @param array  $formData Default data for form fields.
     * @param int    $pad      Optional padding level for formatted output (default: 0).
     *
     * @return string The formatted HTML for the form contents.
     */
    public function getContentsFormProxy(string $action, array $dbData, array $formData, int $pad = 0): string
    {
        return $this->getContentsForm($action, $dbData, $formData, $pad);
    }
}
