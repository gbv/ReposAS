<?php

namespace ReposAS;

class ReposasFilterHttpStatus
{
    // TODO Why is this empty?
    public function __construct()
    {
    }

    public function edit(& $reposasLogline)
    {
        $httpStatus = $reposasLogline->HttpStatusCode;
        if (! ($httpStatus == 200 || $httpStatus == 202 || $httpStatus == 202)) {
            $reposasLogline->Subjects[] = "reposas:filter:httpStatus";
        }
    }
}
