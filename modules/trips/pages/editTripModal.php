<script>
jQuery(document).ready(function (){
    jQuery('#editetarrivaldate').datepicker({format: "dd-mm-yyyy",autoclose:true});
    jQuery('#editSDate').datepicker({format:"dd-mm-yyyy", autoclose: true});
    jQuery('#editSTime').timepicker({format: "hh:ii:ss", autoclose: true, });
    jQuery("#editconsignor").autocomplete({
        source: "trip_ajax.php?action=consignorauto", minLength: 1,
        select: function (event, ui) {
            jQuery('#consignorid').val(ui.item.id);
        }
    });
    jQuery("#editconsignee").autocomplete({
        source: "trip_ajax.php?action=consigneeauto", minLength: 1,
        select: function (event, ui) {
            jQuery('#consigneeid').val(ui.item.id);
        }
    });
});
</script>
<style>
    div.tripDetails {
        float: left;
        width: 50%;
    }
    div.statusHistory {
        float: right;
        width: 50%;
    }
    .statusHistory th{
        border-top :black;
    }
</style>
<div id="editTripModal" class="modal fade" role="dialog"  style="width:82%;margin-left:-40%;margin-top:-300px;position:absolute;display:none;">
    <div class="modal-dialog" >
    <!-- Modal content-->
    <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title">Edit Trip</h3>
      </div>
        <div class='container' style="width:100%;">
            <div class="tripDetails">
                <form name="edittripform" id="edittripform" method="POST" action="" onsubmit="edittripdetails('edittripform'); return false;">
                    <table class='table table-condensed' style="float:left;width: 100%;">
                        <tbody>
                            <tr>
                                <td colspan="4" id="ajaxstatus"></td>
                            </tr>
                            <tr>
                                <td >Vehicle No</td>
                                <td>
                                    <input  type="text" name="vehicleno" id="editvehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
                                    <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                                    <div id="display" class="listvehicle"></div>
                                </td>
                                <td >Triplog No</td>
                                <td><input type="text" name="triplogno" id="triplogno" value="' . $triplogno . '" required >
                                </td>
                            </tr>
                            <tr>
                                <td >Trip Status</td>
                                <td><select name="tripstatus" id='edittripstatus'>

                                    </select>

                                </td>
                                <td >Date & Time </td>
                                <td>
                                    <input type="text" name="SDate" id="editSDate"  value="" style="width: 90px;">

                                   <!--  <input type="text" name="STime" id="editSTime" data-date="" data-default-value="" style="width: 40px;"> -->
                                    <input type="text" name="STime" id="editSTime" style="width: 60px;">
                                </td>
                            </tr>
                            <tr>
                                <td >Route Name</td>
                                <td><input type="text" name="routename" id="routename" value="" ></td>
                                <td >Budgeted Kms </td>
                                <td><input type="text" name="budgetedkms" id="budgetedkms" value="" ></td>
                            </tr>
                            <tr>
                                <td >Budgeted Hrs</td>
                                <td><input type="text" name="budgetedhrs" id="budgetedhrs" value="" ></td>
                                <td >Consignor</td>
                                <td>
                                    <input type='text' name='consignor' id='editconsignor' value="">
                                    <input type='hidden' name='consignorid' id='consignorid' value="">
                                </td>
                            </tr>
                            <tr>
                                <td >Estimated Date Arrival </td>
                                <td>
                                    <input type="text" name="etarrivaldate" id="editetarrivaldate" value=""  style="width: 120px;">
                                </td>
                                <td >Material type</td>
                                <td>
                                    <select name="materialtype" id="materialtype">
                                        <option value="0">Select type</option>
                                        <option value="1">Dry</option>
                                        <option value="2">Freeze</option>
                                        <option value="3">Chiller</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td >Consignee</td>
                                <td>
                                    <input type='text' name='consignee' id='editconsignee' value="">
                                    <input type='hidden' name='consigneeid' id='consigneeid' value="">
                                </td>
                                <td >Billing Party</td>
                                <td><input type="text" name="billingparty" id="billingparty" value="<?php echo $billingparty; ?>"></td>
                            </tr>
                            <tr>
                                <td >Minimum Temperature</td>
                                <td><input type="text" name="mintemp" id="mintemp" value="<?php echo $mintemp; ?>" ></td>
                                <td >Maximum Temperature</td>
                                <td><input type="text" name="maxtemp" id="maxtemp" value="<?php echo $maxtemp; ?>" ></td>
                            </tr>
                            <tr>
                                <td >Driver Name</td>
                                <td><input type="text" name="drivername" id="drivername" value="<?php echo $drivername; ?>"></td>
                                <td >Driver Mobile NO.</td>
                                <td><input type="text" name="drivermobile1" id="drivermobile1" value="<?php echo $drivermobile1; ?>" ></td>
                            </tr>
                            <tr>
                                <td >Driver Mobile NO(Other).</td>
                                <td><input type="text" name="drivermobile2" id="drivermobile2" value="<?php echo $drivermobile2; ?>"></td>
                                <td >Per Day-Km </td>
                                <td><input type="text" name="perdaykm" id="perdaykm" value="<?php echo $perdaykm; ?>"/></td>
                            </tr>
                            <tr>
                                <td >Remark</td>
                                <td><textarea name="remark" id="remark"><?php echo $remark; ?></textarea></td>
                                <td >Actual Km Traveled</td>
                                <td><input type="text" style='width:100px;' name="actualkms" id="actualkm" readonly value=""/> Km</td>
                            </tr>
                            <tr>
                                <td >Estimated Time </td>
                                <td><input type="text" style='width:100px;' id="esttime" name="esttime" readonly value=""> Hrs</td>
                                <td >Actual Hrs Traveled</td>
                                <td><input type="text" style='width:100px;' id="actualhrs" name="actualhrs" readonly value=""> Hrs</td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="checkbox" style="float: right;"  id="istripend" value="1" name="istripend"></td>
                                <td colspan="2"><label style="float: left;" for="istripend"> Trip End</label></td>
                            </tr>
                            <tr>
                                <td colspan="4">

                                 </td>
                            </tr>
                            <tr>
                                <td colspan="4"  style="text-align: center;">
                                    <input type="submit" name="addtripsubmit" id="btnUpdate" value="Update" class='btn btn-primary'>
                                    <input type='hidden' name='tripid' id='tripid' value="">
                                    <input type="hidden" name="groupid" value="">
                                    <input type="hidden" name="isApi" value="1">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                 </form>
            </div>
             <div class="statusHistory">
                 <div id="tripHistoryDiv"></div>
                 <div id="tripDropPointsDiv"></div>
                 <div id="tripLrDetailsDiv"></div>
             </div>
        </div>
    </div>
