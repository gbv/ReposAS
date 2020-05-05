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

        if ($this->config['getmethod'] == 'file') {
            $path = $this->config['datadir'] . '/' . $this->getFilePathById($objectid) . '/' . $objectid . '.xml';
        } else {
            $path = $this->config['url_prefix'] . "/api/v1/objects/" . $objectid;
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
                $parentids[] = $element->getAttribute("xlink:href");
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
                $parentids[] = $element->getAttribute("xlink:href");
            }

            $elements = $xpath->query("//mods:mods/mods:identifier[@type='urn' or @type='doi']");
            foreach ($elements as $element) {
                $objectids[] = $element->nodeValue;
            }
        } else {
            return null;
        }

        $this->cache[$objectid] = new MyCoReObject($objectid, $parentid);
        return $this->cache[$objectid];
    }
}
