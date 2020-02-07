<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
?>
<script>
    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#deviceid').val(Value);
        jQuery('#display').hide();
    }

    function getHumidityTempReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#tempreportForm").serialize();
        jQuery.ajax({
            url: "humiditytemprep_ajax.php",
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
<?php if ($_REQUEST['id'] == 48 && isset($_REQUEST['vehicleid'])) { ?>
            getHumidityTempReport();
<?php }
?>
    });

</script>
<script src="../../scripts/highcharts/js/highcharts.js" type='text/javascript'></script>
<script src="../../scripts/highcharts/js/modules/exporting.js" type='text/javascript'></script>
<form action="reports.php?id=48" method="POST" onsubmit="getHumidityTempReport();
        return false;" id="tempreportForm">
      <?php
      $getvehicleid1 = "";
      $getvehicleno = "";
      $flag = false;
      if (isset($_REQUEST['vehicleid'])) {
          $getvehicleid1 = $_REQUEST['vehicleid'];
          $vehicleDetails = getvehicle_byID($getvehicleid1);
          if(isset($vehicleDetails) && !empty($vehicleDetails)){
            $getvehicleno = $vehicleDetails->vehicleno;
             $flag = TRUE;
          }
         
      }
      if ($_SESSION['switch_to'] == 3) {
          if (isset($_SESSION['Warehouse'])) {
              $vehicle = $_SESSION['Warehouse'];
          } else {
              $vehicle = 'Warehouse';
          }
      } else {
          $vehicle = 'Vehicle No';
      }
   
      $today = date('d-m-Y');
      $test = 2;
      include 'panels/humiditytemprep.php';

      if (isset($_SESSION['ecodeid'])) {
          ?>
        <input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate']; ?>" />
        <input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate']; ?>" />
        <?php
    }
    ?>
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
            <input  type="text" name="vehicleno" id="vehicleno" size="17" autocomplete="off" value="<?php echo $getvehicleno; ?>" placeholder="Enter <?php echo $vehicle; ?>" required>
            <input type="hidden" name="deviceid" id="deviceid" size="20" value="<?php echo $_REQUEST['deviceid']; ?>"/>
            <div id="display" class="listvehicle"></div>
        </td>
        <?php
        if ($_SESSION["temp_sensors"] == $test) {
            echo '<td><select id="tempsel" name="tempsel"><option value="1">Temperature 1</option>';
            echo '<option value="2">Temperature 2</option></select></td>';
        }
        ?>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00"/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
        <td>
            <select id="interval" name="interval" required>
                <option value="1">1</option>                                    
                <option value="15" <?php if ($flag == true) {
            echo "selected";
        } ?>>15</option>
                <option value="30">30</option>
                <option value="60">60</option>                
                <option value="120">120</option>                                                                                            
            </select>
        </td>        
        <td>
            <input type="submit"   class="g-button g-button-submit" value="Generate" name="GetReport">

            <a href='javascript:void(0)' onclick="get_pdfreportTempHumidity(<?php echo $_SESSION['customerno']; ?>, <?php echo $_SESSION['switch_to']; ?>);"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>

            <a href='javascript:void(0)' onclick="html2xlsTempHumidity(<?php echo $_SESSION['customerno']; ?>, <?php echo $_SESSION['switch_to']; ?>);
                    return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>

            <a href='javascript:void(0)' onclick="get_temp_humidity_print(<?php echo $_SESSION['customerno']; ?>);"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
            <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#mailStatus").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>

        </td>
    </tr>
</tbody>
</table>
</form>
<br><br>

<center id='centerDiv'></center>
<?php
$mail_function = "send_tempandhumidity_mail(" . $_SESSION['customerno'] . "," . $_SESSION['switch_to'] . ");";
include_once "mail_pop_up.php";
?>
