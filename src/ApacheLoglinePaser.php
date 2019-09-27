<?php

namespace ReposAS;

class ApacheLoglinePaser
{

    public $RegExp;

    public function __construct()
    {
        $RegExp = '(unknown|-|\d+\.\d+\.\d+\.\d+(, unknown)?|([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}) ';      // IP Adress
        $RegExp .= '(.*) ';                            // Remote logname
        $RegExp .= '.* ';                              // Remote user
        $RegExp .= '\[(.*)\] ';                        // Time the request was received
        $RegExp .= '"(.*) (.*) (HTTP\/[1,2]\.[0,1])" ';  // http Method, request URL,
        $RegExp .= '(\d\d\d) ';                        // http Status Code
        $RegExp .= '[0-9-]+ ';                         // Size of response in bytes
        $RegExp .= '"(.*)" ';                          // Referer
        $RegExp .= '"(.*)"';                           // User Agent
    }

    public function parse($line, & $logline)
    {
        $RegExp2 = '/^' . $this->RegExp . '/';
        if (!$logline) {
            $logline = new Logline();
            echo "Error keine  Logline\n";
        }
        if (preg_match($RegExp2, $line, $treffer)) {
            $logline->IP = trim($treffer[1]);

            return true;
        } else {
            return false;
        }
    }
}
