<?php

declare(strict_types=1);

namespace App\models\proposals;

use Exception;
use App\core\common\CustomDebug as Debug;
use App\models\BaseModel        as BaseModel;

/**
 * Model for handling the Observer Data Restoration Request logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

class ObsDataRestorationRequestModel extends BaseModel
{
    public function __construct(
        ?Debug $debug = null
    ) {
        // Use parent class' constructor
        parent::__construct($debug);
        $debugHeading = $this->debug->debugHeading("Model", "__construct");
        $this->debug->debug($debugHeading);
        $this->debug->debug("{$debugHeading} -- Parent class is successfully constructed.");

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
            'reqname'    => '',
            'reqemail'   => '',
            'y'          => date('Y'),
            's'          => '',
            'srcprogram' => '',
            'piprogram'  => '',
            'obsinstr'   => '',
            'reldetails' => '',
        ];
    }
}
