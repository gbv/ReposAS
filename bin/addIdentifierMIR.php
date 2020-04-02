#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$configuration = new \epusta\Configuration();
$config = $configuration->getConfig();
$convertedLoglineParser = new epusta\ConvertedLoglineParser();
$mirToolbox = new epusta\Mycore\MIRToolbox($config);

while (!feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline = new epusta\ConvertedLogline();
        if ($convertedLoglineParser->parse($line, $logline)) {
            $mirToolbox->addIdentifier($logline);
            echo($logline . "\n");
        } else {
            // die("Error: malformed Logline" . $line . "\n")
            // TO DO Goog logging
        }
    }
}
