<?php
/**
 * ASM Master form
 */
?>
<br/>
<div class='container'>
    <center>
    <form name="asmform" id="asmform" method="POST" action="sales.php?pg=asm" onsubmit="addasmdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >ASM Add</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>ASM Name <span class="mandatory">*</span></td><td><input type="text" name="asmname" required></td></tr>
            <tr><td class='frmlblTd'> State <span class="mandatory">*</span></td><td>
                <?php 
                $res = get_state($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="state">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        echo"<option value=".$res[$i]['id'].">".$res[$i]['value']."</option>";
                    }
                    ?>
                </select>
                </td></tr>
           
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
