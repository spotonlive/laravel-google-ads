<?php

namespace LaravelGoogleAds\Exceptions;

class ValidationException extends \ValidationException
{
    const EXCEPTION_FORMAT = 'Validation failed for [%s] with value [%s]: %s';

    /**
     * The trigger for the validation exception.
     * @var $trigger
     */
    protected $trigger;

    /**
     * Constructor for ValidationException where the exception will appear
     * as "Validation failed for [$trigger] with value [$value]: $message".
     *
     * @param string $trigger the trigger for the validation error
     * @param string $value the value for the trigger
     * @param string $message the message representing the error in validation
     */
    public function __construct($trigger, $value, $message)
    {
        $this->trigger = $trigger;

        parent::__construct($trigger, $value, $message);
    }
    /**
     * Get the trigger for the validation error.
     *
     * @return string the trigger for the validation error.
     */
    public function GetTrigger()
    {
        return $this->trigger;
    }
}

