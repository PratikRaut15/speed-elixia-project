<?php
$clientcode = isset($_GET['clientcode']) ? $_GET['clientcode'] : '';
if(isset($_REQUEST['clientcode']) && !empty($_REQUEST['clientcode'])){
    include_once '../user/user_functions.php';
    loginwitheliciacode($_GET['clientcode']);

}

if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/autoload.php';
include_once '../../config.inc.php';
include_once '../../lib/system/utilities.php';
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set('' . $_SESSION['timezone'] . '');
$page = basename($_SERVER['PHP_SELF']);
$_SESSION['subdir'] = $subdir;
if (isset($_SESSION['Session_User'])){
    $session_variable = $_SESSION['Session_User'];
}

?>
<!DOCTYPE html>
<head>
    <link rel="shortcut icon" href="<?php echo $_SESSION['subdir']; ?>/images/favicon.ico" type="image/x-icon" />
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Elixia Speed ϐ | Answers for Where, When and How </title>
    <link href="<?php echo $_SESSION['subdir']; ?>/style/style.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="<?php echo $_SESSION['subdir']; ?>/style/maps.css" rel="stylesheet" type="text/css" media="screen" />
    <?php

    $jquery = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js'></script>";
    echo $jquery;

    echo "<script src='" . $_SESSION['subdir'] . "/scripts/jquery.autoSuggest.js' type='text/javascript'></script>";

    ?>
    <!-- Ag-Grid - Initialise -->
    <script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
    <script type="text/javascript">
        agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    </script>
</head>
<body>
    <div id="pageloaddiv" style='display:none;'></div>

    </div>
    <div id="wrapper">
        <!-- end #header -->
        <div id="page">
            <div id="page-bgtop">
                <div id="page-bgbtm">
                    <div id="content">
                        <div class="post">

                            <div id="panelmap" style="margin-top: 20px;">
                                <div id="color-palette"></div>
                                <div>
                                    <input type="button"  value="refresh" class="g-button g-button-submit" id="toggler1" onclick="refreshmap();" style="background:#000000;"  >
                                </div>
                            </div>
                            <div id="map" class="map" style="float:left;  height:450px"></div>
                            <div style="clear: both;">&nbsp;</div>

                        </div>
<script type="text/javascript">
    var customerrefreshfrqmap = <?php echo $_SESSION['customerno']; ?>;
</script>
<?php include '../panels/footer.php'; ?>
