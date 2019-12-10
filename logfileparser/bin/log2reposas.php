#!/usr/bin/php
<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config.php';

//use Monolog\Logger;
//use Monolog\Handler\StreamHandler;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

//$logger = new Logger('log2reposas');
//$logger->pushHandler(new StreamHandler($config['logdir'].'/log2reposas.log', Logger::DEBUG));


// Regular expression for the apache logline created by format
// %{X-Forwarded-For}i %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\" 
$ReExp ='/^';
//$ReExp.='(-|\d+\.\d+\.\d+\.\d+|([A-F0-9]{1,4}:){7}[A-F0-9]{1,4}) ';      // IP Adress 
//$ReExp.='(unknown|-|\d+\.\d+\.\d+\.\d+(, unknown)?|([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}) ';      // IP Adress
$ReExp.='(.*) ';      // IP Adress
$ReExp.='(.*) ';                            // Remote logname
$ReExp.='.* ';                              // Remote user
$ReExp.='\[(.*)\] ';                        // Time the request was received
$ReExp.='"(.*) (.*) HTTP\/[1,2]\.[0,1]" ';  // http Method, request URL, 
$ReExp.='(\d\d\d) ';                        // http Status Code
$ReExp.='([0-9-]+) ';                       // Size of response in bytes
$ReExp.='"(.*)" ';                          // Referer
$ReExp.='"(.*)"';                           // User Agent
$ReExp.='/';


while (! feof(STDIN)) {
  if ($line = trim(fgets(STDIN))) {
    try {
      $uuid = Uuid::uuid4();
    } catch (UnsatisfiedDependencyException $e) {
      // Some dependency was not met. Either the method cannot be called on a
      // 32-bit system, or it can, but it relies on Moontoast\Math to be present.
      echo 'Caught exception: ' . $e->getMessage() . "\n";
    }

    if (! preg_match($ReExp, $line, $treffer)) {
      $logmsg="Can't parse logline (wrong logformat?):\n";
      $logmsg.="    ".$line."\n";
      $logmsg.="    ".$ReExp."";
      //$logger->error($logmsg);
      die ("Error: ".$logmsg."\n");
    }
    // Qutput reposas format
    $outline = $uuid." ";
    $outline.= $line." ";  // Copy of the Original line
    $outline.= "- ";       // SessionID
    $outline.= "[] ";      // Identifier
    $outline.= "[] ";      // Subjects
    fputs(STDOUT,$outline."\n");

  }
}

