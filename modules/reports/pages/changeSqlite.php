<?php 
   //echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
    //echo "<script src='" . $_SESSION['subdir'] . "/scripts/routehist_new.js' type='text/javascript'></script>";
    //echo "<script src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js' type='text/javascript'></script>";
?>
<script type="text/javascript">
function getSqliteReport(){
    var vehicleid = jQuery("#vehicleid").val();
    if (vehicleid == '' || vehicleid == undefined) {
    
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
//    var STime = jQuery("#STime").val();
//    var ETime = jQuery("#ETime").val();
//    if(STime >= ETime){
//        alert("Please enter correct time");
//        return false;
//    }

    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#changeSqlite").serialize();
    jQuery.ajax({
        //url:"vehicledistancereport_ajax.php",
        url:"changeSqlite_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}


/* Auto dropdown for Vehicle No. */
$(function () {
    jQuery("#vehicleno").autocomplete({
        source: "autocomplete.php?action=dummyData",
        minLength: 1,
        select: function (event, ui) {
            jQuery('#vehicleid').val(ui.item.vehicleid);
            jQuery('#deviceid').val(ui.item.deviceid);
            

        }
    }); 
}); 


</script>

<?php
echo '<script src="../../scripts/highstock/highstock.js" type="text/javascript"></script>';
echo '<script src="../../scripts/highcharts/js/modules/exporting.js" type="text/javascript"></script>';


$display_date = date('d-m-Y');
$sdate1 = date('d-m-Y');
$edate1 = date('d-m-Y');
$vehicleno = isset($_POST["vehicleno"]) ? $_POST["vehicleno"] : '';
$vehicleid = isset($_POST["vehicleid"]) ? $_POST["vehicleid"] : '';
?>

<form action="reports.php?id=changeSqlite" method="POST" id="changeSqlite" onsubmit="getSqliteReport();return false;">
<?php
$title = "Sqlite Report";
$date = date('d-m-Y');
include 'panels/distancereport.php';
if(isset($_SESSION['ecodeid'])){
    $_SESSION['groupid'] = isset($_SESSION['groupid']) ? $_SESSION['groupid']: 0;
?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
<input type="hidden" name="days" id="days" value="<?php echo $_SESSION['days'];?>" />
<?php
}
?>
    <tr>
        <td>Vehicle Number</td>
        <td>Start Date</td>
        <td>Start Hour</td>
        <td>End Date</td>
        <td>End Hour</td>
        <td>Interval</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="17" value="<?php echo $vehicleno; ?>" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value="<?php echo $deviceid; ?>"/>
        
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td>
            <input id="STime" name="STime" type="text" class="input-mini" />
        </td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $date; ?>" required/></td>
        <td>
            <input id="ETime" name="ETime" type="text" class="input-mini" />
        </td>
         <td>
            <select id="interval" name="interval" required>
                <option value="120">120</option>
                <option value="60">60</option>
                <option value="30">30</option>
                <option value="15" selected >15</option>
                <option value="10">10</option>
                <option value="5">5</option>
                <option value="1" >1</option>
            </select>
        </td>

        <td>
            <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
        </td>
    </tr>
</tbody>
</table>
</form>
<br/><br/>
<center id='centerDiv'></center>

