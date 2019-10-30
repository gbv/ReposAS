<?php

namespace ReposAS;

class ApacheLoglineParser
{

    public $regExp;

    public function __construct()
    {
        $regExp = '(unknown|-|\d+\.\d+\.\d+\.\d+(, unknown)?|([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}) ';      // IP Adress
        $regExp .= '(.*) ';                            // Remote logname
        $regExp .= '.* ';                              // Remote user
        $regExp .= '\[(.*)\] ';                        // Time the request was received
        $regExp .= '"(.*) (.*) (HTTP\/[1,2]\.[0,1])" ';  // http Method, request URL,
        $regExp .= '(\d\d\d) ';                        // http Status Code
        $regExp .= '[0-9-]+ ';                         // Size of response in bytes
        $regExp .= '"(.*)" ';                          // Referer
        $regExp .= '"(.*)"';                           // User Agent
    }

    public function parse($line, & $logline)
    {
        $regExp2 = '/^' . $this->regExp . '/';
        if (! $logline) {
            $logline = new ApacheLogline();
            echo "Error keine  ApacheLogline\n";
        }
        if (preg_match($regExp2, $line, $treffer)) {
            $logline->ip = trim($treffer[1]);
            $logline->anonymizeIp();

            return true;
        } else {
            return false;
        }
    }
}
