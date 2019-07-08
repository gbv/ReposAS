#!/usr/bin/php
<?php


$Robots_file=file_get_contents('COUNTER_Robots_list.json');
$Robots= json_decode($Robots_file,true);
//stream_set_blocking(STDIN, 0);

$lastHits = array();

while (! feof(STDIN)) {
    if ($line = trim(fgets(STDIN))) {
        // Example Logline
        // {3e859a6f-5eec-43a3-8a43-e9385fe81c7f} 141.8.183.19 www.gbv.de - [02/Jan/2017:06:36:26 +0100] "GET /dms/bowker/toc/9780521420365.pdf HTTP/1.1" 200 2250 "-" "Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)"	
        if (preg_match('/^\{(.*)\} (\d+)\.(\d+)\.(\d+)\.(\d+) (.*) .* \[(.*)\] "(GET|HEAD|PUT|POST|DELETE|OPTIONS|REPORT) (.*) HTTP\/[1,2]\.[0,1]" (\d\d\d) ([0-9-]+) ".*" "(.*)" (\[.*\]) \{\} (\[.*\])/', $line, $treffer)) {
            //Get Identifier
            $uuid = trim($treffer[1]);
            $ip = $treffer[2].".".$treffer[3].".".$treffer[4].".".$treffer[5];
            $time =  $treffer[7];
            $httpMethod = $treffer[8];
            $path = trim($treffer[9]);
            $httpStatus = $treffer[10];
            if (!($httpStatus == "200" || $httpStatus == "201" || $httpStatus == "304")|| $httpMethod != "GET") {
              fgets(STDIN);
              continue;
            }
            //if (strpos($path,"toc") === false ) continue;
            $agent = trim($treffer[12]);
            //$lpline = trim(fgets(STDIN));
            //if (preg_match('/^\{(.*)\} LP - (\[.*\]) (\[.*\])/', $lpline, $treffer)) {
            //} else {
            //  die("Error: malformed LP-line:".$lpline."\n");
            //} 
            $Filter=array();
            foreach ($Robots as $Robot) {
              $regex='/'.$Robot["pattern"].'/';
              //echo $regex."\n";
              if (preg_match($regex, $agent, $treffer)) $Filter[]="filter:host";
            }

            $unixtime=strtotime($time);
            // delete old entrys
            while (count($lastHits) > 0 && $unixtime - key($lastHits) > 30) {
              array_shift($lastHits);
            }
            // Find duplicate entry
            foreach ($lastHits as $lastHit) {
              if ($lastHit['ip'] == $ip && $lastHit['path']==$path) $Filter[]="filter:30sek";
            }
            $lastHits[$unixtime]['ip']=$ip;
            $lastHits[$unixtime]['path']=$path;

            fputs(STDOUT,$line);
            fputs(STDOUT," ".json_encode($Filter)."\n");
            // Find duplicate entry
            
        } else {
            die("Error: malformed Logline".$line."\n");
        }

    }
}

