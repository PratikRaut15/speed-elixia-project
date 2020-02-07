<?php
/**
 * Edit Reminder master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-reminder');
}
$getreminderdata = $sales->getreminderdata_byid($id);

?>
<br/>
<div class='container'>
    <center>
    <form name="editremindermasterform" id="editremindermasterform" method="POST"  onsubmit="editreminderdata();return false;">
    <table class='table table-condensed'>
        <thead>
            <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-reminder" class="backtextstyle">Back To Reminder View</a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Update Reminder </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Reminder Name <span class="mandatory">*</span></td><td><input type="text" name="rname" value="<?php echo $getreminderdata[0]['remindername'];?>" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'>
                    <input type="submit" name="remindersubmit" value="Update" class='btn btn-primary'>
<!--                    <a href="salesengage.php?pg=view-reminder">View Reminders</a>-->
                </td></tr>
        </tbody>
    </table>
        <input type="hidden" name="reminderid" id="reminderid" value="<?php echo $getreminderdata[0]['id'];?>">
    </form>
    </center>
</div>
