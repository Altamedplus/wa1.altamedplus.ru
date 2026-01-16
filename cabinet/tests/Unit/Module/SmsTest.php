<?php

namespace Tests\Unit\Module;

use APP\Module\Sms;
use PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
    private Sms $sms;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sms = new Sms();
    }

    public function testSendSuccess() {

    }

    public function testSendWithEmptyCredentials() {
     
    }

    public function testPhoneNumberFormatting() {
       
    }

    public function testTextEncoding() {
    
    }
}
