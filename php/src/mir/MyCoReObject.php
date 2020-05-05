<?php

namespace epusta\mir;

class MyCoReObject
{

    public $objectids = null;
    public $parentids = null;

    public function __construct($objectids, $parentids)
    {
        $this->objectids = (is_array($objectids)) $objectids : [];
        $this->parentids = (is_array($parentids)) $parentids : [];
        ;
    }

    public function getAllIdentifier()
    {
        return array_merge($this->objectids, $this->parentids);
    }
}
