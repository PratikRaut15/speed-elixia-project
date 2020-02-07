<?php
    $vehicleno = isset($_REQUEST['vehicleno']) ? $_REQUEST['vehicleno'] : '';
    $userkey = isset($_GET['userkey']) ? $_GET['userkey'] : '';
    $mapdetailsnone = "";
    if (isset($_REQUEST['userkey']) && !empty($_REQUEST['userkey'])) {
        include_once '../user/user_functions.php';
        loginwithuserkey_map($_GET['userkey']);
        $_SESSION['switch_to'] = 3;
        if (isset($_REQUEST['vehicleno']) && $_REQUEST['vehicleno'] != "") {
            $vehicle = new VehicleManager($_SESSION['customerno']);
            $vehicledetails = $vehicle->get_all_vehicles_byId($vehicleno);
            if (isset($vehicledetails) && $vehicledetails != "") {
                $vehicleid = $vehicledetails[0]->vehicleid;
                $vehicleid = isset($vehicleid) ? $vehicleid : '';
            } else {
                echo "Oops Something Went wrong";die;
            }
        }
        $mapdetailsnone = ' mapdetailsnone ';
    ?>
<?php
    }
?>

<?php
    include '../panels/header.php';
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set($_SESSION['timezone']);
?>
<style>
    .mapdetailsnone{
        display: none;
    }
    .mapslabels{
        color:#000;
        font-family:"Lucida Grande","Arial",sans-serif;
        font-size:12px;
        height:14px;
        font-weight:bold;
        padding:2px;
        border:#286217 1px solid;
        background:#fff;
        -webkit-border-radius:1px;
        -moz-border-radius:1px;
        border-radius:1px;
        -moz-box-shadow:15px 15px 10px #888;
        -webkit-box-shadow:15px 15px 10px #888;
        box-shadow:1px 5px 5px #888;
        text-align:center;
        float:none;
        width:auto;
    }

    #info_window_wrapper{background:#fff;font-size:12px;
        height:14px;
        font-weight:bold;
        padding:2px;
        border:#286217 1px solid;
        background:#fff;
        -webkit-border-radius:1px;
        -moz-border-radius:1px;
        border-radius:1px;
        -moz-box-shadow:15px 15px 10px #888;
        -webkit-box-shadow:15px 15px 10px #888;
        box-shadow:1px 5px 5px #888;
        text-align:left;width:inherit;height:auto;}
    #info_window_header{color:#fff;text-align:center;padding:2px;height:auto;width:inherit;background:#227bdd;-webkit-border-radius:1px;-moz-border-radius:1px;border-radius:1px;}
    #info_body{color:#000;font-size:12px;height:auto;padding:2px;}.circular{-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;font-size:13px;font-weight:bold;background:#fff;color:#fff;padding:5px;border:#ccc 1px solid;-moz-box-shadow:10px 10px 5px #888;-webkit-box-shadow:10px 10px 5px #888;box-shadow:10px 10px 5px #888;margin-bottom:20px auto;}
</style>

<div id="map" class="map" style="float:left;  height:100%"></div>
</div>
<input type="hidden" name="userkey" id="userkey" value="<?php echo isset($userkey) ? $userkey : ''; ?>">
<input type="hidden" name="vehicleid_given" id="vehicleid_given" value="<?php echo isset($vehicleid) ? $vehicleid : ''; ?>">
<script type="text/javascript">
    var customerrefreshfrqmap =<?php echo $_SESSION['customerno']; ?>;
</script>
<?php include '../panels/footer.php';?>
<?php
    function gettemp($rawtemp) {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
        return $temp;
    }
?>
