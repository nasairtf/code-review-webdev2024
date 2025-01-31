<?php

declare(strict_types=1);

namespace App\models;

use App\core\common\CustomDebug as Debug;

/**
 * Model for handling common model logic.
 *
 * @category Models
 * @package  IRTF
 * @author   Miranda Hawarden-Ogata
 * @version  1.0.0
 */

abstract class BaseModel
{
    /**
     * @var Debug Instance of Debug for logging and debugging purposes.
     */
    protected $debug;

    /**
     * Constructor to initialize the BaseModel with a Debug instance.
     *
     * @param Debug|null $debug Optional. An instance of Debug for logging; defaults to null.
     */
    public function __construct(
        ?Debug $debug = null
    ) {
        // Debug output
        $this->debug = $debug ?? new Debug('default', false, 0);
        $debugHeading = $this->debug->debugHeading("BaseModel", "__construct");
        $this->debug->debug($debugHeading);

        // Constructor completed
        $this->debug->debug("{$debugHeading} -- Parent Model initialisation complete.");
    }

    // Abstract methods: initializeDefaultData()

    abstract public function initializeDefaultData(?array $data = null): array;

    /**
     * Recursively merges new|user-submitted form data with default data.
     *
     * @param array $defaults The default data array with all form fields.
     * @param array $submitted The new|user-submitted data array (e.g., $_POST).
     *
     * @return array The merged array with defaults filled where missing.
     */
    public function mergeNewDataWithDefaults(array $defaults, array $submitted): array
    {
        // Debug output
        $debugHeading = $this->debug->debugHeading("Model", "mergeNewDataWithDefaults");
        $this->debug->debug($debugHeading);
        $this->debug->debugVariable($defaults, "{$debugHeading} -- defaults");
        $this->debug->debugVariable($submitted, "{$debugHeading} -- submitted");

        $merged = $defaults;
        foreach ($submitted as $key => $value) {
            // If value is an array and exists in defaults as an array, recurse
            if (is_array($value) && isset($defaults[$key]) && is_array($defaults[$key])) {
                $merged[$key] = $this->mergeNewDataWithDefaults($defaults[$key], $value);
            } else {
                // Otherwise, use the submitted value, overriding defaults
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
}
