<?php

declare(strict_types=1);

namespace App\controllers\proposals;

use Exception;
use App\exceptions\ValidationException;
use App\core\common\Config;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                                   as Debug;
use App\models\proposals\DownloadApplicationPdfsModel               as Model;
use App\views\pages\proposals\DownloadApplicationPdfsView           as View;
use App\validators\forms\proposals\DownloadApplicationPdfsValidator as Validator;

/**
 * Controller for handling the Download Application Pdfs logic.
 *
 * @category Controllers
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class DownloadApplicationPdfsController
{
    private $formatHtml;
    private $debug;
    private $model;
    private $view;
    private $valid;
    private $secret;
    private $pdfpath;

    public function __construct(
        ?bool $formatHtml = null,
        ?Debug $debug = null,
        ?Model $model = null,
        ?View $view = null,
        ?Validator $valid = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Controller", "__construct");
        $this->debug->debug($debugHeading);

        // Set the global html formatting
        $this->formatHtml = $formatHtml ?? false;

        // Fetch the config for tokens
        $config = Config::get('tokens_config');

        // Check if the requested secret exists in the config
        if (!isset($config['proposal-pdf'])) {
            $this->debug->fail("Download configuration not found.");
        }

        // Save the string
        $this->secret  = $config['proposal-pdf']['secret'];
        $this->pdfpath = $config['proposal-pdf']['filepath'];
        $this->debug->debug("{$debugHeading} -- Configuration loaded successfully.");

        // Initialise dependencies with fallbacks
        $this->model = $model ?? new Model($this->pdfpath, $this->debug);
        $this->view = $view ?? new View($this->formatHtml, $this->debug);
        $this->valid = $valid ?? new Validator($this->secret, $this->debug);
        $this->debug->debug("{$debugHeading} -- Model, View, Validator classes successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Controller initialisation complete.");
    }

    /**
     * Handles incoming requests.
     *
     * Determines whether to process a form submission or render the form page.
     * Delegates specific actions to helper methods.
     *
     * @return void
     */
    public function handleRequest(): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($_GET, "{$debugHeading} -- _GET");

        if (isset($_GET['t']) && isset($_GET['type'])) {
            // Handle required parameters
            $this->handleRequestParameters($_GET);
        } else {
            // Display the error page if parameters are missing
            $errorTitle = 'PDF Download Error';
            $errorMessage = 'Missing required parameters.';
            $this->renderErrorPage(
                $errorTitle,
                $errorMessage
            );
        }
    }

    /**
     * Data validation methods that call the validation helpers and then determine
     * whether to throw exceptions or pass the data on to the processing methods.
     *
     * handleRequestParameters - validates form data and passes to semester processor method
     */

    private function handleRequestParameters(
        array $formData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "handleRequestParameters");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($formData, "{$debugHeading} -- formData");

        try {
            // Validate the parameters
            $validData = $this->valid->validateFormData($formData);
            $this->debug->debugVariable($validData, "{$debugHeading} -- validData");
            // If validation passes, proceed to processing the download request
            $this->processDownloadRequest($validData);
        } catch (ValidationException $e) {
            // Debug output
            $this->debug->debugVariable($e->getMessages(), "Validation Errors");
            // If validation fails, render error page
            $this->renderErrorPage('The type or token is not valid.', $e->getMessage());
        } catch (Exception $e) {
            // If validation fails, render error page
            $this->renderErrorPage('Unexpected error: ', $e->getMessage());
        }
    }

    /**
     * Data processing methods that set up interface with the DB Class
     *
     * processSemesterSubmit - delivers the selected semester's data to renderer
     */

    private function processDownloadRequest(
        array $validData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "processDownloadRequest");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        try {
            // Construct the requested file's path
            $filePath = $this->model->resolveDownloadPath($validData['token']);
            // Verify the file exists
            $this->model->verifyRequestedFile($filePath);
            // Stream the file to the user
            $this->model->serveRequestedFile($filePath);
        } catch (Exception $e) {
            // Handle any errors during the data fetching process
            $this->renderErrorPage("Error fetching requested file", $e->getMessage());
        }
    }

    /**
     * Page rendering methods that interface with View Class
     *
     * renderErrorPage              - renders the error page displayed for caught exceptions
     */

    private function renderErrorPage(
        string $errorTitle,
        string $errorMessage
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Controller", "renderErrorPage");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($errorTitle, "{$debugHeading} -- errorTitle");
        $this->debug->debugVariable($errorMessage, "{$debugHeading} -- errorMessage");

        // Call the view to render the error page
        echo $this->view->renderErrorPage(
            $errorTitle,
            $errorMessage
        );
    }
}
