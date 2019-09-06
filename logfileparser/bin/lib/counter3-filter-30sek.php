<?php

class Counter3Filter30sek {

    var $lastHits = array();

    function __construct() {
    }

    function edit(& $reposasLogline) {
        $ip=$reposasLogline->IP;
        $path=$reposasLogline->URL;
        $time=$reposasLogline->Time;
        $unixtime=strtotime($time);
        // delete old entrys
        while (count($this->lastHits) > 0 && $unixtime - key($this->lastHits) > 30) {
            array_shift($this->lastHits);
        }
        // Find duplicate entry
        foreach ($this->lastHits as $lastHit) {
            if ($lastHit['ip'] == $ip && $lastHit['path']==$path) {
                $reposasLogline->Subjects[]="filter:30sek:counter3";
            }
        }
        $this->lastHits[$unixtime]['ip']=$ip;
        $this->lastHits[$unixtime]['path']=$path;
    }
}
?>
