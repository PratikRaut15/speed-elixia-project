<?php
//$dm = new DeliveryManager($_SESSION['customerno']);
$pickup = new Pickup($_SESSION['customerno'], $_SESSION['userid']);

$inp_date = isset($_REQUEST['d']) ? $_REQUEST['d'] : date('Y-m-d');
$allFence = get_zones();
$allSlots = get_slots();
$todays_zs_orders = $pickup->getZoneSlotOrders_count_pickup($inp_date);
$todays_mapped_orders = $pickup->getMappedOrders_pickup($inp_date);
$mapped_vehs = get_Mapped_Zone_Slot_pickup();

?>
<style>
.table th, .table td{padding: 5px;}
.zneSlt{width:150px;}
.zoneName{background-color:#CCCCCC !important;font-weight:bold;text-align: center;color:#000 !important;}
#droptableid .orderDisp{font-weight:bold;text-align:center;color:#FE6700;}
.vehcntIssue{border:red solid 2px !important;}
</style>
<div class='container-fluid'>
    <h3>Order Mapping</h3><br/>
    
    <?php
    
    if($todays_zs_orders['zoneZero']==0){
        echo "<button class='btn btn-success' style='margin-bottom:5px;' onclick='runRouteAlog();return false;'>Run Route Algorithm</button><br style='clear:both'/>";
    }
    else{
        $s = ($todays_zs_orders['zoneZero']>1) ? 's' : '';
        echo "<a href='pick.php?id=3' style='color:#fff;'><button class='btn btn-danger' style='margin-bottom:5px;' >Zone Assigning Pending for {$todays_zs_orders['zoneZero']} order$s</button></a><br style='clear:both'/>";
    }
    ?>
    
    
    <div style='border:1px #000 solid; float:left;padding: 2px 9px 2px 5px'>
        Total Orders(With proper zones): <?php echo $todays_zs_orders['total'];?>
    </div>
    <div style="float:right;margin-right:50px;">
        <?php $dispDate = date('d-m-Y', strtotime($inp_date)); ?>
        <input type="text" value='<?php echo $dispDate;?>' name="orderdate" name="orderdate" placeholder="Order date" style="margin-bottom:2px;"/>
        <a href='javascript:void(0)' onclick="orderMappingExcel('<?php echo $dispDate;?>'); return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
    </div>
    
    <table class="table" id='droptableid' >
        <thead>
            <?php
            echo "<tr>";
            echo '<th class="zneSlt" >Zones/Slots</th>';
            foreach($allSlots as $sid=>$sText){
                //echo "<th id='slot$sid'>{$sText['timing']}</th>";
                echo "<th id='slot$sid'>$sid</th>";
            }
            echo "</tr>";
            ?>
        </thead>
        <tbody>
        <?php
        if(isset($allFence)){
        foreach($allFence as $fid=>$fname){
            echo "<tr>";
            echo "<td id='z$fid' class='zoneName'>{$fname['zname']}</td>";
            foreach($allSlots as $sid=>$sText){
                $text = '';
                $assgn_veh_issue = '';
                $this_order = retval_issetor($todays_zs_orders['orders'][$fid][$sid],'');
                if($this_order!=''){
                    $s = ($this_order>1) ? 's':'';
                    $text = "$this_order order$s<br/>";
                    
                    /*to highlight*/
                    if(isset($mapped_vehs["z{$fid}_slot$sid"])){
                        $aC = count($mapped_vehs["z{$fid}_slot$sid"]);
                        //echo "($this_order/$aC)<=24";
                        $assgn_veh_issue = ($this_order/$aC)<=$max_orders ? '': 'vehcntIssue';
                    }
                    /**/
                
                }
                $this_order = retval_issetor($todays_mapped_orders['orders'][$fid][$sid],'');
                $details = '';
                if($this_order!=''){
                    foreach($this_order as $vehid=>$orderDet){
                        $t = array();
                        $i=0;
                        foreach($orderDet as $sO){
                            $i++;
                            if($_SESSION['Session_UserRole']=='elixir'){
                                $t[] =  "$i] {$sO['oid']}--{$sO['areaid']}";
                            }
                            else{
                                $t[] =  "$i] {$sO['oid']}";
                            }
                        }
                        $teText = implode('<br/>', $t);
                        $text .= "Pickup Boy: {$sO['vehno']}===><br/>$teText<br/>";
                    }
                }
                
                echo "<td class='orderDisp $assgn_veh_issue' >$text</td>"; //vehcntIssue
            }
            echo "</tr>";
        }}
        ?>
        </tbody>
    </table>
    
    <br/>
    
    <div class='row' style='font-weight:bold;'>
        <span class='vehcntIssue'>Note: Maximum <?php echo $max_orders;?> orders can be allotted for 1 vehicle in 1 box</span>
    </div>
    
</div>