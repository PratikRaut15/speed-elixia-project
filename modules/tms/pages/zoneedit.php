<style>
#ajaxstatus{text-align:center;font-weight:bold;display:none}
.mandatory{color:red;font-weight:bold;}
#addorders table{width:50%;}
#addorders .frmlblTd{text-align:center}    
</style>
<br/>
<div class='container' >
    <center>
    <form id="addorders" method="POST" action="action.php?action=edit-zone">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Zone</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Zone Name<span class="mandatory">*</span></td>
                <td><input type="text" name="zone_name" value="<?php echo $zones[0]['zonename']?>" required maxlength="25"></td></tr>
               
        <input type='hidden' id='zoneid' name='zoneid' value="<?php echo $zones[0]['zoneid']?>" >
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>  

