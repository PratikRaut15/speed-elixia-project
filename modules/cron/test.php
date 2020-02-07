<?php 
    $RELATIVE_PATH_DOTS = "../../";
    require_once "../../lib/system/utilities.php";
    require_once '../../lib/autoload.php';
    if(isset($_POST)){
        pr('Received from POST');
        p($_POST);
    }
    if(isset($_GET)){
        p($_GET);
    }
    


?>