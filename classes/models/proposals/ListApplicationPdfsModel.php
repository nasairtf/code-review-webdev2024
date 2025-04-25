<?php

declare(strict_types=1);

namespace App\models\proposals;

use Exception;
use App\core\common\Config;
use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                       as Debug;
use App\services\database\troublelog\read\ObsAppService as DbRead;

/**
 * Model for handling the List Application Pdfs logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ListApplicationPdfsModel
{
    private $debug;
    private $dbRead;

    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null
    ) {
        // Debug output
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise the additional classes needed by this model
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Service class successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    /**
     * Query methods that interface directly with DB Class
     *
     * fetchSemesterData - retrieves the semester data
     */

    public function fetchSemesterData(
        int $year,
        string $semester
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchSemesterData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($year, "{$debugHeading} -- year");
        $this->debug->debugVariable($semester, "{$debugHeading} -- semester");

        return $this->dbRead->fetchSemesterProposalListingPageData($year, $semester);
    }

    public function generateProposalLinks(
        array $proposals
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "generateProposalLinks");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($proposals, "{$debugHeading} -- proposals");

        // Fetch the token config
        $config = $this->retrieveTokenConfig('proposal-pdf');
        $secret = $config['sec'];
        $url    = $config['url'];

        // Generate the proposal download tokens
        $updatedProposals = [];
        $inx = [
            'add' => 'AddendumToken',
            'app' => 'ApplicationToken',
        ];
        foreach ($proposals as $index => $proposal) {
            $code = $proposal['code'];
            $pdfs = [
                'add' => $proposal['UploadFileName'],
                'app' => $proposal['ProposalFileName'],
            ];
            $proposal[$inx['add']] = '';
            $proposal[$inx['app']] = '';

            foreach ($pdfs as $type => $file) {
                if (!empty($file)) {
                    $token = $this->generateToken($code, $pdfs[$type], $type, $secret);
                    $proposal[$inx[$type]] = "{$url}?t={$token}&type={$type}";
                }
            }

            $updatedProposals[$index] = $proposal;
        }

        return $updatedProposals;
    }

    private function generateToken(
        string $proposalCode,
        string $fileName,
        string $type,
        string $secret
    ): string {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "generateToken");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($proposalCode, "{$debugHeading} -- proposalCode");
        $this->debug->debugVariable($fileName, "{$debugHeading} -- fileName");
        $this->debug->debugVariable($type, "{$debugHeading} -- type");
        $this->debug->debugVariable($secret, "{$debugHeading} -- secret");

        // Generate the token
        $timestamp = time();
        $payload = "$proposalCode|$fileName|$type|$timestamp";
        $hash = hash_hmac('sha256', $payload, $secret);
        return base64_encode("$payload|$hash");
    }

    private function retrieveTokenConfig(
        string $tokenName
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "retrieveTokenConfig");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($tokenName, "{$debugHeading} -- tokenName");

        // Fetch the config for tokens
        $config = Config::get('tokens_config');

        // Check if the requested secret exists in the config
        if (!isset($config[$tokenName])) {
            $this->debug->fail("Token configuration for '{$tokenName}' not found.");
        }

        // Return the secret and downloader url for use
        return [
            'sec' => $config[$tokenName]['secret'],
            'url' => $config[$tokenName]['downloader']
        ];
    }
}
