<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
  #ajaxstatus{text-align:center;font-weight:bold;display:none}
  .mandatory{color:red;font-weight:bold;}
  #addorders table{width:50%;}
  #addorders .frmlblTd{text-align:center}
</style>
<br/>

<div class='container' >
  <center>
    <form id="addorders" method="POST" action="action.php?action=edit-indent">
      <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Indent</th></tr></thead>
        <tbody>

          <tr>
            <td colspan="100%" id="ajaxstatus"></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Indent ID</td>
            <td><input type="text" name="indentid" id="indentid" value="<?php echo $indents[0]['indentid'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Proposed Indent ID</td>
            <td><input type="text" name="proposedindentid" id="proposedindentid" value="<?php echo $indents[0]['proposedindentid'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Factory</td>
            <td><input type="text" name="factory_name" id="factory_name" value="<?php echo $indents[0]['factoryname'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Depot</td>
            <td><input type="text" name="depot_name" id="depot_name" value="<?php echo $indents[0]['depotname'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Transporter</td>
            <td><input type="text" name="transporter" id="transporter" value="<?php echo $indents[0]['transportername'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Proposed Vehicle Type</td>
            <td><input type="text" name="proposedvehiclecode" id="proposedvehiclecode" value="<?php echo $indents[0]['proposedvehiclecode'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Actual Vehicle Type</td>
            <td><input type="text" name="actualvehiclecode" id="actualvehiclecode" value="<?php echo $indents[0]['actualvehiclecode'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Vehicle No</td>
            <td><input type="text" name="vehicleno" id="vehicleno" value="<?php echo $indents[0]['vehicleno'] ?>" readonly=""></td>
          </tr>

          <tr>
            <td class='frmlblTd'>Date Required</td>
            <td><input type="text" name="date_required" id="SDate" value="<?php echo date('d-m-Y', strtotime($indents[0]['date_required'])) ?>" readonly=""></td>
          </tr>
          <tr>
            <td class='frmlblTd'>Loading Status</td>
            <td>
              <select name="loadstatus" id="loadstatus">
                <option value="0">Select Status</option>
                <option value="1">Loaded</option>
                <option value="-1">Rejected</option>
                <option value="2">Vehicle Not Placed</option>
                <option value="3">Other</option>
              </select>
            </td>
          </tr>
          <tr>
            <td class='frmlblTd'>Shipment No</td>
            <td>
              <input type="text" name="shipmentno" id="shipmentno" value="">
            </td>
          </tr>
          <tr>
            <td class='frmlblTd'>Remarks</td>
            <td>
              <textarea id="remark" name="remark"></textarea>
            </td>
          </tr>


          <tr>
            <td colspan="100%" class='frmlblTd'><input type="button" value="Save" class='btn btn-primary' onclick="indentEdit();"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </center>
</div>