#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$convertedLoglineParser = new ReposAS\ConvertedLoglineParser();
$opusToolbox = new ReposAS\Opus4\OpusToolbox();
if($argv > 1)
{
    $praefix = $argv[1];
} else {
    $praefix = NULL;
}


while (!feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline = new ReposAS\ConvertedLogline();
        if ($convertedLoglineParser->parse($line, $logline)) {
            $opusToolbox->addIdentifier($logline, $praefix);
            echo($logline . "\n");
        }
    }
}
