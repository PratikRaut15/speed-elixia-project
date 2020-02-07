<style>
#div {
    background-color: yellow;
    padding: 20px;
    margin-top: -500px;
    display: none;
}
#div img {
    width: auto;
    height: auto;
}
a:hover + #div {
    display: inline;
    position: absolute;
    content: "";
}
</style>
<?php
if (isset($_GET['vehicleno']) && $_GET['vehicleno']) {
    $vehicleid = $_GET['vehicleno'];
    /*Get vehicle number*/
    $vehicleNo         = getVehicleNumberFromId($vehicleid);
    $deviceNo          = getDeviceNumberFromVehicle($vehicleid);
}
?>
<form action="reports.php?id=13" method="POST" onsubmit="getTempReport();return false;" id="tempreportForm">
    <?php
    $getvehicleid1 = "";
    $getvehicleno = "";
    $devid = "";
    if (isset($_REQUEST['vehicleid'])) {
        $getvehicleid1  = $_REQUEST['vehicleid'];
        $vehicleDetails = getvehicle_byID($getvehicleid1);
        $getvehicleno   = $vehicleDetails->vehicleno;
          //  $temperatureName = getName_ByType($nid);
    }
    if (isset($_REQUEST['devid'])) {
        $devid = $_REQUEST['devid'];
    }
  // $tempsen = $_SESSION['temp_sensors'];
    if (isset($_REQUEST['tempsen'])) {
        $tempsen = $_REQUEST['tempsen'];
    }
    if ($_SESSION['switch_to'] == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $vehicle = $_SESSION['Warehouse'];
        } else {
            $vehicle = 'Warehouse';
        }
    } else {
        $vehicle = 'Vehicle No.';
    }
    $today = date('d-m-Y');
   // $test = 2;
    include 'panels/tempreptabular.php';
    if (isset($_SESSION['ecodeid'])) {
        ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <?php
         if(isset($_SESSION['codecalculateddate'])){
        ?>
              <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y', strtotime($_SESSION['codecalculateddate'])); ?>" />
        <?php
         }
        ?>
    <?php } ?>
    <tr>
        <td><?php echo $vehicle; ?></td>
        <?php
        if ($_SESSION["temp_sensors"]){
            echo "<td></td>";
        }
        ?>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td>Interval[mins]</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="17" value="<?php echo $getvehicleno; ?>"  autocomplete="off" placeholder="Enter <?php echo $vehicle; ?>" required>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value="<?php echo $devid; ?>"/>
            <input type="hidden" name="vno" id="vno" value="<?php echo $getvehicleno; ?>"/>

            <input type="hidden" name="customMinTemp" id="customMinTemp" value="-1"/>
            <input type="hidden" name="customMaxTemp" id="customMaxTemp" value="-1"/>
            <?php
            if(isset($vehicleNo) && $vehicleNo != '')
            {
            ?>
                <input type="hidden" id="hiddenvehicleno" name="hiddenvehicleno" value="<?php echo $vehicleNo; ?>" />
            <?php
            } ?>
             <?php
            if(isset($deviceNo) && $deviceNo != '')
            {
            ?>
            <input type="hidden" id="hiddendeviceno" name="hiddendeviceno" value="<?php echo $deviceNo; ?>" />
            <?php } ?>
            <div id="display" class="listvehicle"></div>
        </td>
             <?php
        if (isset($_SESSION["temp_sensors"])){
            ?>
             <td>
                <select id="tempsel" name="tempsel">
             <?php
                    if($_SESSION["temp_sensors"] == "1"){ 
                        if ($tempsen == 1) { $selected = "selected"; }  else {   $selected="";   }  ?>
                            <option value="1-0" <?php echo $selected;?>>Temperature 1</option>';
                      <?php
                        }
                        else if($_SESSION["temp_sensors"] == "2"){ 
                            if ($tempsen == 1) {$selected="selected";} else {  $selected="";}  ?>
                                <option value="1-0" <?php echo $selected;?>>Temperature 1</option>
                     <?php           
                    if ($tempsen == 2) { $selected = "selected"; } else {   $selected = "";  }?>
                                <option value="2-0" <?php echo $selected;?> >Temperature 2</option>
                                <option value="<?php echo $_SESSION['temp_sensors']; ?>-all">All</option>
                            <?php
                        }
                        else if($_SESSION["temp_sensors"] == "3"){
                              if ($tempsen == 1) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="1-0" <?php echo $selected;?>>Temperature 1</option>
                                    <?php   if ($tempsen == 2) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="2-0" <?php echo $selected;?>>Temperature 2</option>
                                 <?php   if ($tempsen == 3) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="3-0" <?php  echo $selected;?>>Temperature 3</option>
                                <option value="<?php echo $_SESSION['temp_sensors']; ?>-all">All</option>
                            <?php
                        }
                        else if($_SESSION["temp_sensors"] == "4"){
                         if ($tempsen == 1) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="1-0" <?php echo $selected;?>>Temperature 1</option>
                                <?php  if ($tempsen == 2) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="2-0"<?php echo $selected;?> >Temperature 2</option>
                               <?php  if ($tempsen == 3) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="3-0"  <?php echo $selected;?>>Temperature 3</option>
                                <?php  if ($tempsen == 4) { $selected = "selected"; }  else {   $selected="";   }  ?>
                                <option value="4-0" <?php echo $selected;?>>Temperature 4</option>
                                <option value="<?php echo $_SESSION['temp_sensors']; ?>-all">All</option>
                            <?php
                        } ?>
                </select>
            </td>
            <?php
            }
        ?>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00"/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
        <td>
            <select id="interval" name="interval" required>
                <option value="120">120</option>
                <option value="60">60</option>
                <option value="30">30</option>
                <option value="15" <?php if (isset($_REQUEST['devid'])) {echo "selected";} ?>>15</option>
                <option value="10">10</option>
                <option value="5">5</option>
                <option value="1" >1</option>
            </select>
        </td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportTemp(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);">
                <img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsTemp(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
                    return false;">
                <img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a href='javascript:void(0)' onclick="get_temp_print(<?php echo $_SESSION['customerno']; ?>);">
                <img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("")';>
                <img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" />
            </a>
        </td>
    </tr>
</tbody>
</table>
<input type="hidden" id="tripmin" name="tripmin" value="">
<input type="hidden" id="tripmax" name='tripmax' value="">
</form>
<br><br>
<center id='centerDiv'></center>
<?php
$mail_function = "send_temp_mail(" . $_SESSION['customerno'] . ", " . $_SESSION['switch_to'] . ");";
include_once "mail_pop_up.php";
?>
<script type="text/javascript">
    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vno').val(strparam);
        jQuery('#deviceid').val(Value);
        jQuery('#display').hide();
    }
    function getTempReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#tempreportForm").serialize();
        jQuery.ajax({
            url: "temprep_ajax.php",
            type: 'POST',
            data: data,
            success: function (result) {
                jQuery("#centerDiv").html(result);
            },
            complete: function () {
                jQuery('#pageloaddiv').hide();
            }
        });
    }
    $(function () {
        var userkey = GetParameterValues('userkey');
        if (userkey != '' && typeof userkey !== "undefined") {
            $('#divheader').hide();
            $("#footer").hide();
        }
        var tripmin = GetParameterValues('tripmin');
        var tripmax = GetParameterValues('tripmax');
        var SDate = GetParameterValues('sdate');
        var STime = GetParameterValues('stime');
        if (STime == undefined) {
            STime = "00:00";
        }
        var EDate = GetParameterValues('edate');
        var ETime = GetParameterValues('etime');
        if(ETime == undefined){
              ETime = "23:59";
        }
        var interval = GetParameterValues('interval');
        //var deviceid = GetParameterValues('deviceid');
        //var vehicleno = decodeURI(GetParameterValues('vehicleno'));
        var vehicleno = $("#hiddenvehicleno").val();
        var deviceid  = $("#hiddendeviceno").val();
        var temp = GetParameterValues('temp');
        var customMinTemp = GetParameterValues('customMinTemp');
        var customMaxTemp = GetParameterValues('customMaxTemp');
        if (SDate != undefined) {
            jQuery('#SDate').val(SDate);
            jQuery('#STime').val(STime);
            jQuery('#EDate').val(EDate);
            jQuery('#ETime').val(ETime);
            jQuery('#interval').val(interval);
            jQuery('#vehicleno').val(vehicleno);
            jQuery('#deviceid').val(deviceid);
            jQuery('#tempsel').val(temp);
            jQuery('#tripmin').val(tripmin);
            jQuery('#tripmax').val(tripmax);
            jQuery('#customMinTemp').val(customMinTemp);
            jQuery('#customMaxTemp').val(customMaxTemp);
            getTempReport();
        }
<?php if (isset($_REQUEST['tempsen'])) { ?>
            jQuery('#tempsel').val(<?php echo $tempsen; ?>);
            getTempReport();
<?php } ?>
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete.php",
            ajaxParams: "dummydata=location",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });
        $("#tempsel").focus(function(){
             var deviceid     = $('#deviceid').val();
             var data         = {nomensdeviceid :deviceid};
            jQuery.ajax({
                url: "temprep_ajax.php",
                type: 'POST',
                data: data,
                datatype :html,
                success: function (data){
                      htmlResult  ='';
                    result= jQuery.parseJSON(data);
                    var count=0;
                    $.each(result, function(i, item) {
                       count=count+1;
                      htmlResult += '<option value='+i+' id="hddn_'+i+'">'+item+'</option>';
                    })
                     htmlResult += '<option value="'+count+'-all">All</option>';
                  jQuery("#tempsel").html(htmlResult);
                },
                complete: function (){
                }
            });
        })
    });
</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
