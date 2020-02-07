<?php
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/model/VODevices.php");
$_scripts[] = "../../scripts/trash/prototype.js";

include("header.php");

class testing {
    
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$db = new DatabaseManager();

//echo(rand(1000,9000));
//random number genrate 
/*
  $i = 0;
  $tmp = mt_rand(1,9);
  do {
  $tmp .= mt_rand(0, 9);
  } while(++$i < 14);
  echo $tmp;
 */
// UNIT PURCHASE
//$message = "";
$devices = array();
$db = new DatabaseManager();
$isGenset = 0;
$customerid = 1;
$SQL = sprintf("SELECT uid,unitno,activationcode,created_on FROM " . DB_Trace . ".unit  WHERE unit.customerno = %d order by uid desc", $customerid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    $x = 1;
    while ($row = $db->get_nextRow()) {
        $device = new VODevices();
        $device->uid = $row["uid"];
        $device->unitno = $row["unitno"];
        $device->activationcode = $row["activationcode"];
        $device->created_on = $row["created_on"];
        $devices[] = $device;
        $x++;
    }
}




// ---------------------------------------- Unit Purchase Form  -------------------------------------------
if (IsHead()) {
    ?>
    <div class="panel">
        <div class="paneltitle" align="center">
            New Trace Purchase</div>
        <div class="panelcontents">

            <!--            <form method="post" name="myform" id="myform" onsubmit="ValidateForm();return false;"  enctype="multipart/form-data">-->
            <form method="post" name="myform" id="myform" enctype="multipart/form-data">
                <table width="50%">
                    <tr>
                        <td colspan="2"><h3>Device</h3></td>
                    </tr>

                    <tr><td colspan='2'><div id='errormessage' style='font-size:12px;' ></div></td></tr>

                    <tr>
                        <td>Unit Type<span style='color:red;'>*</span></td>
                        <td> 
                            <input name="device" id="unittype1" type="radio" value="1" onclick="genratecode();" checked /> &nbsp;Personal &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="device" id="unittype2" type="radio" value="2" onclick="genratecode();" /> &nbsp;Portable &nbsp;&nbsp;&nbsp;&nbsp;
                            <input name="device" id="unittype3" type="radio" value="3" onclick="genratecode();" /> &nbsp;Mobile Tracker &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td>Activation Code<span style='color:red;'>*</span></td><td><input name = "activationno" id="activationno" type="text" maxlength="4"></td>            
                    </tr>
                    <tr>
                        <td>Unit No.<span style='color:red;'>*</span></td><td><input name = "punitno" id="punitno" maxlength="16" type="text"></td>            
                    </tr>
                </table>
                <div><input type="button" id="submitbtn" name="submitbtn" onclick="purchasecheck();" value="Purchase Unit"/></div>
            </form>
        </div>

    </div>
    <div class="panel">
        <div class="paneltitle" align="center">Unit List</div>
        <div class="panelcontents">
            <?php
            $dg = new objectdatagrid($devices);
//$dg->AddAction("View/Edit", "../../images/edit.png", "pushcommand.php?id=%d");
//$dg->AddColumn("Sr.No", "x");
            $dg->AddColumn("uid #", "uid");
            $dg->AddColumn("unitno #", "unitno");
            $dg->AddColumn("Activation Code #", "activationcode");
            $dg->AddColumn("Created On #", "created_on");
            $dg->AddIdColumn("uid");
            $dg->Render();
            ?>
        </div>
    </div>
    <?php
}
?>
<br/>
<?php
include("footer.php");
?>
<script type="text/javascript">

    $(document).ready(function () {
        $("#errormessage").hide();
        $("#activationno").focus();

        $('body').click(function () {
            // do something here
            $("#errormessage").hide();
        });

        $('#punitno').keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                //alert('You pressed a "enter" key in textbox');	
                var len = $('#punitno').val().length;
                var actlen = $('#activationno').val().length;
                var punitno = $("#punitno").val();
                var activationno = $("#activationno").val();
                var type = $('input[name=device]:checked', '#myform').val();

                if ($.isNumeric(activationno) == false) {
                    alert("Activation No should be numeric");
                    return false;
                }
                else if ($.isNumeric(punitno) == false) {
                    alert("Unitnumber should be numeric");
                    return false;
                }
                else if (punitno == "" || type == undefined || activationno == "")
                {
                    alert("Please check valid fields");
                    return false;
                } else if (actlen < 4) {
                    alert("Activation code should not be less than 4 digit.");
                    $("#activationno").val('');
                    $("#activationno").focus();
                    return false;
                } else if (len < 15) {
                    alert("Unit no should not be less than 15 digit.");
                    $("#punitno").val('');
                    $("#punitno").focus();
                    return false;
                } else {
                    var unitno = $("#punitno").val();
                    var unittype = $('input[name=device]:checked', '#myform').val();
                    checktraceunits(unitno, unittype, activationno);
                }
            }
            event.stopPropagation();
        });


        $('#activationno').keypress(function (event) {
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                $("#punitno").focus();
            }
            event.stopPropagation();
        });
        /*
         $('#punitno').live('keydown', function (e) {
         if (e.keyCode == 9) {
         e.preventDefault();
         var len = $('#punitno').val().length;
         var actlen = $('#activationno').val().length;
         var punitno = $("#punitno").val();
         var activationno = $("#activationno").val();
         var type = $('input[name=device]:checked', '#myform').val();
         //alert(len);
         
         if (punitno == "" || type == undefined || activationno == "")
         {
         alert("Please check valid fields");
         return false;
         }
         if($.isNumeric(activationno)==false){
         alert("Activation No should be numeric");
         return false;
         }else if($.isNumeric(punitno)==false){
         alert("Unitnumber should be numeric");
         return false;
         }else if (actlen < 4) {
         alert("Activation code should not be less than 4 digit.");
         return false;
         } else if (len < 15) {
         alert("Unit no should not be less than 15 digit");
         return false;
         } else {
         var unitno = $("#punitno").val();
         var unittype = $('input[name=device]:checked', '#myform').val();
         checktraceunits(unitno, unittype, activationno);
         }
         }
         });
         */
    });

    function purchasecheck() {
        var punitno = $("#punitno").val();
        var activationno = $("#activationno").val();
        var type = $('input[name=device]:checked', '#myform').val();
        var len = $('#punitno').val().length;
        var actlen = $('#activationno').val().length;

        if (punitno == "" || type == undefined || activationno == "")
        {
            alert("Please check valid fields");
            return false;
        } else if (actlen < 4) {
            alert("Activation code should not be less than 4 digit.");
            return false;
        } else if (len < 15) {
            alert("Unit no should not be less than 15 digit");
            return false;
        } else {
            var unitno = $("#punitno").val();
            var unittype = $('input[name=device]:checked', '#myform').val();
            checktraceunits(unitno, unittype, activationno);
        }
    }

    function ValidateForm() {
        var punitno = $("#punitno").val();
        var type = $('input[name=device]:checked', '#myform').val();
        if (punitno == "" || type == undefined)
        {
            alert("Please check valid fields");
            return false;
        } else {
            $("#myform").submit();
        }
    }


    function genratecode() {
        var type = $('input[name=device]:checked', '#myform').val();
        if (type == 3) {
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: {action: 'genaratecode'},
                dataType: 'html',
                success: function (html) {
                    jQuery("#activationno").val('');
                    jQuery("#punitno").val('');
                    jQuery("#punitno").val(html);
                }
            });
        } else {
            jQuery("#punitno").val('');
            jQuery("#activationno").val('');
        }
        return false;
    }


    function checktraceunits(unitno, unittype, activationcode) {
        var comments = $("#comments").val();
        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {action: "purchasetrace", unitnumber: unitno, unittypeid: unittype, activationcode: activationcode},
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.status == "sucess") {
                    $("#errormessage").show();
                    jQuery("#errormessage").css("color", "green");
                    jQuery("#errormessage").html(obj.message);
                    jQuery("#punitno").val('');
                    jQuery("#activationno").val('');
                    jQuery("#unittype1").prop("checked", true);
                    $("#activationno").focus();
                    location.reload();
                } else if (obj.status == "failure") {
                    $("#errormessage").show();
                    jQuery("#errormessage").css("color", "red");
                    jQuery("#errormessage").html(obj.message);
                }
            }
        });
    }
</script> 

