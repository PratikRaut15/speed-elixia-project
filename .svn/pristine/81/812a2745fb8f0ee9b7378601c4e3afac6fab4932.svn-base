<?php
    //error_reporting("Error");
    error_reporting(E_ALL);
ini_set('display_errors', 1);
    if (isset($_REQUEST['userkey']) && !empty($_REQUEST['userkey'])) {
        if (!isset($_SESSION)) {
            include_once '../user/user_functions.php';
            loginwithuserkey($_GET['userkey']);
            $_SESSION['switch_to'] = 0;
        }
    }
    include '../panels/header.php';
?>

<div id="pageloaddiv" style='display:none;'></div>
<div class="entry">
    <center>
        <?php include 'reporttabs.php';?>
    </center>
</div>
<?php include '../panels/footer.php';?>
