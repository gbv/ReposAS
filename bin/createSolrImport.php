#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$convertedLoglineParser = new epusta\ConvertedLoglineParser();

while (!feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline = new epusta\ConvertedLogline();
        if ($convertedLoglineParser->parse($line, $logline)) {
            $str = '{ "uuid": "' . $logline->uuid . '"';
            $str .= ', "identifier":' . json_encode($logline->identifier);
            $time = new DateTime($logline->time);
            $str .= ', "dateTime":"' . $time->format(DateTime::ISO8601) . '"';
            $str .= ', "subjects":' . json_encode($logline->subjects);
            $str .= '}';
            echo $str . "\n";
        } else {
            // die("Error: malformed Logline - abort Processing.\n");
        }
    }
}
