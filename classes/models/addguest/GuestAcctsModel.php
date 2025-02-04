<?php

declare(strict_types=1);

namespace App\models\addguest;

use Exception;
use App\exceptions\ExecutionException;
use App\core\common\CustomDebug as Debug;
use App\models\BaseModel        as BaseModel;
use App\legacy\traits\LegacyAddguestTrait;

/**
 * Model for handling the guest account logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class GuestAcctsModel extends BaseModel
{
    use LegacyAddguestTrait;

    private $sys_command;

    public function __construct(
        ?Debug $debug = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Initialise the class properties
        $sys_command = '/home/addguest/bin/add_guest/run_addguest_on_webserver';
        //$send = "";
        $send = ' -x';
        $verbose = ($this->debug->isDebugMode()) ? ' -v' : '';
        $this->sys_command = $sys_command . $send . $verbose;

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
            'command'    => $data[0],
            'username'   => '',
            'acctname'   => '',
            'uid'        => 0,
            'gid'        => 0,
            'shell'      => '/bin/tcsh',
            'passwd'     => '',
            'accttype'   => 0,
            'expiredays' => 0,
        ];
    }

    public function queueCommand(array $validData): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "queueCommand");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($validData, "{$debugHeading} -- validData");

        // Queue the requested command
        switch ($validData['command']) {
            case 'clearguest':
                // handle clearguest command;
                return $this->processClearguest($validData);
                break;
            case 'createguest':
                // handle createguest command;
                return $this->processCreateguest($validData);
                break;
            case 'extendguest':
                // handle extendguest command;
                return $this->processExtendguest($validData);
                break;
            case 'removeguest':
                // handle removeguest command;
                return $this->processRemoveguest($validData);
                break;
            case 'addguest':
            default:
                // handle addguest command;
                return $this->processAddguest($validData);
                break;
        }
    }

    private function processAddguest(array $data): array
    {
        // Build the addguest command;
        $command = $this->sys_command
            . " -c adduser"
            . " -u {$data['username']}"
            . " -l '{$data['acctname']}'"
            . " -i {$data['uid']}"
            . " -g {$data['gid']}"
            . " -r {$data['shell']}"
            . " -p '{$data['passwd']}'"
            . " -a {$data['accttype']}"
            . " -e {$data['expiredays']}";

        // Execute the addguest command;
        return $this->executeCommand($command);
    }

    private function processClearguest(array $data): array
    {
        // Build the clearguest command;
        $command = $this->sys_command
            . " -c clearguest";

        // Execute the clearguest command;
        return $this->executeCommand($command);
    }

    private function processCreateguest(array $data): array
    {
        // Build the createguest command;
        $command = $this->sys_command
            . " -c createguest"
            . " -t";

        // Execute the createguest command;
        return $this->executeCommand($command);
    }

    private function processExtendguest(array $data): array
    {
        // Build the extendguest command;
        $command = $this->sys_command
            . " -c extendguest"
            . " -u {$data['username']}"
            . " -e {$data['expiredays']}";

        // Execute the extendguest command;
        return $this->executeCommand($command);
    }

    private function processRemoveguest(array $data): array
    {
        // Build the remuser command;
        $command = $this->sys_command
            . " -c remuser"
            . " -u {$data['username']}"
            . " -i {$data['uid']}"
            . " -a {$data['accttype']}";

        // Execute the remuser command;
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
        $input  = sprintf("<pre style='text-align: left; color: green;' class='result-messages'>\n%s\n</pre>", $results['input']);
        $output = sprintf("<pre style='text-align: left; color: red;'   class='error-messages'>\n%s\n</pre>", implode("\n", $results['output']));

        // Throw the exception
        throw new ExecutionException("Execution error", [$input, $output]);
    }
}
