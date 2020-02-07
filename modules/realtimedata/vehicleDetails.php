<?php
include 'vehicleDashboardFunctions.php';
$cust_ecodeid = isset($_SESSION['ecodeid']) ? $_SESSION['ecodeid'] : '';
$cust_e_id = isset($_SESSION['e_id']) ? $_SESSION['e_id'] : '';
$type = "";
$refreshtime = (int) (isset($_SESSION['refreshtime']) ? $_SESSION['refreshtime'] : '1');
if (isset($_SESSION['Driver'])) {
    $driverLabel = $_SESSION['Driver'];
} else {
    $driverLabel = "Driver";
}
$vehicleId = $_GET['vid'];
?>
<!--<link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,700italic' rel='stylesheet' type='text/css'>-->
<!-- Added by Pratik Raut -->
<link rel="stylesheet" href="../../css/magnificPopUp/jquery.magnific.popup.css">
<!-- Added by Pratik Raut -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type="text/javascript" src="../../scripts/magnificPopUp/jquery.magnific.popup.js"> </script>
<div>
</div>
<div style="display: inline-flex;height: 45px;">
    <div>
    </div>
        <div style='width:700px;'>
            <ul id="tabnav">
                <?php
echo "<li><a class='selected' href='vehicleDashboard.php?vid=$vehicleId'>Vehicle and Device Data</a></li>";
?>
</ul>
</div>
</div>
<?php
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    ?>
                <div id="rec">
                    <?php
if (!isset($_SESSION['ecodeid'])) {
        if (isset($_POST['Filter'])) {
            display_filter_vehicledata($_POST['sel_status'], $_POST['sel_stoppage'], $_POST['vehicleid']);
        } else {
            display_vehicledata($_GET['vid']);
        }
        ?>
                    <?php
} else {
        if (isset($_POST['Filter'])) {
            display_filter_vehicledata($_POST['sel_status'], $_POST['sel_stoppage'], $_POST['vehicleid']);
        } else {
            display_vehicledata();
        }
    }
    ?>
                </div>
                <div class="result" style="dispaly:none;"></div>
            <?php
}
if (!isset($_POST['STdate'])) {
    $StartDate = getdate_IST();
} else {
    $StartDate = strtotime($_POST['STdate']);
}
if (!isset($_POST['STime'])) {
    $stime = "00:00";
} else {
    $stime = $_POST['STime'];
}
?>
            <?php
include 'rtd_fuelModal.php';
include 'rtd_buzzerModal.php';
include 'rtd_freezeModal.php';
include 'rtd_driverModal.php';
include 'rtd_messageToDriver.php';
include 'rtd_vehicleHistory.php';
include 'rtd_immobiliserModal.php';
include 'rtd_muteModal.php';
?>
            <?php
// For Realtime Slide map width
if (!isset($_SESSION['ecodeid'])) {
    echo '<input type="hidden" name="wwidth" id="wwidth" value="75">';
} else {
    echo '<input type="hidden" name="wwidth" id="wwidth" value="100">';
}
?>
            <script type="text/javascript">
                var test = 0 + ",";
                jQuery('.rate4').live('click', function() {
                    jQuery(this).attr('class', 'b-s-t Ye rate3');
                    var ecodeid = '<?php echo $cust_ecodeid; ?>';
                    var userrole = '<?php echo $_SESSION['Session_UserRole']; ?>';
                    var e_id = '<?php echo $cust_e_id; ?>';
                    var grp = '<?php echo $_SESSION['groupid']; ?>';
                    var buzzer = '<?php echo $_SESSION['buzzer']; ?>';
                    var customerno = jQuery('#customerno').val();
                    // var test='';
                    // test += this.id+",";
                    test = test.replace(this.id + ",", "");
                    var test1 = test.replace(0 + ",", "");
                    //alert(test);
                    jQuery.ajax({
                        type: "GET",
                        url: "searchfilter.php",
                        data: "customer_no=" + customerno + "&sel_status=" + test1 + "&userrole=" + userrole + "&ecodeid=" + ecodeid + "&eid=" + e_id + "&grp=" + grp + "&buzzer=" + buzzer,
                        success: function(html) {
                            jQuery("#rec").html(html);
                            loadrefresh();
                        }
                    });
                });
                jQuery('.rate3').live('click', function() {
                    jQuery(this).attr('class', 'b-s-t Ye rate4');
                    var ecodeid = '<?php echo $cust_ecodeid; ?>';
                    var userrole = '<?php echo $_SESSION['Session_UserRole']; ?>';
                    var e_id = '<?php echo $cust_e_id; ?>';
                    var grp = '<?php echo $_SESSION['groupid']; ?>';
                    var buzzer = '<?php echo $_SESSION['buzzer']; ?>';
                    var customerno = jQuery('#customerno').val();
                    test += this.id + ",";
                    var test1 = test.replace(0 + ",", "");
                    //alert(test);
                    jQuery.ajax({
                        type: "GET",
                        url: "searchfilter.php",
                        data: "customer_no=" + customerno + "&sel_status=" + test1 + "&userrole=" + userrole + "&ecodeid=" + ecodeid + "&eid=" + e_id + "&grp=" + grp + "&buzzer=" + buzzer,
                        success: function(html) {
                            jQuery("#rec").html(html);
                            loadrefresh();
                        }
                    });
                });
                jQuery('.rate6').live('click', function() {
                    jQuery(this).attr('class', 'b-s-t Ye rate5');
                    var ecodeid = '<?php echo $cust_ecodeid; ?>';
                    var userrole = '<?php echo $_SESSION['Session_UserRole']; ?>';
                    var e_id = '<?php echo $cust_e_id; ?>';
                    var grp = '<?php echo $_SESSION['groupid']; ?>';
                    var buzzer = '<?php echo $_SESSION['buzzer']; ?>';
                    var customerno = jQuery('#customerno').val();
                    // var test='';
                    // test += this.id+",";
                    test = test.replace(this.id + ",", "");
                    var test1 = test.replace(0 + ",", "");
                    //alert(test);
                    jQuery.ajax({
                        type: "GET",
                        url: "searchfilter.php",
                        data: "customer_no=" + customerno + "&sel_status=" + test1 + "&userrole=" + userrole + "&ecodeid=" + ecodeid + "&eid=" + e_id + "&grp=" + grp + "&buzzer=" + buzzer,
                        success: function(html) {
                            jQuery("#rec").html(html);
                            loadrefresh();
                        }
                    });
                });
                jQuery('.rate5').live('click', function() {
                    jQuery(this).attr('class', 'b-s-t Ye rate6');
                    var ecodeid = '<?php echo $cust_ecodeid; ?>';
                    var userrole = '<?php echo $_SESSION['Session_UserRole']; ?>';
                    var e_id = '<?php echo $cust_e_id; ?>';
                    var grp = '<?php echo $_SESSION['groupid']; ?>';
                    var buzzer = '<?php echo $_SESSION['buzzer']; ?>';
                    var customerno = jQuery('#customerno').val();
                    test += this.id + ",";
                    var test1 = test.replace(0 + ",", "");
                    //alert(test);
                    jQuery.ajax({
                        type: "GET",
                        url: "searchfilter.php",
                        data: "customer_no=" + customerno + "&sel_status=" + test1 + "&userrole=" + userrole + "&ecodeid=" + ecodeid + "&eid=" + e_id + "&grp=" + grp + "&buzzer=" + buzzer,
                        success: function(html) {
                            jQuery("#rec").html(html);
                            loadrefresh();
                        }
                    });
                });
                $(document).on('click','.notesPopup',function(){
                    var vehicleId = $(this).attr('vehicleId');
                    var customerno = $(this).attr('customerno');
                    // var notes = $('#notes').val();
                    // if(notes == "")
                    $('.result').show();
                    $.ajax({
                        type: "POST",
                        url: "getNotes.php",
                        data: {
                            task : "getNotes",
                            vehicleId : vehicleId,
                            customerno : customerno
                        },
                        success: function(html) {
                            $.magnificPopup.open({
                                items: {
                                    src: $('.result').html(html),
                                    type: 'inline'
                                },
                            });
                        },
                        beforeSend:function(html){
                        },
                         complete : function(html){
                        }
                    });
                    return false;
                });
                 $(document).on('click','.btnSubmitForNotes',function(){
                    var customerno = $(this).attr('customerno');
                    var vehicleId = $(this).attr('vehicleId');
                    var notes = $.trim($('#vehicleId_'+vehicleId).val());
                    if(notes == ""){
                        alert("Please enter any value.");
                        return false;
                    }else{
                        $.ajax({
                            type: "POST",
                            url: "getNotes.php",
                            data: {
                                task : "submitNotes",
                                vehicleId : vehicleId,
                                customerno : customerno,
                                notes : notes,
                            },
                            success: function(html) {
                                console.log(html);
                                var obj = $.parseJSON(html);
                                var output = ``;
                                var i = 0;
                                $.each(obj,function(key,val){
                                    i++;
                                        output+=`<tr><td><center> `+i+` </center></td>
                                                    <td><center> `+val.createdBy+` </center></td>
                                                    <td><center> `+val.note+` </center></td>
                                                    <td><center> `+val.updatedOn+`</center></td>
                                                </tr>`;
                                });
                                $('.notesBody').html(output);
                                alert('Notes Added SuccessFully');
                                // mfp-close
                               return false;
                            },
                            beforeSend:function(html){
                            },
                            complete : function(html){
                            }
                        });
                    }
                    return false;
                 });
                 $(document).on('click','.Modalclose',function(){
                     $('.mfp-close').click();
                 });
                 </script>