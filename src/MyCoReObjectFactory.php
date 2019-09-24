<?php
namespace ReposAS;

class MyCoReObjectFactory {

    private $config=null;
    private $cache = array();

    function __construct($config) {
        $this->config=$config;
    }

    public function create ($objectid) {
        if (isset ($this->cache[$objectid])) return $this->cache[$objectid];
        $parentid=null;
        $doi=null;
        $urn=null;
        $doc = new \DOMDocument();
        if ($this->config['getmethod']=='file') {
            $path=$this->config['datadir'].'/'.MyCoReDerivateFactory::getFilePathById($objectid).'/'.$objectid.'.xml';
        } else {
            $path=$this->config['url_prefix']."/api/v1/objects/".$objectid;
        }
        $doc = MyCoReDerivateFactory::getDOMByURL($path);
        if ($doc == null) return null;
        $nodename = $doc->documentElement->nodeName;
        if ($nodename=="mycoreobject") {
            $xpath = new \DOMXpath($doc);
            $elements = $xpath->query("/mycoreobject/structure/parents/parent");
            $element = $elements->item(0);
            if ($element) {
                $parentid = $element->getAttribute("xlink:href");
            }
            $xpath->registerNamespace('mods', "http://www.loc.gov/mods/v3");
            $elements = $xpath->query("//mods:mods/mods:identifier[@type='urn']");
            $element = $elements->item(0);
            if ($element) {
                $urn=$element->nodeValue;
            }
            $elements = $xpath->query("//mods:mods/mods:identifier[@type='doi']");
            $element = $elements->item(0);
            if ($element) {
                $doi=$element->nodeValue;
            }
        } else {
            return null;
        }
        $this->cache[$objectid] =  new MyCoReObject($objectid,$parentid,$doi,$urn);
        return $this->cache[$objectid];
    }

}
