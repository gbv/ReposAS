<?php
namespace ReposAS;

class ReposasLogline extends Logline{
    public $Identifier;
    public $UUID;
    public $SessionID;
    public $Subjects;

    function __construct() {
    }

    public function __toString() {
        $str=$this->UUID." ";
        $str.=parent::__toString();
        $str.=" ";
        $str.=$this->SessionID." ";
        $str.=json_encode($this->Identifier)." ";
        $str.=json_encode($this->Subjects)." ";

        return $str;
    }
}
