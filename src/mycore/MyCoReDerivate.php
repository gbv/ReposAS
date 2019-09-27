<?php
namespace ReposAS\mycore;

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
