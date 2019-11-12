<?php

namespace ReposASTest;

use ReposAS\ApacheLogline;

class ApacheLoglineTest extends \PHPUnit\Framework\TestCase
{
    public function testString()
    {
        $logline = new ApacheLogline();
        $string = $logline->__toString();
        $this->assertEquals('   [] "  "   "" ""', $string);
    }
}
