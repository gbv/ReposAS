<?php

namespace ReposAS;

class Counter3Filter30sek
{

    public $lastHits = [];

    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function edit(& $convertedLogline)
    {
        $ip = $convertedLogline->ip;
        $path = $convertedLogline->url;
        $time = $convertedLogline->time;
        $unixtime = strtotime($time);

        // delete old entrys
        while (count($this->lastHits) > 0 && $unixtime - key($this->lastHits) > 30) {
            array_shift($this->lastHits);
        }

        // Find duplicate entry
        foreach ($this->lastHits as $lastHit) {
            if ($lastHit['ip'] == $ip && $lastHit['path'] == $path) {
                $convertedLogline->subjects[] = "filter:30sek:counter3";
            }
        }

        $this->lastHits[$unixtime]['ip'] = $ip;
        $this->lastHits[$unixtime]['path'] = $path;
    }
}
