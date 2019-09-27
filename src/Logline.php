<?php

namespace ReposAS;

class Logline
{
    public $IP;
    public $RemoteLogname;
    public $RemoteUser;
    public $Time;
    public $HttpMethod;
    public $URL;
    public $HttpProtokol;
    public $HttpStatusCode;
    public $SizeOfResponse;
    public $Referer;
    public $UserAgent;

    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function __toString()
    {
        $str = $this->IP . " ";
        $str .= $this->RemoteLogname . " ";
        $str .= $this->RemoteUser . " ";
        $str .= '[' . $this->Time . "] ";
        $str .= '"' . $this->HttpMethod . " ";
        $str .= $this->URL . " ";
        $str .= $this->HttpProtokol . '" ';
        $str .= $this->HttpStatusCode . ' ';
        $str .= $this->SizeOfResponse . " ";
        $str .= '"' . $this->Referer . '" ';
        $str .= '"' . $this->UserAgent . '"';

        return $str;
    }
}
