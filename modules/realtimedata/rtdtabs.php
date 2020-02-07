<?php
include 'rtd_functions.php';
$cust_ecodeid = isset($_SESSION['ecodeid']) ? $_SESSION['ecodeid'] : '';
$cust_e_id = isset($_SESSION['e_id']) ? $_SESSION['e_id'] : '';
$type = "";
$refreshtime = (int) (isset($_SESSION['refreshtime']) ? $_SESSION['refreshtime'] : '1');
if (isset($_SESSION['Driver'])) {
    $driverLabel = $_SESSION['Driver'];
} else {
    $driverLabel = "Driver";
}
?>
<!--<link href='https://fonts.googleapis.com/css?family=Crimson+Text:400,700italic' rel='stylesheet' type='text/css'>-->
<!-- Added by Pratik Raut -->
<link rel="stylesheet" href="../../css/magnificPopUp/jquery.magnific.popup.css">
<!-- Added by Pratik Raut -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<script type="text/javascript" src="../../scripts/magnificPopUp/jquery.magnific.popup.js"> </script>
<div>
    <?php if ($_SESSION['portable'] != '1') {
    ?>
        <div class="row-fluid">
            <div class="span4" style="margin-top:12px;position: relative;bottom: 15px; left:-15px;">
                <div>
                    <?php if (isset($_SESSION['rel_manager']) && $_SESSION['rel_manager'] != '') {?>
                        <div class="stick">
                            <ul>
                                <li>
                                    <a href="#">
                                        <p>Your Dedicated Relationship Manager is<span style="color: red;">                                                                                                            <?php echo $_SESSION['manager_name']; ?> </span></br>
                                            Call On <span style="color: red;"><?php echo $_SESSION['manager_mobile'] ?> </span>
                                            or Mail @ <span style="color: red;"><?php echo $_SESSION['manager_email'] ?></span>
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div id="loaddata" class="span8">
                <?php display_vehicle_status();?>
            </div>
        </div>
    <?php } else {
    echo '<div class="row-fluid" style="height:20px;"></div>';
}
?>
</div>
<div style="display: inline-flex;height: 45px;">
    <div>
        <div style="margin-top: 10px;">Refresh time interval&nbsp;
            <select id="refreshInterval" onchange="refreshInterval1(this.value, 'rtd')">
                <option value="1"                                  <?php if ($refreshtime == 1) {
    echo "selected";
}?>>1</option>
                <option value="5"                                  <?php if ($refreshtime == 5) {
    echo "selected";
}?>>5</option>
                <option value="10"                                   <?php if ($refreshtime == 10) {
    echo "selected";
}?>>10</option>
            </select>
            <span id="time" style="padding-left: 10px;"></span> min
        </div>
    </div>
    <?php
if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == 'elixir') {
    ?>
        <div style='width:700px;'>
            <ul id="tabnav">
                <?php
if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='realtimedata.php?id=1'>Vehicle and Device Data</a></li>";
        } else {
            echo "<li><a href='realtimedata.php?id=1'>Vehicle and Device Data</a></li>";
        }
        if ($_GET['id'] == 3) {
            echo "<li><a class='selected' href='realtimedata.php?id=3'>SIM Data</a></li>";
        } else {
            echo "<li><a href='realtimedata.php?id=3'>SIM Data</a></li>";
        }
        if ($_GET['id'] == 4) {
            echo "<li><a class='selected' href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
        }
        if ($_GET['id'] == 5) {
            echo "<li><a class='selected' href='realtimedata.php?id=5'>Miscellaneous Data</a></li>";
        } else {
            echo "<li><a href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
        }
    } else {
        echo "<li><a class='selected' href='realtimedata.php?id=1'>Vehicle and Device Data</a></li>";
        echo "<li><a href='realtimedata.php?id=3'>SIM Data</a></li>";
        echo "<li><a href='realtimedata.php?id=4'>Miscellaneous Data</a></li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='width:300px;'></div>";
}
?>
            <!-- Don not render following for consignee starts here -->
            <?php
if (isset($_SESSION['role_modal']) && strtolower($_SESSION['role_modal']) != 'consignee') {
    ?>
                <div style="padding-left: 100px;margin-top: 16px;">
                    <a href='javascript:void(0)' onclick="exportTo('pdf')"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
                    <a href='javascript:void(0)' onclick="exportTo('xls')"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
                </div>
           <!-- Don not render following for consignee ends here -->
           <?php
}
?>
<?php
echo "</div>";
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    ?>
                <div id="rec">
                    <?php
if (!isset($_SESSION['ecodeid'])) {
        if (isset($_POST['Filter'])) {
            display_filter_vehicledata($_POST['sel_status'], $_POST['sel_stoppage'], $_POST['vehicleid']);
        } else {
            display_vehicledata();
        }
        ?>
                        <center>
                            <div>
                                <?php
if (!isset($_SESSION['ecodeid']) || $_SESSION['customerno'] != 177) {
            include '../speed_dashboard/route_dashboard_functions.php';
            include '../speed_dashboard/pages/viewvehicles.php';
        }
        ?>
                            </div>
                        </center>
                    <?php
} else {
        if (isset($_POST['Filter'])) {
            display_filter_vehicledata($_POST['sel_status'], $_POST['sel_stoppage'], $_POST['vehicleid']);
        } else {
            display_vehicledata();
        }
        ?>
                        <center>
                            <div>
                                <?php
if (!isset($_SESSION['ecodeid']) || $_SESSION['customerno'] != 177) {
            include '../speed_dashboard/route_dashboard_functions.php';
            include '../speed_dashboard/pages/viewvehicles.php';
        }
        ?>
                            </div>
                        </center>
                    <?php
}
    ?>
                </div>
                <div class="result" style="dispaly:none;"></div>
                <!-- <div class="loading loader" display>
                    <img src="../../images/loader.gif" class="img-responsive" alt="">
                </div> -->
            <?php
} elseif ($_GET['id'] == 3) {
    display_simdata();
} elseif ($_GET['id'] == 4) {
    display_misc();
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
echo '<input type="hidden" name="loginUserRole" id="loginUserRole" value="' . $_SESSION['Session_UserRole'] . '"/>';
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