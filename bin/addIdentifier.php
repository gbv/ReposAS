#!/usr/bin/php
<?php

require_once __DIR__.'/../vendor/autoload.php';

$config = \ReposAS\Configuration::getConfig();
$convertedLoglineParser=new ReposAS\ConvertedLoglineParser();
$mirToolbox=new ReposAS\MIRToolbox($config);

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline=new ReposAS\ConvertedLogline();
        if ( $convertedLoglineParser->parse($line, $logline)) {
          $mirToolbox->addIdentifier($logline);
          echo ($logline."\n");
        } else {
            //die("Error: malformed ApacheLogline".$line."\n");
            // TO DO Goog logging
        }
    }
}
