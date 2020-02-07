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
        <?php
        //$factory_delivery
        ?>
        <form id="addorders" method="POST" action="action.php?action=edit-factory-delivery">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Sku</th></tr></thead>
                <tbody>

                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'>Factory<span class="mandatory">*</span></td>
                        <td><input type="text" name="factory_name" id="factory_name" value="<?php echo $factory_delivery[0]['factoryname'] ?>" required maxlength="50"></td>
                <input type="hidden" name="factoryid" id="factoryid" value="<?php echo $factory_delivery[0]['factoryid']?>"/>
                    </tr>


                    <tr><td class='frmlblTd'>Sku Code<span class="mandatory">*</span></td>
                        <td><input type="text" name="sku_code" id="sku_code" value="<?php echo $factory_delivery[0]['skucode'] ?>" required maxlength="50"></td>
                    <input type="hidden" name="skuid" id="skuid" value="<?php echo $factory_delivery[0]['skuid']?>"/>
                    </tr>
                    
                    <tr><td class='frmlblTd'>Depot<span class="mandatory">*</span></td>
                        <td><input type="text" name="depot_name" id="depot_name" value="<?php echo $factory_delivery[0]['depotname'] ?>" required maxlength="50"></td>
                    
                    <input type="hidden" name="depotid" id="depotid" value="<?php echo $factory_delivery[0]['depotid']?>" >
                    </tr>

                    <tr><td class='frmlblTd'>Date Required<span class="mandatory">*</span></td>
                        <td><input type="text" name="date_required" id="SDate" value="<?php echo $factory_delivery[0]['date_required'] ?>" required maxlength="25"></td></tr>

                    <tr><td class='frmlblTd'>Weight<span class="mandatory">*</span></td>
                        <td><input type="text" name="weight" value="<?php echo $factory_delivery[0]['netWeight'] ?>" required maxlength="10"></td></tr>

                    </tr>


                <input type='hidden' id='fdid' name='fdid' value="<?php echo $factory_delivery[0]['fdid'] ?>" >
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

