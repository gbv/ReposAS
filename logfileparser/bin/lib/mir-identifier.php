<?php

class MyCoReObject {

        public $objectid=null;
	public $parentid=null;
        public $urn=null;
	public $doi=null;

	function __construct($objectid,$parentid,$doi,$urn) {
                $this->objectid=$objectid;
		$this->parentid=$parentid;
                $this->doi=$doi;
                $this->urn=$urn;
        }

	function getAllIdentifier() {
		$ret = array();
		if ($this->parentid) $ret[]=$this->parentid;
		if ($this->urn) $ret[]=$this->urn;
		if ($this->doi) $ret[]="doi:".$this->doi;
		return $ret;

	}
}

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
		$doc = new DOMDocument();
		if ($this->config['getmethod']=='file') {
			$path=$this->config['datadir'].'/'.getFilePathById($objectid).'/'.$objectid.'.xml';
		} else {
			$path=$this->config['url_prefix']."/api/v1/objects/".$objectid;
		}
                $doc = getDOMByURL($path);
                if ($doc == null) return null;
		$nodename = $doc->documentElement->nodeName;
                if ($nodename=="mycoreobject") {
                        $xpath = new DOMXpath($doc);
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

class MyCoReDerivate {

	public $derivateid=null;
	public $objectid=null;
	public $maindoc=null;
	// In MIR LTS2016 URN stored in derivate
	public $urn=null;

	function __construct($derivateid,$objectid,$maindoc,$urn) {
		$this->derivateid=$derivateid;
		$this->objectid=$objectid;
		$this->maindoc=$maindoc;
		$this->urn=$urn;
	}
	function getAllIdentifier() {
		$ret=array();
		if ($this->derivateid) $ret[]=$this->derivateid;
		if ($this->urn) $ret[]=$this->urn;
                return $ret;
        }

}

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
                        $path=$this->config['datadir'].'/'.getFilePathById($derivateid).'/'.$derivateid.'.xml';
                } else {
                        $path=$this->config['url_prefix']."/receive/".$derivateid."?XSL.Style=xml";
                }
                $doc = getDOMByURL($path);
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
                fwrite(STDERR, "Error: unable to get Data from ".$url.". Try to reconnect(".$count.")\n");
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



class MIRToolbox {
	var $dbh=false;
	var $config=false;

	var $MyCoReObjectFactory=null;
	var $MyCoReDerivateFactory=null;

	
	/**
	 * prepares database connection and instance variables
	 * @param $config config to be used
	 * @param $logger optional: set custom logger
	 */
	function __construct($config, $logger=false) {
		$this->config=$config;
		$this->logger=$logger;
		$this->cache=array();
		$this->lastDerivate="";
		$this->MyCoReDerivateFactory = new MyCoReDerivateFactory($config);
		$this->MyCoReObjectFactory = new MyCoReObjectFactory($config);
	}

  function addIdentifier(& $reposasLogline) {
      $path=$reposasLogline->URL;
      $referer=$reposasLogline->Referer;
      $id_list=array();
      $types=array();
      if (preg_match('/\/rsc\/stat\/([^\/]+_[^\/]+_[0-9]{8}).css$/', $path, $match)) {
          // The Fake css download 
          fwrite(STDERR, "Match - fake css:".$path."\n"); 
          $object=$this->MyCoReObjectFactory->create($match[1]);
          if ($object) {
            $reposasLogline->Identifier=$object->getAllIdentifier();
            // TO Do 'types'=>array('abstract')
            return (true);
          }
      } elseif (preg_match('/\/receive\/([^\/]+_[^\/]+_[0-9]{8})(;jsessionid.+|$)/', $path, $match)) {
      // Metadatapage docportal and citelink
          fwrite(STDERR, "Match - receive:".$path."\n");
          $object=$this->MyCoReObjectFactory->create($match[1]);
          if ($object) {
              $reposasLogline->Identifier=$object->getAllIdentifier();
              // TO Do 'types'=>array('abstract')
              return (true);
          }
      } elseif ($this->config['oldMirLogs'] === true && preg_match('/\/servlets\/solr.+?&rows=1.+?XSL.Style=browse.*/',$path, $match)) {
      // Forces one searchrenult so display Metdadatapage
      // !!! possible: id and objectType not requested
      /*fwrite(STDERR, "Match - old searchresult:".$path."\n");
                        $url=$this->config['url_prefix'].$match[0];
                        $url=str_replace(array("&XSL.Style=browse","&wt=xml"), "", $url);
                        $url.="&wt=json";
                        $json = file_get_contents($url);
                        $json = json_decode($json,true);
                        if (! empty($json['grouped']['returnId']['groups'][0]['doclist']['docs'][0]['id'])) { 
				// Successfull grouped search by returnId 
                        	$id_list[]=$this->config['oai_prefix'].$json['grouped']['returnId']['groups'][0]['doclist']['docs'][0]['id'];
				$types[]='abstract';
			} elseif (isset($json['grouped']['returnId']['groups']) && count($json['grouped']['returnId']['groups']) == 0) {
				// Empty grouped search by returnId
				$types[]='any';
			} elseif (!empty($json['response']['docs'][0]['id'])) {
				// Successfull search
				$id_list[]=$this->config['oai_prefix'].$json['response']['docs'][0]['id'];
				$types[]='abstract';
			} elseif (isset($json['response']['docs']) && count($json['response']['docs'] == 0) ) {
				// Empty search
				$types[]='any';
			} else {
				$id_list[]="Error(3a2g5d7j) by geting Data From JSON! Wrong path?";
				//$id_list[]=print_r($json,true);
				$types[]='abstract';
			}
        */
          die ("oldMirLogs not longer supported\n");
      } elseif (preg_match('/\/MCRFileNodeServlet\/([^\/]+_derivate_[0-9]+)\/([^;?]+)(;jsessionid)?([?]view)?.*/', $path, $match)) {
          //Fulltext download
          fwrite(STDERR, "Match - MCRFileNodeServlet:".$path."\n");
          fwrite(STDERR, "Derivat (".$match[1].")");
          if (isset($match[3]) && !(strpos ($match[3],"?view") === false)) {  // If intern Dokviewer
              fwrite(STDERR, "nur Ansicht.\n");
              return false;
	  }
          if (strpos($referer,"pdf.worker.js") !== false || strpos($referer,"pdf.min.worker.js") !== false ) {
              fwrite(STDERR, "nur Ansicht(pdfWorker).\n");
              return false;
	  }  
          $derivateid=$match[1];
          if ($derivateid == $this->lastDerivate ) {
              fwrite(STDERR, "doppeltes Derivate).\n");
              return false;
	  }
          $this->lastDerivate=$derivateid;
          $derivate=$this->MyCoReDerivateFactory->create($derivateid);
	  if ($derivate == null)  return false;	
	  $maindoc = $derivate->maindoc;
	  $filename = urldecode($match[2]);
	  fwrite(STDERR, $maindoc." - ".$filename."\n");
	  if ($maindoc == $filename) {
	      // TO DO Type of access $types[] = 'fulltext';
 	      $reposasLogline->Identifier[] = $derivateid;
				
	      // Add objectid
	      $objectid = $derivate->objectid;
              $object=$this->MyCoReObjectFactory->create($objectid);
	      $reposasLogline->Identifier = array_merge($object->getAllIdentifier(),$reposasLogline->Identifier);

	      //Add URN
	      $urn = $derivate->urn;
	      if ($urn) $reposasLogline->Identifier = $urn;
              return true;
          } else {
	      fwrite(STDERR, "nicht das Hauptdokument\n");
	      //$types[]='any';
              return false;
	  }
      } elseif (preg_match('/\/MCRZipServlet\/([^\/]+_derivate_[0-9]+)(;jsessionid)?.*/', $path, $match)) {
          fwrite(STDERR, "Match - MCRZipServlet:".$path."\n");
          fwrite(STDERR, "Derivate (".$match[1].")\n");
          $derivateid=$match[1];
          // To DO $types[] = 'fulltext';
          $derivate=$this->MyCoReDerivateFactory->create($derivateid);
          $reposasLogline->Identifier = $derivateid;
          $objectid = $derivate->objectid;
          $object=$this->MyCoReObjectFactory->create($objectid);
          $reposasLogline->Identifier = array_merge($object->getAllIdentifier(),$reposasLogline->Identifier);
          //Add URN
          $urn = $derivate->urn;
          if ($urn) $reposasLogline->Identifier = $urn;
      } elseif (preg_match('/\/rsc\/pdf\/([^\/]+_derivate_[0-9]+)[?]pages=1-\d+$/', $path, $match)) {
          fwrite(STDERR, "Match - PDF Download:".$path."\n");
          fwrite(STDERR, "Derivate (".$match[1].")\n");
          $derivateid=$match[1];
          // TO DO Subjects $types[] = 'fulltext';
          $derivate=$this->MyCoReDerivateFactory->create($derivateid);
	  $reposasLogline->Identifier = $derivateid;
          $objectid = $derivate->objectid;
          $object=$this->MyCoReObjectFactory->create($objectid);
          $reposasLogline->Identifier = array_merge($object->getAllIdentifier(),$reposasLogline->Identifier);
          //Add URN
          $urn = $derivate->urn;
          if ($urn) $reposasLogline->Identifier = $urn; 
      } else {
          return false;
      }
   }
}
