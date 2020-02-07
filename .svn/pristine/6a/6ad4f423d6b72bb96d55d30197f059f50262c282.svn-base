<?php
$insurance = getinsuranceid($_GET['insid']);
?>
<div style = "float:none; padding-left:41%;">
    <form name = "editinsurance" action = "route.php" method = "POST" id = "editinsurance" onsubmit="return submitinsurance();">
        <table id = "floatingpanel">
            <thead>
                <tr>
                    <th id = "formheader" colspan = "2">Edit Insurance</th>
                </tr>
            </thead>
            <tr>
                <td colspan = "2" id = "problem" style = "display: none;color: #FF0000;">Please enter Insurance Company name</td>
            </tr>
            <tr>
                <td>Insurance Name</td>
                <td><input type = "text" name = "insurancename" id = "insurancename" size = "60" value = "<?php echo $insurance->name; ?>" placeholder = "Name"></td>
                    <input type ="hidden" name ="insuranceid" value="<?php echo $insurance->id ?>"
            </tr>
            <tfoot>
                <tr>
                    <td colspan = "2" align = "center"><input type = "submit" name = "editinsurancedetails" class = "btn  btn-primary" value = "Modify"></td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>
<script>
    function submitinsurance()
    {
        if (jQuery("#insurancename").val() == "")
        {
            jQuery("#problem").show();
            jQuery("#problem").fadeOut(3000);
            return false;
        } else {
            jQuery("#editinsurance").submit();
        }
    }
</script>