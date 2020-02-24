<?php

namespace epusta;

class FilterHttpMethod {


    function __construct() {
    }

    function edit(& $convertedLogline) {
        $httpMethod = $convertedLogline->HttpMethod;
        if ($httpMethod !=  'GET' ) {
            $convertedLogline->Subjects[]="epusta:filter:httpMethod";
        }
    }
}