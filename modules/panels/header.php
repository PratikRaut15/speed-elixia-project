<?php

//ini_set("zlib.output_compression", "On");
if (!isset($_SESSION)) {
    session_start();
}

include_once '../../lib/autoload.php';
include_once '../../config.inc.php';
include_once '../../lib/system/utilities.php';
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}

/* 
    changes Made By : Pratik Raut
    Date : 23-09-2019
    Reason : checking if it is coming from api or logging through UserKey 
*/

if (isset($_GET['userkey'])) {
    if (!empty($_GET['userkey'])) {
        $userKey = trim($_GET['userkey']);

        if (!function_exists('loginwithuserkey')) {
            include_once '../../modules/user/user_functions_login.php';

            $user =  loginwithuserkey($userKey);

            if (!empty($user)) {
                $page = basename($_SERVER['PHP_SELF']);
                if ($page == "realtimedata.php") {
                } else {
                    header("location: ../../index.php");
                }
                //    redirect user with user key 
                //header("location:http://uat-erp.elixiatech.com/bookingrequest/bookingPage/erp_user_key=$userKey");
                // bookingrequest/bookingPage/erp_user_key = userkey

            }
        }
    }
}
/* changes ends Here  */



date_default_timezone_set('' . $_SESSION['timezone'] . '');
$page = basename($_SERVER['PHP_SELF']);
if (!isset($_SESSION['username']) && ($page != 'index.php' || $page != 'elixiacode.php') && !isset($_SESSION['ecodeid'])) {
    header("location: ../../index.php");
}
$_SESSION['subdir'] = $subdir;
if (isset($_SESSION['Session_User'])) {
    $session_variable = $_SESSION['Session_User'];
}
$groupname = '';
if (isset($_SESSION['groupid'])) {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getgroupname($_SESSION['groupid']);
    $groupname = isset($groups->groupname) ? $groups->groupname : "";
    $_SESSION['groupname'] = isset($groupname) ? $groupname : '';
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
    include 'forcss.php';
    $jquery = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js'></script>";
    echo $jquery;
    if ($page == 'reports.php') {
        $chartapi = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
        echo $chartapi;
    }
    echo "<script src='" . $_SESSION['subdir'] . "/scripts/jquery.autoSuggest.js' type='text/javascript'></script>";

    ?>
    <!-- Ag-Grid - Initialise -->
    <script src="../../scripts/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
    <script type="text/javascript">
        agGrid.LicenseManager.setLicenseKey("Elixia_Tech_Solutions_Ltd_MultiApp_1Devs16_March_2019__MTU1MjY5NDQwMDAwMA==c4c29e5702e62789dbdb799433c73545");
    </script>

    <!-- Select2 cdn for css and js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <!--  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.js"></script> -->

</head>

<body>
    <div id="pageloaddiv" style='display:none;'></div>
    <script>
        function switch_to(cid) {
            jQuery('#pageloaddiv').show();
            jQuery.ajax({
                type: "POST",
                url: "../user/route.php",
                data: "switchto=" + cid,
                success: function(page) {
                    window.location.href = page;
                },
                complete: function() {
                    jQuery('#pageloaddiv').hide();
                }
            });
        }
    </script>
    <?php
    $switch_to = isset($_SESSION['switch_to']) ? $_SESSION['switch_to'] : 0;
    if ($switch_to == 1) {
        include 'menubar_hierarchy.php';
    } elseif ($switch_to == 2) {
        include 'menubar_delivery.php';
    } elseif ($switch_to == 3) {
        include 'menubar_warehouse.php';
    } elseif ($switch_to == 4) {
        include 'menubar_routing.php';
    } elseif ($switch_to == 5) {
        include 'menubar_mobility.php';
    } elseif ($switch_to == 6) {
        include 'menubar_secsales.php';
    } elseif ($switch_to == 7) {
        include 'menubar_pickup.php';
    } elseif ($switch_to == 8) {
        include 'menubar_salesengage.php';
    } elseif ($switch_to == 9) {
        include 'menubar_tms.php';
    } elseif ($switch_to == 10) {
        include 'menubar_pickupwow.php';
    } elseif ($switch_to == 12) {
        include 'menubar_secondarytms.php';
    } elseif ($page == 'reports.php' && isset($_REQUEST['id']) && ($_REQUEST['id'] == 'changeSqlite' || $_REQUEST['id'] == 'updateSqlite')) {
        // include 'menubar_secondarytms.php';
    } else {
        include 'menubar.php';
    }
    ?>
    <?php
    if ($page == 'realtimedata.php' || $page == 'warehouse.php') {
        if ($_SESSION['visits_modal'] == '0') {
            $jquery = "<script type='text/javascript'src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js'></script>";
            echo $jquery;
            include_once 'panel_functions.php';
            include_once '../../modules/realtimedata/pages/bootstrap_modal.php';
        }
    }
    $page = basename($_SERVER['PHP_SELF']);
    if ($page == 'index.php' || $page == 'elixiacode.php') {
    ?>
        <div id="header-wrapper">
            <div id="header">
                <div id="logo">
                    <div style="float:left;  padding-bottom:15px;">
                        <h1><a href="#">Elixia <span>Speed </span><sup class='off'>BETA</sup></a></h1>
                        <p id="p-bold">Answers for Where, When and How </p>
                    </div>
                </div>
            </div>
            <div style=" clear:both;"></div>
        <?php
    }
    include 'sidebar.php';
        ?>
        </div>
        <div id="wrapper">
            <!-- end #header -->
            <div id="page">
                <div id="page-bgtop">
                    <div id="page-bgbtm">
                        <div id="content">
                            <div class="post">