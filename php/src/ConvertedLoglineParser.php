<?php

namespace ReposAS;

class ConvertedLoglineParser
{
    public $regExp;

    //private $SubLoglineparser;

    public function __construct()
    {
        //$SubLoglineparser=$subLoglineParser;

        $this->regExp = '([^ ]*) ';
        //$this->RegExp.='(unknown|-|\d+\.\d+\.\d+\.\d+(, unknown)?|([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}) ';     // IP Adress
        $this->regExp .= '([^ ]*) ';                                 // IP Adress
        $this->regExp .= '([^ ]*) ';                                 // Remote logname
        $this->regExp .= '([^ ]*) ';                                 // Remote user
        $this->regExp .= '\[([^\]]*)\] ';                            // Time the request was received
        $this->regExp .= '"([^ ]*) ([^ ]*) (HTTP\/[1,2]\.[0,1])" ';  // http Method, request URL, http Protokoll
        $this->regExp .= '(\d\d\d) ';                                // http Status Code
        $this->regExp .= '([0-9-]+) ';                               // Size of response in bytes
        $this->regExp .= '"([^"]*)" ';                               // Referer
        $this->regExp .= '"([^"]*)"';                             // User Agent
        $this->regExp .= ' ';
        $this->regExp .= '(.*) ';                                    // SessionID
        $this->regExp .= '(\[[^\]]*\]) ';                            // Identifier
        $this->regExp .= '(\[[^\]]*\])';                             // Subjects
    }

    public function parse($line, & $logline)
    {
        $logline = new ConvertedLogline;
        $regExp2 = '/^' . $this->regExp . '/';

        if (! $logline) {
            $logline = new ApacheLogline();
            echo "Error keine  ApacheLogline\n";
        }

        $line2 = $line;
        $line2 = str_replace('"\"', '"', $line2);
        $line2 = str_replace('\""', '"', $line2);

        if (preg_match($regExp2, $line2, $treffer)) {
            $logline->uuid = trim($treffer[1]);
            $logline->ip = trim($treffer[2]);
            $logline->remoteLogname = trim($treffer[3]);
            $logline->remoteUser = trim($treffer[4]);
            $logline->time = trim($treffer[5]);
            $logline->httpMethod = trim($treffer[6]);
            $logline->url = trim($treffer[7]);
            $logline->httpProtokol = trim($treffer[8]);
            $logline->httpStatusCode = trim($treffer[9]);
            $logline->sizeOfResponse = trim($treffer[10]);
            $logline->referer = trim($treffer[11]);
            $logline->userAgent = trim($treffer[12]);
            $logline->sessionId = trim($treffer[13]);
            $logline->identifier = json_decode(trim($treffer[14]), true);
            $logline->subjects = json_decode(trim($treffer[15]), true);

            return true;
        } else {
            if (preg_split('/ /', $line)[7] == '408') {return false;}
            fwrite(STDERR, "Error: can't parse ApacheLogline:\n");
            fwrite(STDERR, "    " . $line2 . "\n");
            fwrite(STDERR, "    " . $regExp2 . "\n");

            return false;
        }
    }
}
