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
    <form id="addorders" method="POST" action="action.php?action=edit-location">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Location</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Location<span class="mandatory">*</span></td>
                <td><input type="text" name="location_name" value="<?php echo $location[0]['locationname']?>" required maxlength="50"></td></tr>
               
        <input type='hidden' id='locationid' name='locationid' value="<?=$location[0]['locationid']?>" >
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>    

