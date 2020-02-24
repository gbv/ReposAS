<?php

namespace epusta;

class FilterHttpMethod
{


    public function __construct()
    {
    }

    public function edit(& $convertedLogline)
    {
        $httpMethod = $convertedLogline->HttpMethod;
        if ($httpMethod != 'GET') {
            $convertedLogline->Subjects[] = "epusta:filter:httpMethod";
        }
    }
}
