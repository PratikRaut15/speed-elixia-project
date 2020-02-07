<?php
if (isset($_SESSION['username'])) {
    $page = basename($_SERVER['PHP_SELF']);
}
$assign = ($page == 'assign.php' && isset($_GET['id']) && $_GET['id'] == 2);
$pickup = ($page == 'pick.php' && isset($_GET['id']) && $_GET['id'] == 2);
$fencing = ($page == 'fencing.php' && isset($_GET['id']) && $_GET['id'] == 4);
$zoning = ($page == 'zone.php' && isset($_GET['id']) && $_GET['id'] == 4);
if ($page == 'map.php' || $page == 'routeMap.php' || $assign || $fencing || $zoning || $pickup || $page == 'busRouteMap.php' || $page == 'directionMap.php' || $page == 'busZone.php' || $page == 'mappage.php') {
    ?>
    <div id="sidebar">
        <ul>
            <?php
if ($assign) {
        include "userarea_assign.php";
    } elseif ($pickup) {
        include "userarea_pickup.php";
    } elseif ($fencing) {
        include "userarea_fencing.php";
    } elseif ($zoning) {
        include 'zoneSideBar.php';
    } elseif ($page == 'map.php' || $page == 'mappage.php') {
        include "userarea.php";
    } elseif ($page == 'busRouteMap.php') {
        include "busroute_userarea.php";
    } elseif ($page == 'busZone.php') {
        include "zoneSideBar.php";
    } elseif ($page == 'directionMap.php') {
        include "directionMap_userarea.php";
    } elseif ($page == 'routeMap.php') {
        ?>
                <div class="scrollheader">
                    <span class="tw_b">Vehicles
                    </span>
                    <div class="scroll_head_container" >
                        <label class="all_select scroll_lable tc_blue " data-type="vehicles" title="Click here to show all" >All </label>
                        <label  class="scroll_lable  ">|</label>
                        <label class="all_clear scroll_lable tc_blue " data-type="vehicles" title="Click here to clear all" >Clear</label>
                    </div>
                </div>
                <div class="scrollablediv">
                    <input type="checkbox" class="veh_all" id ="veh_1" onclick="vehplot(<?php echo 1; ?>);" checked/> Route 107<br/>
                    <input type="checkbox" class="veh_all" id ="veh_2" onclick="vehplot(<?php echo 2; ?>);" checked/> Route 111<br/>
                    <input type="checkbox" class="veh_all" id ="veh_3" onclick="vehplot(<?php echo 3; ?>);" checked/> Route 904<br/>
                </div>
                <?php
}
    ?>
        </ul>
    </div>
    <div id="maptoggler" onclick="onclicktog();">
        <img src="../../images/br_next.png" id="next"  style="display:none;" /><img src="../../images/br_prev.png" id="pre"  style="display:none;"/>
    </div>
    <?php
} elseif ($page == 'warehousemap.php') {
    ?>
    <div id="sidebar">
        <ul>
            <?php
include "warehouse_userarea.php";
    ?>
        </ul>
    </div>
    <div id="maptoggler" onclick="onclicktog();">
        <img src="../../images/br_next.png" id="next"  style="display:none;" /><img src="../../images/br_prev.png" id="pre"  style="display:none;"/>
    </div>
    <?php
} elseif ($page == 'index.php' || $page == 'elixiacode.php') {
    ?>
    <div id="sidebar">
        <ul>
            <?php
include "loginarea.php";
    ?>
        </ul>
    </div>
    <?php
} elseif ($page == 'radar.php') {
    ?>
    <div id="sidebar" style="width:350px; display: none;">
        <ul>
            <?php
include "radararea.php";
    ?>
        </ul>
    </div>
    <div id="maptoggler" onclick="onclicktog();" style="display: none;">
        <img src="../../images/br_next.png" id="next"  style="display:none;" /><img src="../../images/br_prev.png" id="pre"  style="display:none;"/>
    </div>
    <?php
}
?>
