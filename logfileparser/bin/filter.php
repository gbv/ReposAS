#!/usr/bin/php
<?php

require_once __DIR__.'/../config.php';
require_once __DIR__.'/lib/reposas-loglinepaser.php';
require_once __DIR__.'/lib/reposas-filter-robots.php';
require_once __DIR__.'/lib/counter3-filter-30sek.php';
require_once __DIR__.'/lib/reposas-filter-httpStatus.php';
require_once __DIR__.'/lib/reposas-filter-httpMethod.php';

$reposasLoglineParser = new ReposasLogfileParser();
$reposasFilterRobots = new ReposasFilterRobots ();
$counter3Filter30sek = new Counter3Filter30sek ();
$reposasFilterHttpStatus = new ReposasFilterHttpStatus ();
$reposasFilterHttpMethod = new ReposasFilterHttpMethod ();

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logLine=new ReposasLogline();
        if ( $reposasLoglineParser->parse($line, $logLine)) {
            $reposasFilterRobots->edit($logLine);
            $counter3Filter30sek->edit($logLine);
            $reposasFilterHttpStatus->edit($logLine);
            $reposasFilterHttpMethod->edit($logLine);
            echo ($logLine."\n");
        } else {
            //die("Error: malformed Logline".$line."\n");
            // TO DO Goog logging
        }
    }
}

?>
