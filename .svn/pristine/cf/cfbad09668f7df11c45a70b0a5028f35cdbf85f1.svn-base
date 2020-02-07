<?php
    error_reporting(0);
    $vehicleno = isset($_REQUEST['vehicleno']) ? $_REQUEST['vehicleno'] : '';
    $userkey = isset($_GET['userkey']) ? $_GET['userkey'] : '';
    $mapdetailsnone = "";
    if (isset($_REQUEST['userkey']) && !empty($_REQUEST['userkey'])) {
        include_once '../user/user_functions.php';
        loginwithuserkey_map($_GET['userkey']);
        $_SESSION['switch_to'] = 0;
        if (isset($_REQUEST['vehicleno']) && $_REQUEST['vehicleno'] != "") {
            $vehicle = new VehicleManager($_SESSION['customerno']);
            $vehicledetails = $vehicle->get_all_vehicles_byId($vehicleno);
            if (isset($vehicledetails) && $vehicledetails != "") {
                $vehicleid = $vehicledetails[0]->vehicleid;
                $vehicleid = isset($vehicleid) ? $vehicleid : '';
            } else {
                echo "Oops Something Went wrong";die;
            }
            //$mapdetailsnone = ' mapdetailsnone ';
        }
        $mapdetailsnone = ' mapdetailsnone ';
    }
?>
<style>
    .mapdetailsnone{
        display: none;
    }
    .canvasjs-chart-canvas{
        width:300px;
        height: 250px;
    }
</style>
<?php
    include '../panels/header.php';
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set($_SESSION['timezone']);
    error_reporting(0);
?>
<!-- dt: 22nd oct 14, ak added below search div-->
<div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;width:360px;height:auto; display:none;position:absolute; left:20%; z-index:100;">
    <div id="chk_box" style="width:350px; height:auto; float:left; text-align:left;">
        <a class="a" id="address"> Search </a>  <input type="text" name="chkA" id="chkA"  class="chkp_inp" style="width: 280px;">&nbsp;
    </div>
</div>
<!-- search div ends -->
<div id="panelmap" style="margin-top: 80px;">
    <div id="color-palette"></div>
    <div>
        <input type="button"  value="refresh" class="g-button g-button-submit" id="toggler1" onclick="refreshmap();" style="background:#000000;"  >
    </div>
</div>
<div id="map" class="map" style="float:left;  height:450px"></div>
<div style="clear: both;">&nbsp;</div>
<div id="displaydata">
    <div style="float: left;top: 25px;">
    </div>
    <div style="float: left; margin-left: 100px;top:-25px;">
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<input type="hidden" name="userkey" id="userkey" value="<?php echo isset($userkey) ? $userkey : ''; ?>">
<input type="hidden" name="vehicleid_given" id="vehicleid_given" value="<?php echo isset($vehicleid) ? $vehicleid : ''; ?>">
<!-- Date: 17th nov 14, ak added, for add location popup-->
<?php include "../reports/pages/location_pop_html.php";?>
<script type="text/javascript">
    var customerrefreshfrqmap =                                                                                              <?php echo $_SESSION['customerno']; ?>;
</script>
<?php include '../panels/footer.php';?>
<?php
    function gettemp($rawtemp) {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
        return $temp;
    }

?>
