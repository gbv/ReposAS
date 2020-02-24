<?php

namespace epusta\Mycore;

class Derivate
{

    public $derivateid = null;
    public $objectid = null;
    public $maindoc = null;
    // In MIR LTS2016 URN stored in derivate
    public $urn = null;

    public function __construct($derivateid, $objectid, $maindoc, $urn)
    {
        $this->derivateid = $derivateid;
        $this->objectid = $objectid;
        $this->maindoc = $maindoc;
        $this->urn = $urn;
    }

    public function getAllIdentifier()
    {
        $ret = [];
        if ($this->derivateid) {
            $ret[] = $this->derivateid;
        }
        if ($this->urn) {
            $ret[] = $this->urn;
        }
        return $ret;
    }
}
