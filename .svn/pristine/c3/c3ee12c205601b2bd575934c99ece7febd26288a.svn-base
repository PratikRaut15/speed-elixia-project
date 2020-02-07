<script>
    $(function () {
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "autocomplete_cz.php",
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
<form name="chkcreate" id="chkcreate" action="routecz.php" method="POST" style="widows: 80%;">
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
    </tr>
    <tr>
        <td>Phone Number</td>
        <td><input type="text" name="cphone" id="cphone" value="<?php echo $checkpoint->cphone; ?>"></td>
        <td>Email</td>
        <td><input type="text" name="cemail" id="cemail" value="<?php echo $checkpoint->cemail; ?>"></td>
        <td>Group</td>
        <td>
            <?php
            $grouplist = grouplist();
            ?>  
            <select name="grouplist" id="grouplist">
                <option value="0">Select Group</option>
                    <?php
                    if (isset($grouplist)) {
                        foreach ($grouplist as $row) {
                            $selected ="";
                            if($row->groupid==$checkpoint->groupid){
                               $selected = "selected";  
                            }
                            echo"<option value=" . $row->groupid . " $selected   >" . $row->groupname . "</option>";
                        }
                    }
                    ?>
            </select> 
        </td>
    </tr>
    <tr>
        <td colspan="7" align="center">
            <input class="btn  btn-primary" type="button" name="modifychk" id="modifychk" value="Modify Checkpoint" onclick="editchekpoints();">&nbsp;
            <input type="hidden" id="cgeolat" name="cgeolat" value="<?php echo $checkpoint->cgeolat; ?>">
            <input type="hidden" id="cgeolong" name="cgeolong" value="<?php echo $checkpoint->cgeolong; ?>">
        </td>
    </tr>
</tbody>
</table>
</form>
<div id="map"></div>
