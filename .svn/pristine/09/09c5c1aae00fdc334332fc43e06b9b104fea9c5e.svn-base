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
        <form id="addorders" method="POST" action="action.php?action=edit-sku">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Sku</th></tr></thead>
                <tbody>

                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'>Sku Code<span class="mandatory">*</span></td>
                        <td><input type="text" name="skucode" value="<?php echo $skus[0]['skucode'] ?>" required maxlength="100"></td></tr>
                    <tr><td class='frmlblTd'>Sku Description<span class="mandatory">*</span></td>
                        <td><input type="text" name="sku_description" value="<?php echo $skus[0]['sku_description'] ?>" required maxlength="250"></td></tr>


                    <tr><td class='frmlblTd'>Sku Type<span class="mandatory">*</span></td>
                        <td><input type="text" name="factory_name" id="type_name"  value="<?php echo $skus[0]['type'] ?>" required maxlength="20" autocomplete="off"></td>
                <div id="display1" class="listlocation"></div>
                <input type="hidden" name="typeid" id="typeid" value="<?php echo $skus[0]['tid'] ?>" maxlength="50"/>
                </tr>


                <tr><td class='frmlblTd'>Volume<span class="mandatory">*</span></td>
                    <td><input type="text" name="volume" value="<?php echo $skus[0]['volume'] ?>" required maxlength="10"></td></tr>

                <tr><td class='frmlblTd'>Weight<span class="mandatory">*</span></td>
                    <td><input type="text" name="weight" value="<?php echo $skus[0]['weight'] ?>" required maxlength="10"></td></tr>

                <tr><td class='frmlblTd'>Net Gross Percent<span class="mandatory">*</span></td>
                    <td><input type="text" name="netgrosspercent" value="<?php echo $skus[0]['netgross'] ?>" required maxlength="6"></td></tr>


                <input type='hidden' id='skuid' name='skuid' value="<?php echo $skus[0]['skuid'] ?>" >
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

