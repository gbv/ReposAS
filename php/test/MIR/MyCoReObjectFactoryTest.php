<?php

namespace epustaTest;

use epusta\Configuration;
use epusta\mir\MyCoReObjectFactory;

/**
 * This class test extraction of the Identifers für an MyCoRe Object direct vom the xml Files.
 * The test use dummy xml Files for test_mods_0000001.xml. 
 * 
 */

class MyCoReObjectFactoryTest extends \PHPUnit\Framework\TestCase
{
    private $mcrObject;
        

    public function setUp()
    {
        parent::setUp();
        $configuration = new Configuration();
        $config = $configuration->getPhpUnitConfig();
        $this->assertDirectoryExists($config['datadir'],"The MIR-datadirectory (".$config['datadir'].") doesn't exsists.");
        $mcrObjectFactory = new MyCoReObjectFactory($config);
        $this->mcrObject = $mcrObjectFactory->create('test_mods_00000001');
        $this->assertTrue(is_object($this->mcrObject),"MyCoReObject couldn't created.");
    }

    /**
     * Check for alle identifier in the MyCoRe Object.
     */
    public function testIdentifier()
    {
        $identifier = $this->mcrObject->getAllIdentifier();
        $this->assertContains("test_mods_00000001", $identifier, "MyCoReID is missed in list of identifier");
        $this->assertContains("11111/11111-0", $identifier, "DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:1-1", $identifier, "URN is missed in list of identifier");
        //ParentIDs
        $this->assertContains("test_mods_00000002", $identifier, "Parent MyCoReID is missed in list of identifier");
        $this->assertContains("22222/22222-0", $identifier, "Parent DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:2-5", $identifier, "Parent  URN is missed in list of identifier");
        //GrandparentIDs
        $this->assertContains("test_mods_00000003", $identifier, "Grandparent MyCoReID is missed in list of identifier");
        $this->assertContains("33333/33333-0", $identifier, "Grandparent DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:3-9", $identifier, "Grandparent  URN is missed in list of identifier");
    }
    
    public function testObjectIdentifier()
    {
        $identifier = $this->mcrObject->getObjectIdentifier();
        $this->assertContains("test_mods_00000001", $identifier, "MyCoReID is missed in list of identifier");
        $this->assertContains("11111/11111-0", $identifier, "DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:1-1", $identifier, "URN is missed in list of identifier");
    }
    
    public function testParentIdentifier()
    {
        $identifier = $this->mcrObject->getParentIdentifier();
        fwrite(STDERR, print_r($identifier, TRUE));
        $this->assertContains("test_mods_00000002", $identifier, "Parent MyCoReID is missed in list of identifier");
        $this->assertContains("22222/22222-0", $identifier, "Parent DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:2-5", $identifier, "Parent  URN is missed in list of identifier");
        //GrandparentIDs
        $this->assertContains("test_mods_00000003", $identifier, "Grandparent MyCoReID is missed in list of identifier");
        $this->assertContains("33333/33333-0", $identifier, "Grandparent DOI is missed in list of identifier");
        $this->assertContains("urn:nbn:de:test:3-9", $identifier, "Grandparent  URN is missed in list of identifier");
    }
}
