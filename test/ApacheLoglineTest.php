<?php

namespace ReposASTest;

use ReposAS\ApacheLogline;

class ApacheLoglineTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyInput()
    {
        $logline = new ApacheLogline();
        $string = $logline->__toString();
        $this->assertEquals('   [] "  "   "" ""', $string);
    }

    /**
     * Test the correct IP anonymization of an IPv4
     */
    public function testAnonymizeIp4()
    {
        $logline = new ApacheLogline();
        $logline->ip = '155.202.64.122';
        $logline->anonymizeIp();
        $this->assertEquals('155.202.XXX.XXX', $logline->ip);
    }

    /**
     * Test the correct IP anonymization of an IPv6
     */
    public function testAnonymizeIp6()
    {
        $logline = new ApacheLogline();
        $logline->ip = '3ffe:1900:4545:3:200:f8ff:fe21:67cf';
        $logline->anonymizeIp();
        $this->assertEquals('3ffe:1900:4545:3:200:f8ff:XXXX:XXXX', $logline->ip);
    }
}
