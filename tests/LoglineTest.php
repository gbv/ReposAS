<?php
namespace ReposASTest;

use ReposAS\Logline;

class LoglineTest extends \PHPUnit\Framework\TestCase
{
    public function testString()
    {
        $logLine = new Logline();
        $string = $logLine->__toString();
        $this->assertEquals('   [] "  "   "" ""', $string);
    }
}
