<?php
$objRole = new Hierarchy();
$objRole->customerno = $_SESSION['customerno'];
$types = getTrasactionTypes($objRole);
?>

<script type="text/javascript">
    var rowCount = 0;
            function addMoreRows(frm, typeid) {
            rowCount++;
                    var recRow = '<p id="rowCount' + rowCount + '">\n\
        <tr>\n\
            <td><input name="condition' + rowCount + '" class="cls_condition" type="text" id="condition' + rowCount + '" size="17%"  maxlength="120"  autocomplete="off" onkeyup="getTransactionCondition(' + rowCount + ', ' + typeid + ');" /></td>\n\
            <td><input name="minvalue' + rowCount + '" type="text"  class="cls_minvalue" maxlength="120" style="margin-left: 15px;"/></td>\n\
        <td><input name="maxvalue' + rowCount + '" type="text"  class="cls_maxvalue" maxlength="120" style="margin-left: 15px;"/></td>\n\
            <td><input name="approver' + rowCount + '" type="text"  class="cls_approver" maxlength="120" id="approver' + rowCount + '" autocomplete="off" onkeyup="getApproverRole(' + rowCount + ', ' + typeid + ');"style="margin-left: 15px;"/></td>\n\
                <td><input name="sequnceno' + rowCount + '" type="text"  class="cls_sequnceno" maxlength="120" style="margin-left: 15px;"/></td>\n\
        <td></td>\n\
        </tr>\n\
        <a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');">\n\
            <img src="../../images/hide.gif" alt="Delete"/></a>\n\
            <div id="chkdisplay' + rowCount + '" class="checkpointlist"></div>\n\
            <div id="approverdisplay' + rowCount + '" class="approvelist">dff</div>\n\
            <input name="conditionid' + rowCount + '" type="hidden" id="conditionid' + rowCount + '" class="cls_conditionid"/> <input name="approverid' + rowCount + '" type="hidden" id="approverid' + rowCount + '" class="cls_approverid"/><input name="typeid' + rowCount + '" type="hidden" id="typeid' + rowCount + '" class="cls_typeid" value="' + typeid + '"/></p>';
                    jQuery('#addedRows' + typeid).append(recRow);
            }

    function removeRow(removeNum) {
    jQuery('#rowCount' + removeNum).remove();
    }
</script>

<form  class="form-horizontal well " name="createconditions" id="createconditions" action="action.php?action=addrole" method="POST" style="width:70%; text-align: left;">
    <?php include 'panels/addvehicle.php'; ?>    
    <?php foreach ($types as $type) { ?>
        <fieldset>
            <div class="control-group">
                <div class="input-prepend ">
                    <span class="add-on" style="width: 200px;"><?php echo $type[categoryname] ?></span>                </div>
            </div>
            <?php
            $objCondition = new Hierarchy();
            $objCondition->transactiontypeid = $type[transactiontypeid];
            $objCondition->customerno = $_SESSION['customerno'];
            $conditions = getTrasactionConditions($objCondition);
            if (isset($conditions) && !empty($conditions)) {
                ?>    
                <table rules="all" style="background:#fff; width: 100%">
                    <tr>
                        <td colspan="100%">
                            <div style="width: 20%; float: left">Condition </div>
                            <div style="width: 20%; float: left">Min Value </div>
                            <div style="width: 20%; float: left">Max Value </div>
                            <div style="width: 20%; float: left">Approver Role </div>
                            <div style="width: 20%; float: left">Priority <span style="float: right;" onclick="addMoreRows(this.form,<?php echo $type[transactiontypeid]; ?>);">
                                    <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                </span></div>
                        </td>    
                    </tr>
                </table>
                <div id="addedRows<?php echo $type[transactiontypeid]; ?>"></div>

                <?php
            }
            ?>
        </fieldset>
    <?php } ?>

    <fieldset>
        <div class="control-group pull-right">
            <input type="hidden" name="custno" id="custno" value="<?php echo $_SESSION['customerno']?>"/>
            <input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION['userid']?>"/>
            <input type="button" value="Add New Rule" class="btn  btn-primary" onclick="createCondition();">
        </div>      
    </fieldset>
</form>
<script>
            function submitvehicle()
            {
            if (jQuery("#vehicleno").val() == "")
            {
            jQuery("#vehiclecomp").show();
                    jQuery("#vehiclecomp").fadeOut(3000);
            }

            else
            {

            var vehicleno = jQuery("#vehicleno").val();
                    jQuery.ajax({
                    type: "POST",
                            url: "route_ajax.php",
                            data: {vehicleno: vehicleno},
                            async: true,
                            cache: false,
                            success: function (statuscheck) {

                            if (statuscheck == "ok")
                            {
                            jQuery("#createvehicle").submit();
                            }
                            else
                            {
                            jQuery("#samename").show();
                                    jQuery("#samename").fadeOut(3000);
                            }
                            }
                    });
            }
            }
</script>