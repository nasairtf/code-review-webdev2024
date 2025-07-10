<?php

declare(strict_types=1);

namespace App\transformers;

use App\core\common\DebugFactory;
use App\core\common\AbstractDebug                as Debug;
use App\transformers\common\core\TransformerCore as Core;

abstract class BaseTransformer
{
    /** @var Debug Debugging/tracing interface */
    protected $debug;

    /** @var Core TransformerCore: orchestrates utility-backed transformer methods */
    protected $core;

    public function __construct(
        ?Debug $debug = null,
        ?Core $core = null
    ) {
        // Initialize debugging
        $this->debug = $debug ?? DebugFactory::create('default', false, 0);
        $debugHeading = $this->debug->debugHeading("BaseTransformer", "__construct");
        $this->debug->debug($debugHeading);

        // Initialise dependencies with fallbacks
        $this->core = $core ?? new Core();
        $this->debug->debug("{$debugHeading} -- Core class successfully initialised.");

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent Transformer initialisation complete.");
    }

    /**
     * Converts a numeric rating to its descriptive text equivalent.
     *
     * @param int $rating The numeric rating (0-5).
     *
     * @return string The descriptive rating text, e.g., "Excellent".
     */
    public function returnRatingText(int $rating): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnRatingText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($rating, "{$debugHeading} -- rating");

        // Return the appropriate rating text
        return $this->core->returnRatingText($rating);
    }

    /**
     * Converts a numeric location code to a descriptive text equivalent.
     *
     * @param int $location The location code (0 for "Remote", 1 for "Onsite").
     *
     * @return string The location description, either "Remote" or "Onsite".
     */
    public function returnLocationText(int $location): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnLocationText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($location, "{$debugHeading} -- location");

        // Return the appropriate location text
        return $this->core->returnLocationText($location);
    }

    /**
     * Converts the numeric flag for email sending into human-readable form.
     *
     * @param int $emailType The email sending mode (0 for dummy, 1 for real).
     *
     * @return string Descriptive text for the email sending type.
     */
    public function returnEmailsSendTypeText(int $emailType): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnEmailsSendTypeText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($emailType, "{$debugHeading} -- emailType");

        // Return the appropriate selected email-sending type text
        return $this->core->returnEmailsSendTypeText($emailType);
    }

    /**
     * Converts a numeric interval unit code into its label.
     *
     * @param int $unitType The interval unit (0 = Days, 1 = Weeks).
     *
     * @return string Descriptive text for the interval unit.
     */
    public function returnIntervalUnitTypeText(int $unitType): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnIntervalUnitTypeText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($unitType, "{$debugHeading} -- unitType");

        // Return the appropriate interval unit type text
        return $this->core->returnIntervalUnitTypeText($unitType);
    }

    /**
     * Retrieves the descriptive names for selected items and returns them as a comma-separated string.
     *
     * Maps each key in the options array to its corresponding value in the allowed
     * array, then concatenates them into a single comma-separated string.
     *
     * @param array $options Selected option keys.
     * @param array $allowed Associative array of allowed options with keys and names.
     *
     * @return string A comma-separated list of names for the selected options.
     */
    public function returnSelectionText(array $options, array $allowed): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnSelectionText");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($options, "{$debugHeading} -- options");
        $this->debug->debugVariable($allowed, "{$debugHeading} -- allowed");

        // Return the appropriate selection text
        return $this->core->returnSelectionText($options, $allowed);
    }

    public function returnTextDate(int $timestamp, string $format = 'M d, Y'): string
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "returnTextDate");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($timestamp, "{$debugHeading} -- timestamp");
        $this->debug->debugVariable($format, "{$debugHeading} -- format");

        // Return the appropriate date text
        return $this->core->returnTextDate($timestamp, $format);
    }

    /**
     * Combines instrument selections from user input and allowed options.
     *
     * @param array  $facility        Facility instruments selected.
     * @param string $visitor         Visitor instrument selected.
     * @param array  $allowedFacility Allowed facility instrument options.
     * @param array  $allowedVisitor  Allowed visitor instrument options.
     *
     * @return array An array containing 'values' (inputs) and 'allowed' (database options).
     */
    public function transformInstruments(array $f, array $v, array $af, array $av): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Transformer", "transformInstruments");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($f, "{$debugHeading} -- f");
        $this->debug->debugVariable($v, "{$debugHeading} -- v");
        $this->debug->debugVariable($af, "{$debugHeading} -- af");
        $this->debug->debugVariable($av, "{$debugHeading} -- av");

        // Consolidate the selected instruments
        return $this->core->transformInstruments($f, $v, $af, $av);
    }
}
