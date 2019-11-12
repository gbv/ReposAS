<?php

namespace ReposASTest;

use ReposAS\ConvertedLogline;
use ReposAS\ConvertedLoglineParser;
use ReposAS\MIRToolbox;


class MIRToolboxTest extends \PHPUnit\Framework\TestCase
{
    private $mirToolbox;
    private $convertedLoglineParser;
    private $testFile;

    public function setUp()
    {
        parent::setUp();
        require_once __DIR__ . '/../config/config.php';
        $this->mirToolbox = new MIRToolbox($config);
        $this->convertedLoglineParser = new ConvertedLoglineParser();

        $this->testFile = fopen("ressources/log2reposas.log", "r");
    }

    /**
     * Tests, if the identifier and subject of a logfile with fulltext-download in OPUS4 are correct
     */
    public function testAddIdentifierOpusFulltext()
    {
        $logline = new ConvertedLogline();

        $testline = trim(fgets($this->testFile));

        $this->convertedLoglineParser->parse($testline, $logline);
        $this->mirToolbox->addIdentifier($logline);

        $expectedIdentifier = ["opus4-foo1-59"];
        $expectedSubjects = ["oas:content:counter"];
        $this->assertEquals($logline->identifier, $expectedIdentifier);
        $this->assertEquals($logline->subjects, $expectedSubjects);
    }

    /**
     * Tests, if the identifier and subject of a logfile with frontdoor-access in OPUS4 are correct
     */
    public function testAddIdentifierOpusFrontdoor()
    {
        $logline = new ConvertedLogline();

        while (!feof($this->testFile)) {
            $lines[] = fgets($this->testFile);
        }
        $testline = $lines[2];

        $this->convertedLoglineParser->parse($testline, $logline);
        $this->mirToolbox->addIdentifier($logline);

        $expectedIdentifier = ["opus4-foo3-16412"];
        $expectedSubjects = ["oas:content:counter_abstract"];
        $this->assertEquals($logline->identifier, $expectedIdentifier);
        $this->assertEquals($logline->subjects, $expectedSubjects);
    }
}
