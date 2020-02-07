<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

$customerno = exit_issetor($_SESSION['customerno']);
$dm = new DeliveryManager($customerno);

$inp_date = isset($_REQUEST['d']) ? $_REQUEST['d'] : date('Y-m-d');
$dispDate = date('d-m-Y', strtotime($inp_date));

$allFence = get_zones();
$allSlots = get_slots();
$todays_zs_orders = $dm->getZoneSlotOrders_count($inp_date);
$todays_mapped_orders = $dm->getMappedOrders($inp_date);
$mapped_vehs = get_Mapped_Zone_Slot();

?>
<style>
.zoneName{background-color:#CCCCCC !important;font-weight:bold;text-align: center;color:#000 !important;}
.table th{background-color:#CCCCCC !important;font-weight:bold;text-align: center;color:#000 !important;}
#droptableid .orderDisp{font-weight:bold;text-align:center;color:#FE6700;}
.vehcntIssue{border:red solid 2px !important;}
</style>
<?php
$sb = array(
    "Total Orders(With proper zones): {$todays_zs_orders['total']}",
    "Date: $dispDate"
);
echo excel_header('Order Mapping', $sb);
?>

    <table class="table" id='droptableid' >
        <?php
        echo "<tr>";
        echo '<th class="zneSlt" >Zones/Slots</th>';
        foreach($allSlots as $sid=>$sText){
            echo "<th id='slot$sid'>$sid</th>";
        }
        echo "</tr>";
        ?>
        <?php
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
                            $t[] =  "$i] {$sO['oid']}";
                        }
                        $teText = implode('<br/>', $t);
                        $text .= "Vehicle No: {$sO['vehno']}===><br/>$teText<br/>";
                    }
                }
                
                echo "<td class='orderDisp $assgn_veh_issue' >$text</td>"; //vehcntIssue
            }
            echo "</tr>";
        }
        ?>
        
    </table>
    
    <br/>
    
    <table style='font-weight:bold;'>
        <tr><td colspan='7'>Note: Maximum <?php echo $max_orders;?> orders can be allotted for 1 vehicle in 1 box</td></tr>
    </table>
    
