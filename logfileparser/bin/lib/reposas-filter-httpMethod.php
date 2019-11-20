<?php
class ReposasFilterHttpMethod {


    function __construct() {
    }

    function edit(& $reposasLogline) {
        $httpMethod = $reposasLogline->HttpMethod;
        if ($httpMethod !=  'GET' ) {
            $reposasLogline->Subjects[]="reposas:filter:httpMethod";
        }
    }
}
?>
