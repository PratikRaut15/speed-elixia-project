<?php
/**
 * Stock Edit Master form
 */
?>
<?php

    $stockeditdata = stockedit($_SESSION['customerno'],$_SESSION['userid'],$stockid);
    if(isset($stockeditdata))
    {
        foreach($stockeditdata as $row)
        {
            $stockid = $row['stockid'];
            $srcode = $row['srcode'];
            $salesid = $row['salesid'];
            $distcode = $row['distcode'];
            $distname = $row['distname'];
            $distributorid = $row['distributorid'];
            $categoryname = $row['categoryname'];
            $categoryid = $row['categoryid'];
            $styleno = $row['styleno'];
            $styleid = $row['styleid'];
            $quantity = $row['quantity'];
        }
    }
?>
<br/>
<div class='container'>
    <center>
    <form name="stockeditform" id="stockeditform" method="POST" action="sales.php?pg=stockedit?&stockid=<?php echo$stockid;?>" onsubmit="updatestockdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Edit Stock </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'> Srcode<span class="mandatory">*</span></td><td>
                <?php 
                $res = get_srcode($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="srcode" id='srcode'>
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        $oldid = $res[$i]['id'];
                        $oldval = $res[$i]['value'];
                        ?>
                    <option value="<?php echo $oldid;?>" <?php if($oldid==$salesid){echo "selected";}?>><?php echo $oldval;?></option>
                    
                     <?php
                     //echo"<option value=".$oldid.">".$oldval."</option>";
                    }
                    ?>
                </select>
                </td>
            </tr>
            
            <tr>
                <td class='frmlblTd'> Distributor <span class="mandatory">*</span> </td><td>
                <select name="distid" id="distid">
                   <option value="0">Select</option> 
                   <?php if (!empty($distributorid))
                    {
                       echo "<option value='".$distributorid."' selected >".$distname."</option>";
                    }
                   ?>
                </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'> Category </td><td>
                <?php 
                $res = get_category($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="catid" id="catid">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                         $oldidc = $res[$i]['id'];
                        $oldvalc = $res[$i]['value'];
                        //echo"<option value=".$res[$i]['id'].">".$res[$i]['value']."</option>";
                    ?>
                    <option value="<?php echo $oldidc;?>" <?php if($oldidc==$categoryid){echo "selected";}?>><?php echo $oldvalc;?></option>
                    <?php
                    }
                    ?>
                </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'> Style  </td><td>
                <select name="styleid" id='styleid'>
                    <option value="0">Select</option>
                     <?php if (!empty($styleid))
                    {
                       echo "<option value='".$styleid."' selected >".$styleno."</option>";
                    }
                   ?>
                </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'> Quantity </td><td><input type='text' name='qty' id='qty' value="<?php echo $quantity;?>" /></td></tr>  
          
            
            <tr>
                <td class='frmlblTd'> Date </td>
                <td>
                    <input type="text" name="STdate" id="SDate">
                    <input id="STime" class="input-mini" type="text" data-date="00:00" name="STime">
                </td>
            </tr>
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="stockid" id="stockid" value="<?php echo $stockid;?>">
    </form>
    </center>
</div>