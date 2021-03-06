
<?php
$elixiacode = getElixiaCode($_REQUEST['ecode']);
$codevehicle = getElixiaCodeVehicles($_REQUEST['ecode']);
$route_dashboard_customer = explode(",", speedConstants::ROUTE_DASHBOARD_CUSTNO);
$category = (int) $elixiacode->menu;
    $binarycategory = sprintf("%08s",DecBin($category));
    $category_array = array(0);
    for($shifter=1;$shifter<=536870912;$shifter=$shifter<<1)
    {
        $binaryshifter = sprintf("%08s",DecBin($shifter));
        if($category & $shifter)
        {
             $category_array[]= $shifter;
        }
    }
?>
<script type="text/javascript">
$(document).ready(function(){
    $("#vehicleno").autoSuggest({
     ajaxFilePath: "autocomplete.php",
     ajaxParams: "dummydata=dummyData",
     autoFill: false,
     iwidth: "auto",
     opacity: "0.9",
     ilimit: "10",
     idHolder: "id-holder",
     match: "contains"
   });
    $("#STime").val("<?php echo date('H:i', strtotime($elixiacode->startdate))?>");
    $("#ETime").val("<?php echo date('H:i', strtotime($elixiacode->enddate))?>");
});
function fill(Value, strparam)
 {
   jQuery('#vehicleno').val(strparam);
   jQuery('#vehicleid').val(Value);
   jQuery('#display').hide();
   addvehicle(Value, strparam)
 }
</script>
<form name="updatecode" id="updatecode">

<?php include 'panels/createecode.php'; ?>
    <tr>
        <td >Start Date</td>
        <td colspan="2" style="text-align:center;">
        <?php $currentdate = getcurrentdate();?>
            <input id="currentdate" name="currentdate" type="hidden" value="<?php echo date('d-m-Y H:i:s',$currentdate);?>">
            <input name="SDate" id="SDate" type="text" value="<?php echo date('d-m-Y',  strtotime($elixiacode->startdate));?>" required/>
        </td>
        <td >Time</td>
        <td  colspan="4">
                    <input id="STime" name="STime" type="text" value="<?php echo date('H:i', strtotime($elixiacode->startdate))?>" />
        </td>
    </tr>
    
    <tr>
        <td>End Date</td>
        <td colspan="2" style="text-align:center;">
        <?php $currentdate = getcurrentdate();?>
            <input name="EDate" id="EDate" type="text" value="<?php echo date('d-m-Y',strtotime($elixiacode->enddate));?>" required/>
        </td>
        <td >Time</td>
        <td colspan="4">
                    <input id="ETime" name="ETime" type="text" value="<?php echo date('H:i', strtotime($elixiacode->enddate))?>"  />
        </td>
    </tr>
    
    <tr>
        <td >Select Vehicles</td>
        <td colspan="2">
            <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
            <select id="vehicleroute" name="vehicleroute"  style="display: none;">
                
                <?php
                $vehicles = getvehicles();
                foreach ($vehicles as $vehicle)
                {
                    echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                }
                ?>
            </select>
        </td>
        <td colspan="4">
            <input type="button" value="Add All" onclick="addallvehicle()" class="g-button g-button-submit">
        </td>
    </tr>
   
    <tr>
        <td colspan="100%"><div id="vehicle_list"> 
        <?php 
            if(isset($codevehicle))
            {
                foreach($codevehicle  as $v)
                {
                    echo '<div class="recipientbox" id="to_vehicle_div_'.$v->vehicleid.'"><span>'.$v->vehicleno.'</span>
                         <input type="hidden" value="'.$v->vehicleid.'" name="to_vehicle_'.$v->vehicleid.'">
                         <img class="clickimage" src="../../images/boxdelete.png" onclick="removeVehicle('.$v->vehicleid.')" ></div>';
                }
            }
        ?>
        </td>
    </tr>
        <?php $ecoderandom = mt_rand(0, 999999); ?>
    <tr>
        <th colspan="100%" style="text-align:center; background-color: #4D90FE; color: white; font-size: 13px;">Share Your Code : <?php echo $elixiacode->ecodeid; ?>
            <input type="hidden" name="randomecode" id="randomecode" value="<?php echo $elixiacode->ecodeid; ?>"></input>
            <input type="hidden" name="e_id" id="e_id" value="<?php echo $elixiacode->id; ?>"></input>
        </th>
    
    </tr>
    <tr>
        <td style="text-align:center;">Email</td>
        <td colspan="2" style="text-align:center;"><input type="text" name="email" id="email" value="<?php echo $elixiacode->email;?>"></td>
        <td style="text-align:center;">SMS</td>
        <td colspan="4" style="text-align:center;"><input type="text" name="sms" id="sms" value="<?php if($elixiacode->sms > 0)echo $elixiacode->sms ;?>"></td>
        
    </tr>
      <tr>
          <td colspan="1">Days</td><td colspan="1"><input type="text" name="days" id="days" value="<?php echo $elixiacode->days; ?>" placeholder="For e.g 10"></td>
    </tr>
    
    
</tbody>
</table>

<center>
   <?php
   
   if(!empty($category_array))
   {
       ?>
    <div  style="float:none; padding-left:25%;">
        
        <table id="floatingpanel" style="text-align :left;">
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle History</th>
                 </tr>
                 <tr>
                    <td>Route History</td>
                    <td><input type="checkbox" id="rh" name="checkbox[]" value="1" <?php if(in_array(1,$category_array)){ echo "checked"; }  ?> ></td>
                 </tr>
                 <tr>
                    <td> Travel History</td>
                    <td><input type="checkbox" id="th" name="checkbox[]" value="2" <?php if(in_array(2,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Alert History</td>
                    <td><input type="checkbox" id="ah" name="checkbox[]" value="4" <?php if(in_array(4,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Fuel Refill History</td>
                    <td><input type="checkbox" id="fh" name="checkbox[]" value="8" <?php if(in_array(8,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>   Location History</td>
                    <td><input type="checkbox" id="lh" name="checkbox[]" value="16" <?php if(in_array(16,$category_array)){ echo "checked"; }  ?>"></td>
                 </tr>
                 <tr>
                    <td>  Stoppage History</td>
                    <td><input type="checkbox" id="sh" name="checkbox[]" value="32" <?php if(in_array(31,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>   Overspeed History</td>
                    <td><input type="checkbox" id="oh" name="checkbox[]" value="64" <?php if(in_array(64,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Genset History</td>
                    <td><input type="checkbox" id="gh" name="checkbox[]" value="128" <?php if(in_array(128,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                <tr>
                    <td>Distance History</td>
                    <td><input type="checkbox" id="dh" name="checkbox[]" value="8388608" checked="" <?php if(in_array(8388608,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
         
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle Reports</th>
                 </tr>
                 <tr>
                    <td>Trip Report</td>
                    <td><input type="checkbox" id="tr" name="checkbox[]" value="256" <?php if(in_array(256,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td> Checkpoint Report</td>
                    <td><input type="checkbox" id="cr" name="checkbox[]" value="1024" <?php if(in_array(1024,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Fuel Consumption Report</td>
                    <td><input type="checkbox" id="fcr" name="checkbox[]" value="2048" <?php if(in_array(2048,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
           
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle Analysis</th>
                 </tr>
                 <tr>
                    <td>Route Report</td>
                    <td><input type="checkbox" id="rar" name="checkbox[]" value="4096" <?php if(in_array(4096,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td> Distance Report</td>
                    <td><input type="checkbox" id="dar" name="checkbox[]" value="8192" <?php if(in_array(8192,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Idle-Time Report</td>
                    <td><input type="checkbox" id="iar" name="checkbox[]" value="16384" <?php if(in_array(16384,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Genset Report</td>
                    <td><input type="checkbox" id="gar" name="checkbox[]" value="32768" <?php if(in_array(32768,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Overspeed Report</td>
                    <td><input type="checkbox" id="oar" name="checkbox[]" value="65536" <?php if(in_array(65536,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Fence Conflict Report</td>
                    <td><input type="checkbox" id="fencear" name="checkbox[]" value="131072" <?php if(in_array(131072,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Location Report</td>
                    <td><input type="checkbox" id="lar" name="checkbox[]" value="262144" <?php if(in_array(262144,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Fuel Consumption Report</td>
                    <td><input type="checkbox" id="fcar" name="checkbox[]" value="524288" <?php if(in_array(524288,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Trip Report</td>
                    <td><input type="checkbox" id="tar" name="checkbox[]" value="1048576" <?php if(in_array(1048576,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                <tr>
                    <td>Checkpoint Report</td>
                    <td><input type="checkbox" id="char" name="checkbox[]" value="16777216" checked="" <?php if(in_array(16777216,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                     <tr>
                    <td>Enhanced Route Report</td>
                    <td><input type="checkbox" id="erar" name="checkbox[]" value="33554432" checked="" <?php if(in_array(33554432,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                     <tr>
                    <td>Stoppage Analyasis Report</td>
                    <td><input type="checkbox" id="saar" name="checkbox[]" value="67108864" checked="" <?php if(in_array(67108864,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                     <tr>
                    <td>Inactive Device Report</td>
                    <td><input type="checkbox" id="idar" name="checkbox[]" value="134217728" checked="" <?php if(in_array(134217728,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                <tr>
                    <td>Vehicle In Out Report</td>
                    <td><input type="checkbox" id="vioar" name="checkbox[]" value="268435456" checked="" <?php if(in_array(268435456,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                  <?php 
                  if (in_array($_SESSION['customerno'], $route_dashboard_customer)) {
                    ?>
                  <td>Route Dashboard</td>
                    <td><input type="checkbox" id="rdar" name="checkbox[]" value="536870912" checked="" <?php if(in_array(536870912,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <?php } ?>
            
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
                 <tr>
                    <th id="formheader" colspan="100%">Temperature Report</th>
                 </tr>
                 <tr>
                    <td>Graphical Format</td>
                    <td><input type="checkbox" id="gtr" name="checkbox[]" value="2097152" <?php if(in_array(2097152,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 <tr>
                    <td>Tabular Format</td>
                    <td><input type="checkbox" id="ttr" name="checkbox[]" value="4194304" <?php if(in_array(4194304,$category_array)){ echo "checked"; }  ?>></td>
                 </tr>
                 
            </form>
        </table>
        <div style="clear: both;"></div>
    </div>
       <?php
   }
   else
   {
       ?>
    <div  style="float:none; padding-left:25%;">
        
        <table id="floatingpanel" style="text-align :left;">
            
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle History</th>
                 </tr>
                 <tr>
                    <td>Route History</td>
                    <td><input type="checkbox" id="rh" name="checkbox[]" value="1" checked=""></td>
                 </tr>
                 <tr>
                    <td> Travel History</td>
                    <td><input type="checkbox" id="th" name="checkbox[]" value="2" checked=""></td>
                 </tr>
                 <tr>
                    <td>Alert History</td>
                    <td><input type="checkbox" id="ah" name="checkbox[]" value="4" checked=""></td>
                 </tr>
                 <tr>
                    <td>Fuel Refill History</td>
                    <td><input type="checkbox" id="fh" name="checkbox[]" value="8" checked=""></td>
                 </tr>
                 <tr>
                    <td>   Location History</td>
                    <td><input type="checkbox" id="lh" name="checkbox[]" value="16" checked=""></td>
                 </tr>
                 <tr>
                    <td>  Stoppage History</td>
                    <td><input type="checkbox" id="sh" name="checkbox[]" value="32" checked=""></td>
                 </tr>
                 <tr>
                    <td>   Overspeed History</td>
                    <td><input type="checkbox" id="oh" name="checkbox[]" value="64" checked=""></td>
                 </tr>
                 <tr>
                    <td>Genset History</td>
                    <td><input type="checkbox" id="gh" name="checkbox[]" value="128" checked=""></td>
                 </tr>
                     <tr>
                    <td>Distance History</td>
                    <td><input type="checkbox" id="dh" name="checkbox[]" value="8388608" checked=""></td>
                 </tr>
         
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
            
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle Reports</th>
                 </tr>
                 <tr>
                    <td>Trip Report</td>
                    <td><input type="checkbox" id="tr" name="checkbox[]" value="256" checked=""></td>
                 </tr>
                 <tr>
                    <td> Checkpoint Report</td>
                    <td><input type="checkbox" id="cr" name="checkbox[]" value="1024" checked=""></td>
                 </tr>
                 <tr>
                    <td>Fuel Consumption Report</td>
                    <td><input type="checkbox" id="fcr" name="checkbox[]" value="2048" checked=""></td>
                 </tr>
                 
            
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
           
                 <tr>
                    <th id="formheader" colspan="100%">Vehicle Analysis</th>
                 </tr>
                 <tr>
                    <td>Route Report</td>
                    <td><input type="checkbox" id="rar" name="checkbox[]" value="4096" checked=""></td>
                 </tr>
                 <tr>
                    <td> Distance Report</td>
                    <td><input type="checkbox" id="dar" name="checkbox[]" value="8192" checked=""></td>
                 </tr>
                 <tr>
                    <td>Idle-Time Report</td>
                    <td><input type="checkbox" id="iar" name="checkbox[]" value="16384" checked=""></td>
                 </tr>
                 <tr>
                    <td>Genset Report</td>
                    <td><input type="checkbox" id="gar" name="checkbox[]" value="32768" checked=""></td>
                 </tr>
                 <tr>
                    <td>Overspeed Report</td>
                    <td><input type="checkbox" id="oar" name="checkbox[]" value="65536" checked=""></td>
                 </tr>
                 <tr>
                    <td>Fence Conflict Report</td>
                    <td><input type="checkbox" id="fencear" name="checkbox[]" value="131072" checked=""></td>
                 </tr>
                 <tr>
                    <td>Location Report</td>
                    <td><input type="checkbox" id="lar" name="checkbox[]" value="262144" checked=""></td>
                 </tr>
                 <tr>
                    <td>Fuel Consumption Report</td>
                    <td><input type="checkbox" id="fcar" name="checkbox[]" value="524288" checked=""></td>
                 </tr>
                 <tr>
                    <td>Trip Report</td>
                    <td><input type="checkbox" id="tar" name="checkbox[]" value="1048576" checked=""></td>
                 </tr>
                    <tr>
                    <td>Checkpoint Report</td>
                    <td><input type="checkbox" id="char" name="checkbox[]" value="16777216" checked=""></td>
                 </tr>
                     <tr>
                    <td>Enhanced Route Report</td>
                    <td><input type="checkbox" id="erar" name="checkbox[]" value="33554432" checked=""></td>
                 </tr>
                     <tr>
                    <td>Stoppage Analyasis Report</td>
                    <td><input type="checkbox" id="saar" name="checkbox[]" value="67108864" checked=""></td>
                 </tr>
                     <tr>
                    <td>Inactive Device Report</td>
                    <td><input type="checkbox" id="idar" name="checkbox[]" value="134217728" checked=""></td>
                 </tr>
                     <tr>
                    <td>Vehicle In Out Report</td>
                    <td><input type="checkbox" id="vioar" name="checkbox[]" value="268435456" checked=""></td>
                 </tr>
                 <?php 
                  if (in_array($_SESSION['customerno'], $route_dashboard_customer)) {
                    ?>
                 <tr>
                <td>Route Dashboard</td>
                    <td><input type="checkbox" id="rdar" name="checkbox[]" value="536870912" checked=""></td>
                 </tr>
                 <?php } ?>
            
        </table>
        
        <table id="floatingpanel" style="text-align :left;">
                 <tr>
                    <th id="formheader" colspan="100%">Temperature Report</th>
                 </tr>
                 <tr>
                    <td>Graphical Format</td>
                    <td><input type="checkbox" id="gtr" name="checkbox[]" value="2097152" checked=""></td>
                 </tr>
                 <tr>
                    <td>Tabular Format</td>
                    <td><input type="checkbox" id="ttr" name="checkbox[]" value="4194304" checked=""></td>
                 </tr>
            </form>
        </table>
        <div style="clear: both;"></div>
    </div>
    <?php
   }
   ?>
    <table >
        
        <tr >
        <td colspan="100%" style="margin-top: 20px;"> <input type="button" value="Save" class="btn btn-mini btn-primary" onclick="chksubmitupdate();"></td>
    </tr>
</table>
</center>

<form>

