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
        <form id="addorders" method="POST" action="action.php?action=edit-vehicle-type">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Vehicle Type</th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr><td class='frmlblTd'>Vehicle Code<span class="mandatory">*</span></td>
                        <td><input type="text" name="code" value="<?php echo $vehicletype[0]['vehiclecode'] ?>" required maxlength="20"></td></tr>
                    <tr><td class='frmlblTd'>Vehicle Description<span class="mandatory">*</span></td>
                        <td><input type="text" name="description" value="<?php echo $vehicletype[0]['vehicledescription'] ?>" required maxlength="50"></td></tr>

                    <tr><td class='frmlblTd'>Sku Type<span class="mandatory">*</span></td>
                        <td><input type="text" name="type_name" id="type_name" value="<?php echo $vehicletype[0]['type'] ?>" required maxlength="50" autocomplete="off"></td>
                <input type="hidden" id="typeid" name="typeid" value="<?php echo $vehicletype[0]['skutypeid'] ?>" />
                    </tr>

                    <tr><td class='frmlblTd'>Volume<span class="mandatory">*</span></td>
                        <td><input type="text" name="volume" id="volume" value="<?php echo $vehicletype[0]['volume'] ?>" required maxlength="10"></td>


                    </tr>

                    <tr><td class='frmlblTd'>Weight<span class="mandatory">*</span></td>
                        <td><input type="text" name="weight" id="weight"  value="<?php echo $vehicletype[0]['weight'] ?>" required maxlength="10"></td>
                <div id="display" class="listzone"></div>
                </tr>

                <input type='hidden' id='vehicletypeid' name='vehicletypeid' value="<?php echo $vehicletype[0]['vehicletypeid'] ?>" >
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

