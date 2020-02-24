<?php

namespace ReposAS;

class FilterHttpStatus
{
    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function edit(& $convertedLogline)
    {
        $httpStatus = $convertedLogline->httpStatusCode;
        //TODO: Why 2 times the same http status?
        if (! ($httpStatus == 200 || $httpStatus == 202 || $httpStatus == 202)) {
            $convertedLogline->subjects[] = "reposas:filter:httpStatus";
        }
    }
}
