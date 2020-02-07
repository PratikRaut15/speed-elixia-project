<?php
ini_set('max_input_vars', 2000);
include 'account_functions.php';
if (isset($_POST) && count($_POST) > 0) {
    if (isset($_POST['userid'])) {
        modifyuser_hierarchy($_POST);

    } else {
        include_once "../user/exception_functions.php";
        insertuser_hierarchy($_POST);
    //    sendMail($_POST);

        if($_POST['role']!='elixir' && $_POST['customerno'] != 64){
            sendMailNewUser($_POST);
        }
    }
}

function sendMail($user) {
    $to = $user['email'];
    $subject = "Test";
    $content = "Dear " . $user['name'] . "<br/>Message";

    /**
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
    // message sending failed
    return false;
    }
    return true;

     */
}

?>
