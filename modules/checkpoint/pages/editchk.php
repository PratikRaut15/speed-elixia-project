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

    function fill(Value, strparam)
    {
        jQuery('#vehicleno').val(strparam);
        jQuery('#vehicleid').val(Value);
        jQuery('#display').hide();
        VehicleForRoute_ById(Value, strparam)

    }
</script>

<?php
$checkpoint = getchk($_GET['chkid']);
?>
<form name="chkcreate" id="chkcreate" action="route.php" method="POST" style="widows: 90%;">
    <?php include 'panels/editchk.php'; ?>
    <tr>
        <td>Name</td>
        <td><input type="text" name="chkName" id="chkName" value="<?php echo $checkpoint->cname; ?>">
            <input type="hidden" name="chkId" id="chkId" value="<?php echo $checkpoint->checkpointid; ?>"></td>
        <td>Radius</td>
        <td><input type="text" name="crad" id="crad" value="<?php echo $checkpoint->crad; ?>" size="5" readonly></td>

        <td>ETA</td>
        <td colspan="2">
            <?php
            if ($checkpoint->eta != '00:00:00') {
                echo '<input type="text" name="STime" data-date="' . $checkpoint->eta . '"  id="STime" size="5">(HH:MM)';
            } else {
                echo '<input type="text" name="STime" id="STime" data-date="00:00" value="" size="5">(HH:MM)';
            }
            ?>

        </td>
        <td>Select Checkpoint Type</td>
        <td  colspan="2">
            <select id="chktype" name="chktype" style="width: 95%;">
                <option value='0'>Select Type</option>
                <?php
                $types = getchktypes();
                if (isset($types)) {
                    foreach ($types as $type) {
                        if ($type->ctid == $checkpoint->chktype) {
                            echo "<option value='$type->ctid' selected>$type->name</option>";
                        } else {
                            echo "<option value='$type->ctid'>$type->name</option>";
                        }
                    }
                }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>Phone Number</td>
        <td><input type="text" name="cphone" id="cphone" value="<?php echo $checkpoint->cphone; ?>"></td>
        <td>Email</td>
        <td><input type="text" name="cemail" id="cemail" value="<?php echo $checkpoint->cemail; ?>"></td>
        <td style="padding-left: 10px;">Select Vehicles</td>

        <td style="display: none;">
            <select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()" style="display: none;">
                <option value=''>Select Vehicle</option>
                <?php
                $vehicles = getvehicles_all();
                if (isset($vehicles)) {
                    foreach ($vehicles as $vehicle) {
                        echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                    }
                }
                ?>
            </select>
        </td>

        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
            <div id="display" class="listvehicle"></div>
        </td>
        <td>
            <input type="button" value="Add All" onclick="addallvehicleForRoute()" class="g-button g-button-submit">
        </td>
        <td colspan="2" align="center">
            <input class="btn  btn-primary" type="button" name="modifychk" id="modifychk" value="Modify Checkpoint" onclick="editchekpoints();">&nbsp;
            <input type="hidden" id="cgeolat" name="cgeolat" value="<?php echo $checkpoint->cgeolat; ?>">
            <input type="hidden" id="cgeolong" name="cgeolong" value="<?php echo $checkpoint->cgeolong; ?>">
        </td>
    </tr>


    <tr>
        <td colspan="100%"><div id="vehicle_list_route">
                <?php
                $addedvehicles = getaddedvehicles_chkpt($_GET['chkid']);
                if (isset($addedvehicles)) {
                    foreach ($addedvehicles as $vehicle) {
                        ?>
                        <input type="hidden" class="mappedvehicles" id="hid_v<?php echo ($vehicle->vehicleid); ?>" rel="<?php echo ($vehicle->vehicleid); ?>" value="<?php echo ($vehicle->vehicleno); ?>">
                        <?php
                    }
                }
                ?>
            </div></td>
    </tr>

</tbody>
</table>
</form>
<div id="map"></div>
