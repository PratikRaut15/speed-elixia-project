<?php
if(isset($_GET['did']))
{
    //$deltask = deltask($_GET['did']);
    header("location:task.php?id=2");
}else{
    $task = gettasksbyid($_GET['tid']);
}   
?>
<div  style="float:none; padding-left:35%;">
<form name="edittask" action="route.php" method="POST" id="edittask">
    <table id="floatingpanel">
        <thead>
        <tr>
            <th id="formheader" colspan="2">Update Task Details</th>
        </tr>
        </thead>
        <tr>
            <td colspan="2" id="perfectinfo" style="display: none">Task Added</td>
            <td colspan="2" id="problem" style="display: none">Please enter name</td>    
        </tr>    
        <tr>
            <td>Name</td>
            <td><input type="text" name="editname" id="editname" style="width:250px;" size="60" value="<?php echo $task->task_name; ?>" placeholder="Name"></td>
        </tr>
        <tr>
            <td>Task Amount</td>
            <td><input type="text" name="taskamount" id="taskamount" style="width:250px;" size="60" value="<?php echo $task->unitamount; ?>" placeholder="0.00"></td>
        </tr>
        <tr>
            <td>Task Discount</td>
            <td><input type="text" name="taskdiscount" id="taskdiscount" style="width:250px;" size="60" value="<?php echo $task->unitdiscount; ?>" placeholder="0.00"></td>
        </tr>
        <tfoot>
            <input type="hidden" name="taskid" id="taskid" value="<?php echo $_GET['tid']; ?>" />
        <tr>
            <td colspan="2" align="center"><input type="button" name="edituserdetails" class="btn  btn-primary" value="Update Task" onclick="submittask();"></td>
        </tr>
        </tfoot>
    </table>
</form>
</div>

<script>
function submittask()
{
    if(jQuery("#editname").val() == "")
    {
        jQuery("#problem").show();
        jQuery("#problem").fadeOut(3000);                 
    }
    else
    {
        jQuery("#edittask").submit();
    }
}
</script>