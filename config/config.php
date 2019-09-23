<?php
$config=array(
  
    // Logfiles of the logfileparser
    'logdir'=>'/mcr/repper/reposas/logfileparser/logs',

    // Directory where the accesslogs are located
    'accesslogdir'=>'/mcr/repper/reposas/accesslogs',

    // Enable parsing old MIR logs before css load in deltailview
    'oldMirLogs' => false,

    // get-method for metadata (file - access datadir, http - uses recive and Restapi)
    'getmethod' => 'file',

    // Datadir - location of XML metadata
    'datadir' => '/mcr/repper/data'

  );
