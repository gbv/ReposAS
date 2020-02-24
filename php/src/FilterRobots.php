<?php

namespace epusta;

class FilterRobots
{

    public $robots = null;
    public $robotsFileName = __DIR__ . '/../config/COUNTER_Robots_list.json';

    public function __construct()
    {
        $robotsFile = file_get_contents($this->robotsFileName);
        $this->robots = json_decode($robotsFile, true);
    }

    public function edit(& $convertedLogline)
    {
        $agent = $convertedLogline->userAgent;
        foreach ($this->robots as $robot) {
            $regex = '/' . $robot["pattern"] . '/';
            if (preg_match($regex, $agent, $treffer)) {
                $convertedLogline->subjects[] = "filter:robot";

                if (in_array("oas:content:counter", $convertedLogline->subjects)) {
                    if (($key = array_search("oas:content:counter", $convertedLogline->subjects)) !== false) {
                        unset($convertedLogline->subjects[$key]);
                    }

                    $convertedLogline->subjects = array_values($convertedLogline->subjects);
                    $convertedLogline->subjects[] = "oas:content:robots";
                }

                if (in_array("oas:content:counter_abstract", $convertedLogline->subjects)) {
                    if (($key = array_search(
                        "oas:content:counter_abstract",
                        $convertedLogline->subjects
                    )) !== false) {
                        unset($convertedLogline->subjects[$key]);
                    }

                    $convertedLogline->subjects = array_values($convertedLogline->subjects);
                    $convertedLogline->subjects[] = "oas:content:robots_abstract";
                }
            }
        }
    }
}
