<?php
include_once '../../lib/bo/UserManager.php';
$new = '<img src=' . $_SESSION['subdir'] . '/images/new_small.gif>';
if (isset($_SESSION['username'])) {
    $customerno = $_SESSION['customerno'];
    $company = $_SESSION['customercompany'];
    $probity_customer = explode(",", speedConstants::PROBITY_CUSTNO);
    ?>
    <div id="divheader" class="bs-docs-example" style="height:41px;">
        <div class="navbar navbar-static" id="navbar-example">
            <div class="navbar-inner">
                <div style="width: auto;" class="container">
                    <?php
$umgr = new UserManager();
    $accuser = $umgr->getUserForAccountSwitch($_SESSION['userid']);
    if ($_SESSION["customerno"] == '116') {
        ?>
                            <a href="" class="brand"><img style="width: 120px;" src="../../images/116/image001.png"></img>
                                <span style="color: black; text-transform: uppercase; font-style: italic; font-weight: bold; font-size:small;">Track & Trace !!...</span>
                            </a>
                        <?php
} elseif ($_SESSION["customerno"] == '9') {
        ?>
                                <a href="" class="brand"><img src="../../images/9/ttllogo.png"></img></a>
                            <?php
} elseif ($_SESSION["customerno"] == '520') {
        ?>
                                <a href="" class="brand"><img src="../../images/520/logo.png"></img></a>
                            <?php
} elseif ($_SESSION["customerno"] == '563') {
        ?>
                                <a href="" class="brand"><img src="../../images/563/logo.jpg"></img></a>
                            <?php
} elseif ($_SESSION["role_modal"] == 'elixir') {
        include_once '../../lib/bo/CustomerManager.php';
        ?>
                        <a data-position="bottom" data-toggle="dropdown" class="brand dropdown dropdown-toggle" role="button" id="drop2" style="cursor: pointer;">
                            <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
                            <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
                            <span style="color: black; font-weight: bold; font-size:small;">for</span>
                            <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><?php echo $company; ?> <b class="caret"></b></span>
                        </a>
                        <ul aria-labelledby="drop2" role="menu" class="dropdown-menu" style="margin-top: -9px; left: 10%; overflow-y: auto; height: 500px; ">
                            <?php
$umgr = new UserManager();
        $arrusers = $umgr->getAllCustomerElixir();
        foreach ($arrusers as $user) {
            ?>
                                    <li role="presentation" onclick="changeaccount(<?php echo $user->userid; ?>)" style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><a><?php echo $user->customerno; ?> -<?php echo $user->customercompany ?></a></li>
                                    <?php
}
        ?>
                        </ul>
                    <?php
} elseif (!empty($accuser)) {
        ?>
                        <a data-position="bottom" data-toggle="dropdown" class="brand dropdown dropdown-toggle" role="button" id="drop2" style="cursor: pointer;">
                            <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
                            <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
                            <span style="color: black; font-weight: bold; font-size:small;">for</span>
                            <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><?php echo $company; ?> <b class="caret"></b></span>
                        </a>
                        <ul aria-labelledby="drop2" role="menu" class="dropdown-menu" style="margin-top: -9px; left: 10%;">
                            <?php
foreach ($accuser as $user) {
            ?>
                                <li role="presentation" onclick="changeaccount(<?php echo $user->childid; ?>)" style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><a><?php echo $user->customercompany ?></a></li>
                            <?php
}
        ?>
                        </ul>
                    <?php
} else {
        ?>
                            <a href="javascript:void(0);" class="brand">
                                <span style="color: black; font-weight: bold; font-size: initial;">elixia</span>
                                <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span>
                                <span style="color: black; font-weight: bold; font-size:small;">
                                    <?php if ($_SESSION["customerno"] != '9') {
            ?>
                                        for </span>
                                    <span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;">
                                        <?php
echo $company;
        }
        ?>
                                </span>
                            </a>
                <?php }?>
                    <ul role="navigation" class="nav">
                        <?php
include_once 'generatemenu.php';
    //createmenu('map', 'View Vehicles On Map');
    echo '<li class="dropdown">
                    <a data-intro="Analyze your data" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="droprealtime" href="javascript:void(0);
                   ">Realtime <b class="caret"></b></a><ul aria-labelledby="droprealtime" role="menu" class="dropdown-menu">';
    if ($_SESSION['role_modal'] != 'Viewer' && strtolower($_SESSION['role_modal']) != 'consignee') {
        if ($_SESSION['role_modal'] != 'elixir') {
            current_page_new("map/map.php", 'View Vehicles On Map', 'Map');
        }

        current_page_new("realtimedata/realtimedata.php", 'View Recent Vehicle Data', 'Data');
        echo '</ul>
                                                </li>';
        //createmenu('realtimedata', 'View Recent Vehicle Data');
        /* Trip Module Menu */
        //$tripCustomer = array(2, 59, 100, 206);
        //if (in_array($_SESSION['customerno'], $tripCustomer)) {
        if (isset($_SESSION['use_trip']) && $_SESSION['use_trip'] == 1) {
            echo '<li class="dropdown"><a data-intro="Manage your Trips" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Trips<b class="caret"></b></a>
                                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">';
            //current_page_new("trips/trips.php?pg=addlr", 'LR', 'LR');
            current_page_new("trips/trips.php?pg=tripview", 'Trips', 'Trips');
            if ($_SESSION["customerno"] == 447) {
                echo '<li class="dropdown-submenu"><a tabindex="-1" href="javascript:void(0);">Dashboard</a>
                                                <ul class="dropdown-menu">';
                //echo "<li class='current_page_item'><a href='".$_SESSION['subdir']."/modules/trips/trips.php?pg=mgmtDashboard' title='Management Dashboard' target='_blank'>Management Dashboard</a></li>";
                current_page_new("trips/trips.php?pg=mgmtDashboard", 'Management Dashboard', 'Management Dashboard');
                current_page_new("trips/trips.php?pg=oprtDashboard", 'Operational Dashboard', 'Operational Dashboard');
                echo "</ul></li>";
            }
            if (!($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] == 'Custom')) {
                echo '<li class="dropdown-submenu"><a tabindex="-1" href="javascript:void(0);">Masters</a>
                                                <ul class="dropdown-menu">';
                current_page_new("trips/trips.php?pg=statusview", 'Trip Status', 'Trip Status');
                current_page_new("trips/trips.php?pg=consigneview", 'Consignee', 'Consignee');
                current_page_new("trips/trips.php?pg=consignerview", 'Consignor', 'Consignor');
                echo "</ul></li>";
            }
            echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Reports</a>
                                                <ul class = 'dropdown-menu'>";
            current_page_new("trips/trips.php?pg=tripreport", 'Closed Trip Report', 'Closed Trip Report');
            echo "</ul></li>";
            echo '</ul>';
        }
        ?>
                                                            <?php
if (!($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] == 'Custom')) {
            ?>
                                                                <li class="dropdown">
                                                                    <a data-intro="Analyze your reports" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);
                                                                                                        ">Reports <b class="caret"></b></a>
                                                                    <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                                                        <?php
echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Vehicle History</a>
                                                <ul class = 'dropdown-menu'>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php' tabindex = '-1' role = 'menuitem'>Route History</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=59' tabindex = '-1' role = 'menuitem'>Distance Report</a></li>
                                                ";
            /* Trip Module Menu */
            if ($_SESSION['use_toggle'] == 1) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=50' tabindex = '-1' role = 'menuitem'>Toggle Switch History</a></li>";
            }
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=2' tabindex = '-1' role = 'menuitem'>Travel History</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=12' tabindex = '-1' role = 'menuitem'>Alert History</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=20' tabindex = '-1' role = 'menuitem'>Fuel Refill History</a></li>";
            }
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=16' tabindex = '-1' role = 'menuitem'>Location History</a></li>";
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=15' tabindex = '-1' role = 'menuitem'>Stoppage History</a></li>";
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=14' tabindex = '-1' role = 'menuitem'>Speed History</a></li>";
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=53' tabindex = '-1' role = 'menuitem'>Summary Report</a></li>";
            if ($_SESSION['customerno'] == 135) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=63' tabindex = '-1' role = 'menuitem'>Freeze Ignition On Report</a></li>";
            }
            if ($_SESSION['use_ac_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1) {
                echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>" . $_SESSION["digitalcon"] . " History</a>
                                                                    <ul class = 'dropdown-menu'>";
                ?>
                                                                            <?php
?>
                                                                            <?php echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=45' tabindex = '-1' role = 'menuitem'>Detail Report</a></li>"; ?>
                                                                            <?php echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=46' tabindex = '-1' role = 'menuitem'>Summary Report</a></li>"; ?>
                                                                        </ul>
                                                                    <?php
}
            if ($_SESSION['use_door_sensor'] == 1) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=39' tabindex = '-1' role = 'menuitem'>Door Sensor History</a></li>";
            }
            if ($_SESSION['use_extradigital'] == 1) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=41' tabindex = '-1' role = 'menuitem'>Switch History</a></li>";
            }
            echo "</ul>
                                                </li>";
            echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Vehicle Reports</a>
                                                <ul class = 'dropdown-menu'>";
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=8' tabindex = '-1' role = 'menuitem'>Trip Report</a></li>
                                                                            <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=6' tabindex = '-1' role = 'menuitem'>Checkpoint Report</a></li>
                                                                            ";
                if ($_SESSION['customerno'] == speedConstants::MAHINDRA_CUSTOMERNO) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=65' tabindex = '-1' role = 'menuitem'>Inactive Vehicle Report</a></li>";
                }
                //<li role='presentation'><a href='".$_SESSION['subdir']."/modules/reports/reports.php?id=21' tabindex='-1' role='menuitem'>Fuel Consumption Report</a></li>
            }
            echo "</ul>
                                                </li>";
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
                echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Vehicle Analysis</a>
                                                <ul class = 'dropdown-menu'>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=22' tabindex = '-1' role = 'menuitem'>Route Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=23' tabindex = '-1' role = 'menuitem'>Distance Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=24' tabindex = '-1' role = 'menuitem'>Idle Time Report</a></li>";
                if ($_SESSION['use_ac_sensor'] == 1) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=25' tabindex = '-1' role = 'menuitem'>" . $_SESSION["digitalcon"] . " Usage Report</a></li>";
                }
                //<li role='presentation'><a href='".$_SESSION['subdir']."/modules/reports/reports.php?id=29' tabindex='-1' role='menuitem'>Fuel Consumption Report</a></li>
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=26' tabindex = '-1' role = 'menuitem'>Overspeed Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=27' tabindex = '-1' role = 'menuitem'>Fence Conflict Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=28' tabindex = '-1' role = 'menuitem'>Location Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=30' tabindex = '-1' role = 'menuitem'>Trip Report</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=32' tabindex = '-1' role = 'menuitem'>Checkpoint Report</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=31' tabindex = '-1' role = 'menuitem'>Enhanced Route Report</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=52' tabindex = '-1' role = 'menuitem'>Stoppage Analysis</a></li>";
                if (isset($_SESSION['use_advanced_alert']) && $_SESSION['use_advanced_alert'] == 1) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=33' tabindex = '-1' role = 'menuitem'>Harsh Break Report</a></li> ";
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=34' tabindex = '-1' role = 'menuitem'>Sudden Acceleration Report</a></li> ";
                    //echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/reports/reports.php?id=35' tabindex = '-1' role = 'menuitem'>Sharp Turn Report</a></li> ";
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=36' tabindex = '-1' role = 'menuitem'>Towing Report</a></li> ";
                }
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=58' tabindex = '-1' role = 'menuitem'>Inactive Device Report</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=62' tabindex = '-1' role = 'menuitem'>Vehicle In Out Report</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=69' tabindex = '-1' role = 'menuitem'>Driver Performance Report</a></li>";
                echo " </ul>
                                                </li>";
            }
            if ($_SESSION['temp_sensors'] > 0) {
                ?>
                                                                    <li class="divider" role="presentation"></li>
                                                                    <?php
/* echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Temperature Report</a>
                <ul class = 'dropdown-menu'>
                <li><a href = '".$_SESSION['subdir']."/modules/reports/reports.php?id=11'>Graphical Format</a></li>
                <li><a href = '".$_SESSION['subdir']."/modules/reports/reports.php?id=13'>Tabular Format</a></li>
                </ul>
                </li>"; */
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=13' >Temperature Report</a></li>";
                if (isset($_SESSION['customerno']) && $_SESSION['customerno'] == speedConstants::CUSTNO_NESTLE) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=61' >Temperature Compliance Report</a></li>";
                }
                if ($_SESSION['use_humidity'] == 1) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=47' >Humidity Report</a></li>";
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=48' >Humidity & Temperature Report</a></li>";
                }
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=44' >Temperature Exception Report</a></li>";
                if (isset($_SESSION['customerno']) && $_SESSION['customerno'] == speedConstants::CUSTNO_PHARM_EASY) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=72' >Group wise Temperature Report</a></li>";
                }
            }
            if ($_SESSION['use_fuel_sensor'] == 1) {
                ?>
                                                                    <li class="divider" role="presentation"></li>
                                                                    <?php
echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=42' >Fuel Sensor Report</a></li>";
            }
            ?>
                                                                <li class="divider" role="presentation"></li>
                                                                <?php
echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/speed_dashboard/speed_dashboard.php' tabindex = '-1' role = 'menuitem'>Summary</a></li>";
            if ($_SESSION['customerno'] == 73 || $_SESSION['customerno'] == 132 || $_SESSION['customerno'] == 199 || $_SESSION['customerno'] == 458) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=40' tabindex = '-1' role = 'menuitem'>Route Summary</a></li>";
            }
            if ($_SESSION['customerno'] == 132 || $_SESSION['customerno'] == 563) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=54' tabindex = '-1' role = 'menuitem'>Route wise Tracking Report</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=70' tabindex = '-1' role = 'menuitem'>Route wise ETA Report</a></li>";
            }
            if ($_SESSION['customerno'] == 64 || isset($_GET['info']) || ($_SESSION['customerno'] == 206 && $_SESSION["role_modal"] == 'elixir') || $_SESSION['customerno'] == 756) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/dashboard/informatics.php' tabindex = '-1' role = 'menuitem'>Informatics</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/dashboard/informatics_new.php' tabindex = '-1' role = 'menuitem'>New Informatics</a></li>";
            }
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/dashboard/exception.php' tabindex = '-1' role = 'menuitem'>Exceptions</a></li>";
            if (in_array($_SESSION['customerno'], speedConstants::ALLOWED_CUST_FOR_ANNEXURE)) {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=68' tabindex = '-1' role = 'menuitem'>Annexure</a></li>";
            }
            ?>
                                                            </ul>
                                                            </li>
                                                        <?php }?>
                                                        <?php
if ($_SESSION["role_modal"] == 'Administrator' || $_SESSION["role_modal"] == 'elixir' || $_SESSION["role_modal"] == 'Master' || $_SESSION["role_modal"] == 'Admin Head' || $_SESSION["role_modal"] == "Owner" || ($_SESSION['use_maintenance'] == 1 && $_SESSION['heirarchy_id'] == 0)) {
            ?>
                                                            <li class="dropdown">
                                                                <a data-intro="Manage your Masters" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);
                                                                                                    ">Masters <b class="caret"></b></a>
                                                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                                                    <?php
echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/vehicle/vehicle.php?id=2' tabindex = '-1' role = 'menuitem'>Vehicles</a></li>";
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/driver/driver.php?id=2' tabindex = '-1' role = 'menuitem'>Drivers</a></li>";
            if ($_SESSION["roleid"] != "8") {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/account/users.php?id=2' tabindex = '-1' role = 'menuitem'>Users</a></li>";
            }
            if ($_SESSION['customerno'] == "59") {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/vehicle/trips.php' tabindex = '-1' role = 'menuitem'>Trips</a></li>";
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/genset/genset.php' tabindex = '-1' role = 'menuitem'>Genset Mapping</a></li>";
            }
            if ($_SESSION['customerno'] == "64") {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/unit/unit.php?id=1' tabindex = '-1' role = 'menuitem'>UNIT</a></li>";
            }
            //echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/smstracking/smstracking.php?id=2' tabindex = '-1' role = 'menuitem'>Sms Tracking</a></li>";
            //echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/location/location.php?id=2' tabindex = '-1' role = 'menuitem'>Create Your Location</a></li>";
             ?>
                                                                    <li class="divider" role="presentation"></li>
                                                                    <?php
if ($_SESSION['role_modal'] != 'elixir') {
                echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Checkpoints</a>
                                                <ul class = 'dropdown-menu'>
                                                <!--<li><a href = '" . $_SESSION['subdir'] . "/modules/checkpoint/checkpoint.php'>Simple Checkpoint</a></li>-->
                                                <li><a href = '" . $_SESSION['subdir'] . "/modules/checkpoint/checkpoint.php'>Circular</a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/fencing/fencing.php' tabindex = '-1' role = 'menuitem'>Polygonal</a></li>
                                                <li><a href = '" . $_SESSION['subdir'] . "/modules/enh_checkpoint/enh_checkpoint.php'>Enhanced Checkpoint</a></li>
                                                <li><a href = '" . $_SESSION['subdir'] . "/modules/checkpoint/checkpointException.php'>Checkpoint Exception</a></li>
                                                <li><a href = '" . $_SESSION['subdir'] . "/modules/checkpoint/checkpointtype.php'>Checkpoint Type</a></li>
                                                <!-- <li><a href = '" . $_SESSION['subdir'] . "/modules/checkpoint/checkPointStoppageAlerts.php'>Checkpoint Stoppage alerts</a></li> -->
                                                </ul>
                                                </li>";
            }

            //echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/fencing/fencing.php' tabindex = '-1' role = 'menuitem'>Fences</a></li>";
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/ecode/ecode.php' tabindex = '-1' role = 'menuitem'>Client Code</a></li>";
            echo '<li class="divider" role="presentation"></li>';
            echo "<li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Route</a>
                                                <ul class = 'dropdown-menu'>
                                                <li><a href = '" . $_SESSION['subdir'] . "/modules/route/route.php' tabindex = '-1' role = 'menuitem'>Route </a></li> ";
            echo "<li><a href = '" . $_SESSION['subdir'] . "/modules/route/enh_route.php' tabindex = '-1' role = 'menuitem'>Enhanced Route </a></li>
                                                <li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/route/route_dashboard.php' tabindex = '-1' role = 'menuitem'>Enhanced Route Dashboard </a></li> ";
            echo "</ul>
                                                </li>";
            //                            echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/route_enc/route_enc.php' tabindex = '-1' role = 'menuitem'>Route Enhanced </a></li>";
            if ($_SESSION['switch_to'] == '0' || $_SESSION["use_hierarchy"] == '0') {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/group/group.php' tabindex = '-1' role = 'menuitem'>Group </a></li>";
            }
            echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/nomenclature/nomenclature.php?id=2' tabindex = '-1' role = 'menuitem'>Nomenclature</a></li>";
            if ($_SESSION['role_modal'] == 'elixir') {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/travelSettings/travelSettings.php?id=2' tabindex = '-1' role = 'menuitem'>Travel Settings</a></li>";
            }
            //echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/route/route.php?id=6' tabindex = '-1' role = 'menuitem'>Route NEW </a></li>";
            // echo "<li role = 'presentation'><a href = '".$_SESSION['subdir']."/modules/route/route_dashboard.php' tabindex = '-1' role = 'menuitem'>Route Dashboard </a></li>";
            ?>
                                                                </ul>
                                                            </li>
                                                            <?php
if ($_SESSION["customerno"] == '161') {
                echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/drivermapping/drivermapping.php' tabindex = '-1' role = 'menuitem'>Mapping</a></li>";
            }
            if ($_SESSION['role_modal'] == 'elixir') {
                //if ($_SESSION["customerno"] == '15' || $_SESSION["customerno"] == '21' || $_SESSION["customerno"] == '277' || $_SESSION["customerno"] == '289' || $_SESSION["customerno"] == '302') {
                if (in_array($_SESSION['customerno'], $probity_customer)) {
                    echo "<li role = 'presentation'><a href = '" . $_SESSION['subdir'] . "/modules/probity/probity.php?id=2' tabindex = '-1' role = 'menuitem'>Probity</a></li>";
                }
            }
        }
        // $_SESSION["role_modal"]=='elixir' ||
        $route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
        if (in_array($_SESSION['customerno'], $route_dashboard_customer)) {
            echo "<li><a href = '" . $_SESSION['subdir'] . "/modules/route_dashboard/route_dashboard.php' title = 'route dashboard'>Route Dashboard</a></li>";
        }
        if ($_SESSION["customerno"] == 73 || $_SESSION["customerno"] == 458) {
            echo "<li><a href = '" . $_SESSION['subdir'] . "/modules/distance_dashboard/distance_dashboard.php' title = 'distance dashboard'>Distance Dashboard</a></li>";
        }
        $DriverExpCustomer = array(2, 206);
        if (!($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] == 'Custom')) {
            if (in_array($_SESSION['customerno'], $DriverExpCustomer)) {
                echo '<li class="dropdown"><a data-intro="Manage your Expense" data-position="bottom" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Driver Expenses<b class="caret"></b></a>
                                                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">';
                current_page_new("driver/driver.php?id=5", 'Fund Allotement', 'Fund Allotement');
                echo '<li class="dropdown-submenu"><a tabindex="-1" href="javascript:void(0);">Masters</a>
                                                                <ul class="dropdown-menu">';
                current_page_new("expense/expense.php?id=2", 'Expense Category', 'Category');
                echo "</ul></li>
                                                                <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/expmanage/expensemng.php?id=2' tabindex='-1' role='menuitem'>Expenses</a></li>
                                                                <li class = 'dropdown-submenu'><a tabindex = '-1' href = 'javascript:void(0);'>Reports</a>
                                                                <ul class = 'dropdown-menu'>";
                current_page_new("reports/reports.php?id=51", 'Expense Analysis', 'Expense Analysis');
                echo "</ul></li>";
                echo '</ul>';
            }
        }
        ?>
                                                        <!-- NEW RReport Meu -->
                                                        <!-- End Of Menu -->
                                                    <?php
} elseif (strtolower($_SESSION['role_modal']) == 'consignee') {
        current_page_new("realtimedata/realtimedata.php", 'View Recent Vehicle Data', 'Data');
        echo '</ul>';
    }
    ?>
                    </ul>
                    </li>
                    </ul>
                    <?php
require_once 'right_panel.php';
    ?>
                </div>
            </div>
        </div> <!-- /navbar-example -->
    </div>
    <!-- end #menu -->
<?php
} elseif (isset($_SESSION['ecodeid'])) {
    $umgr = new UserManager();
    $company = $umgr->getCompanyName($_SESSION['customerno']);
    $todaymenu = date('Y-m-d');
    $start = date('Y-m-d', strtotime($_SESSION['startdate']));
    $end = date('Y-m-d', strtotime($_SESSION['enddate']));
    $menu = $_SESSION['menuoption'];
    $category_array = array();
    $category = (int) $menu;
    $binarycategory = sprintf("%08s", DecBin($category));
    for ($shifter = 1; $shifter <= 536870912; $shifter = $shifter << 1) {
        $binaryshifter = sprintf("%08s", DecBin($shifter));
        if ($category & $shifter) {
            $category_array[] = $shifter;
        }
    }
    ?>
    <div class="bs-docs-example" id="divheader" style="height:41px;">
        <div class="navbar navbar-static" id="navbar-example">
            <div class="navbar-inner">
                <div style="width: auto; " class="container">
                    <?php if ($_SESSION["customerno"] == '9') {?> <a href="" class="brand"><img src="images/9/ttllogo.png"></img></a><?php }?>
                    <a href="javascript:void(0);" class="brand">
                        <span style="color: black;font-weight: bold;font-size: initial;">elixia</span>
                        <span style="color: #0193CC; font-weight: bolder; font-size: initial;">speed</span><span style="color: black; font-weight: bold; font-size:small;">
                            <?php if ($_SESSION["customerno"] != '9') {?> for </span><span style="color: black; text-transform: uppercase; font-weight: bold; font-size:small;"><?php echo $company;} ?></span></a>
                    <ul role="navigation" class="nav">
                        <?php
include_once 'generatemenu.php';
    if ($todaymenu >= $start && $todaymenu <= $end) {
        //createmenu('map', 'View Vehicles On Map');
        //createmenu('realtimedata', 'View Recent Vehicle Data');
        echo '<li class="dropdown">
                    <a data-intro="Analyze your data" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="droprealtime" href="javascript:void(0);
                   ">Realtimedata <b class="caret"></b></a><ul aria-labelledby="droprealtime" role="menu" class="dropdown-menu">';
        current_page_new("map/map.php", 'View Vehicles On Map', 'Map');
        current_page_new("realtimedata/realtimedata.php", 'View Recent Vehicle Data', 'Data');
        echo '</ul></li>';
    }
    ?>
                        <?php
if ($menu > 0) {
        ?>
                            <li class="dropdown">
                                <a data-intro="Analyze your reports" data-position="left" data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop2" href="javascript:void(0);">Reports <b class="caret"></b></a>
                                <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                                    <?php
if (in_array(1, $category_array) || in_array(2, $category_array) || in_array(4, $category_array) || in_array(8, $category_array) || in_array(16, $category_array) || in_array(32, $category_array) || in_array(64, $category_array) || in_array(128, $category_array) || in_array(8388608, $category_array)) {
            echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Vehicle History</a>";
            echo "<ul class='dropdown-menu'>";
            if (in_array(1, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php' tabindex='-1' role='menuitem'>Route History</a></li>";
            }
            if (in_array(8388608, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=59' tabindex='-1' role='menuitem'>Distance Report</a></li>   ";
            }
            if (in_array(2, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=2' tabindex='-1' role='menuitem'>Travel History</a></li>";
            }
            if (in_array(4, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=12' tabindex='-1' role='menuitem'>Alert History</a></li>";
            }
            if (in_array(8, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=20' tabindex='-1' role='menuitem'>Fuel Refill History</a></li>";
            }
            if (in_array(16, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=16' tabindex='-1' role='menuitem'>Location History</a></li>";
            }
            if (in_array(32, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=15' tabindex='-1' role='menuitem'>Stoppage History</a></li>";
            }
            if (in_array(64, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=14' tabindex='-1' role='menuitem'>Speed History</a></li>";
            }
            if (in_array(128, $category_array)) {
                echo "<li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=7' tabindex='-1' role='menuitem'>" . $_SESSION["digitalcon"] . " History</a></li>   ";
            }
            echo "</ul> ";
            echo "</li>";
        }
        if (in_array(256, $category_array) || in_array(1024, $category_array)) {
            echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Vehicle Reports</a>
                                    <ul class='dropdown-menu'>";
            if (in_array(256, $category_array)) {
                echo " <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=8' tabindex='-1' role='menuitem'>Trip Report</a></li> ";
            }
            if (in_array(1024, $category_array)) {
                echo " <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=6' tabindex='-1' role='menuitem'>Checkpoint Report</a></li>";
            }
            //if(in_array(2048, $category_array)){
            //  echo " <li role='presentation'><a href='".$_SESSION['subdir']."/modules/reports/reports.php?id=21' tabindex='-1' role='menuitem'>Fuel Consumption Report</a></li>";
            //}
            echo "</ul>
                                </li>";
        }
        if (isset($_SESSION['ecodeid'])) {
            //if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392) {
            if (in_array(4096, $category_array) || in_array(8192, $category_array) || in_array(16384, $category_array) || in_array(32768, $category_array) || in_array(65536, $category_array) || in_array(131072, $category_array) || in_array(262144, $category_array) || in_array(1048576, $category_array) || in_array(16777216, $category_array) || in_array(33554432, $category_array) || in_array(67108864, $category_array) || in_array(134217728, $category_array) || in_array(268435456, $category_array) || in_array(536870912, $category_array)) {
                echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Vehicle Analysis</a>
                                  <ul class='dropdown-menu'>";
                if (in_array(4096, $category_array)) {
                    echo "     <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=22' tabindex='-1' role='menuitem'>Route Report</a></li>";
                }
                if (in_array(8192, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=23' tabindex='-1' role='menuitem'>Distance Report</a></li>";
                }
                if (in_array(16384, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=24' tabindex='-1' role='menuitem'>Idle Time Report</a></li>";
                }
                if (in_array(32768, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=25' tabindex='-1' role='menuitem'>" . $_SESSION["digitalcon"] . " Usage Report</a></li>";
                }
                if (in_array(65536, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=26' tabindex='-1' role='menuitem'>Overspeed Report</a></li>";
                }
                if (in_array(131072, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=27' tabindex='-1' role='menuitem'>Fence Conflict Report</a></li>";
                }
                if (in_array(262144, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=28' tabindex='-1' role='menuitem'>Location Report</a></li>";
                }
                /* if(in_array(524288, $category_array)){
                echo "    <li role='presentation'><a href='".$_SESSION['subdir']."/modules/reports/reports.php?id=29' tabindex='-1' role='menuitem'>Fuel Consumption Report</a></li>    ";
                } */
                if (in_array(1048576, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=30' tabindex='-1' role='menuitem'>Trip Report</a></li> ";
                }
                if (in_array(16777216, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=32' tabindex='-1' role='menuitem'>Checkpoint Report</a></li> ";
                }
                if (in_array(33554432, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=31' tabindex='-1' role='menuitem'>Enhanced Route Report</a></li> ";
                }
                if (in_array(67108864, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=52' tabindex='-1' role='menuitem'>Stoppage Analyasis Report</a></li> ";
                }
                if (in_array(134217728, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=58' tabindex='-1' role='menuitem'>Inactive Device Report</a></li> ";
                }
                if (in_array(268435456, $category_array)) {
                    echo "    <li role='presentation'><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=62' tabindex='-1' role='menuitem'>Vehicle In Out Report</a></li> ";
                }
                $route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
                if (in_array($_SESSION['customerno'], $route_dashboard_customer)) {
                    if (in_array(536870912, $category_array)) {
                        echo "    <li role='presentation'><a href = '" . $_SESSION['subdir'] . "/modules/route_dashboard/route_dashboard.php' title = 'route dashboard'>Route Dashboard</a></li> ";
                    }
                }
                echo " </ul>
                                 </li>";
            }
            if (in_array(2097152, $category_array)) {
                echo '<li class="divider" role="presentation"></li>';
                echo "<li class='dropdown-submenu'><a tabindex='-1' href='javascript:void(0);'>Temperature Report</a>
                                    <ul class='dropdown-menu'>";
                if (in_array(2097152, $category_array)) {
                    echo "     <li><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=13'>Temperature Report</a></li>";
                }
                /* if(in_array(4194304, $category_array)){
                echo "     <li><a href='".$_SESSION['subdir']."/modules/reports/reports.php?id=13'>Tabular Format</a></li>";
                } */
                if (in_array(8388608, $category_array)) {
                    echo "     <li><a href='" . $_SESSION['subdir'] . "/modules/reports/reports.php?id=59'>Distance Report</a></li>";
                }
                echo " </ul></li>";
            }
        }
        ?>
                                </ul>
                            </li>
                        <?php
}
    ?>
                    </ul>
                    <?php require_once 'right_panel.php';?>
                </div>
            </div>
        </div> <!-- /navbar-example -->
    </div>
<?php
}
?>