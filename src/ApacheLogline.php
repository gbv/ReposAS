<?php

namespace ReposAS;

class ApacheLogline
{
    public $ip;
    public $remoteLogname;
    public $remoteUser;
    public $time;
    public $httpMethod;
    public $url;
    public $httpProtokol;
    public $httpStatusCode;
    public $sizeOfResponse;
    public $referer;
    public $userAgent;

    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function __toString()
    {
        $str = $this->ip . " ";
        $str .= $this->remoteLogname . " ";
        $str .= $this->remoteUser . " ";
        $str .= '[' . $this->time . "] ";
        $str .= '"' . $this->httpMethod . " ";
        $str .= $this->url . " ";
        $str .= $this->httpProtokol . '" ';
        $str .= $this->httpStatusCode . ' ';
        $str .= $this->sizeOfResponse . " ";
        $str .= '"' . $this->referer . '" ';
        $str .= '"' . $this->userAgent . '"';

        return $str;
    }
}
