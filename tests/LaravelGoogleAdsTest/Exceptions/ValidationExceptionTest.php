<?php

namespace LaravelGoogleAdsTest\Exceptions;

use LaravelGoogleAds\Exceptions\ValidationException;
use PHPUnit_Framework_TestCase;

require_once 'vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Common/Lib/ValidationException.php';

class ValidationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $trigger = 'demo';
        $value = 'demoValue';
        $message = 'demoMessage';

        $exception = new ValidationException($trigger, $value, $message);

        $this->assertSame(
            $exception->getMessage(),
            sprintf(
                ValidationException::EXCEPTION_FORMAT,
                $trigger,
                $value,
                $message
            )
        );

        $this->assertSame(
            $exception->GetTrigger(),
            $trigger
        );

        $this->setExpectedException(
            ValidationException::class
        );

        throw $exception;
    }
}