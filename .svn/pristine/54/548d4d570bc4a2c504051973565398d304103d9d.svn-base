<script>
    $(function () {
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
    });
    function getFuelReport() {
        jQuery('#centerDiv').html('');
        jQuery('#pageloaddiv').show();
        var data = jQuery("#fuelreportForm").serialize();
        jQuery.ajax({
            url: "new_fuel_report_ajax.php",
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
    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehicleid').val(Value);
        jQuery('#display').hide();

    }
</script>
<script src="../../scripts/highstock/highstock.js" type='text/javascript'></script>
<script src="../../scripts/highstock/modules/exporting.js"></script>
<form action="reports.php?id=21" method="POST"  id='fuelreportForm' onsubmit="getFuelReport();
        return false;">
    <?php
    if ($_SESSION['switch_to'] == 3) {
        if (isset($_SESSION['Warehouse'])) {
            $vehicle = $_SESSION['Warehouse'];
        } else {
            $vehicle = 'Warehouse';
        }
    } else {
        $vehicle = 'Vehicle No.';
    }
    $title = "Fuel Report";
    $today = date('d-m-Y');
    ?>
    <table>
        <thead>
            <tr><th id="formheader" colspan="100%"><?php echo $title; ?></th></tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="100%">
                    <span id="error" name="error" style="display: none;">Data Not Available</span>
                    <span id="error2" name="error2" style="display: none;">Please Check Start Date</span>
                    <span id="error3" name="error3" style="display: none;">Please Select A Vehicle</span>
                    <span id="error4" name="error4" style="display: none;">Date Selection Difference Not More Than 30 Days</span>
                    <span id="error7" name="error" style="display: none;">Data Not Available From Selection </span>
                    <span id="error8" name="error8" style="display: none;">Please Select Date Between  <?php echo date('Y-m-d', strtotime($_SESSION['startdate'])); ?> AND <?php echo date('Y-m-d', strtotime($_SESSION['enddate'])); ?></span>
                </td>
            </tr>

            <tr>
                <td><?php echo $vehicle; ?></td>
                <td>Start Date</td>
                <td>Start Hour</td>
                <td>End Date</td>
                <td>End Hour</td>
                <td>Interval[mins]</td>
                <td colspan="2" ></td>
            </tr>
            <tr>
                <td>
                    <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter <?php echo $vehicle; ?>" required>
                    <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                    <div id="display" class="listvehicle"></div>
                </td>
                <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
                <td><input id="STime" name="STime" type="text" class="input-mini" data-date="00:00" /></td>
                <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today; ?>" required/></td>
                <td><input id="ETime" name="ETime" type="text" class="input-mini" data-date2="23:59"/></td>
                <td>
                    <select required="" name="interval" id="interval">
                        <option value="120">120</option>
                        <option value="60">60</option>
                        <option value="30">30</option>
                        <option value="15">15</option>
                        <option value="1">1</option>
                    </select>
                </td>
                <td>
                    <input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport">
                    <a href='javascript:void(0)' onclick="get_pdfFuelConsumption_new();"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                    <a href='javascript:void(0)' onclick="html2xlsFuelConsumption_new();
                    return false;"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                </td>
            </tr>
        </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>