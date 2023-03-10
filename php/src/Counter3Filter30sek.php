<?php

namespace epusta;

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
        $identifier = $convertedLogline->identifier;
        $unixtime = strtotime($time);

        // delete old entrys
        while (count($this->lastHits) > 0 && $unixtime - key($this->lastHits) > 30) {
            array_shift($this->lastHits);
        }
        // Find duplicate entry
        foreach ($this->lastHits as $lastHitsForSec) {
            foreach ($lastHitsForSec as $lastHit) {
                if ($lastHit['ip'] == $ip &&
                    ($lastHit['path'] == $path ||
                        (count($identifier) > 0 && count(array_diff($identifier, $lastHit['identifier'])) == 0)
                    )
                ) {
                    $convertedLogline->subjects[] = "filter:30sek:counter3";
                }
            }
        }

        if (! isset($this->lastHits[$unixtime])) {
            $this->lastHits[$unixtime] = [];
        }
        $thisHit = [];
        $thisHit['ip'] = $ip;
        $thisHit['path'] = $path;
        $thisHit['identifier'] = $identifier;
        $this->lastHits[$unixtime][] = $thisHit;
    }
}
