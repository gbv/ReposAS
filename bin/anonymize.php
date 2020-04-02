#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$convertedLoglineParser = new epusta\ConvertedLoglineParser();

while (!feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline = new epusta\ConvertedLogline();
        if ($convertedLoglineParser->parse($line, $logline)) {
            $logline->anonymizeIp();
            echo($logline . "\n");
        }
    }
}
