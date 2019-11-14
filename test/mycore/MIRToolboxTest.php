<?php

namespace ReposASTest;

use ReposAS\ConvertedLogline;
use ReposAS\ConvertedLoglineParser;
use ReposAS\mycore\MIRToolbox;
use ReposAS\Configuration;

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

        $this->testFile = fopen(__DIR__ . "/ressources/reposasLoglineWithoutIdentifiersAndSubjects.log", "r");
    }
}
