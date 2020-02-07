<?php
/**
 * Edit Style Master form
 */
?>
<?php 
$styleditdata = styleedit($_SESSION['customerno'],$_SESSION['userid'],$styleid);

if(isset($styleditdata))
    {
        foreach($styleditdata as $row)
        {
            $styleid = $row['styleid'];
            $categoryid = $row['categoryid'];
            $styleno = $row['styleno'];
            $mrp = $row['mrp'];
            $distprice = $row['distprice'];
            $retailprice = $row['retailprice'];
            $companysellingprice = $row['companysellingprice'];
            $customerno = $row['customerno'];
            $carton = $row['carton'];
            $imagelink = $row['imagelink'];
            $productimage = $row['productimage'];
        }
    }
?>

<br/>
<div class='container'>
    <center>
    <form name="styleeditform" id="styleeditform" method="POST" action="sales.php?pg=styleedit?&styleid=<?php echo$styleid;?>" onsubmit="updatestyledata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update SKU</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'> SKU Number <span class="mandatory">*</span></td><td><input type="text" name="styleno" value="<?php echo $styleno;?>"></td></tr>
            <tr><td class='frmlblTd'> Category <span class="mandatory">*</span></td><td>
                <?php 
                $res = get_category($_SESSION['customerno'],$_SESSION['userid']);
                $c = count($res);
                ?>                    
                <select name="category" style="width:250px;">
                    <option value="0">Select</option>
                    <?php
                    for($i=0; $i< $c; $i++){
                        $oldid = $res[$i]['id'];
                        $oldval = $res[$i]['value'];
                    ?>
                    <option value="<?php echo $oldid;?>" <?php if($oldid==$categoryid){ echo "selected";}?>><?php echo $oldval;?></option>
                    <?php
                    }
                    ?>
                </select>
                </td></tr>
            <tr><td class='frmlblTd'> MRP <span class="mandatory">*</span></td><td><input type="number" name="mrp" step="0.01" value="<?php echo $mrp;?>" required></td></tr>
            <tr><td class='frmlblTd'> Distributor Price </td><td><input type="number" name="distprice" step="0.01" value="<?php echo $distprice;?>"></td></tr>
            <tr><td class='frmlblTd'> Company Selling Price </td><td><input type="number" name="companysellingprice" step="0.01" value="<?php echo $companysellingprice;?>"></td></tr>
            <tr><td class='frmlblTd'> Retail Price </td><td><input type="number" name="retprice" step="0.01" value="<?php echo $retailprice;?>"></td></tr>
            <tr><td class='frmlblTd'> Carton </td><td><input type="number" name="carton" value="<?php echo $carton; ?>"></td></tr>
            <tr>
                <td class='frmlblTd'> Image Upload </td>
                <td>
                     <input type="file" name="imgupload" id="imgupload"/>
                    <?php 
                    if($imagelink!="" && $productimage!=""){
                    ?>
                     <br>
<!--                     <span><a href="<?php echo $imagelink; ?>"><?php echo $productimage;  ?></a></span>-->
                     <span><a href="<?php echo $imagelink; ?>" style="text-decoration: underline;" target="_blank" ><?php echo "Download Image";  ?></a></span>
                    <?php 
                    }
                    ?>
                     
                     
                </td>
            </tr>
            
            
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" id="styleid" name="styleid" value="<?php echo $styleid;?>"/>
    </form>
    </center>
</div>
