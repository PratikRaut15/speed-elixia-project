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
<div id="447_editTripModal" class="modal fade" role="dialog"  style="width:82%;margin-left:-45%;margin-top:-250px;position:absolute;display:none;padding-right:33px;">
    <div class="modal-dialog" >
    <!-- Modal content-->
    <div class="modal-content">
       <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title">Edit Trip</h3>
      </div>
        <div class='container' style="width:100%;margin-top:20px;padding:20px;">
            <div class="tripDetails">
                <form name="edittripform" id="edittripform" method="POST" action="" onsubmit="edittripdetails('edittripform'); return false;">
                    <table class='table table-condensed' style="float:left;">
                        <tbody>
                            <tr>
                                <td colspan="4" id="ajaxstatus"></td>
                            </tr>
                            <tr>
                                 <td class='frmlblTd'>From(Yard Name)</td>
                                <td><input type="text" name="cname" id="cname"  value="" style="width: 90px;" readonly>
                                </td>
                                <td class='frmlblTd'>Return Yard</td>
                                <td>
                                    <select name="returnYard" id="returnYard">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class='frmlblTd'>Challan No</td>
                                <td><input type="text" name="challanNo" id="challanNo"  value="" style="width: 90px;" readonly>
                                </td>
                            </tr>
                             <tr>
                                <td class='frmlblTd'>Trip Status</td>
                                <td><select name="tripstatus" id='edittripstatus'>
                                    </select>
                                
                                </td>
                                <td class='frmlblTd'>Date & Time </td>
                                <td>
                                    <input type="text" name="SDate" id="editSDate"  value="" style="width: 90px;">
                                    <input type="text" name="STime" id="editSTime" style="width: 60px;">
                                </td>
                            </tr>
                            <tr>
                                <td class='frmlblTd'>Product</td>
                                <td><input type="text" name="stockCode" id="stockCode"  value="" style="width: 90px;" readonly>
                                
                                </td>
                                <td class='frmlblTd'>Consignee</td>
                                <td>
                                    <input type='text' name='consignee' id='editconsignee' value="" readonly>
                                    <input type='hidden' name='consigneeid' id='consigneeid' value="">
                                </td>

                            </tr>
                            <tr>
                                <td class='frmlblTd'>Quantity in metric ton</td>
                                <td><input type="text" name="quantity" id="quantity"  value="" style="width: 90px;" readonly>
                                </td>
                                <td class='frmlblTd'>Bags</td>
                                <td>
                                    <input type="text" name="bags" id="bags"  value="" style="width: 90px;" readonly>
                                </td>
                            </tr>
                            <tr>
                               
                            </tr>
                            <tr>
                                <td class='frmlblTd'>Driver Phone No</td>
                                <td>
                                    <input type='text' name='drivermobile1' id='drivermobile1' value="" readonly>
                                </td>
                                <td class='frmlblTd'>Truck No</td>
                                <td>
                                    <input type='text' name='vehicleno' id='editvehicleno' value="" readonly>
                                    <input type='hidden' name='vehicleid' id='vehicleid' value="">
                                </td>
                            </tr>
                            <tr>
                                <td class='frmlblTd'>Estimated Time </td>
                                <td><input type="text" style='width:100px;' id="esttime" name="esttime" readonly value=""> Hrs</td>
                                <td class='frmlblTd'>Actual Hrs Travelled</td>
                                <td><input type="text" style='width:100px;' id="actualhrs" name="actualhrs" readonly value=""> Hrs</td>
                            </tr>
                            <tr>
                                <td class='frmlblTd' colspan="4">Trip distance</td>
                            </tr>
                            <tr>
                                <td>From yard to last delivery</td>
                                <td class='frmlblTd' ><input type="text" id='yardToCheckpointDistance' name='yardToCheckpointDistance' value="" readonly></td>
                                <td class='frmlblTd'>From yard to yard in</td>
                                <td class='frmlblTd'><input type="text" id='YardToYardDistance' name='YardToYardDistance' value="" readonly=""></td>
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
                                <td colspan="4" class='frmlblTd' style="text-align: center;">
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
   