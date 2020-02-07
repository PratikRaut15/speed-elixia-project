<?php
$customerno = exit_issetor($_SESSION['customerno']);
$orderid = exit_issetor($_GET['oid']);

$dm = new DeliveryManager($customerno);
$details = $dm->getorder_details($orderid);
if(empty($details)){
    exit('Order not found');
}

$lat = $details->lat;
$longi = $details->longi;
$accu = $details->accuracy;

function accuracyDropdwn($accu){
    $drp = array(1=>'Accurate', 2=>'Normal', 3=>'Weak');
    $cma = '<select name="accuracy">';
    foreach($drp as $key=>$text){
        if($accu==$key){
            $cma .= "<option value='$key' selected>$text</option>";
        }
        else{
            $cma .= "<option value='$key'>$text</option>";
        }
    }
    $cma .=  "</select>";
    return $cma;
}
?>
<div class="container-fluid">
    <form id="updateLocation" type='POST' action='' onsubmit='updateLocation();return false;'>
    <table>
        <tr>
            <th colspan="100%">Update Location</th>
        </tr>
        <tr id='ajaxStatus' ><td colspan="100%" style='text-align:center;'></td></tr>
        <tr>
            <td></td>
            <td>Latitude</td>
            <td>Longitude</td>
            <!--<td>Address</td>-->
            <td>Accuracy</td>
            <td></td>
        </tr>
        <tr>
            <td><a href='assign.php?id=3'>&lt;&lt;Back to orders</a></td>
            <input type='hidden' name='orderid' value='<?php echo $orderid;?>'/>
            <td><input type='text' name='latitude' id='latitude' value='<?php echo $lat; ?>' /></td>
            <td><input type='text' name='longitude' id='longitude'value='<?php echo $longi; ?>' /></td>
            <!--<td><input type='text'  id='address'/></td>-->
            <td><?php echo accuracyDropdwn($accu);?></td>
            <td><input type='submit' value='Submit'/></td>
        </tr>
    </table>
    </form>
    <!-- Location search div-->
    <div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;width:360px;height:auto; display:none;position:absolute; left:20%; z-index:100;">
    <div id="chk_box" style="width:350px; height:auto; float:left; text-align:left;">
        <a class="a" id="address"> Search </a>  <input type="text" name="chkA" id="chkA"  class="chkp_inp" style="width: 280px;">&nbsp;
    </div>	
    </div>
    <!-- Location search div ends -->

    <!-- map div-->
    <div id="map" class="map" style="float:left;  height:550px"></div>
    <!-- map div ends-->
    
    <div style="clear: both;">&nbsp;</div>
    
</div>
<script>
     var initStartLat = <?php if($lat){echo $lat;}else{ echo 19.06;} ?>;
     var initStartLong = <?php if($longi){echo $longi;}else{echo 72.89;} ?>;
</script>
    