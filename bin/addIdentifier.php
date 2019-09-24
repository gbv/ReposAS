#!/usr/bin/php
<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config/config.php';

$reposasLoglineParser=new ReposAS\ReposasLogfileParser();
$mirToolBox=new ReposAS\MIRToolbox ($config);

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logLine=new ReposAS\ReposasLogline();
        if ( $reposasLoglineParser->parse($line, $logLine)) {
          $mirToolBox->addIdentifier($logLine);
          echo ($logLine."\n");
        } else {
            //die("Error: malformed Logline".$line."\n");
            // TO DO Goog logging
        }
    }
}
