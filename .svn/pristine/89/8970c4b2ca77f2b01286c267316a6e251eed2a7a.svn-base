<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/realtimedata/rtd_functions.php';
include_once("../../lib/system/Date.php");

// 
class testing {
    
}
?>

<?php
//$_scripts[] = "../../scripts/prototype.js";
//$_scripts[] = "../../scripts/jquery-1.7.2.min.js";
//$_scripts[] = "../../scripts/jquery-ui-1.8.13.custom.min.js";
//$_scripts[] = "../../scripts/jQueryRotate.2.1.js";
//$_scripts[] = "../../scripts/jquery-latest.js";
$_scripts[] = "../../scripts/tablesorter/jquery.tablesorter.js";
include("header.php");
$crmmanager = $_SESSION["sessionteamrid"];
if (empty($crmmanager)) {
    $crmmanager = "0";
}

$message = "";
$issuetype = "0";
$icustomer = '0';

$db = new DatabaseManager();
$SQL = sprintf("SELECT relationship_manager.manager_name, customer.customerno, customer.customercompany FROM " . DB_PARENT . ".customer LEFT OUTER JOIN relationship_manager ON customer.rel_manager = relationship_manager.rid");
$db->executeQuery($SQL);
$customer = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $testing = new testing();
        $testing->customerno = $row["customerno"];
        $testing->customername = $row["customerno"] . "( " . $row['customercompany'] . " )";
        $testing->manager_name = $row["manager_name"];        
        $customer[] = $testing;
    }
}

$pc_cnt = 0;
$ln_cnt = 0;
$dpi_cnt = 0;
$tamp_cnt = 0;
$un_cnt = 0;
$airtel_cnt = 0;
$voda_cnt = 0;
$idea_cnt = 0;
$shailesh_cnt = 0;
$custsim_cnt = 0;
$aircel_cnt = 0;
$other_cnt = 0;
$allinactive = getlastupdatefordevicesreason();
foreach ($allinactive as $allinactives) {
//to calculate count for device inactive reason    
    if ($allinactives->powercut == 0) {
        $reason = "Power Cut";
        $pc_cnt++;
    } elseif (round($allinactives->gsmstrength / 31 * 100) < 30) {
        $reason = "Low Network";
        $ln_cnt++;
    } elseif ($allinactives->gprsregister < 14) {
        $reason = "Data Packet Inactive";
        $dpi_cnt++;
    } elseif ($allinactives->tamper == 1) {
        $reason = "Tampered";
        $tamp_cnt++;
    } else {
        $reason = "Unknown Reason";
        $un_cnt++;
    }
// to calculate inactive simacard vendorwise
    if ($allinactives->vendorid == 1) {
        $airtel_cnt++;
    } elseif ($allinactives->vendorid == 2) {
        $idea_cnt++;
    } elseif ($allinactives->vendorid == 3) {
        $shailesh_cnt++;
    } elseif ($allinactives->vendorid == 4) {
        $custsim_cnt++;
    } elseif ($allinactives->vendorid == 5) {
        $voda_cnt++;
    } elseif ($allinactives->vendorid == 6) {
        $aircel_cnt++;
    } else {
        $other_cnt++;
    }
}


if (isset($_POST["invoicesearch"])) {
    $crmmanager = $_POST["crmmanager"];
    $issuetype = $_POST["issuetype1"];
    $icustomer = $_POST["customerno"];

    $vehicledata = team_getvehicles_all_inactive($crmmanager, $issuetype, $icustomer);
    $cnt = count($vehicledata);

    $tempdata = gettemp_wirecuts($crmmanager, $issuetype, $icustomer);
    $temp_cnt = count($tempdata);
} else {
//    $vehicledata = getvehicles_all_inactive($from_inactive_date, $to_inactive_date, $crmmanager, $issuetype, $icustomer);
    //   $cnt = count($vehicledata);
}
$blankremark = array();
if ($vehicledata != "") {
    foreach ($vehicledata as $row) {
        $alterremark = $row->issuetype;
        if ($alterremark == 0) {
            $blankremark[] = $row->issuetype;
        }
    }
}
$remarkblankcount = count($blankremark);
//issue type count 1- Customer issue, 2- elixia issue
$issue_comp = array();
$issue_elixia = array();
if ($vehicledata != "") {
    foreach ($vehicledata as $row) {
        $issue = $row->issuetype;
        if ($issue == 1) {
            $issue_comp[] = $row->issuetype;
        } else if ($issue == 2) {
            $issue_elixia[] = $row->issuetype;
        }
    }
}
$comp_issue_count = count($issue_comp);
$elixia_issue_count = count($issue_elixia);

$db = new DatabaseManager();
$SQL = sprintf("SELECT rid, manager_name FROM " . DB_PARENT . ".relationship_manager");
$db->executeQuery($SQL);
$crmman = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $crm = new testing();
        $crm->mname = $row["manager_name"];
        $crm->rid = $row["rid"];
        $crmman[] = $crm;
    }
}
?>
<div class="panel">
    <div align="center" class="paneltitle">Inactive Search</div>
    <div class="panelcontents">
        <?php
        if (!empty($message)) {
            echo"<span style='color:red; font-size:12px;'>" . $message . "</span>";
        }
        ?>
        <form name="inactiveform" id="inactiveform" action="inactive.php"  method="POST">
            <table align="center" cellpadding="5">
                <tr><td><b>CRM Manager :</b></td>
                    <td>
                        <select name="crmmanager" id="crmmanager">
                            <option value="0">Select CRM</option>
                            <?php
                            print_r($crmman);
                            foreach ($crmman as $thiscrm) {
                                ?>
                                <option value="<?php echo($thiscrm->rid); ?>" <?php
                                if ($thiscrm->rid == $crmmanager) {
                                    echo "selected";
                                }
                                ?>  ><?php echo($thiscrm->mname); ?></option>
                                        <?php
                                    }
                                    ?>
                        </select>
                    </td>
                    <td><b>Issue Type :</b></td>
                    <td><select name="issuetype1" id="issuetype1">
                            <option value="0">Select issue type</option>
                            <option value="1">Customer Issue</option>
                            <option value="2">Elixia Issue</option>
                        </select>
                    </td>
                    <td colspan="3" style="text-align:right;">Customer :</td>
                    <td colspan="3">
                        <input  type="text" name="icustomer" id="icustomer" size="25" value="<?php
                        if (isset($customerno)) {
                            echo $customerno;
                        }
                        ?>" autocomplete="off" placeholder="Enter Customer No Or Name" onkeyup="getCust()" />
                        <input type="hidden" name="customerno" id="customerno" />
                    </td>
                    <td colspan="3"><input type="submit" value="Search" name="invoicesearch" id="invoicesearch"/></td>
                </tr></table>
        </form>
    </div>
</div>   

<div class="panel">
    <div class="panelcontents">

        <table align="center" border="1" cellpadding="5">
            <tr>

                <td><b>Blank Remarks </b></td><td><?php echo $remarkblankcount; ?></td>
                <td><b>Customer Issues </b></td><td><?php echo $comp_issue_count; ?></td>
                <td><b>Elixir Issues </b></td><td><?php echo $elixia_issue_count; ?></td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 50%">
                    <table border="1">
                        <thead>
                        <th>
                            <b>Vendor Name</b>
                        </th>
                        <th>
                            <b>Count of Inactive Sim</b>
                        </th>
                        </thead>

                        <tr>
                            <td><b>Bharti Airtel</b></td>
                            <td><?php echo $airtel_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Idea</b></td>
                            <td><?php echo $idea_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Shailesh</b></td>
                            <td><?php echo $shailesh_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Customer SIM</b></td>
                            <td><?php echo $custsim_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Vodafone</b></td>
                            <td><?php echo $voda_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Aircel</b></td>
                            <td><?php echo $aircel_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Others</b></td>
                            <td><?php echo $other_cnt; ?></td>
                        </tr>

                    </table>
                </td>


                <td style="width: 50%">
                    <table border="1" style="float:right">
                        <thead>
                        <th>
                            <b>Inactive Devices Status</b>
                        </th>
                        <th>
                            <b>Counts</b>
                        </th>
                        </thead>

                        <tr>
                            <td><b>Power Cut</b></td>
                            <td><?php echo $pc_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Low Network</b></td>
                            <td><?php echo $ln_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Data Packet Inactive</b></td>
                            <td><?php echo $dpi_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Tampered</b></td>
                            <td><?php echo $tamp_cnt; ?></td>
                        </tr>
                        <tr>
                            <td><b>Unknown Reason</b></td>
                            <td><?php echo $un_cnt; ?></td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

    </div>
    <br/>
    <?php
    if (isset($_POST["invoicesearch"])) {
        ?>    
        <div class="paneltitle" align="center">Inactive data <span style="float:right;">Inactive device Count : <?php echo $cnt; ?></span></div>    
        <div class="panelcontents">
            <?php
            display_vehicledata_all_inactive($vehicledata);
            ?>
        </div>

        <div class="paneltitle" align="center">Temperature Wire Cuts <span style="float:right;">Wire Cut Count : <?php echo $temp_cnt; ?></span></div>
        <div class="panelcontents">

            <?php
            display_vehicledata_all_temp_wirecuts($tempdata);
            ?>
        </div>
        <?php
    } else {
        ?>
        <table width="100%" cellspacing="0" cellpadding="10">
            <tr>
                <td style="width: 50%">
                    <table border="1">
                        <thead>
                        <th>
                            <b>Sr No.</b>
                        </th>                            
                        <th>
                            <b>Customer No.</b>
                        </th>
                        <th>
                            <b>Customer Name</b>
                        </th>
                        <th>
                            <b>Relationship Manager</b>
                        </th>                        
                        <th>
                            <b>Inactive Since Last 1 Day</b>
                        </th>                        
                        <th>
                            <b>Inactive Since Last 4 Days</b>
                        </th>                                                
                        <th>
                            <b>Temperature Wire Cuts</b>
                        </th>                                                
                        </thead>    
                        <?php
                        $x = 1;
                        $total_1 = 0;
                        $total_4 = 0;
                        foreach ($customer as $thiscustomer) {
                            $vehicledata_1 = team_getvehicles_all_inactive_days(0, 0, $thiscustomer->customerno,1);
                            $cnt_1 = count($vehicledata_1);
                            $total_1 = $total_1 + $cnt_1;
                            $vehicledata_4 = team_getvehicles_all_inactive_days(0, 0, $thiscustomer->customerno,4);
                            $cnt_4 = count($vehicledata_4);
                            $total_4 = $total_4 + $cnt_4;
                            $tempdata = gettemp_wirecuts(0, 0, $thiscustomer->customerno);
                            $temp_cnt = count($tempdata);

                            if ($cnt_1 != 0 || $temp_cnt != 0 || $cnt_4 != 0) {
                                ?>
                                <tr align="center">
                                    <td><?php echo $x; ?></td>                                    
                                    <td><?php echo $thiscustomer->customerno; ?></td>
                                    <td><?php echo $thiscustomer->customername; ?></td>
                                    <td><?php echo $thiscustomer->manager_name; ?></td>                                    
                                    <td><?php echo $cnt_1-$cnt_4; ?></td>
                                    <td><?php echo $cnt_4; ?></td>                                    
                                    <td><?php echo $temp_cnt; ?></td>
                                </tr>
                                <?php
                                $x++;
                            }
                            ?>

                            <?php
                        }
                        ?>
                    </table>
                    Total Inactive Since 1 day: <?php echo($total_1); ?> <br/>
                    Total Inactive Since 4 days: <?php echo($total_4); ?>
                    <?php
                }
                ?>
                <?php
                include("footer.php");
                ?>
                <script>
                    function getCust() {
                        jQuery("#icustomer").autocomplete({
                            source: "route_ajax.php?customername=getcust",
                            select: function (event, ui) {

                                /*clear selected value */
                                jQuery(this).val(ui.item.value);
                                jQuery('#customerno').val(ui.item.cid);
                                return false;
                            }
                        });
                    }
                </script>