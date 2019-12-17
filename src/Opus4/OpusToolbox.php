<?php

namespace ReposAS\Opus4;

class OpusToolbox
{
    public function addIdentifier(& $convertedLogline, $praefix=Null)
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
                $this->$value($path, $convertedLogline, $praefix);
                $convertedLogline->identifier = array_unique($convertedLogline->identifier);
                if ($convertedLogline->httpMethod == 'GET')
                {
                    $convertedLogline->subjects = array_unique($convertedLogline->subjects);
                } elseif ($convertedLogline->httpMethod == 'HEAD') {
                    $convertedLogline->subjects = ["oas:content:counter_head"];
                }
        }

    }

    public function ruleDownload($path, & $convertedLogline, $praefix=Null)
    {
        if ($praefix == Null)
        {
            if (preg_match("|/([^/]+)/frontdoor/deliver/index/docId/([0-9]+)/file/([A-Za-z0-9.]+)|", $path, $match)) {
                $convertedLogline->subjects[] = "oas:content:counter";
                $convertedLogline->identifier[] = $match[1] . "-" . $match[2];
            }
        } else {
            if (preg_match("|/frontdoor/deliver/index/docId/([0-9]+)/file/([A-Za-z0-9.]+)|", $path, $match)) {
            $convertedLogline->subjects[] = "oas:content:counter";
            $convertedLogline->identifier[] = $praefix . "-" . $match[1];
            }
        }
    }

    public function ruleFrontdoorAccess($path, & $convertedLogline, $praefix=Null)
    {
        if ($praefix == Null)
        {
            if (preg_match("|/([^/]+)/frontdoor/index/.*/docId/([0-9]+)|", $path, $match)) {
                $convertedLogline->subjects[] = "oas:content:counter_abstract";
                $convertedLogline->identifier[] = $match[1] . "-" . $match[2];
            }
        } else {
            if (preg_match("|/frontdoor/index/.*/docId/([0-9]+)|", $path, $match)) {
                $convertedLogline->subjects[] = "oas:content:counter_abstract";
                $convertedLogline->identifier[] = $praefix . "-" . $match[1];
            }
        }
    }

    public function ruleCssAccess($path, & $convertedLogline, $praefix=Null)
    {
        if ($praefix == Null)
        {
            if (preg_match("|/([^/]+)/.+.css$|", $path, $match)) {
                $convertedLogline->subjects[] = "oas:content:counter_css";
                $convertedLogline->identifier[] = $match[1];
            }
        } else {
            if (preg_match("|.css$|", $path, $match)) {
                $convertedLogline->subjects[] = "oas:content:counter_css";
                $convertedLogline->identifier[] = $praefix;
            }
        }
    }
}
