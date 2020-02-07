
<form action="reports.php?id=16" method="POST" id='locationreportForm' onsubmit="getLocationReport();
            return false;">
<?php 
if (isset($_GET['vehicleno']) && $_GET['vehicleno']) {
    $vehicleid = $_GET['vehicleno'];
    /*Get vehicle number*/
    $vehicleNo = getVehicleNumberFromId($vehicleid);
    $deviceNo = getDeviceNumberFromVehicle($vehicleid);
}
if($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal']=='Custom') {
    $SDate = "id='sdate' readonly" ;
    $EDate = "id='edate' readonly" ;
    $STime = "id='stime' readonly" ;
    $ETime = "id='etime' readonly" ;
    $vehicleno = "id='vehicleno1' name='vehicleno1' readonly" ;
    $frequency = "id='frequency' readonly" ; 
    $interval = "id='interval' readonly" ; 
    
?>
        <input id='SDate' name="STdate" type="hidden" value="<?php echo $date; ?>"/>
        <input id='STime' name="STime" type="hidden" class="input-mini" data-date="00:00" />
        <input id='EDate' name="EDdate" type="hidden" value="<?php echo $date; ?>" required/>
        <input id='ETime' name="ETime" type="hidden" class="input-mini" data-date="23:59" />
        <input type="hidden" name="vehicleno" id='vehicleno' size="18" value="" />
<?php
}else{
    $SDate = "id='SDate'" ;
    $EDate = "id='EDate'" ;
    $STime = "id='STime'" ;
    $ETime = "id='ETime'" ;
    $vehicleno = "id='vehicleno' name='vehicleno'" ;
    $frequency = "id='frequency'" ; 
    $interval = "id='interval'" ; 
}

 ?>

          <?php
          $title = 'Location Report';
          $date = date('d-m-Y');
          include 'panels/locationreport.php';
          if (isset($_SESSION['ecodeid'])) {
              ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y',strtotime($_SESSION['codecalculateddate']));?>" />
        <?php
    }
    ?>
    <tr>
        <td>Vehicle No.</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td colspan='3'></td>
    </tr>
<?php //echo $_SESSION['role_modal']; //if($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal']=='Custom'){ ?>
    <tr>
        <td>
            <input type="text" <?php echo $vehicleno; ?> size="18" value="" placeholder="Enter Vehicle No" autocomplete="off" required/>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value=""/>
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
        <td><input <?php echo $SDate; ?> name="STdate" type="text" value="<?php echo $date; ?>"/></td>
        <td><input <?php echo $STime; ?> name="STime" type="text" class="input-mini" data-date="00:00" /></td>
        <td><input <?php echo $EDate; ?> name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td><input <?php echo $ETime; ?> name="ETime" type="text" class="input-mini" data-date="23:59" /></td>
        <td>
            <select <?php echo $frequency; ?> name="frequency" onchange="selecttype();">
                <option value="1">Time</option>
                <option value="2">Distance</option>
            </select>
        </td>
        <td id="intervalid">Every
            <select <?php echo $interval; ?> name="interval">
                <option value="60">60</option>
                <option value="30">30</option>
                <option value="10">10</option>
                <option value="5">5</option>
                <option value="1">1</option>
            </select> mins
        </td>
        <td id="distanceid" style="display:none;">Every
            <select id="distance" name="distance">
                <option value="1">1</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select> kms
        </td>
        <td>
            <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportLocation(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlslocation(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);
                                return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
            <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
        </td>
    </tr>
<?php //}//else{
?>
   <!--  <tr>
       <td>
           <input  type="text" name="vehicleno" id="vehicleno" size="18" value="" placeholder="Enter Vehicle No" autocomplete="off" required/>
           <input type="hidden" name="deviceid" id="deviceid" size="20" value=""/>
           <div id="display" class="listvehicle"></div>
       </td>
       <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
       <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
       <td><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
       <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date="23:59" /></td>
       <td>
           <select id="frequency" name="frequency" onchange="selecttype();">
               <option value="1">Time</option>
               <option value="2">Distance</option>
           </select>
       </td>
       <td id="intervalid">Every
           <select id="interval" name="interval">
               <option value="60">60</option>
               <option value="30">30</option>
               <option value="10">10</option>
               <option value="5">5</option>
               <option value="1">1</option>
           </select> mins
       </td>
       <td id="distanceid" style="display:none;">Every
           <select id="distance" name="distance">
               <option value="1">1</option>
               <option value="5">5</option>
               <option value="10">10</option>
               <option value="20">20</option>
           </select> kms
       </td>
       <td>
           <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
           <a href='javascript:void(0)' onclick="get_pdfreportLocation(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
           <a href='javascript:void(0)' onclick="html2xlslocation(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['userid']; ?>);
                               return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
           <a onclick="standardized_print('<?php echo $title; ?>');" href="javascript:void(0)"><img title="Print Report" class="exportIcons" alt="Print Report" src="../../images/print.png"></a>
           <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>
       </td>
   </tr> -->
<?php
//} ?>

</tbody>
</table>
<input type="hidden" name="triplogno" id="triplogno" value="">
<input type="hidden" name="userkey" id="userkey" value="">
<input type="hidden" name="tripid" id="tripid" value="">
</form>
<br/><br/>
<center id='centerDiv'></center>
<script type="text/javascript" src="createcheck.js"></script>
<?php
$mail_function = "send_location_historydetails_mail(" . $_SESSION['customerno'] . ");";
include_once "mail_pop_up.php";
?>

<script>
            function getLocationReport() {
                        jQuery('#centerDiv').html('');
                                jQuery('#pageloaddiv').show();
                                var data = jQuery("#locationreportForm").serialize();
                                jQuery.ajax({
                                url: "locationreport_ajax.php",
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
                var deviceid  = $("#hiddendeviceno").val();
                var triplogno = GetParameterValues('triplogno');
                var tripid = GetParameterValues('tripid');      
                var SDate = GetParameterValues('sdate');
                var STime = GetParameterValues('stime');
                var EDate = GetParameterValues('edate');
                var ETime = GetParameterValues('etime');
                var interval = GetParameterValues('interval');
                var vehicleno = decodeURI(GetParameterValues('vehicleno'));
                var vehicle_no = $("#hiddenvehicleno").val();
                if (typeof vehicle_no !== 'undefined' && vehicle_no != '') {
                    var vehicleno = vehicle_no;
                }
                var deviceid = decodeURI(GetParameterValues('deviceid'));
                var distance = 1;
                var frequency = 1;
                if (SDate != undefined) {
                    jQuery('#triplogno').val(triplogno);
                    jQuery('#tripid').val(tripid);
                <?php if($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal']=='Custom'){ ?>  
                        jQuery('#sdate').val(SDate);
                        jQuery('#stime').val(STime);
                        jQuery('#edate').val(EDate);
                        jQuery('#etime').val(ETime);

                        jQuery('#SDate').val(SDate);
                        jQuery('#STime').val(STime);
                        jQuery('#EDate').val(EDate);
                        jQuery('#ETime').val(ETime);
                        
                        jQuery('#vehicleno1').val(vehicleno);
                <?php }else{ ?>        
                        jQuery('#SDate').val(SDate);
                        jQuery('#STime').val(STime);
                        jQuery('#EDate').val(EDate);
                        jQuery('#ETime').val(ETime);
                <?php } ?>
                        jQuery('#interval').val(interval);
                        jQuery('#vehicleno').val(vehicleno);
                        jQuery('#deviceid').val(deviceid);
                        jQuery('#userkey').val(userkey);
                        getLocationReport();
                }
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
                });
                        function fill(Value, strparam) {
                        jQuery('#vehicleno').val(strparam);
                                jQuery('#deviceid').val(Value);
                                jQuery('#display').hide();
                        }

                function getTemperatureReport(userkey, tripmin, tripmax, edate, etime, sdate, stime, deviceid, interval, vehicleno, temp) {
                window.open('../reports/reports.php?id=13&userkey=' + userkey + '&tripmin=' + tripmin + '&tripmax=' + tripmax + '&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&interval=' + interval + '&vehicleno=' + vehicleno + '&temp=' + temp, '_blank');
                }
                function getSpeedReport(userkey, edate, etime, sdate, stime, deviceid, vehicleno) {
                window.open('../reports/reports.php?id=14&userkey=' + userkey + '&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&vehicleno=' + vehicleno, '_blank');
                }
                function getGensetReport(userkey, edate, sdate, deviceid, vehicleno) {
                window.open('../reports/reports.php?id=45&userkey=' + userkey + '&sdate=' + sdate + '&edate=' + edate + '&deviceid=' + deviceid + '&vehicleno=' + vehicleno, '_blank');
                }

                function getRouteHistReport(userkey, vehicleid, edate, etime, sdate, stime, deviceid, vehicleno) {
                window.open('../reports/reports.php?userkey=' + userkey + '&vehicleid=' + vehicleid + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&deviceid=' + deviceid + '&vehicleno=' + vehicleno, '_blank');
                }


</script>
