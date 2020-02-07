<?php
require_once "mobility_function.php";
/**
 * Edit Category master form
 */
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=view-category');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$catdata = $mob->getcatdata_byid($id);
?>
<br/>
<div class='container'>
    <center>
    <form name="editcatform" id="editcatform" method="POST" action="mobility.php?pg=edit-category" onsubmit="editcatdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Category Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Category Name <span class="mandatory">*</span></td><td><input type="text" name="catname" id="catname" value="<?php if(isset($catdata[0]['category_name'])){ echo $catdata[0]['category_name'];}?>" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="categorysubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="catid" id="catid" value="<?php echo $id;?>">
    </form>
    </center>
</div>
