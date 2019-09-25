#!/usr/bin/php
<?php

require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../vendor/autoload.php';

$reposasLoglineParser= new ReposAS\ReposasLogfileParser();
$reposasFilterRobots= new ReposAS\ReposasFilterRobots ();
$counter3Filter30sek = new ReposAS\Counter3Filter30sek ();

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logLine=new ReposAS\ReposasLogline();
        if ( $reposasLoglineParser->parse($line, $logLine)) {
            $reposasFilterRobots->edit($logLine);
            $counter3Filter30sek->edit($logLine);
            echo ($logLine."\n");
        } else {
            //die("Error: malformed Logline".$line."\n");
            // TO DO Goog logging
        }
    }
}
