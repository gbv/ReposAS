<?php

namespace epusta;

class ConvertedLogline extends ApacheLogline
{
    public $identifier;
    public $uuid;
    public $sessionId;
    public $subjects;

    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        $str = $this->uuid . " ";
        $str .= parent::__toString();
        $str .= " ";
        $str .= $this->sessionId . " ";
        $str .= json_encode($this->identifier) . " ";
        $str .= json_encode($this->subjects) . "";

        return $str;
    }

    public function convertLogline($line)
    {
        $out = $this->uuid . " ";
        $out .= $line . " "; // Copy of the Original line
        $out .= "- ";       // SessionID
        $out .= "[] ";      // Identifier
        $out .= "[] ";

        return $out;
    }
}
