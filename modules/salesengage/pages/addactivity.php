<?php
/**
 * Activity master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:salesengage.php?pg=view-order');
}

$getreminder = $sales->getreminderdataselect();
?>
<ul id="tabnav">
<?php
if($_REQUEST['pg']=="add-activity"){
    ?>
    <li><a class='selected' href='salesengage.php?pg=add-activity&id=<?php echo $id;?>'>Add  Activity</a></li>
    <li><a class='' href='salesengage.php?pg=view-activity&id=<?php echo $id;?>'>View Activity</a></li>
<?php } ?>
</ul>
    <br/>
<div class='container'>
    <center>
    <form name="activitymasterform" id="activitymasterform" method="POST"  onsubmit="addactivitydata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Activity Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr>
                <td class='frmlblTd'>Reminder Name <span class="mandatory">*</span></td>
                <td>
<!--                    <input type="text" name="remindernameauto" id="remindernameauto" required>
                    <input type="hidden" name="remid" id="remid">-->
                    <select name="remid" id="remid">
                    <option value="">Select</option>
                    <?php 
                        if(!empty($getreminder)){
                    ?>
                    <option value="<?php echo $getreminder[0]["id"];?>"><?php echo $getreminder[0]["value"]; ?></option>
                    <?php    
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'>Notes</td><td><textarea name="notes" id="notes"></textarea></td></tr>
            <tr><td class='frmlblTd'>Activity time</td><td>
<!--                    <input type="text" name="activitytime" id="activitytime">-->
                    <input type='text' name='activitytime' id='activitytime' placeholder='Date'/>
                    <input type="date" data-date="00:00" class="input-mini" name="STime" id="STime">
                </td></tr>
            <tr><td class='frmlblTd'>Activity Reminder Duration in <br> Minutes</td><td><input type="number" name="activityrduration" id="activityrduration"></td></tr>
<!--            <tr><td class='frmlblTd'>Activity Status</td><td><input type="radio" name="activitystatus" value="1"> Yes <input type="radio" name="activitystatus" value="2"> No</td></tr>-->
            <tr><td class='frmlblTd'>Request For </td><td><input type="checkbox" name="emailreq" id="emailreq" value="1"/> Email <input type="checkbox" name="smsreq" id="smsreq" value="1"> SMS </td></tr>
            <tr><td class='frmlblTd'>Payment Amount </td><td><input type="text" name="paymentamt" id="paymentamt"></td></tr>
            <tr><td class='frmlblTd'>Activity Type <span class="mandatory">*</span></td><td><input type="radio" name="activitytype" value="1"> Client <input type="radio" name="activitytype" value="2"> Elixir</td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="productsubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="orderid" id="orderid" value="<?php echo $id;?>">
    </form>
    </center>
</div>
