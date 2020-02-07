<?php
/**
 * Distributor Edit Master form
 */
?>
<?php 
$disteditdata = distedit($_SESSION['customerno'],$_SESSION['userid'],$distid);
if(isset($disteditdata))
    {
        foreach($disteditdata as $row)
        {
            $distname = $row['distname'];
            $distcode = $row['distcode'];
            $distid = $row['distid'];
            $saleid = $row['salesid'];
            $dob = $row['dob'];
            $dphone = $row['dphone'];
            $demail = $row['demail'];
            
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
    <form name="distributoredit" id="distributoredit" method="POST" action="sales.php?pg=distedit&distid=<?php echo$distid;?>" onsubmit="updatedistdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Distributor</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
          
            <tr><td class='frmlblTd'> Sales <span class="mandatory">*</span></td><td>
                <?php 
                $salesdata = get_sales_all($_SESSION['customerno'],$_SESSION['userid']);
                
                ?>                    
                <select name="saleid" style="width:250px;">
                    <option value="0">Select</option>
                    <?php
                     foreach ($salesdata as $row) {
                    ?>    
                    <option value="<?php echo $row->userid;?>"<?php if($row->userid == $saleid){echo "selected";}?>><?php echo $row->realname; ?></option>
                    <?php
                        }
                    ?>
                </select>
                </td></tr>
            <tr><td class='frmlblTd'> Distributor Code <span class="mandatory">*</span></td><td><input type="text" name="distcode" value="<?php echo $distcode;?>"></td></tr>
            <tr><td class='frmlblTd'> Distributor Name <span class="mandatory">*</span></td><td><input type="text" name="distname" value="<?php echo $distname;?>"></td></tr>
            <tr><td class='frmlblTd'>Birth Date</td><td><input type="text" name="cdob"  maxlength="15" value="<?php echo $dob1;?>"></td></tr>
            <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="distphone"  maxlength="10" value="<?php echo $dphone; ?>"></td></tr>
            <tr><td class='frmlblTd'>Email Id</td><td><input type="text" name="emailid" id="emailid" value="<?php echo $demail; ?>"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" id="distid" name="distid" value="<?php echo $distid;?>">
    </form>
    </center>
</div>
