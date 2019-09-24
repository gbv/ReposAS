<?php
namespace ReposAS;

class ReposasFilterRobots {

    var $Robots=null;
    var $Robots_file_name=__DIR__.'/COUNTER_Robots_list.json';

    function __construct() {
        $Robots_file=file_get_contents($this->Robots_file_name);
        $this->Robots= json_decode($Robots_file,true);
    }

    function edit(& $reposasLogline) {
        $agent = $reposasLogline->UserAgent;
        foreach ($this->Robots as $Robot) {
            $regex='/'.$Robot["pattern"].'/';
            if (preg_match($regex, $agent, $treffer)) {
                $reposasLogline->Subjects[]="filter:robot";
                if (in_array("oas:content:counter",$reposasLogline->Subjects)) {
                    if (($key = array_search("oas:content:counter", $reposasLogline->Subjects)) !== false) {
                        unset($reposasLogline->Subjects[$key]);
                    }
                    $reposasLogline->Subjects=array_values($reposasLogline->Subjects);
                    $reposasLogline->Subjects[]="oas:content:robots";
                } 
                if (in_array("oas:content:counter_abstract",$reposasLogline->Subjects)) {
                    if (($key = array_search("oas:content:counter_abstract", $reposasLogline->Subjects)) !== false) {
                        unset($reposasLogline->Subjects[$key]);
                    }
                    $reposasLogline->Subjects=array_values($reposasLogline->Subjects);
                    $reposasLogline->Subjects[]="oas:content:robots_abstract";
                }
            }
        }
    }
}
