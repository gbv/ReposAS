<?php



class ReposasFilterHttpStatus {


    function __construct() {
    }

    function edit(& $reposasLogline) {
        $httpStatus = $reposasLogline->HttpStatusCode;
        if (! ($httpStatus == 200 || $httpStatus == 202 || $httpStatus == 202) ) {
            $reposasLogline->Subjects[]="reposas:filter:httpStatus";
        }
    }
}
