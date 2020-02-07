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
        <form id="addorders" method="POST" action="action.php?action=edit-vehicle">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Vehicle</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Vehicle No<span class="mandatory">*</span></td>
                <td><input type="text" name="vehicleno" value="<?php echo $vehicle[0]['vehicleno']?>" required maxlength="10"></td></tr>
            <tr><td class='frmlblTd'>Vehicle Type<span class="mandatory">*</span></td>
                <td><input type="text" name="vehicletype" id="vehicletype_list" value="<?php echo $vehicle[0]['vehiclecode']?>" required maxlength="10" autocomplete="off"></td>
                 <input type="hidden" name="vehicletypeid" id="vehicletypeid" value="<?php echo $vehicle[0]['vehicletypeid']?>" maxlength="50"/>
            <div id="display" class="listzone"></div>
            </tr>
            
            <tr><td class='frmlblTd'>Transporter<span class="mandatory">*</span></td>
                <td><input type="text" name="transporter" id="transporter_name"  value="<?php echo $vehicle[0]['transportername']?>" required maxlength="10" autocomplete="off"></td>
                <div id="display1" class="listlocation"></div>
            </tr>
               <input type="hidden" name="transporterid" id="transporterid" value="<?php echo $vehicle[0]['transporterid']?>" maxlength="50"/>
        <input type='hidden' id='vehicleid' name='vehicleid' value="<?php echo $vehicle[0]['vehicleid']?>" >
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>    

