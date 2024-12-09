<?php

declare(strict_types=1);

namespace App\services\database\troublelog;

use App\services\database\DatabaseService as BaseService;

/**
 * TroublelogService class that provides core functionality for all troublelog services.
 *
 * @category Services
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class TroublelogService extends BaseService
{
    public function __construct(bool $debugMode = false)
    {
        parent::__construct('troublelog', $debugMode);
    }
}
