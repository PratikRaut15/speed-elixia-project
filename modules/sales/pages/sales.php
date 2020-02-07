<?php
/**
 * Sales Master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="salesform" id="salesform" method="POST" action="sales.php?pg=sales" onsubmit="addsalesdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Sales Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            
            <tr><td class='frmlblTd'> Asm Name <span class="mandatory">*</span></td><td>
                <?php 
                $res = get_asm($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="asmid">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        echo"<option value=".$res[$i]['id'].">".$res[$i]['value']."</option>";
                    }
                    ?>
                </select>
                </td></tr>
            <tr><td class='frmlblTd'> S. R.code <span class="mandatory">*</span></td><td><input type="text" name="srcode"></td></tr>
            <tr><td class='frmlblTd'> S. R.Name <span class="mandatory">*</span></td><td><input type="text" name="srname"></td></tr>
            <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="srphoneno"  maxlength="15"></td></tr>
            <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob"  maxlength="15"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
