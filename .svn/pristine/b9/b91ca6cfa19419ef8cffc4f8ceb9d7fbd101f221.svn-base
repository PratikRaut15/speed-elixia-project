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
    <form id="addorders" method="POST" action="action.php?action=modify-proposed-indent">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Modify Proposed Indent </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Proposed Indent ID<span class="mandatory">*</span></td>
                <td><input type="text" name="proposed_indentid" value="<?php echo $proposedIndentArray[0]['proposedindentid']?>" readonly=""></td></tr>
            <tr><td class='frmlblTd'>Factory<span class="mandatory">*</span></td>
                <td>
                    <input type="text" name="factoryname" value="<?php echo $proposedIndentArray[0]['factoryname']?>" readonly="">
                    <input type="hidden" name="factoryid" value="<?php echo $proposedIndentArray[0]['factoryid']?>" >
                </td></tr>
            <tr><td class='frmlblTd'>Depot<span class="mandatory">*</span></td>
                <td>
                    <input type="text" name="depotname" value="<?php echo $proposedIndentArray[0]['depotname']?>" readonly="">
                    <input type="hidden" name="depotid" value="<?php echo $proposedIndentArray[0]['depotid']?>">
                </td></tr>
               
       
        
         <tr><td class='frmlblTd'>Proposed Vehicle Code<span class="mandatory">*</span></td>
             <td><input type="text" name="proposedvehiclecode" value="<?php echo $proposedIndentArray[0]['proposedvehiclecode']?>"readonly=""></td></tr>
         
          <tr><td class='frmlblTd'>Actual Vehicle Code<span class="mandatory">*</span></td>
              <td><input type="text" name="vehicletypeid" id='vehicletype_list' value="<?php echo $proposedIndentArray[0]['actualvehiclecode']?>" maxlength="20" autocomplete="off"></td></tr>
           <input type="hidden"  name="actualvehicletypeid" id="vehicletypeid" value="" />
          
        <tr><td class='frmlblTd'>Vehicle No<span class="mandatory">*</span></td>
                <td><input type="text" name="vehicleno" id="vehicleno" value="<?php echo $proposedIndentArray[0]['vehicleno']?>" maxlength="20"></td></tr>
        
        <tr><td class='frmlblTd'>Driver Mobile No<span class="mandatory">*</span></td>
                <td><input type="text" name="drivermobileno" id="drivermobileno" value="<?php echo $proposedIndentArray[0]['drivermobileno']?>" maxlength="20"></td></tr>
        
        <tr><td class='frmlblTd'>Vehicle Requirement Date <span class="mandatory">*</span></td>
            <td><input type="text" name="date_required" value="<?php echo date('d-m-Y', strtotime($proposedIndentArray[0]['date_required']))?>" readonly=""></td></tr>
        
        
        <tr><td class='frmlblTd'>Accept / Reject <span class="mandatory">*</span></td>
            <td><input type="radio" name="isaccepted" id="isaccepted" value="1" group="accepted" checked="">Accept
                <input type="radio" name="isaccepted" id="isaccepted" value="-1" group="accepted" required maxlength="50"> Reject</td></tr>
        
        <tr>
            <td class='frmlblTd'>Remark</td>
            <td><textarea id="remarks" name="remarks"></textarea></td>
        </tr>
        
        <input type="hidden" name="pitmappingid" id="pitmappingid" value="<?php echo $proposedIndentArray[0]['pitmappingid']?>" />
        <input type="hidden" name="proposed_vehicletypeid" id="proposed_vehicletypeid" value="<?php echo $proposedIndentArray[0]['proposed_vehicletypeid']?>" />
        <input type="hidden" name="proposed_transporterid" id="proposed_transporterid" value="<?php echo $proposedIndentArray[0]['proposed_transporterid']?>" />
        
        <tr><td colspan="100%" class='frmlblTd'><input type="button" value="Save" id="btnSubmitAcceptReject" class='btn btn-primary' onclick="ValidateProposedIndent();"></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>    

