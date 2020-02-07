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
$getvehicleid1 = "";
$getvehicleno = "";
$devid = "";
if (isset($_REQUEST['devid'])) {
    $devid = $_REQUEST['devid'];
}
if (isset($_GET['vehicleno']) && $_GET['vehicleno']) {
    $vehicleid = $_GET['vehicleno'];
    /*Get vehicle number*/
    $vehicleNo   = getVehicleNumberFromId($vehicleid);
    $deviceNo    = getDeviceNumberFromVehicle($vehicleid);
    $devid = $deviceNo;

}elseif (isset($_REQUEST['vehicleid'])) {
    $getvehicleid1  = $_REQUEST['vehicleid'];
    $vehicleDetails = getvehicle_byID($getvehicleid1);
    $getvehicleno   = $vehicleDetails->vehicleno;
}
//echo $devid;
?>
<form action="reports.php?id=61" method="POST" onsubmit="getTempReport();return false;" id="tempreportForm">
    <?php
    $tempsen = 1;
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
    $test = 2;
    include 'panels/tempreptabular.php';
    if (isset($_SESSION['ecodeid'])) {
        ?>
        <input type="hidden" name="reportId" id="reportId" value="61" />
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days']; ?>" />
        <input type="hidden" name="calculateddate" id="calculateddate" value="<?php echo date('d-m-Y', strtotime($_SESSION['codecalculateddate'])); ?>" />
    <?php } ?>
    <tr>
        <td><?php echo $vehicle; ?></td>
        <?php
        if ($_SESSION["temp_sensors"] == $test) {
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
            <input type="hidden" id="hiddenvehicleno" name="hiddenvehicleno" value="<?php echo $vehicleNo; ?>" />
            <input type="hidden" id="hiddendeviceno" name="hiddendeviceno" value="<?php echo $deviceNo; ?>" />
            <input type="hidden" name="reportId" id="reportId" value="61" />
            <div id="display" class="listvehicle"></div>
        </td>
        <?php if ($_SESSION["temp_sensors"] == $test) { ?>
            <td><select id="tempsel" name="tempsel"><option value="1-0" <?php
                    if ($tempsen == 1) {
                        echo "selected";
                    }
                    ?> >Temperature 1</option>';
                    <option value="2-0" <?php
                if ($tempsen == 2) {
                    echo "selected";
                }
                ?>>Temperature 2</option>
                <option value="<?php echo $_SESSION['temp_sensors']; ?>-all">All</option>
            </select></td>
<?php }
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
                <option value="15" <?php if (isset($_REQUEST['devid'])) {
    echo "selected";
} ?>>15</option>
                <option value="10">10</option>
                <option value="5">5</option>
                <option value="1" >1</option>
            </select>
        </td>
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">
            <a href='javascript:void(0)' onclick="get_pdfreportTemp_nestle(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);">
                <img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
            <a href='javascript:void(0)' onclick="html2xlsTemp_nestle(<?php echo $_SESSION['customerno']; ?>,<?php echo $_SESSION['switch_to']; ?>);
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
            url: "temprep_ajax_nestle.php",
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
        if(STime == undefined){
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

        var searchString = decodeURI(GetParameterValues('vehicleno'));



        if (SDate != undefined) {

            jQuery('#SDate').val(SDate);
            jQuery('#STime').val(STime);
            jQuery('#EDate').val(EDate);
            jQuery('#ETime').val(ETime);
            jQuery('#interval').val(interval);
            jQuery('#vehicleno').val(vehicleno);
            if(searchString == undefined){
                jQuery('#deviceid').val(deviceid);
            }



            jQuery('#tempsel').val(temp);
            jQuery('#tripmin').val(tripmin);
            jQuery('#tripmax').val(tripmax);
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
    });
</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
