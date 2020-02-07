<?php
    include 'whrtd_functions.php';
    $cust_ecodeid = isset($_SESSION['ecodeid']) ? $_SESSION['ecodeid'] : '';
    $cust_e_id = isset($_SESSION['e_id']) ? $_SESSION['e_id'] : '';
    /**/
    $type = "";
    $refreshtime = (int) isset($_SESSION['refreshtime']) ? $_SESSION['refreshtime'] : '1';
?>
<link href='http://fonts.googleapis.com/css?family=Crimson+Text:400,700italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<style type="text/css">
    .rt{
        opacity: 0.6;
    }
    .rate3 {
        padding:2px;
        border: 2px solid #ccc;
        border-radius: 5px;
        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */
        background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
    }
    .rate4 {
        padding:2px;
        border: 2px solid #ccc;
        border-radius: 5px;
        background: #88bfe8; /* Old browsers */
        background: -moz-linear-gradient(top, #88bfe8 0%, #70b0e0 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#88bfe8), color-stop(100%,#70b0e0)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* IE10+ */
        background: linear-gradient(to bottom, #88bfe8 0%,#70b0e0 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#88bfe8', endColorstr='#70b0e0',GradientType=0 ); /* IE6-9 */
    }
    .rate5 {
        padding:2px;
        border: 2px solid #ccc;
        border-radius: 5px;
        background: #ffffff; /* Old browsers */
        background: -moz-linear-gradient(top, #ffffff 0%, #e5e5e5 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e5e5e5)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #ffffff 0%,#e5e5e5 100%); /* IE10+ */
        background: linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-9 */
    }
    .rate6 {
        padding:2px;
        border: 2px solid #ccc;
        border-radius: 5px;
        background: #88bfe8; /* Old browsers */
        background: -moz-linear-gradient(top, #88bfe8 0%, #70b0e0 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#88bfe8), color-stop(100%,#70b0e0)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #88bfe8 0%,#70b0e0 100%); /* IE10+ */
        background: linear-gradient(to bottom, #88bfe8 0%,#70b0e0 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#88bfe8', endColorstr='#70b0e0',GradientType=0 ); /* IE6-9 */
    }
    .vote {
        height:30px;
        width:auto;
        margin-bottom:10px;
    }
    .ek1{
        min-width: 50px;
        max-width: 50px;
    }
    div.inline{
        float:left;
        padding: 1px;
        display: table-row;
    }
    .divBorder{
        border-top:1px solid #d9d9d9;
        display: table;
    }
    #innerImgWh{
        width: auto;
        height: auto;
    }
    #innerSmallWh{
        position: absolute;
        bottom: -2px;
        left: 1px;
        width: 27px;
        height: 27px;
    }
</style>
<div>
    <?php if ($_SESSION['portable'] != '1') {
        ?>
        <div class="row-fluid">
            <div class="span4" style="margin-top:12px;position: relative;bottom: 15px; left:-15px;">
                <div>
                    <?php
                        $manager = getManager();
                            if (!empty($manager) && !isset($_SESSION['ecodeid'])) {
                            ?>
                        <div class="stick">
                            <ul>
                                <li>
                                    <a href="#">
                                        <p>Your Dedicated Relationship Manager is
                                        <span style="color: red;">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              <?php echo $manager->name; ?> </span>
                                        </br>Call On <span style="color: red;"><?php echo $manager->mobile ?> </span>
                                        or Mail @ <span style="color: red;"><?php echo $manager->email ?></span></p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div id="loaddata" class="span3">
            </div>
            <div  id="loaddata" class="span5"  >
                <?php display_warehouse_status();?>
            </div>
        </div>
        <?php
            } else {
                echo '<div class="row-fluid" style="height:20px;"></div>';
            }
        ?>
</div>
<div style="display: inline-flex;height: 45px;"><?php if ($refreshtime > 0) {
                                                    ?>
        <div style="margin-top: 10px;">Refresh time interval&nbsp;
            <select id="refreshInterval" onchange="refreshInterval1(this.value, 'whrtd')">
                <option value="1"                                  <?php if ($refreshtime == 1) {
                                              echo "selected";
                                      }
                                      ?> >1</option>
                <option value="5"                                  <?php if ($refreshtime == 5) {
                                              echo "selected";
                                      }
                                      ?> >5</option>
                <option value="10"                                   <?php if ($refreshtime == 10) {
                                               echo "selected";
                                       }
                                       ?> >10</option>
            </select>
            <span id="time" style="padding-left: 10px;"></span> min
        </div>
    <?php }if ($_SESSION['Session_UserRole'] == 'elixir') {
        ?>
        <div>
            <ul id="tabnav">
                <?php
                    if (isset($_SESSION["Warehouse"])) {
                            $custom_wh = $_SESSION["Warehouse"];
                        } else {
                            $custom_wh = "Warehouse";
                        }
                        if (isset($_GET['id'])) {
                            if ($_GET['id'] == 1) {
                                echo "<li><a class='selected' href='warehouse.php?id=1'>" . $custom_wh . " and Device Data</a></li>";
                            } else {
                                echo "<li><a href='warehouse.php?id=1'>" . $custom_wh . " and Device Data</a></li>";
                            }
                        } else {
                            echo "<li><a class='selected' href='warehouse.php?id=1'>" . $custom_wh . " and Device Data</a></li>";
                        }
                    ?>
            </ul>
        </div>
        <?php
            } else {
                echo "<div style='width:500px;'></div>";
            }
        ?>
    <div  style="padding-left: 100px;margin-top: 16px;">
        <a href='javascript:void(0)' onclick="exportTo('pdf')"><img src="../../images/pdf_icon.png" alt="Export to PDF" class='exportIcons' title="Export to PDF" /></a>
        <a href='javascript:void(0)' onclick="exportTo('xls')"><img src="../../images/xls.gif" alt="Export to Excel" class='exportIcons' title="Export to Excel" /></a>
<!--        <a href='javascript:void(0)' onclick="exportTo('html')"><img src="../../images/print.png" alt="Print Report" class='exportIcons' title="Print Report" /></a>
        <a href='#mail_pop' data-toggle="modal" onclick='jQuery("#rtdExportMail").html("");'><img src="../../images/email_icon.png" alt="Email Report" class='exportIcons' title="Email Report" /></a>-->
    </div>
</div>
<?php
    if (!isset($_GET['id']) || $_GET['id'] == 1) {
    ?>
    <div id="rec">
        <?php
            display_vehicledata();
            ?>
    </div>
    <?php
        } elseif ($_GET['id'] == 3) {
            display_simdata();
        } elseif ($_GET['id'] == 4) {
            display_misc();
        }
        //echo $_REQUEST['next']."hhhehhehe";
    ?>
<?php
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
<div id="Buzzer" class="modal hide in" style="width:500px; height:190px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 id="header-2" style="color:#0679c0"></h4>
                </div>
                <div class="modal-body">
                    <div class="control-group"><img class = 'buzzer' src = '../../images/buzzer.png' title = 'Buzzer'></div>
                    <div class="control-group" style="color: #000; height: 3px;">Do You Like To Alarm The Warehouse ?</div>
                    <input type="hidden" id="unitno" name="unitno"  value="">
                    <input type="hidden" id="vehicle_id" name="vehicle_id"  value="">
                    <input type="hidden" id="cusromerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                </div>
                <div class="modal-footer" style="text-align: center; height: 5px;">
                    <button onclick="add_buzzer();" name="addfuel" id="save" value="addfuel" data-toggle="modal" class="btn btn-success">Yes</button>
                    <button data-dismiss="modal" class="btn btn-success">No</button>
                </div>
            </fieldset>
        </form>
    </center>
</div>
<div id="BuzzerNot" class="modal hide in" style="width:500px; height:250px; display:none;">
    <center>
        <form class="form-horizontal" id="addfuelentry" method="post" action="realtimedata.php">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 id="header-10" style="color:#0679c0"></h4>
                </div>
                <div class="modal-body">
                    <div class="control-group"><img class = 'buzzer' src = '../../images/buzzer.png' title = 'Buzzer'></div>
                    <div class="control-group" style="color: #000; height: 3px;">Buzzer Not Installed For This Warehouse <br/>* Note: For further information please contact an elixir.<br/></div>
                </div>
                <br/>
                <div class="" style="text-align: center; height: 5px;">
                    <button data-dismiss="modal" class="btn btn-success">OK</button>
                </div>
            </fieldset>
        </form>
    </center>
</div>
<div id="Mute" class="modal hide in" style="width:600px; height:190px; display:none;">
    <center>
        <form class="form-horizontal" id="mutevehicle" method="post">
            <fieldset>
                <div class="modal-header" >
                    <button class="close" data-dismiss="modal">×</button>
                    <h4 id="header-mute" style="color:#0679c0"></h4>
                </div>
                <div class="modal-body">
                    <div class="control-group"></div>
                    <div class="control-group" id='alertmsg' style="color: #000; height: 3px;"></div>
                    <div class="control-group" id='notemsg' style="color: #000; height: 3px;"></div>
                    <input type="hidden" id="temp" name="temp"  value="">
                    <input type="hidden" id="condition" name="condition"  value="">
                    <input type="hidden" id="vehicle_id" name="vehicle_id"  value="">
                    <input type="hidden" id="customerno" name="customerno"  value="<?php echo $_SESSION['customerno']; ?>">
                    <input type="hidden" id="userid" name="userid"  value="<?php echo $_SESSION['userid']; ?>">
                </div>
                <div class="modal-footer" style="text-align: center; height: 5px;">
                    <button onclick="updateVehicleMute();" name="addfuel" id="save" value="addfuel" data-toggle="modal" class="btn btn-success">Yes</button>
                    <button data-dismiss="modal" class="btn btn-success">No</button>
                </div>
            </fieldset>
        </form>
    </center>
</div>
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
    jQuery('.rate4').live('click', function () {
        jQuery('#pageloaddiv').show();
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
            success: function (html) {
                jQuery("#rec").html(html);
                loadrefresh();
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    });
    jQuery('.rate3').live('click', function () {
        jQuery('#pageloaddiv').show();
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
            success: function (html) {
                jQuery("#rec").html(html);
                loadrefresh();
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    });
    jQuery('.rate6').live('click', function () {
        jQuery('#pageloaddiv').show();
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
            success: function (html) {
                jQuery("#rec").html(html);
                loadrefresh();
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    });
    jQuery('.rate5').live('click', function () {
        jQuery('#pageloaddiv').show();
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
            success: function (html) {
                jQuery("#rec").html(html);
                loadrefresh();
            },
            complete: function(){
                jQuery('#pageloaddiv').hide();
            }
        });
    });
</script>
