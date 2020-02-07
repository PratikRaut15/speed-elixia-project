<?php
if (isset($_POST['search'])) {
    $batterydata = getFilteredBatteryDetails($_POST['vehid']);
} else {
    $batterydata = getBatteryDetails();
}
?>
<form action="" method="POST" >
    <table  style="border: none">
        <tr><td style="border: none">
                Vehicle No
            </td>
            <td style="border: none">
                <input  type="text" name="vehicleno" id="vehicleno" size="18" value="<?php if (isset($_POST['vehicleno'])) {
    echo $_POST['vehicleno'];
} ?>" placeholder="Enter Vehicle No" autocomplete="off" required/>
                <input type="hidden" name="vehid" id="vehid" size="20" value=""/>
                <div id="display" class="listvehicle"></div>
            </td>
            <td style="border: none">
                <input type="submit" class=" btn btn-primary" name="search" id="search" value="Search" />
            </td>
        </tr>
    </table>
</form>
<table  class="table  table-bordered table-striped dTableR dataTable"  style=" width:50%">
    <thead>

        <tr>

            <th>Sr. No.</th>
            <th>Vehicle No.</th>
            <th>Battery Serial No.</th>
            <th>Installation Date</th>
            <th>Modified Date</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($batterydata)) {
            foreach ($batterydata as $batterydatas) {
                echo "<tr>";
                echo "<td>$batterydatas->x</td>";
                echo "<td>$batterydatas->vehicleno</td>";
                echo "<td>$batterydatas->batt_serialno</td>";
                echo "<td>$batterydatas->installedon</td>";
                echo "<td>$batterydatas->updatedon</td>";
                echo "<td><a href='battery_srno.php?id=4&bmid=$batterydatas->batt_mapid' ><i class='icon-pencil'></i></a></td>";

                echo "</tr>";
            }
        } else {
            echo "<tr>
            <td colspan=100% style='text-align:center;'>No Battery Serial Nos. Added</td>
        </tr>";
        }
        ?>
    </tbody>
</table>
<script type="text/javascript">

    jQuery("#vehicleno").autoSuggest({
        ajaxFilePath: "batterysrno_ajax.php",
        ajaxParams: "dummydata=vehicleno",
        autoFill: false,
        iwidth: "auto",
        opacity: "0.9",
        ilimit: "10",
        idHolder: "id-holder",
        match: "contains"
    });

    function fill(Value, strparam) {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehid').val(Value);
        jQuery('#display').hide();
    }
</script>