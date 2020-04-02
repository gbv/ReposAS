#!/usr/bin/php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$configuration = new \epusta\Configuration();
$config = $configuration->getConfig();

//use Monolog\Handler\StreamHandler;
//use Monolog\Logger;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use epusta\ConvertedLogline;

//$logger = new Logger('log2epusta');
//$logger->pushHandler(new StreamHandler($config['logdir'] . '/log2epusta.log', Logger::DEBUG));


while (!feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        $logline = new ConvertedLogline();
        try {
            $logline->uuid = Uuid::uuid4();
        } catch (UnsatisfiedDependencyException $e) {
            // Some dependency was not met. Either the method cannot be called on a
            // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }

        $message = $logline->checkFormat($line);
        if ($message != True) {
            //$logger->error($message);
            die ("Error: " . $message . "\n");
        }

        echo($logline->convertLogline($line) . "\n");
    }
}
