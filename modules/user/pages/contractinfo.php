<?php
$use_hierarchy = $_SESSION['use_hierarchy'];
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/modules/team/bootstrap.css" type="text/css" />';
/**
  $con = id_details($_SESSION['customerno']);
  set_ids($con, $_SESSION['customerno']);
  die();
 * 
 */
$city = '';
if (isset($_POST['filter'])) {
    //print_r($_POST);
    $nation_id = GetSafeValueString($_POST['nationid'], "string");
    $state_id = GetSafeValueString($_POST['stateid'], "string");
    $district_id = GetSafeValueString($_POST['districtid'], "string");
    $city_id = GetSafeValueString($_POST['cityid'], "string");
    $group_id = GetSafeValueString($_POST['group'], "string");

    $devices = getfilteredcontract_details($nation_id, $state_id, $district_id, $city_id, $group_id);
}
else {
    $devices = pullcontract();
}
?>



<style>

    /*Helpers*/

    .centered {
        text-align: center;
    }

    .goleft {
        text-align: left;
    }

    .goright {
        text-align: right;
    }

    .mt {
        margin-top: 25px;
    }

    .mb {
        margin-bottom: 25px;
    }

    .ml {
        margin-left: 5px;
    }

    .no-padding {
        padding: 0 !important;
    }

    .no-margin {
        margin: 0 !important;
    }

    /* BASIC THEME CONFIGURATION */

    ul li {
        list-style: none;
    }

    a, a:hover, a:focus {
        text-decoration: none;
        outline: none;
    }
    ::selection {

        background: #68dff0;
        color: #fff;
    }


    .huge {
        font-size: 40px;
    }

    .panel-green {
        border-color: #5cb85c;
    }

    .panel-green .panel-heading {
        border-color: #5cb85c;
        color: #fff;
        background-color: #5cb85c;
    }

    .panel-green a {
        color: #5cb85c;
    }

    .panel-green a:hover {
        color: #3d8b3d;
    }

    .panel-red {
        border-color: #d9534f;
    }

    .panel-red .panel-heading {
        border-color: #d9534f;
        color: #fff;
        background-color: #d9534f;
    }

    .panel-red a {
        color: #d9534f;
    }

    .panel-red a:hover {
        color: #b52b27;
    }

    .panel-orange {
        border-color: orange;
    }

    .panel-orange .panel-heading {
        border-color: orange;
        color: #fff;
        background-color: orange;
    }

    .panel-orange a {
        color: orange;
    }

    .panel-orange a:hover {
        color: orange;
    }

</style>
<?php
$contract = contract_details($_SESSION['customerno']);
?>
<div class='entry' id="invoice_info" >
    <h5></h5>



    <div class="row">
        <div class="col-lg-12">


            <!-- TOtal Devices -->
            <div class="col-lg-4 col-md-6 mb">
                <div class="panel-green"style="border:1px solid #3d8b3d; border-radius: 5px;">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div>Total Devices</div>
                                <div class="huge"><?php echo $contract->unit ?></div>


                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="panel-body">
                        
                    </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    -->
                </div>
            </div>

            <!-- Total Simcard -->
            <div class="col-lg-4 col-md-6  mb" >
                <div class="panel-green" style="border:1px solid #3d8b3d; border-radius: 5px; ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div>Total Simcard</div>
                                <div class="huge"><?php echo $contract->simcard ?></div>

                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="panel-body">
                        
                        </div>
                    <a href="#">
                        <div class="panel-footer">
                            <span class="pull-left">View Details</span>
                            <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                    -->
                </div>
            </div>

            <!-- total vehicles covered -->
            <div class="col-lg-4 col-md-6  mb" >
                <div class="panel-green" style="border:1px solid #3d8b3d; border-radius: 5px; ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">

                                <div>Total Vehicles Covered</div>
                                <div class="huge"><?php echo $contract->unit ?></div>
                            </div>
                        </div>
                    </div>
                    <!--
                <div class="panel-body">
                    
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><img src="../../img/glyphicons-circle-arrow-right.png"></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
                    -->
                </div>

            </div>

        </div>    
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!-- Expiry Date -->
            <div class="col-lg-4 col-md-6  mb" >
                <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px; ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div> Total Device Active</div>
                                <div class="huge"><?php echo $contract->active; ?></div>


                            </div> 
                        </div>
                    </div>
                </div>

            </div>


            <!-- Expired Devices -->
            <div class="col-lg-4 col-md-6  mb" >
                <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px; ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div>Total Device Inactive</div>
                                <div class="huge"><?php echo $contract->inactive; ?></div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4 col-md-6  mb" >
                <div class="panel-primary" style="border:1px solid #007ED1; border-radius: 5px; ">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <div>Total Device In Warranty</div>
                                <div class="huge"><?php echo $contract->warranty ?></div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
        ?>     
        <form method="POST">  
            <table style="margin-left: 50px; width: 93%;">
                <tr>

                    <td>
                        <select id="nationid" name="nationid" style="width:100%;" onchange="getstate()">
                            <option value=''>Select <?php echo ($_SESSION['nation']); ?></option>
                            <?php
                            $nations = getnations($_SESSION['userid']);
                            if (isset($nations)) {
                                foreach ($nations as $nation) {
                                    if ($nation_id == $nation->nationid) {
                                        echo "<option value='$nation->nationid' selected='selected'>$nation->name</option>";
                                    }
                                    else {
                                        echo "<option value='$nation->nationid'>$nation->name</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="stateid" name="stateid" style="width:100%;" onchange="getdistrict()">
                            <option value=''>Select <?php echo ($_SESSION['state']); ?></option>
                            <?php
                            $states = getstates($_SESSION['userid']);
                            if (isset($states)) {
                                foreach ($states as $state) {
                                    if ($state_id == $state->stateid) {
                                        echo "<option value='$state->stateid' selected='selected'>$state->statename</option>";
                                    }
                                    else {
                                        echo "<option value='$state->stateid'>$state->statename</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td> 
                    <td>
                        <select id="districtid" name="districtid" style="width:100%;" onchange="getcity()">
                            <option value=''>Select <?php echo ($_SESSION['district']); ?></option>
                            <?php
                            $districts = getdistricts($_SESSION['userid']);
                            if (isset($districts)) {
                                foreach ($districts as $district) {
                                    if ($district_id == $district->districtid) {
                                        echo "<option value='$district->districtid' selected='selected'>$district->districtname</option>";
                                    }
                                    else {
                                        echo "<option value='$district->districtid'>$district->districtname</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="cityid" name="cityid" style="width:100%;" onchange="getbranch()">
                            <option value=''>Select <?php echo ($_SESSION['city']); ?></option>
                            <?php
                            $cities = getcities($_SESSION['userid']);
                            if (isset($cities)) {
                                foreach ($cities as $city) {
                                    if ($city_id == $city->cityid) {
                                        echo "<option value='$city->cityid' selected='selected'>$city->cityname</option>";
                                    }
                                    else {
                                        echo "<option value='$city->cityid'>$city->cityname</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="group" name="group" style="width:100%;">
                            <option value=''>Select <?php echo ($_SESSION['group']); ?></option>
                            <?php
                            $groups = getgroups();
                            if (isset($groups)) {
                                foreach ($groups as $group) {
                                    if ($group_id == $group->groupid) {
                                        echo "<option value='$group->groupid' selected='selected'>$group->groupname</option>";
                                    }
                                    else {
                                        echo "<option value='$group->groupid'>$group->groupname</option>";
                                    }
                                }
                            }
                            ?>
                        </select>
                    </td>        
                    <td style="padding-bottom:10px;"><input type="submit" class="g-button g-button-submit" value="Search" name="filter"></td>
                </tr>
                </tbody>
            </table>
        </form>
        <?php
    }
    ?>

    <table class="table newTable" id="inventory_mgt" style="margin-left: 50px; width: 93%;">
        <thead>
            <tr>
                <th colspan="100%" id="formheader">Inventory Management   <span style="float: right;"><a href="#invoice_info">Top</a></span></th>
            </tr>
        </thead>
        <tbody>
            <tr class="tableSub">
                <td>Sr #</td>
                <td>Vehicle #</td>
                <td>Unit #</td>
                <td>Simcard #</td>
                <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                    ?>
                    <td><?php echo ($_SESSION['district']); ?></td>
                    <td><?php echo ($_SESSION['city']); ?></td>
                    <?php
                }
                ?>
                <td>Group Name</td>
                <td>Installation location</td>
                <td>Date of Installation</td>
                <td>Date Warranty Start</td>
                <td>Date of Warranty Expiry</td>
                <td>Days Left</td>
                <td>PO No. </td>
                <td>PO Date </td>

                <?php
                if ($use_hierarchy) {
                    echo "<td>Hierarchy</td>";
                }
                ?>
                <td>History</td>
            </tr>
            <?php
            $i = 1;

            if (isset($devices)) {
                foreach ($devices as $contract) {
                    $installedLocation = "NA";
                    if ($contract->installlat != 0 && $contract->installlng != 0) {
                        $objGeoCoder = new GeoCoder($_SESSION['customerno']);
                        $installedLocation = $objGeoCoder->get_location_bylatlong($contract->installlat, $contract->installlng);
                    }
                    $today = date("Y-m-d");
                    $month = date("Y-m-d", strtotime($today . '+30 days'));
                    $date = new DateTime($contract->registeredon);
                    $group_details = getgroupname_hierarchy_contract($contract->groupid);
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $contract->vehicleno; ?></td>
                        <td><?php echo $contract->unitno; ?></td>
                        <td><?php echo $contract->phone; ?></td>
        <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
            ?>
                            <td><?php echo $contract->district; ?></td>
                            <td><?php echo $contract->city; ?></td>
                            <?php
                        }
                        ?>
                        <td><?php echo $group_details['groupname']; ?></td>
                        <td><?php echo $installedLocation ?></td>
                        <td>
                            <?php
                            if ($contract->installdate != '0000-00-00') {
                                $install = date("d-M-Y", strtotime($contract->installdate));
                                echo $install;
                            }
                            ?>
                        </td>

                        <td>
                            <?php
                            if ($contract->installdate != '0000-00-00') {
                                $install = date("d-M-Y", strtotime($contract->installdate));
                                echo $install;
                            }
                            ?>
                        </td>

                        <td>
                            <?php
                            if ($contract->warranty != '0000-00-00') {
                                $warrenty = date("d-M-Y", strtotime($contract->warranty));
                                if (strtotime($warrenty) >= strtotime($today) && strtotime($warrenty) < strtotime($month)) {
                                    echo "<span style='color:darkorange'>" . $warrenty . "</span>";
                                }
                                else if (strtotime($today) > strtotime($warrenty)) {
                                    echo "<span style='color:red'>" . $warrenty . "</span>";
                                }
                                else {
                                    echo "<span style='color:green'>" . $warrenty . "</span>";
                                }
                            }
                            else if ($contract->installdate != '0000-00-00') {
                                echo $warrenty = date("d-M-Y", strtotime($contract->installdate . '+365 days'));
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($contract->warranty != '0000-00-00') {
                                $expirydate = date("Y-m-d", strtotime($contract->warranty));
                                $dStart = new DateTime($today);
                                $dEnd = new DateTime($expirydate);
                                $dDiff = $dStart->diff($dEnd);
                                // echo $dDiff->format('%R'); // use for point out relation: smaller/greater

                                if ($today > $expirydate) {
                                    echo "<span style='color:red'>Expired</span>";
                                }
                                else {
                                    echo $dDiff->days . " Days Left";
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <?php echo $contract->pono; ?>
                        </td>
                        <td> <?php
                        if ($contract->podate != '0000-00-00' && $contract->podate != '1970-01-01') {
                            echo date("d-M-Y", strtotime($contract->podate));
                        }
                        ?></td>
                        <?php
                        /*
                          echo "<td>$contract->pono</td>
                          <td>$contract->podate</td>
                          <td>$contract->warrenty_expiry_date</td>
                          echo "<td>$contract->device_invoiceno</td>";
                          echo "<td>$contract->invoiceno</td>";
                         * 
                         */
                        if ($use_hierarchy) {
                            echo "<td>{$group_details['hierarchy']}</td>";
                        }
                        echo "<td><a href='accinfo.php?id=5&vid=$contract->vehicleid'><img src='../../images/history.png' /><a/></td>";
                    }
                }
                else {
                    echo "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr>";
                }
                ?>
        </tbody>
    </table>
</div>
<script>
    jQuery(document).ready(function () {
        jQuery("#page").css('overflow', 'unset');
    });
</script>