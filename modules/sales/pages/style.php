<?php
/**
 * Style Master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="styleform" id="styleform"  method="POST" action="sales.php?pg=style" onsubmit="addstyledata();return false;" enctype="multipart/form-data" >
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Add SKU </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'> SKU Number <span class="mandatory">*</span></td><td><input type="text" name="styleno"></td></tr>
            <tr><td class='frmlblTd'> Category <span class="mandatory">*</span></td><td>
                <?php 
                $res = get_category($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="category" style="width:250px;">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        echo"<option value=".$res[$i]['id'].">".$res[$i]['value']."</option>";
                    }
                    ?>
                </select>
                </td></tr>
            <tr><td class='frmlblTd'> MRP <span class="mandatory">*</span></td><td><input type="number" name="mrp" step="0.01" required></td></tr>
            <tr><td class='frmlblTd'> Distributor Price </td><td><input type="number" name="distprice" step="0.01"></td></tr>
            <tr><td class='frmlblTd'> Retail Price </td><td><input type="number" name="retprice" step="0.01"></td></tr>
<tr><td class='frmlblTd'> Company Selling Price </td><td><input type="number" name="companysellingprice" step="0.01"></td></tr>

            <tr><td class='frmlblTd'> Cartons</td><td><input type="number" name="carton"></td></tr>
            <tr>
                <td class='frmlblTd'> Image Upload </td>
                <td>
                     <input type="file" name="imgupload" id="imgupload"/>
                </td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" id ="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
