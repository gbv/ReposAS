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

    /**
     * Anonymize the IP-Address. This is very important it the context of data-security
     */
    public function anonymizeIp()
    {

        if(filter_var($this->ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV4))
        {
            $ipTemp = explode('.', $this->ip);
            $ipTemp[2] = 'XXX';
            $ipTemp[3] = 'XXX';
            $this->ip = implode('.', $ipTemp);
        } elseif(filter_var($this->ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)) {
            $ipTemp = explode(':', $this->ip);
            $ipTemp[6] = 'XXXX';
            $ipTemp[7] = 'XXXX';
            $this->ip = implode(':', $ipTemp);
        }
    }
}
