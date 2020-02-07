<?php
$todo = isset($_POST['todo']) ? $_POST['todo'] : '';
include_once 'exception_functions.php';

if($todo=='addException'){
    add_exception();
}
elseif($todo=='deleteEception'){
    delete_exception();
}
elseif($todo=='getException'){
    $userid = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['userid'];
    $userid = (int) $userid;
    $existing_exceptions = get_exception_alerts('table',$userid);
    if(isset($existing_exceptions[0])){
        echo $existing_exceptions[0];
    }
    exit;
}
?>
