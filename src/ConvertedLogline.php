<?php

namespace ReposAS;

class ConvertedLogline extends ApacheLogline
{
    public $identifier;
    public $uuid;
    public $sessionId;
    public $subjects;
    private $format;

    // TODO Why is this empty?
    // TODO: Maybe combine with parser?
    public function __construct()
    {
        $this->createFormatExpression();
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

    private function createFormatExpression()
    {
        // Regular expression for the apache logline created by format
        // %{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"
        $regExp = '/^';
        //$regExp.='(-|\d+\.\d+\.\d+\.\d+|([A-F0-9]{1,4}:){7}[A-F0-9]{1,4}) ';      // IP Adress
        //$regExp.='(unknown|-|\d+\.\d+\.\d+\.\d+(, unknown)?|([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}) ';      // IP Adress
        $regExp .= '(.*) ';      // IP Adress
        $regExp .= '(.*) ';                            // Remote logname
        $regExp .= '.* ';                              // Remote user
        $regExp .= '\[(.*)\] ';                        // Time the request was received
        $regExp .= '"(.*) (.*) HTTP\/[1,2]\.[0,1]" ';  // http Method, request URL,
        $regExp .= '(\d\d\d) ';                        // http Status Code
        $regExp .= '([0-9-]+) ';                       // Size of response in bytes
        $regExp .= '"(.*)" ';                          // Referer
        $regExp .= '"(.*)"';                           // User Agent
        $regExp .= '/';

        $this->format = $regExp;
    }

    public function checkFormat($line)
    {
        if (!preg_match($this->format, $line, $treffer)){
            $logmsg = "Can't parse logline (wrong logformat?):\n";
            $logmsg .= "    " . $line . "\n";
            $logmsg .= "    " . $this->format . "";

            return $logmsg;
        }

        return True;
    }
}
