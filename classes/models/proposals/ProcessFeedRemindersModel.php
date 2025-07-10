<?php

declare(strict_types=1);

namespace App\models\proposals;

use App\core\common\AbstractDebug as Debug;
use App\models\BaseModel;

/**
 * Model for updating the application date.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ProcessFeedRemindersModel extends BaseModel
{
    private $dbRead;
    private $dbWrite;

    public function __construct(
        ?Debug $debug = null,
        ?DbRead $dbRead = null,
        ?DbWrite $dbWrite = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

        // Initialise the additional classes needed by this model
        //$this->dbRead = $dbRead ?? new DbRead($this->debug->isDebugMode());
        //$this->dbWrite = $dbWrite ?? new DbWrite($this->debug->isDebugMode());
        //$this->debug->debug("{$debugHeading} -- Service classes successfully initialised.");

        // Class initialisation complete
        $this->debug->debug("{$debugHeading} -- Model initialisation complete.");
    }

    // Abstract method: initializeDefaultData()

    public function initializeDefaultData(?array $data = null): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "initializeDefaultData");
        $this->debug->debug($debugHeading);
        // Calcualte necessary fields
        // Return the data
        return [
            'emailLeadTime' => -1,             // collect programs from emailleadtime and back one day
            'blockWindow'   => 16,             // bobby wants a 15-day look-ahead window, so added to
                                               //  the -1 gives us 16 here;
            'serviceObsCm'  => 'Service Obs.', // Service comment
            'units'         => 1,              // 1 = DAY, 0 = WEEK
            'emails'        => 0,              // 1 = send emails, 0 = dummy emails
        ];
    }
}
