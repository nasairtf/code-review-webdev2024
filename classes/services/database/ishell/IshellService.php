<?php

declare(strict_types=1);

namespace App\services\database\ishell;

use App\exceptions\DatabaseException;
use App\services\database\DBConnection;
use App\core\common\CustomDebug;
use App\services\database\DatabaseService as BaseService;

/**
 * IshellService class that provides core functionality for all ishell services.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class IshellService extends BaseService
{
    public function __construct(
        bool $debugMode = false,
        ?DBConnection $db = null,
        ?CustomDebug $debug = null
    ) {
        parent::__construct('ishell', $debugMode, $db, $debug);
    }
}
