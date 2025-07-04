<?php

declare(strict_types=1);

namespace App\transformers\common\core;

use App\transformers\common\utilities\LabelUtility;
use App\transformers\common\utilities\InstrumentUtility;

class TransformerCore
{
    //** LabelUtility **//
    // public function returnRatingText(...) {}
    // public function returnLocationText(...) {}
    // public function returnEmailsSendTypeText(...) {}
    // public function returnIntervalUnitTypeText(...) {}
    // public function returnSelectionText(...) {}
    // public function returnTextDate(...) {}
    use LabelTrait;

    //** InstrumentUtility **//
    // public function transformInstruments(...) {}
    use InstrumentTrait;
}
