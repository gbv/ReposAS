<?php

namespace ReposASTest;

use ReposAS\ConvertedLogline;
use ReposAS\ConvertedLoglineParser;

class ConvertedLoglineParserTest extends \PHPUnit\Framework\TestCase
{
    public function testParse()
    {
        $testFile = fopen(__DIR__."/ressources/reposasLoglineWithoutIdentifiersAndSubjects.log", "r");
        $testline = trim(fgets($testFile));
        $convertedLoglineParser = new ConvertedLoglineParser();
        $logline = new ConvertedLogline();
        $convertedLoglineParser->parse($testline, $logline);
        $this->assertEquals($testline, $logline->__toString());
    }
}
