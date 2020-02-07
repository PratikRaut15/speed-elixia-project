<?php
require_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';

if (!isset($_SESSION)) {
    session_start();
}
function pullcontract() {
    $devmanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devmanager->get_all_devices();
    return $devices;
}
function getcustom() {
    $usermanager = new UserManager();
    $custom = $usermanager->get_custom($_SESSION['customerno']);
    return $custom;
}
function getuser() {
    $usermanager = new UserManager();
    $user = $usermanager->get_user($_SESSION['customerno'], $_SESSION["userid"]);
    return $user;
}
function get_stoppage_alerts() {
    $usermanager = new UserManager();
    $user = $usermanager->get_stoppage_alerts($_SESSION['customerno'], $_SESSION["userid"]);
    return $user;
}
function modifyTalerts($form) {
    $user = new VOUser();
    $usermanager = new UserManager();
    $user->customerno = $_SESSION['customerno'];
    $user->userid = $_SESSION['userid'];
    $user->aci_sms = 1;
    if (!isset($form['acisms'])) {
        $user->aci_sms = 0;
    }
    $user->aci_email = 1;
    if (!isset($form['aciemail'])) {
        $user->aci_email = 0;
    }
    $user->aci_time = GetSafeValueString($form['aciselect'], 'long');
    $usermanager->modifyTalerts($user, $_SESSION['userid']);
}
function modifyEalerts($form) {
    $user = new VOUser();
    $usermanager = new UserManager();
    $user->mess_sms = 1;
    if (!isset($form["messsms"])) {
        $user->mess_sms = 0;
    }
    $user->mess_email = 1;
    if (!isset($form["messemail"])) {
        $user->mess_email = 0;
    }
    $user->speed_sms = 1;
    if (!isset($form["speedsms"])) {
        $user->speed_sms = 0;
    }
    $user->speed_email = 1;
    if (!isset($form["speedemail"])) {
        $user->speed_email = 0;
    }
    $user->power_sms = 1;
    if (!isset($form["powersms"])) {
        $user->power_sms = 0;
    }
    $user->power_email = 1;
    if (!isset($form["poweremail"])) {
        $user->power_email = 0;
    }
    $user->tamper_sms = 1;
    if (!isset($form["tampersms"])) {
        $user->tamper_sms = 0;
    }
    $user->tamper_email = 1;
    if (!isset($form["tamperemail"])) {
        $user->tamper_email = 0;
    }
    $user->chk_sms = 1;
    if (!isset($form["chksms"])) {
        $user->chk_sms = 0;
    }
    $user->chk_email = 1;
    if (!isset($form["chkemail"])) {
        $user->chk_email = 0;
    }
    $user->ac_sms = 1;
    if (!isset($form["acsms"])) {
        $user->ac_sms = 0;
    }
    $user->ac_email = 1;
    if (!isset($form["acemail"])) {
        $user->ac_email = 0;
    }
    $user->ignition_sms = 1;
    if (!isset($form["igsms"])) {
        $user->ignition_sms = 0;
    }
    $user->ignition_email = 1;
    if (!isset($form["igemail"])) {
        $user->ignition_email = 0;
    }
    $user->temp_sms = 1;
    if (!isset($form["tempsms"])) {
        $user->temp_sms = 0;
    }
    $user->temp_email = 1;
    if (!isset($form["tempemail"])) {
        $user->temp_email = 0;
    }
    $user->userid = $_SESSION['userid'];
    $user->customerno = $_SESSION['customerno'];
    $usermanager->modifyalerts($user, $_SESSION['userid']);
}
function modify_alerts_ajax() {
    $usermanager = new UserManager();
    $user->realname = $name;
    $user->role = $role;
    $user->userid = $_SESSION['userid'];
    $user->email = $email;
    $user->phone = $phoneno;
    $user->customerno = $_SESSION['customerno'];
    $usermanager->SaveUser($user, $_SESSION['userid']);
    echo 'ok';

}
function generatepass($username) {
    $char = "\$+_abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass;
    for ($i = 0; $i < 6; $i++) {
        $n = rand(0, strlen($char) - 1);
        $pass[$i] = $char[$n];
    }
    $password = implode($pass);
    $message = "Your New Password " . $password;
    $user = GetSafeValueString($username, 'string');
    $usermanager = new UserManager;
    $getuser = $usermanager->checkuser($user);
    if (isset($getuser)) {
        if ($getuser->email != NULL) {
            $usermanager->setnewpassword($password, $user);
            $communicationmanager = new CommunicationQueueManager();
            $communicationmanager->Insert('0', $getuser->email, '', 'New Password', $message);
            echo 'ok';
        } else {
            echo 'noemail';
        }
    } else {
        echo 'notok';
    }
}
function modifyuser($name, $email, $phoneno, $role) {
    $usermanager = new UserManager();
    $user->realname = $name;
    $user->role = $role;
    $user->userid = $_SESSION['userid'];
    $user->email = $email;
    $user->phone = $phoneno;
    $user->customerno = $_SESSION['customerno'];
    $usermanager->SaveUser($user, $_SESSION['userid']);
    echo 'ok';
}
function chkandchangepasswd($oldpwd, $newpasswd) {
    $usermanager = new UserManager();
    $user = $usermanager->get_user($_SESSION['customerno'], $_SESSION['userid']);
    $oldpwd = GetSafeValueString($oldpwd, "string");
    $oldpwd = sha1($oldpwd);
    if ($oldpwd == $user->password) {
        if ($newpasswd == "") {
            echo ("newempty");
        } else {
            $username = $_SESSION['username'];
            $newpass = GetSafeValueString($newpasswd, "string");
            $usermanager->setnewpassword($newpass, $username);
            echo ("ok");
        }
    } else {
        echo ("notok");
    }
}
function checklogin($username, $password) {
    $username = GetSafeValueString($username, "string");
    $password = GetSafeValueString($password, "string");
    $um = new UserManager();
    $user = $um->authenticate($username, $password);
    $status = NULL;
    if (isset($user)) {
        // Check contractvalidity
        $dm = new DeviceManager($user->customerno);
        $devices = $dm->checkforvalidity();
        $initday = 0;
        if (isset($devices)) {
            foreach ($devices as $thisdevice) {
                $days = checkvalidity($thisdevice->expirydate, $thisdevice->today);
                if ($days > 0) {
                    $initday = $days;
                }
            }
        }
        if ($initday > 0) {
            echo 'ok';
        } else {
            echo 'notok';
        }
    } else {
        echo 'notok';
    }

}
function checkvalidity($expirydate, $currentdate) {
    $realtime = strtotime($currentdate);
    $expirytime = strtotime($expirydate);
    $diff = $expirytime - $realtime;
    return $diff;
}
function login($username, $password) {
    /*if($_POST['rememberme']=='on')
    setrawcookie('elixia', $_POST['username'],time()+60*60*24);
    else
    setrawcookie('elixia');*/

    $username = GetSafeValueString($username, "string");
    $password = GetSafeValueString($password, "string");
    $um = new UserManager();
    $user = $um->authenticate($username, $password);
    $um->updatevisit($user->customerno, $user->id);

    // Setting session variables
    $_SESSION['Session_UserRole'] = $user->role;
    $_SESSION["realname"] = $user->realname;
    $_SESSION["customerno"] = $user->customerno;
    $_SESSION["userid"] = $user->id;
    $_SESSION["visits_modal"] = $user->visits;
    $_SESSION["username"] = $user->username;
    $_SESSION['Session_User'] = $user;
    $_SESSION["sessionauth"] = $user->role;
    $_SESSION["groupid"] = $user->groups;

    $log = new Log();
    if ($log->createlog($_SESSION['customerno'], "Logged In", $_SESSION['userid'])) {

    }
}

/*
function newpasswd($newpasswd)
{
$username = $_SESSION['username'];
$newpass = GetSafeValueString($newpasswd, "string");
$usermanager = new UserManager;
$usermanager->setnewpassword($newpass,$username);
}
 *
 */
?>
