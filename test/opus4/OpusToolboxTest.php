<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 13.11.19
 * Time: 18:17
 */

namespace ReposASTest\opus4;

use ReposAS\ConvertedLogline;
use ReposAS\ConvertedLoglineParser;
use ReposAS\opus4\OpusToolbox;

class OpusToolboxTest extends \PHPUnit\Framework\TestCase
{
    private $opusToolbox;
    private $convertedLoglineParser;
    private $testFile;

    public function setUp()
    {
        parent::setUp();

        $this->opusToolbox = new OpusToolbox();
        $this->convertedLoglineParser = new ConvertedLoglineParser();

        $this->testFile = fopen(__DIR__."/../ressources/reposasLoglineWithoutIdentifiersAndSubjects.log", "r");
    }

    /**
     * Tests, if the identifier and subject of a logfile with fulltext-download in OPUS4 are correct
     */
    public function testAddIdentifierOpusFulltext()
    {
        $logline = new ConvertedLogline();

        $testline = trim(fgets($this->testFile));

        $this->convertedLoglineParser->parse($testline, $logline);
        $this->opusToolbox->addIdentifier($logline);

        $expectedIdentifier = ["opus4-foo1-1618"];
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

        while (! feof($this->testFile)) {
            $lines[] = fgets($this->testFile);
        }
        $testline = $lines[4];
        $this->convertedLoglineParser->parse($testline, $logline);
        $this->opusToolbox->addIdentifier($logline);

        $expectedIdentifier = ["opus4-foo3-2008"];
        $expectedSubjects = ["oas:content:counter_abstract"];
        $this->assertEquals($logline->identifier, $expectedIdentifier);
        $this->assertEquals($logline->subjects, $expectedSubjects);
    }
}
