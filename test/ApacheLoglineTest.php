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
}
