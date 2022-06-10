<?php

namespace epusta\mir;

class MyCoReObject
{

    public $objectids = null;
    public $parentids = null;
    public $subjects = null;

    public function __construct($objectids, $parentids,$subjects)
    {
        $this->objectids = (is_array($objectids)) ? $objectids : [];
        $this->parentids = (is_array($parentids)) ? $parentids : [];
        $this->subjects = (is_array($subjects)) ? $subjects : [];
    }

    public function getAllIdentifier()
    {
        return array_merge($this->objectids, $this->parentids);
    }
    
    public function getObjectIdentifier()
    {
        return $this->objectids;
    }
    
    public function getParentIdentifier()
    {
        return $this->parentids;
    }

    public function getSubjects()
    {
        return $this->subjects;
    }
}
