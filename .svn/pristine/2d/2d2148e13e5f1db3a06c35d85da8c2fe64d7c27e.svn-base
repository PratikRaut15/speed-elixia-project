<style>
  #addLrDetails table{width:80%;}
  #addLrDetails .frmlblTd{text-align:center}
  #consigneeformedit table{width:50%;}
  #consigneeformedit .frmlblTd{text-align:center}
  .modal {
    top:250px;
    margin: -200px 0 0 -400px;
    width:800px;
  }
  .row{
    margin-left: 0px;
  }
  table td{
    border: none;
    text-align: left;
  }
  table tr{
    line-height: 10px;
  }
  .autooverflow{
    max-height: 400px;
    overflow-x:auto;
  }
</style>
<br/>
<div id='addLrDetails' class="modal hide">
  <div class="col-xl-12" class="form-horizontal well">
    <form class="form-horizontal well" id="frmLrDetails" name="frmLrDetails" method="post" action="">
      <input type="hidden" name="lrid" id="lrid" value="0"/>
      <div class="row">
        <span style="float: left; text-align: center;"><h3 style='text-align:center;'>Add Delivery / LR Details</h3></span>
        <span style="float: right;" class="close" data-dismiss="modal">X</span>
        <div id='ajaxBstatus'></div>
      </div>
      <div class='row autooverflow'>
        <div class="col-xs-12" >
          <table style='width: 98%;' >
            <tr>
              <td colspan="4">
                <input type="radio" name="lrsearch" value="delivery" onclick="showSearchInput();" checked=""/> Delivery No
                <span style="padding: 0px 15px 0px 15px;">OR</span>
                <input type="radio" name="lrsearch" value="lr" onclick="showSearchInput();" /> Lr No
              </td>
            </tr>

            <tr>
              <td>
                <span class="add-on">Delivery No</span>
              </td>
              <td>
                <input type="text" name="delivery_no" id="delivery_no" value="" onblur="checkDeliveryDetails(this.id);">
              </td>
              <td>
                <span class="add-on">LR No</span>
              </td>
              <td>
                <input type="text" name="lr_no" id="lr_no" value="" readonly="" onblur="checkDeliveryDetails(this.id);">              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Shipment No</span>
              </td>
              <td>
                <input type="text" name="shipment_no" id="shipment_no" value="">
              </td>
              <td>
                <span class="add-on">Cost Document No</span>
              </td>
              <td>
                <input type="text" name="cost_document_no" id="cost_document_no" value="">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Truck Type</span>
              </td>
              <td>
                <input type="text" name="truck_type" id="truck_type" value="">
              </td>
              <td>
                <span class="add-on">Route</span>
              </td>
              <td>
                <input type="text" name="route" id="route" value="">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Vehicle No</span>
              </td>
              <td>
                <input type="text" name="vehicle_no" id="vehicle_no" value="">
              </td>
              <td>
                <span class="add-on">Indent ID</span>
              </td>
              <td>
                <input type="text" name="indentid" id="indentid" value="" readonly="">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Vehicle Type</span>
              </td>
              <td>
                <select name="vehicle_type" id="vehicle_type">
                  <?php echo $vehicletypelist; ?>
                </select>
              </td>
              <td>
                <span class="add-on">Movement Type</span>
              </td>
              <td >
                <select name="movement_type" id="movement_type">
                  <?php echo $movementypelist; ?>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">CFA/Rent Cost</span>
              </td>
              <td>
                <input type="text" name="cfa_cost" id="cfa_cost" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Shipment/Delivery Freight In Bill</span>
              </td>
              <td>
                <input type="text" name="shipment_freight_bill" id="shipment_freight_bill" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Loading Charges</span>
              </td>
              <td>
                <input type="text" name="loading" id="loadingcharge" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Unloading Charges</span>
              </td>
              <td>
                <input type="text" name="unloading" id="unloading" value="0" onkeyup="
                    calculateDeliveryAmount();">
              </td>
            </tr>

            <tr>
              <td>
                <span class="add-on">Loading Detention Charges</span>
              </td>
              <td>
                <input type="text" name="loading_charges" id="loading_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Unloading Detention Charges</span>
              </td>
              <td>
                <input type="text" name="unloading_charges" id="unloading_charges" value="0" onkeyup="
                    calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Other Charges</span>
              </td>
              <td>
                <input type="text" name="other_charges" id="other_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Multidrop Charges</span>
              </td>
              <td>
                <input type="text" name="multidrop_charges" id="multidrop_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Toll Charges</span>
              </td>
              <td>
                <input type="text" name="toll_charges" id="toll_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Permit Charges</span>
              </td>
              <td>
                <input type="text" name="permit_charges" id="permit_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Charges Outward</span>
              </td>
              <td>
                <input type="text" name="charges_outword" id="charges_outword" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">GPRS</span>
              </td>
              <td>
                <input type="text" name="gprs" id="gprs" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">No Entry Charge</span>
              </td>
              <td>
                <input type="text" name="noentry_charges" id="noentry_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Auto Charge</span>
              </td>
              <td>
                <input type="text" name="auto_charges" id="auto_charges" value="0" onkeyup="calculateDeliver
                    yAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">LR Charge</span>
              </td>
              <td colspan="3">
                <input type="text" name="lr_charges" id="lr_charges" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">TT Penalty</span>
              </td>
              <td>
                <input type="text" name="tt_penalty" id="tt_penalty" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
              <td>
                <span class="add-on">Any Deduction</span>
              </td>
              <td>
                <input type="text" name="any_deduction" id="any_deduction" value="0" onkeyup="calculateDeliveryAmount();">
              </td>
            </tr>
            <tr>
              <td>
                <span class="add-on">Total Amount Delivery Wise</span>
              </td>
              <td colspan="3">
                <input type="text" name="total_delivery_amount" id="total_delivery_amount" value="0" readonly="">
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div class='row'>
        <div class='col-xs-12' style='text-align:center; margin-top: 25px;' id="buttonpanel">
          <input type="button" class="btn btn-primary" value="Submit" id="addlrdata" onclick="saveLrDetails();"/>
          <input type="button" data-dismiss="modal" class="btn btn-danger" value="Close" />
        </div>
      </div>
    </form>
  </div>
</div>