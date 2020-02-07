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
        <form id="addorders" method="POST" action="action.php?action=edit-plant">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Factory</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            
          
            <tr><td class='frmlblTd'>Factory<span class="mandatory">*</span></td>
                <td><input type="text" name="factorycode" value="<?php echo $plant[0]['factorycode']?>" required maxlength="10"></td></tr>
            
            <tr><td class='frmlblTd'>Factory<span class="mandatory">*</span></td>
                <td><input type="text" name="factoryname" value="<?php echo $plant[0]['factoryname']?>" required maxlength="50"></td></tr>
            
            <tr><td class='frmlblTd'>Zone<span class="mandatory">*</span></td>
                <td><input type="text" name="zonename" id="zonename" value="<?php echo $plant[0]['zonename']?>" required maxlength="10" autocomplete="off"></td>
                 <input type="hidden" name="zoneid" id="zoneid" value="<?php echo $plant[0]['zoneid']?>" maxlength="50"/>
            <div id="display1" class="listlocation"></div>
            </tr>
             
               
        <input type='hidden' id='locationid' name='factoryid' value="<?php echo $plant[0]['factoryid']?>" >
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>    

