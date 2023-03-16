<?php

namespace epustaTest;

use epusta\Counter3Filter30sek;
use epusta\ConvertedLoglineParser;
use epusta\ConvertedLogline;

class Counter3Filter30sekTest extends \PHPUnit\Framework\TestCase
{
    private $convertedLoglineParser;
    private $counter3Filter30sek;
    private $testFile;
    
    public function setUp()
    {
        parent::setUp();
        $this->convertedLoglineParser = new ConvertedLoglineParser();
        $this->counter3Filter30sek = new Counter3Filter30sek ();
        
        $logfile=__DIR__."/ressources/Counter3Filter30sekTest.log";
        $this->assertTrue(is_readable($logfile),"Fail to read file Counter3Filter30sekTest.log");
        $this->testFile = fopen($logfile , "r");
        
    }
    
    public function testAddSubjectCounter3Filter20sek()
    {
        $logline = new ConvertedLogline();
        
        $testline1 = trim(fgets($this->testFile));
        $testline2 = trim(fgets($this->testFile));
        $testline3 = trim(fgets($this->testFile));
        $testline4 = trim(fgets($this->testFile));
        $testline5 = trim(fgets($this->testFile));
        
        $this->convertedLoglineParser->parse($testline1, $logline);
        $this->counter3Filter30sek->edit($logline);
        $this->assertNotContains("filter:30sek:counter3", $logline->subjects, "Line 1 : filter:30sek:counter3 schouldn't present in subjects.");
        
        $this->convertedLoglineParser->parse($testline2, $logline);
        $this->counter3Filter30sek->edit($logline);
        $this->assertContains("filter:30sek:counter3", $logline->subjects, "Line 2 : filter:30sek:counter3 schould present in subjects.");
        
        $this->convertedLoglineParser->parse($testline3, $logline);
        $this->counter3Filter30sek->edit($logline);
        $this->assertContains("filter:30sek:counter3", $logline->subjects, "Line 3 : filter:30sek:counter3 schould present in subjects.");
        
        $this->convertedLoglineParser->parse($testline4, $logline);
        $this->counter3Filter30sek->edit($logline);
        $this->assertContains("filter:30sek:counter3", $logline->subjects, "Line 4 : filter:30sek:counter3 schould present in subjects.");
        
        $this->convertedLoglineParser->parse($testline5, $logline);
        $this->counter3Filter30sek->edit($logline);
        $this->assertNotContains("filter:30sek:counter3", $logline->subjects, "Line 5 : filter:30sek:counter3 schould present in subjects.");
        
    }
}