<?php
/**
 * Activity master form
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno,$userid);


$id = $_GET['id'];
$aid = $_GET['aid'];

if($aid==""|| $aid=="0"){
    header('location:salesengage.php?pg=view-activity&id='.$id);
}
$getactivity = $sales->editgetactivity_byid($id,$aid);
$activitytime1 = date("d-m-Y", strtotime($getactivity[0]['activitytime']));
$sttime = date("h:i", strtotime($getactivity[0]['activitytime']));

$getreminder = $sales->getreminderdataselect();
?>
<br>
<div class='container'>
    <center>
    <form name="editactivitymasterform" id="editactivitymasterform" method="POST"  onsubmit="editactivitydata();return false;">
    <table class='table table-condensed'>
        <thead>
             <tr>
                <td colspan="100%" class="tdnone">
                    <div>
                        <a href="salesengage.php?pg=view-activity&id=<?php echo $id;?>" class="backtextstyle">Back To Activity View </button></a>
                    </div>
                </td>
            </tr>
            <tr><th colspan="100%" >Activity </th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr>
                <td class='frmlblTd'>Reminder Name <span class="mandatory">*</span></td>
                <td>
<!--                    <input type="text" name="remindernameauto" id="remindernameauto" value="<?php echo $getactivity[0]['remindername'];?>" required>
                    <input type="hidden" name="remid" id="remid" value="<?php echo $getactivity[0]['reminderid'];?>">-->
                    <select id="remid" name="remid">
                    <?php 
                        if(!empty($getreminder)){
                    ?>
                    <option value="<?php echo $getreminder[0]["id"];?>" <?php if($getactivity[0]['reminderid']==$getreminder[0]["id"]){ echo"selected"; } ?>><?php echo $getreminder[0]["value"]; ?></option>
                    <?php    
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr><td class='frmlblTd'>Notes</td><td><textarea name="notes" id="notes"><?php echo $getactivity[0]['notes'];?></textarea></td></tr>
            <tr>
                <td class='frmlblTd'>Activity time</td><td>
                    <input type="text" name="activitytime" id="activitytime" value="<?php echo $activitytime1;?>">
                    <input type="date" data-date="<?php echo $sttime; ?>" class="input-mini" name="STime" id="STime" >
                </td></tr>
            <tr><td class='frmlblTd'>Activity Reminder Duration in <br> Minutes</td><td><input type="number" name="activityrduration" id="activityrduration" value ="<?php echo $getactivity[0]['activity_reminder_duration'];?>" ></td></tr>
<!--            <tr><td class='frmlblTd'>Activity Status</td>
                <td>
                    
                    <input type="radio" name="activitystatus" value="1" <?php if($getactivity[0]['isactivitydone']=='1'){ echo"checked"; } ?> > Yes 
                    <input type="radio" name="activitystatus" value="2" <?php if($getactivity[0]['isactivitydone']=='2'){ echo"checked"; } ?>> No
                </td>
            </tr>-->
            <tr><td class='frmlblTd'>Request For </td>
                <td>
                    <input type="checkbox" name="emailreq" id="emailreq" value="1" <?php if($getactivity[0]['isemailrequested']=='1'){echo "checked"; }?> /> Email 
                    <input type="checkbox" name="smsreq" id="smsreq" value="1" <?php if($getactivity[0]['issmsrequested']=='1'){echo "checked"; }?> > SMS 
                </td>
            </tr>
            <tr><td class='frmlblTd'>Payment Amount </td><td><input type="text" name="paymentamt" id="paymentamt" value="<?php echo $getactivity[0]['paymentamount'];?>"></td></tr>
            <tr><td class='frmlblTd'>Activity Type </td><td><input type="radio" name="activitytype" value="1" <?php if($getactivity[0]['activitytype']==1){echo"checked";}?>  > Client <input type="radio" name="activitytype" value="2" <?php if($getactivity[0]['activitytype']==2){echo"checked";}?> > Self</td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="productsubmit" value="Update" class='btn btn-primary'> 
        </tbody>
    </table>
        <input type="hidden" name="orderid" id="orderid" value="<?php echo $id;?>">
        <input type="hidden" name="activityid" id="activityid" value="<?php echo $aid;?>">
    </form>
    </center>
</div>
