<?php

namespace ReposAS\mycore;

use ReposAS\abstracts\AbstractMycoreFactory;

class MyCoReDerivateFactory extends AbstractMycoreFactory
{

    private $config = null;
    private $cache = array();

    function __construct($config)
    {
        $this->config = $config;
    }

    public function create($derivateid)
    {
        if (isset ($this->cache[$derivateid])) return $this->cache[$derivateid];
        $doc = new \DOMDocument();
        //$doc->load($this->config['url_prefix']."/receive/".$derivateid."?XSL.Style=xml");
        if ($this->config['getmethod'] == 'file') {
            $path = $this->config['datadir'] . '/' . $this->getFilePathById($derivateid) . '/' . $derivateid . '.xml';
        } else {
            $path = $this->config['url_prefix'] . "/receive/" . $derivateid . "?XSL.Style=xml";
        }
        $doc = $this->getDOMByURL($path);
        if ($doc == null) return null;
        $xpath = new \DOMXpath($doc);
        $elements = $xpath->query("/mycorederivate/derivate/internals[@class='MCRMetaIFS']/internal");
        $element = $elements->item(0);
        $maindoc = $element->getAttribute("maindoc");
        $elements = $xpath->query("/mycorederivate/derivate/linkmetas[@class='MCRMetaLinkID']/linkmeta");
        if ($elements->length > 1) {
            fwrite(STDERR, "Warning - (" . $derivateid . ") more then one parent.\n");
        }
        $element = $elements->item(0);
        $objectid = $element->getAttribute("xlink:href");
        $elements = $xpath->query("/mycorederivate/derivate/fileset");
        $element = $elements->item(0);
        $urn = ($element) ? $urn = $element->getAttribute("urn") : null;
        $this->cache[$derivateid] = new MyCoReDerivate($derivateid, $objectid, $maindoc, $urn);
        return $this->cache[$derivateid];
    }
}
