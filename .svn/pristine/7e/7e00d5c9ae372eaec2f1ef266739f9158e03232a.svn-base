<?php
include 'user_functions.php';
if (isset($_POST['username']) && isset($_POST['password'])) {
		$loginStatus = 'notOk';
        //login($_POST['username'], $_POST['password']);
        //redirection();
		$arrUserLogin = login($_POST['username'], $_POST['password'], $_POST['authType'], $_POST['otp']);
        if (isset($arrUserLogin)) {
            if ($arrUserLogin['status'] == 'UserLogged') {
                $data = SetSession($arrUserLogin['userDetails']);
                if (isset($data)) {
                    $loginStatus = redirection();
                }
            }
        }
	echo $loginStatus;
}
?>
