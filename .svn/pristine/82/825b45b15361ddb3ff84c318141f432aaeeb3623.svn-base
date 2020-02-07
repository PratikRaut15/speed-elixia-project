<?php
/**
 * View Client interface
 */
?>
<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #proposedindentmaster_filter{display: none}
    .dataTables_length{display: none}
</style>

<script type="text/javascript">
 var rowCount = 0;
 function addMoreRows(frm) {
     rowCount++;
     var recRow = '';
     recRow += '<table style="border:none;" id="rowCount' + rowCount + '" class="rowadded" ><tr>';
     recRow += '<td style="border:none;"><input name="skucode' + rowCount + '" size="5%" class="skucode" type="text" id="sku_code' + rowCount + '" maxlength="25"  onkeyup="chkkey(' + rowCount + ');" /><div id="chkdisplay' + rowCount + '" class="checkpointlist"></div></td>';
     recRow += '<td style="border:none;"><input name="sku_description' + rowCount + '" size="50%" class="sku_description" type="text" id="sku_description' + rowCount + '"  maxlength="250" onkeyup="chkkey(' + rowCount + ');" readonly="readonly"/></td>';
     recRow += '<td style = "border:none;" > <input name = "weight' + rowCount + '" type = "text"  class = "weight" maxlength = "25" /> </td>';
     recRow += '<td style="border:none;"><a href="javascript:void(0);" onclick="removeRow(' + rowCount + ');"><img src="../../images/hide.gif" alt="Delete"/></a><input name="skuid' + rowCount + '" type="hidden" id="skuid' + rowCount + '" class="skuid"/></td></tr></table>';
     jQuery('#addedRows').append(recRow);
 }

 function removeRow(removeNum) {
     jQuery('#rowCount' + removeNum).remove();
 }
</script>
</script>
<br/>
<div class='container' >

    <center>
        <span style="display: none;" id="error_occ"> Occupancy Not More Than 100 %</span>
        <span style="display: none;" id="error1"> Enter Factory Name</span>
        <span style="display: none;" id="error2"> Enter Depot Name</span>
        <span style="display: none;" id="error3"> Enter Sku Type</span>
        <span style="display: none;" id="error4"> Enter Transporter Name</span>
        <span style="display: none;" id="error5"> Enter Vehicle Type</span>
        <span style="display: none;" id="error6"> Enter Vehicle Required Date</span>
        <span style="display: none;" id="error7"> Enter Remark</span>
        <span style="display: none;" id="error8"> Please Add Sku Details</span>
        <span style="display: none;" id="error9"> Please Add Sku Code</span>
        <span style="display: none;" id="error10"> Please Add Sku Weight</span>
        <form id="addorders" method="POST" action="action.php?action=modify-proposed-indent">
            <table class='table table-condensed' style="width: 60%">
                <thead><tr><th colspan="100%" >Add Proposed Indent </th></tr></thead>
                <tbody id="theBody">
                    <tr>
                        <td colspan="100%" id="ajaxstatus"></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>
                            Factory<span class="mandatory">*</span>
                        </td>
                        <td>
                            <?php
                            if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
                             $objFactoty = new Factory();
                             $objFactoty->customerno = $_SESSION["customerno"];
                             $objFactoty->factoryid = $_SESSION['factoryid'];
                             $plant = get_factory($objFactoty);
                             ?>
                             <input type="text" name="factory_name" id="factory_name" value="<?php echo $plant[0][factoryname] ?>" maxlength="50" required='' readonly="">
                             <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $_SESSION['factoryid']; ?>"/>
                             <?php
                            } else {
                             ?>
                             <input type="text" name="factory_name" id="factory_name" value="" maxlength="50" required=''>
                             <input type="hidden" name="factoryid" id="factoryid" value=""/>
                             <?php
                            }
                            ?>
                        </td>

                    </tr>



                    <tr>
                        <td class='frmlblTd'>
                            Depot<span class="mandatory">*</span>
                        </td>
                        <td>
                            <input type="text" name="depot_name" id="depot_name" value="" required maxlength="50">
                            <input type="hidden" name="depotid" id="depotid" value="" >
                        </td>


                    </tr>


                    <tr>
                        <td class='frmlblTd'>
                            Sku Type<span class="mandatory">*</span>
                        </td>
                        <td>
                            <input type="text" name="type_name" id="type_name"  value="" required maxlength="20" autocomplete="off">
                            <input type="hidden" name="typeid" id="typeid" value="" maxlength="50"/>
                        </td>

                    </tr>

                    <tr>
                        <td>
                            Transporter<span class="mandatory">*</span>
                        </td>
                        <td>
                            <input type="text" name="transportername" id="transporter_byZone" value="" maxlength="50" autocomplete="off"/>
                            <input type="hidden" name="transporterid" id="transporterid" value="" maxlength="50"/>
                            <div id="transporterdisplay" class="checkpointlist"></div>
                        </td>
                    </tr>

                    <tr>
                        <td class='frmlblTd'>
                            Vehicle Type <span class="mandatory">*</span>
                        </td>
                        <td>
                            <input type="text" name="vehicletypetrans_list" id="vehicletypetrans_list" value="" autocomplete="off">
                            <input type="hidden" name="vehicletypeid" id="vehicletypeid" value="" >
                            <div id="chkdisplay" class="checkpointlist"></div>
                        </td>


                    </tr>



                    <tr>
                        <td class='frmlblTd'>
                            Vehicle Requirement Date <span class="mandatory">*</span>
                        </td>
                        <td>
                            <input type="text"  name="date_required" id="SDate" value="" maxlength="15"/>
                        </td>
                    </tr>

                    <tr>
                        <td class='frmlblTd'>
                            Remarks <span class="mandatory">*</span>
                            (Max 250 Characters)
                        </td>
                        <td>
                            <textarea name="remark" id="remark" maxlength="250"></textarea>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="2">
                            <table rules="all" style="background:#fff; width: 100%">
                                <tr>
                                    <td style="border:none;">
                                        SKU Code
                                    </td>
                                    <td style="border:none;">
                                        SKU Description
                                    </td>
                                    <td style="border:none;">
                                        Weight
                                    </td>
                                    <td style="border:none;">
                                        <span style="float: right;" onclick="addMoreRows(this.form);">
                                            <a> <img src="../../images/show.png" alt="Add Row"/> </a>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                            <table id="addedRows"></table>
                        </td>
                    </tr>
                    <!--

                    <tr>
                        <td class='frmlblTd'>Accept / Reject <span class="mandatory">*</span></td>
                        <td><input type="radio" name="isaccepted" value="1" group="accepted" checked="">Accept
                            <input type="radio" name="isaccepted" value="-1" group="accepted" required maxlength="50"> Reject</td>
                    </tr>
                    -->
                    <tr id="vehicle_stat" style="display: none;">
                        <td class='frmlblTd'>
                            Vehicle Weight (in tons)
                            <input type="text"  name="vehicleweight" id="vehicleweight" value="" readonly="" >
                        </td>
                        <td>
                            Vehicle  Volume (in cubic feet)
                            <input type="text" name="vehiclevolume" id="vehiclevolume" value="" readonly="">

                        </td>
                    </tr>

                    <tr id="vehicle_occupancy" style="display: none;">
                        <td class='frmlblTd'>
                            Occupancy By Weight (in %)
                            <input type="text" style="width: 120px;"  name="weightoccupancy" id="weightoccupancy" value=""  >
                        </td>
                        <td>
                            Occupancy By Volume (in %)
                            <input type="text" style="width: 120px;" name="volumeoccupancy" id="volumeoccupancy" value="" readonly="">

                        </td>
                    </tr>


                    <tr>

                        <td colspan="100%" class='frmlblTd'>

                            <input type="button" value="Check Occupancy" class='btn btn-primary' onclick="addProposedIndent();">
                        </td>
                    </tr>


                    <tr>

                        <td colspan="100%" class='frmlblTd'>

                            <input type="button" value="Save" id="btnSaveProposeIndent" class='btn btn-primary' disabled="true" onclick="createProposedIndent();">

                        </td>
                    </tr>
                </tbody>
            </table>


        </form>
    </center>

</div>
<hr/>

<?php
//print_r($proposed_indents);
?>
<script type='text/javascript'>
 var data = <?php echo json_encode($proposed_indents); ?>;
 var tableId = 'proposedindentmaster';
 var tableCols = [
     {"mData": "proposedindentid"}
     , {"mData": "factoryname"}
     , {"mData": "depotname"}
     , {"mData": "transportername"}
     , {"mData": "vehiclecode"}
     , {"mData": "date_required"}
     , {"mData": "total_weight"}
     , {"mData": "total_volume"}
     , {"mData": "status"}
     , {"mData": "approve"}
     , {"mData": "edit"}


 ];
</script>
