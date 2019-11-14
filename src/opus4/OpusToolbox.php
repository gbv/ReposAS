<?php
/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 13.11.19
 * Time: 17:58
 */

namespace ReposAS\opus4;

class OpusToolbox
{
    public function addIdentifier(& $convertedLogline)
    {
        $path = $convertedLogline->url;

        /**
         * Add functionality for OPUS4.
         *
         * There are two cases:
         * The first is a frontdoor access. This is tagged with content:counter_abstract and is represented by .../frontdoor/index/...
         * The second is a download. This is tagged with content:counter and represented by .../frontdoor/deliver/... or .../files/...
         * In the logfile there are also other layout-accesses. We decided to not tag this kind of access,
         * because this is not an access of interest.
         */
        $method_names = preg_grep('/^rule/', get_class_methods($this));
        foreach ($method_names as $value) {
            $this->$value($path, $convertedLogline);
            $convertedLogline->identifier = array_unique($convertedLogline->identifier);
            $convertedLogline->subjects = array_unique($convertedLogline->subjects);
        }
    }

    public function ruleDownload($path, $convertedLogline)
    {
        if (preg_match("|/(opus4-[^/]+)/frontdoor/deliver/index/docId/([0-9]+)/file/([A-Za-z0-9.]+)|", $path, $match)) {
            $convertedLogline->subjects[] = "oas:content:counter";
            $convertedLogline->identifier[] = $match[1] . "-" . $match[2];
        }
    }

    public function ruleFrontdoorAccess($path, $convertedLogline)
    {
        if (preg_match("|/(opus4-[^/]+)/frontdoor/index/.*/docId/([0-9]+)|", $path, $match)) {
            $convertedLogline->subjects[] = "oas:content:counter_abstract";
            $convertedLogline->identifier[] = $match[1] . "-" . $match[2];
        }
    }
}
