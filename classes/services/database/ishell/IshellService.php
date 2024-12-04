<?php

namespace App\services\database\ishell;

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
    public function __construct(bool $debugMode = false)
    {
        parent::__construct('ishell', $debugMode);
    }
}
