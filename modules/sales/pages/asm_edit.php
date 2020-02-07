<?php
/**
 * ASM Edit Master form
 */
?>
<?php 
$asmeditdata = asmedit($_SESSION['customerno'],$_SESSION['userid'],$asmid);
if(isset($asmeditdata))
    {
        foreach($asmeditdata as $row)
        {
            $asmname = $row['asmname'];
            $asmid = $row['asmid'];
            $stateid = $row['stateid'];

        }
    }
?>

<br/>
<div class='container'>
    <center>
    <form name="asmeditform" id="asmeditform" method="POST" action="sales.php?pg=asmedit&asmid=<?php echo$asmid;?>"  onsubmit="updateasmdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update ASM Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>ASM Name <span class="mandatory">*</span></td><td><input type="text" name="asmname" value="<?php echo $asmname;?>" required></td></tr>
            <tr><td class='frmlblTd'> State <span class="mandatory">*</span></td><td>
                <?php 
                $res = get_state($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="state">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        $oldid = $res[$i]['id'];
                        $oldval = $res[$i]['value'];
                    ?>    
                    <option value="<?php echo $oldid;?>"<?php if($oldid == $stateid){echo "selected";}?>><?php echo $oldval; ?></option>
                    <?php
                        }
                    ?>
                </select>
                </td></tr>
           
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" id="asmid" name="asmid" value="<?php echo $asmid;?>">
    </form>
    </center>
</div>
