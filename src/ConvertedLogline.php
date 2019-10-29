<?php

namespace ReposAS;

class ConvertedLogline extends ApacheLogline
{
    public $identifier;
    public $uuid;
    public $sessionId;
    public $subjects;

    // TODO Why is this empty?
    // TODO: Maybe combine with parser?
    public function __construct()
    {
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
}
