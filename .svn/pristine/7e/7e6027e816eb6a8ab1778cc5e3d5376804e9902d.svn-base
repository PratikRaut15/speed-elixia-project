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
        <form id="addorders" method="POST" action="action.php?action=edit-share">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Transporter Share</th></tr></thead>
                <tbody>

                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'>Factory<span class="mandatory">*</span></td>
                        <td><input type="text" name="factory_name" id="factory_name"  value="<?php echo $share[0]['factoryname'] ?>" required maxlength="10" autocomplete="off"></td>
                <div id="display1" class="listlocation"></div>
                <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $share[0]['factoryid'] ?>" maxlength="50"/>
                </tr>
                <tr><td class='frmlblTd'>Zone<span class="mandatory">*</span></td>
                    <td><input type="text" name="zonename" id="zonename" value="<?php echo $share[0]['zonename'] ?>" required maxlength="10" autocomplete="off"></td>
                <input type="hidden" name="zoneid" id="zoneid" value="<?php echo $share[0]['zoneid'] ?>" maxlength="50"/>
                <div id="display" class="listzone"></div> 
                </tr>
                <tr><td class='frmlblTd'>Transporter<span class="mandatory">*</span></td>
                    <td><input type="text" name="transporter" id="transporter_name"  value="<?php echo $share[0]['transportername'] ?>" required maxlength="10" autocomplete="off" readonly="readonly"></td>
                <div id="display1" class="listlocation"></div>
                <input type="hidden" name="transporterid" id="transporterid" value="<?php echo $share[0]['transporterid'] ?>" maxlength="50"/>
                </tr>

                <tr><td class='frmlblTd'>Share Percent<span class="mandatory">*</span></td>
                    <td><input type="text" name="sharepercent" value="<?php echo $share[0]['sharepercent'] ?>" required maxlength="10"></td></tr>

                </tr>


                <input type='hidden' id='transportershareid' name='transportershareid' value="<?php echo $share[0]['transportershareid'] ?>" >
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

