<?php

namespace epusta;

class FilterHttpMethod
{
    public function __construct()
    {
    }

    public function edit(& $convertedLogline)
    {
        $httpMethod = $convertedLogline->httpMethod;
        if ($httpMethod != 'GET') {
            $convertedLogline->subjects[] = "epusta:filter:httpMethod";
        }
    }
}
