<?php

namespace epusta;

class FilterHttpStatus
{
    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function edit(& $convertedLogline)
    {
        $httpStatus = $convertedLogline->httpStatusCode;
        
        if (! ($httpStatus == 200 || $httpStatus == 202 || $httpStatus == 206)) {
            $convertedLogline->subjects[] = "epusta:filter:httpStatus";
        }
    }
}
