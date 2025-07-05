<?php

declare(strict_types=1);

namespace App\models\proposals;

use Exception;
use App\exceptions\ExecutionException;
use App\core\common\Config;
use App\core\common\AbstractDebug                           as Debug;
use App\models\BaseModel;
use App\services\database\troublelog\read\GuestAcctsService as DbRead;
use App\legacy\traits\LegacyQueueDataRestoreTrait;

/**
 * Model for handling the Queue Observer Data Restoration logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class QueueDataRestoreModel extends BaseModel
{
    use LegacyQueueDataRestoreTrait;

    private $usr_command;
    private $sys_command;
    private $dbRead;

    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Fetch the config for restores
        $config = Config::get('datarestore_config');

        // Check if the requested secret exists in the config
        if (!isset($config['datarestore']) || !isset($config['verification'])) {
            $this->debug->fail("Data restoration configuration not found.");
        }

        $ver = $config['verification'];
        $cmd = $config['datarestore'];

        // Initialise the class properties
        $this->usr_command = [
            'command'   => $ver['command'],
            'user_flag' => $ver['user'],
            'pwd_flag'  => $ver['pwd'],
        ];
        $this->debug->debugVariable($this->usr_command, "{$debugHeading} -- usr_command");
        $this->sys_command = [
            'command'    => $cmd['command'],
            'quiet_flag' => $cmd['quiet'],
            'email_flag' => $cmd['email'],
            'dsthost'    => $cmd['host']    . ' ' . $cmd['dsthost'],
            'usersrc'    => $cmd['srcprog'] . ' ',
            'userdst'    => $cmd['dstprog'] . ' ',
            'pathleg'    => $cmd['srcpath'] . ' ' . $cmd['pathleg'],
            'pathdst'    => $cmd['dstpath'] . ' ' . $cmd['pathdst'],
            'test_flag'  => $cmd['test'],
            'del_flag'   => $cmd['delete'],
        ];
        $this->debug->debugVariable($this->sys_command, "{$debugHeading} -- sys_command");
        $this->debug->debug("{$debugHeading} -- Configuration loaded successfully.");

        // Initialise the additional classes needed by this model
        $this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        $this->debug->debug("{$debugHeading} -- Service class successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    public function initializeDefaultData(?array $data = null): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "initializeDefaultData");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");

        // Return the data
        return [
            'usersrc'    => '',
            'userdst'    => '',
            'codesrc'    => 'staff restore',
            'codedst'    => 'staff restore',
            'test'       => 0,
            'delete'     => 0,
        ];
    }

    public function fetchSessionCodes(
        string $programSrc,
        string $programDst
    ): array {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "fetchSessionCodes");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($programSrc, "{$debugHeading} -- programSrc");
        $this->debug->debugVariable($programDst, "{$debugHeading} -- programDst");

        if ($programSrc === $programDst) {
            // Fetch the single session code
            $codeSrc = $this->dbRead->fetchProgramSessionData($programSrc, '');
            $codeDst = $codeSrc;
        } else {
            // Fetch the source and destination session codes
            $codeSrc = $this->dbRead->fetchProgramSessionData($programSrc, '');
            $codeDst = $this->dbRead->fetchProgramSessionData($programDst, '');
        }
        // return source and destination session codes
        return [
            'codesrc' => $codeSrc[0]['session'],
            'codedst' => $codeDst[0]['session'],
        ];
    }

    public function verifyPrograms(
        array $validData
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "verifyPrograms");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Queue the requested command
        $this->verifyProgram($validData['usersrc'], $validData['codesrc'], true);
        $this->verifyProgram($validData['userdst'], $validData['codedst'], false);
    }

    private function verifyProgram(
        string $user,
        string $code,
        bool $isSrc
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "verifyProgram");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($user, "{$debugHeading} -- user");
        $this->debug->debugVariable($code, "{$debugHeading} -- code");
        $this->debug->debugVariable($isSrc, "{$debugHeading} -- isSrc");
        $this->debug->debugVariable($this->usr_command, "{$debugHeading} -- usr_command");

        // Queue the requested command
        $this->verifyProgramValidity($this->usr_command, $user, $code, $isSrc);
    }

    /**
     * Handles queuing restore of the requested data to scrh1_restore
     */
    private function verifyProgramValidity(
        array $cmd,
        string $user,
        string $code,
        bool $isSrc
    ): void {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "verifyProgramValidity");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($cmd, "{$debugHeading} -- cmd");
        $this->debug->debugVariable($user, "{$debugHeading} -- user");
        $this->debug->debugVariable($code, "{$debugHeading} -- code");
        $this->debug->debugVariable($isSrc, "{$debugHeading} -- isSrc");

        #-- verify the user name/code provided
        $sys = "{$cmd['command']} {$cmd['user_flag']} {$user} {$cmd['pwd_flag']} {$code}";
        $this->debug->debugVariable($sys, "{$debugHeading} -- sys");

        #-- verify the user name/code provided
        $out = [];
        $tmp = exec($sys, $out);
        if (count($out) != 0) {
            $out = implode("\n", $out);
            $out = explode(":", $out);
        }
        $this->debug->debugVariable($out, "{$debugHeading} -- out");

        #-- generate error notice for source user name/code pair if code doesn't match or user not in db
        if ($isSrc && ($out[0] == 2 || $out[0] == 4)) {
            // Throw exception
            throw new ExecutionException("Execution error", [$out[1]]);
        }

        #-- generate error notice for destination user name/code pair
        if (!$isSrc && $out[0] != 1) {
            // Throw exception
            throw new ExecutionException("Execution error", [$out[1]]);
        }
    }

    public function queueCommand(array $validData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "queueCommand");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Queue the requested command
        return $this->processRestore($validData);
    }

    private function processRestore(array $data): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "processRestore");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($data, "{$debugHeading} -- data");
        $this->debug->debugVariable($this->sys_command, "{$debugHeading} -- this->sys_command");

        // Build the run_restore_on_webserver command;
        $command = $this->sys_command['command']
            . " {$this->sys_command['quiet_flag']}"
            . " {$this->sys_command['email_flag']}"
            . " {$this->sys_command['dsthost']}"
            . " {$this->sys_command['usersrc']}{$data['usersrc']}"
            . " {$this->sys_command['userdst']}{$data['userdst']}"
            . " {$this->sys_command['pathleg']}"
            . " {$this->sys_command['pathdst']}";
        $command .= ($data['test'] === 1)
            ? " {$this->sys_command['test_flag']}"
            : '';
        $command .= ($data['delete'] === 1)
            ? " {$this->sys_command['del_flag']}"
            : '';
        $this->debug->debugVariable($command, "{$debugHeading} -- command");

        // Execute the addguest command;
        return $this->executeCommand($command);
    }

    private function executeCommand(string $command): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "executeCommand");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($command, "{$debugHeading} -- command");

        // Execute the requested command
        $result = $this->execSystemCommand($command);

        // Return the command results
        if ($result['status']) {
            return [
                $result['input'],
                $result['output']
            ];
        } else {
            $this->handleExecutionException($result);
        }
    }

    private function execSystemCommand(string $command): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "execSystemCommand");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($command, "{$debugHeading} -- command");

        // Execute the requested command
        $this->debug->debug("{$debugHeading} -- Executing command.");
        #$output = passthru($command);
        $output = [];
        exec($command, $output);
        $this->debug->debugVariable($output, "{$debugHeading} -- output");

        // Return the command results
        return [
            'status' => (count($output) === 0) ? true : false,
            'input'  => $command,
            'output' => $output,
        ];
    }

    private function handleExecutionException(array $results): void
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "handleExecutionException");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($results, "{$debugHeading} -- results");

        // Format the exception output
        $input  = sprintf(
            "<pre style='text-align: left; color: green;' class='result-messages'>\n%s\n</pre>",
            $results['input']
        );
        $output = sprintf(
            "<pre style='text-align: left; color: red;'   class='error-messages'>\n%s\n</pre>",
            implode("\n", $results['output'])
        );

        // Throw the exception
        throw new ExecutionException("Execution error", [$input, $output]);
    }
}
