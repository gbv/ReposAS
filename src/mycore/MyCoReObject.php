<?php
namespace ReposAS\mycore;

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
        if ($this->objectid) $ret[]=$this->objectid;
        if ($this->urn) $ret[]=$this->urn;
        if ($this->doi) $ret[]="doi:".$this->doi;
        return $ret;

    }
}
