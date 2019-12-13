<?php

class Counter3Filter30sek {

    var $lastHits = array();

    function __construct() {
    }

    function edit(& $reposasLogline) {
        $ip=$reposasLogline->IP;
        $path=$reposasLogline->URL;
        $time=$reposasLogline->Time;
        $identifier=$reposasLogline->Identifier;
        $unixtime=strtotime($time);
        // delete old entrys
        while (count($this->lastHits) > 0 && $unixtime - key($this->lastHits) > 30) {
            array_shift($this->lastHits);
        }
        // Find duplicate entry
        foreach ($this->lastHits as $lastHitsForSec) {
            foreach ($lastHitsForSec as $lastHit) {
               if ($lastHit['ip'] == $ip && 
                        ($lastHit['path']==$path || 
                            ( count($identifier) > 0 && count(array_diff($identifier,$lastHit['identifier'])) == 0)
                        )
                  ) {
                    $reposasLogline->Subjects[]="filter:30sek:counter3";
               }
            }
        }
        if (! isset($this->lastHits[$unixtime])) $this->lastHits[$unixtime]=array();
        $thisHit=array();
        $thisHit['ip']=$ip;
        $thisHit['path']=$path;
        $thisHit['identifier']=$identifier;
        $this->lastHits[$unixtime][]=$thisHit;
        
    }
}
?>
