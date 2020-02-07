<?php
/**
 * View Activity interface
 */
require_once "salesengage_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$sales = new Saleseng($customerno, $userid);

$id = $_GET['id'];
if ($id == "" || $id == "0") {
    header('location:salesengage.php?pg=view-activity');
}
$display_activity = $sales->getactivity_byid($id);
$getreminder = $sales->getreminderdataselect();
?>
<br/>
<div class='container'>
    <div style="float:right;">
        <button class="btn-primary" style="margin:5px; width:auto; display: inline-block;" onclick="addactivity(<?php echo $id; ?>);">Add New Activity <img src="../../images/show.png"></button>
    </div>
    <div style='clear:both;'></div>
    <center>
        <table class='display table table-bordered table-striped table-condensed'>
            <thead>

                <tr class='dtblTh'>
                    <th>Reminder Name</th>
                    <th>Activity Time</th>
                    <th>Activity Reminder Duration</th>
                    <th>Is Email Requested</th>
                    <th>Is SMS Requested</th>
                    <th>Activity Type</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                <?php
                if (!empty($display_activity)) {
                    foreach ($display_activity as $row) {
                        ?> 
                        <tr>
                            <td><?php echo $row['remindername']; ?></td>
                            <td><?php echo $row['activitytime']; ?></td>
                            <td><?php echo $row['activity_reminder_duration']; ?></td>
                            <td><?php if ($row['isemailrequested'] == 1) { echo "Yes"; } else {  echo"No"; } ?></td>
                            <td><?php if ($row['issmsrequested'] == 1) { echo "yes"; } else { echo"No"; } ?></td>
                            <td><?php if ($row['activitytype'] == 1) { echo "Client"; } elseif ($row['activitytype'] == 2) { echo"Self"; } ?></td>
                            <td><a href="salesengage.php?pg=edit-activity&id=<?php echo $id; ?>&aid=<?php echo $row['activityId']; ?>"><img src='../../images/edit_black.png'></a></td>
                            <td><a href='javascript:void(0);' onclick='deleteactivity(<?php echo $row['activityId']; ?>);'><img src='../../images/Delete_red.png'></a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td style='text-align: center;' colspan='100%'> No Activities for this order.</td></tr>
    <?php
}
?>


            </thead>
        </table>
    </center>
</div>


<!--Add Activity pop starts----->
<div id='addActivityformBuble'  class="bubble row" style='position: absolute;' >
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h4 style='text-align:center;'>Add Alerts</h4>
                <div id='ajaxBstatus'></div>
                <table  class="table showtable">
                    <tbody>
                        <tr>
                            <td class='frmlblTd'>Remind me for <span class="mandatory">*</span></td>
                            <td>
                                <select name="remid" id="remid">
                                    <option value="">Select</option>
                                    <?php
                                    if (!empty($getreminder)) {
                                        foreach ($getreminder as $row) {
                                            ?>
                                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["value"]; ?></option>
        <?php
    }
}
?>
                                </select>
                            </td>
                        </tr>
                        <tr><td class='frmlblTd'>Notes</td><td><textarea name="notes" id="notes"></textarea></td></tr>
                        <tr><td class='frmlblTd'>Remind me at</td><td>
                                <input style='display: inline-block; width:135px;' type='text' name='activitytime' id='activitytime' placeholder='Date' value="<?php echo date('d-m-Y'); ?>"/><input type="date" style='display: inline-block;' data-date="00:00" class="input-mini" name="STime" id="STime">
                            </td></tr>
                        <tr><td class='frmlblTd'>Remind me before (in Min)</td><td><input type="number" name="activityrduration" id="activityrduration" value="30"></td></tr>
                        <tr><td class='frmlblTd'>Request For </td><td><input type="checkbox" name="emailreq" id="emailreq" value="1" checked/> Email <input type="checkbox" name="smsreq" id="smsreq" value="1" checked> SMS </td></tr>
                        <tr><td class='frmlblTd'>Payment Amount </td><td><input type="text" name="paymentamt" id="paymentamt"></td></tr>
                        <tr><td class='frmlblTd'>Activity Type <span class="mandatory">*</span></td><td><input type="radio" name="activitytype" checked value="1"> Client <input type="radio" name="activitytype" value="2"> Self</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type='hidden' id='orderid' name='orderid' value='<?php echo $id; ?>'>
                <input type="submit" class="btn btn-primary" value="Submit" id="addactivitydata" onclick="addactivitydatapop();"/> 
                <input type="submit" class="btn  bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change template pop ends-->