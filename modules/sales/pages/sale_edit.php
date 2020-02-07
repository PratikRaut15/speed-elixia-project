<?php
/**
 * Sales Edit Master form
 */
?>
<?php 
$saleeditdata = saleedit($_SESSION['customerno'],$_SESSION['userid'],$saleid);

if(isset($saleeditdata))
    {
        foreach($saleeditdata as $row)
        {
            $salesid = $row['salesid'];
            $asmid = $row['asmid'];
            $srcode = $row['srcode'];
            $srname = $row['srname'];
            $phone = $row['phone'];
            $dob =  $row['dob'];
        }
        
         if(!empty($dob) && $dob!="1970-01-01"){
               $datetest =  date("d-m-Y", strtotime($dob));
               $dob1 = $datetest;
            }else{
                $dob1 = date("d-m-Y");
            }
    }
?>


<br/>
<div class='container'>
    <center>
    <form name="saleseditform" id="saleseditform" method="POST" action="sales.php?pg=saleedit&catid=<?php echo$salesid;?>" onsubmit="updatesalesdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Sales Master</th></tr></thead>
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
                        $oldid = $res[$i]['id'];
                        $oldval = $res[$i]['value'];
                      ?>    
                    <option value="<?php echo $oldid;?>"<?php if($oldid == $asmid){echo "selected";}?>><?php echo $oldval; ?></option>
                    <?php
                    }
                    ?>
                </select>
                </td></tr>
            <tr><td class='frmlblTd'> S. R.code <span class="mandatory">*</span></td><td><input type="text" name="srcode" value="<?php echo $srcode;?>"></td></tr>
            <tr><td class='frmlblTd'> S. R.Name <span class="mandatory">*</span></td><td><input type="text" name="srname" value="<?php echo $srname; ?>"></td></tr>
            <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="srphoneno" value="<?php echo $phone;?>"  maxlength="15"></td></tr>
            <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob" value="<?php echo $dob1;?>" maxlength="15"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="saleid" id="saleid" value="<?php echo $saleid; ?>">
    </form>
    </center>
</div>
