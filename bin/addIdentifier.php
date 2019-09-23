#!/usr/bin/php
<?php

require_once __DIR__.'/lib/mir-identifier.php';
require_once __DIR__.'/lib/reposas-loglinepaser.php';
require_once __DIR__.'/../config.php';

$reposasLoglineParser=new ReposasLogfileParser();
$mirToolBox=new MIRToolbox ($config);

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logLine=new ReposasLogline();
        if ( $reposasLoglineParser->parse($line, $logLine)) {
          $mirToolBox->addIdentifier($logLine);
          echo ($logLine."\n");
        } else {
            //die("Error: malformed Logline".$line."\n");
            // TO DO Goog logging
        }
    }
}
