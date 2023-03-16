<?php

namespace epusta\mir;

class MyCoReObjectFactory extends AbstractFactory
{

    private $config = null;
    private $cache = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function create($mcrobjectid)
    {
        if (isset($this->cache[$mcrobjectid])) {
            return $this->cache[$mcrobjectid];
        }

        $parentids = null;
        $objectids = [];
	$objectids[] = $mcrobjectid;
	$subjects = [];

        if ($this->config['getmethod'] == 'file') {
            $path = $this->config['datadir'] . '/' . $this->getFilePathById($mcrobjectid) . '/' . $mcrobjectid . '.xml';
        } else {
            $path = $this->config['url_prefix'] . "/api/v1/objects/" . $mcrobjectid;
        }

        $doc = $this->getDOMByURL($path);
        if ($doc == null) {
            return null;
        }
        $nodename = $doc->documentElement->nodeName;

        if ($nodename == "mycoreobject") {
            $xpath = new \DOMXpath($doc);
            $xpath->registerNamespace('mods', "http://www.loc.gov/mods/v3");

            $elements = $xpath->query("//mods:mods/mods:relatedItem[@type='host' or @type='series']");
            foreach ($elements as $element) {
                if ($element->getAttribute("xlink:href")) $parentids[] = $element->getAttribute("xlink:href");
            }

            $elements = $xpath->query("//mods:mods/mods:relatedItem[@type='host' or @type='series']/mods:identifier[@type='urn' or @type='doi']");
            foreach ($elements as $element) {
                $parentids[] = $element->nodeValue;
            }

            $elements = $xpath->query("//mods:mods/mods:relatedItem[@type='host' or @type='series']/mods:relatedItem[@type='host' or @type='series']");
            foreach ($elements as $element) {
                $parentids[] = $element->getAttribute("xlink:href");
            }

            $elements = $xpath->query("//mods:mods/mods:relatedItem[@type='host' or @type='series']/mods:relatedItem[@type='host' or @type='series']/mods:identifier[@type='urn' or @type='doi']");
            foreach ($elements as $element) {
                $parentids[] = $element->nodeValue;
            }

            $elements = $xpath->query("//mods:mods/mods:identifier[@type='urn' or @type='doi']");
            foreach ($elements as $element) {
                $objectids[] = $element->nodeValue;
	    }
	    //$elements = $xpath->query("//mods:mods/mods:genre[@type='intern']");
	    $elements = $xpath->query("//mods:mods/mods:genre[contains(@authorityURI, 'classifications/genres') or contains(@authorityURI, 'classifications/mir_genres')]");
	    foreach ($elements as $element) {
                $valueURI=$element->getAttribute("valueURI");
                $genre=substr($valueURI, strpos($valueURI, "#") + 1); 
                $subjects[] = "mir_genre:".$genre;
            }
        } else {
            return null;
        }

        $this->cache[$mcrobjectid] = new MyCoReObject($objectids, $parentids,$subjects);
        return $this->cache[$mcrobjectid];
    }
}
