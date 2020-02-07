<?php
    include_once 'busrouteFunctions.php';
    $inp_date = isset($_REQUEST['d']) ? $_REQUEST['d'] : date('Y-m-d');
    $allFence = getZones();
    $allSlots = getSlots();
    $todays_zs_orders = getZoneSlotOrdersCount();
    $todays_mapped_orders = getMappedOrders();
    $mapped_vehs = getMappedZoneSlot();
?>
<style>
.table th, .table td{padding: 5px;}
.zneSlt{width:150px;}
.zoneName{background-color:#CCCCCC !important;font-weight:bold;text-align: center;color:#000 !important;}
#droptableid .orderDisp{font-weight:bold;text-align:center;color:#FE6700;}
.vehcntIssue{border:red solid 2px !important;}
</style>
<div class='container-fluid'>
    <h3>Bus Stop Mapping</h3><br/>
    <?php
        if ($todays_zs_orders['zoneZero'] == 0) {
            echo "<button class='btn btn-success' style='margin-bottom:5px;' onclick='runBusRouteAlgorithm();return false;'>Run Bus Route Algorithm</button><br style='clear:both'/>";
        } else {
            $s = ($todays_zs_orders['zoneZero'] > 1) ? 's' : '';
            echo "<a href='assign.php?id=3' style='color:#fff;'><button class='btn btn-danger' style='margin-bottom:5px;' >Zone Assigning Pending for {$todays_zs_orders['zoneZero']} Bus Stop$s</button></a><br style='clear:both'/>";
        }
    ?>
    <table class="table" id='droptableid' >
        <thead>
            <?php
                echo "<tr>";
                echo '<th class="zneSlt" >Zones/Slots</th>';
                foreach ($allSlots as $sid => $sText) {
                    //echo "<th id='slot$sid'>{$sText['timing']}</th>";
                    echo "<th id='slot$sid'>$sid </th>";
                }
                echo "</tr>";
            ?>
        </thead>
        <tbody>
        <?php
            foreach ($allFence as $fid => $fname) {
                echo "<tr>";
                echo "<td id='z$fid' class='zoneName'>{$fname['zname']}</td>";
                foreach ($allSlots as $sid => $sText) {
                    $text = '';
                    $assgn_veh_issue = '';
                    $this_order = retval_issetor($todays_zs_orders['orders'][$fid][$sid], '');
                    if ($this_order != '') {
                        $s = ($this_order > 1) ? 's' : '';
                        $text = "$this_order Bus Stop$s<br/>";
                        /*to highlight*/
                        if (isset($mapped_vehs["z{$fid}_slot$sid"])) {
                            $aC = count($mapped_vehs["z{$fid}_slot$sid"]);
                            //echo "($this_order/$aC)<=24";
                            $assgn_veh_issue = ($this_order / $aC) <= $max_orders ? '' : 'vehcntIssue';
                        }
                        /**/
                    }
                    $this_order = retval_issetor($todays_mapped_orders['orders'][$fid][$sid], '');
                    $details = '';
                    if ($this_order != '') {
                        foreach ($this_order as $vehid => $orderDet) {
                            $t = array();
                            $i = 0;
                            foreach ($orderDet as $sO) {
                                $i++;
                                if ($_SESSION['Session_UserRole'] == 'elixir') {
                                    $t[] = "$i] {$sO['oid']}--{$sO['areaid']}";
                                } else {
                                    $t[] = "$i] {$sO['oid']}";
                                }
                            }
                            $teText = implode('<br/>', $t);
                            $delstr = "";
                            $text .= "Vehicle No: {$sO['vehno']}{$delstr}===><br/>$teText<br/>";
                        }
                    }
                    echo "<td class='orderDisp $assgn_veh_issue' >$text</td>"; //vehcntIssue
                }
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
    <br/>
    <div class='row' style='font-weight:bold;'>
        <span class='vehcntIssue'>Note: Maximum                                                                                                                                                                                                                                                                                                                                                                                         <?php echo $max_orders; ?> Bus Stops can be allotted for 1 vehicle in 1 route</span>
    </div>
</div>
<script>
    var customerrefreshfrqmap =                                <?php echo $_SESSION['customerno']; ?>;
</script>