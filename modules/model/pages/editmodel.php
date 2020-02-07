<?php
$model = getmodelid($_GET['modelid']);
?>
<div style = "float:none; padding-left:41%;">
    <form name = "editmodel" action = "route.php" method = "POST" id = "editmodel" onsubmit="return submitmodel();">
        <table id = "floatingpanel">
            <thead>
                <tr>
                    <th id = "formheader" colspan = "2">Edit Model</th>
                </tr>
            </thead>
            <tr>
                 <td colspan = "2" id = "problem" style = "display: none;color: #FF0000;">Please enter model name</td>
            </tr>
            <tr>
                <td>Model Name</td>
                <td><input type = "text" name = "modelname" id = "modelname" size = "60" value = "<?php echo $model->name ?>" placeholder = "Name"></td>
            </tr>
            <tfoot>
            <input type = "hidden" name = "modelid" id = "modelid" value = "<?php echo $_GET['modelid']; ?>" />
            <tr>
                <td colspan = "2" align = "center"><input type = "submit" name = "editmodeldetails" class = "btn  btn-primary" value = "Modify"></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>
    function submitmodel()
    {
        if (jQuery("#modelname").val() == "")
        {
            jQuery("#problem").show();
            jQuery("#problem").fadeOut(3000);
            return false;
        } else {
            jQuery("#editmodel").submit();
        }
    }
</script>
