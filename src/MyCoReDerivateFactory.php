<?php
namespace ReposAS;

class MyCoReDerivateFactory {

    private $config=null;
    private $cache = array();

    function __construct($config) {
        $this->config=$config;
    }

    public function create ($derivateid) {
        if (isset ($this->cache[$derivateid])) return $this->cache[$derivateid];
        $doc = new DOMDocument();
        //$doc->load($this->config['url_prefix']."/receive/".$derivateid."?XSL.Style=xml");
        if ($this->config['getmethod']=='file') {
            $path=$this->config['datadir'].'/'.$this->getFilePathById($derivateid).'/'.$derivateid.'.xml';
        } else {
            $path=$this->config['url_prefix']."/receive/".$derivateid."?XSL.Style=xml";
        }
        $doc = $this->getDOMByURL($path);
        if ($doc == null) return null;
        $xpath = new DOMXpath($doc);
        $elements = $xpath->query("/mycorederivate/derivate/internals[@class='MCRMetaIFS']/internal");
        $element = $elements->item(0);
        $maindoc = $element->getAttribute("maindoc");
        $elements = $xpath->query("/mycorederivate/derivate/linkmetas[@class='MCRMetaLinkID']/linkmeta");
        if ($elements->length>1) {
            fwrite(STDERR, "Warning - (".$derivateId.") more then one parent.\n" );
        }
        $element = $elements->item(0);
        $objectid = $element->getAttribute("xlink:href");
        $elements  = $xpath->query("/mycorederivate/derivate/fileset");
        $element   = $elements->item(0);
        $urn  = ($element) ? $urn = $element->getAttribute("urn") : null;
        $this->cache[$derivateid] =  new MyCoReDerivate($derivateid,$objectid,$maindoc,$urn);
        return $this->cache[$derivateid];
    }

    function getFilePathById($id) {
        if (preg_match('/([^\/]+)_([^\/]+)_([0-9]{4})([0-9]{2})[0-9]{2}$/', $id, $match)) {
            $project=$match[1];
            $type=$match[2];
            $dig4=$match[3];
            $dig2=$match[4];
        } else {
            return null;
        }
        return "metadata/".$project."/".$type."/".$dig4."/".$dig2;
    }

    function getDOMByURL ($url) {
        $doc = new DOMDocument();
        $count = 0;
        @$load = $doc->load($url,LIBXML_NOWARNING);
        while ($count < 10 && ! $load ) {
            //fwrite(STDERR, "Error: unable to get Data from ".$url.". Try to reconnect(".$count.")\n");
            usleep(2000);
            @$load = $doc->load($url,LIBXML_NOWARNING);
            $count ++;
        }
        if (! $load ) {
            //throw new Exception("Error: unable to get Data from ".$this->config['url_prefix']);

            return null;
        }
        return $doc;
    }
}
