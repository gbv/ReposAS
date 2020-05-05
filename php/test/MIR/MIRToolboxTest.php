<?php

namespace epustaTest;

use epusta\ConvertedLogline;
use epusta\ConvertedLoglineParser;
use epusta\mir\MIRToolbox;
use epusta\Configuration;

class MIRToolboxTest extends \PHPUnit\Framework\TestCase
{
    private $mirToolbox;
    private $convertedLoglineParser;
    private $testFile;

    public function setUp()
    {
        parent::setUp();
        $configuration = new Configuration();
        $config = $configuration->getConfig();
        $this->mirToolbox = new MIRToolbox($config);
        $this->convertedLoglineParser = new ConvertedLoglineParser();

        $this->testFile = fopen(__DIR__ . "/../ressources/epustaLoglineWithoutIdentifiersAndSubjects.log", "r");
    }

    /**
     * Because there is a failure, if there is an empty test-class, here a dummy test.
     * This class needs some ressources to be tested, so we need to wait for them.
     * TODO: Test this class
     */
    public function testDummy()
    {
        $this->assertEquals(1, 1);
    }
}
