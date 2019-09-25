#!/usr/bin/php
<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

$reposasLoglineParser=new ReposAS\ReposasLogfileParser();

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logLine=new ReposAS\ReposasLogline();
        if ( $reposasLoglineParser->parse($line, $logLine)) {
            $str='{ "uuid": "'.$logLine->UUID.'"';
            $str.=', "identifier":'.json_encode($logLine->Identifier);
            $time = new DateTime($logLine->Time);
            $str.=', "dateTime":"'.$time->format(DateTime::ISO8601).'"';
            $str.=', "subjects":'.json_encode($logLine->Subjects);
            $str.='}';
            echo $str."\n";
        } else {
            //die("Error: malformed Logline - abort Processing.\n");
        }
    }
}
