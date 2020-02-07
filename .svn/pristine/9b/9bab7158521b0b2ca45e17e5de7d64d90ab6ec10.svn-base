<?php
/**
 * Category edit Master form
 */
?>
<?php 
$cateditdata = catedit($_SESSION['customerno'],$_SESSION['userid'],$catid);

if(isset($cateditdata))
    {
        foreach($cateditdata as $row)
        {
            $catname = $row['categoryname'];
            $catid = $row['catid'];
        }
    }
?>
<br/>
<div class='container'>
    <center>
    <form name="categoryeditform" id="categoryeditform" method="POST" action="sales.php?pg=catedit&catid=<?php echo$catid;?>" onsubmit="updatecategorydata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Category Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Category Name <span class="mandatory">*</span></td>
                <td>
                    <input type="text" name="catname" value="<?php echo $catname;?>" required>
                    <input type="hidden" name="catid" id="catid" value="<?php echo $catid;?>">
                </td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>

    </form> 
    </center>
</div>
