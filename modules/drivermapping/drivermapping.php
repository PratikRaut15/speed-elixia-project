<?php
include '../panels/header.php';
require_once"../../config.inc.php";
require_once "../api/V7/class/database.inc.php";
include_once 'drivermapping_function.php';
echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
?>
<style>
    .ui-autocomplete {
        max-height: 100px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
        /* add padding to account for vertical scrollbar */
        padding-right: 20px;
    }
    input {width:200px;}
    #errspan{ color:red; font-size:11px; display:none; text-align: center;}
</style>    
<div class="entry" style='padding: 25px;'>
    <center>
        <h3> Mapping</h3>
        <form id="addmapping" class="form-horizontal well " enctype="multipart/form-data" method="POST"  name="addmapping" style="width:70%;">
            <span id="errspan"></span>
            <table style='width: 50%'>
                <tr>
                    <td style='width: 30%'>User : </td>
                    <td>
                        <input type='text' name='username[]' id='username' onkeyup='usernamekey(1);' placeholder="User Name">
                        <input type='hidden' name='userid[]' id='userid'>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" style='text-align:right;'><input type='checkbox' name='clubuser' id='clubuser'> Club User </td>
                </tr>

                <tr id='usertr'>
                    <td colspan="2">
                        <b style='color:green; font-size: 9px;'>Note : </b><span style='font-size: 9px;'>Max 3 Users only</span>
                        <table style="display: table; width: 100%" id="myTable">
                            <tr><th>Users</th>
                                <th>
                                    <span id='btnaddrow' style="float: right;" onclick="addrow();">
                                        <a><img  src="../../images/show.png" alt="Add Row"/> </a>
                                    </span>
                                </th>
                            </tr>
                        </table>
                    </td>
                </tr>


                <tr>
                    <td>Vehicle No : </td>
                    <td>
                        <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
                        <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                        <div id="display" class="listvehicle"></div>
                    </td>
                </tr>
                <tr>
                    <td>Driver : </td>
                    <td>
                        <input type='text' name='drivername' id='drivername' placeholder="Driver Name"/>
                        <input type='hidden' name='driverid' id='driverid'/>
                    <td>
                </tr>
                <tr>
                    <td colspan="2" style='text-align: left;'><input type='checkbox' name='addnewuserchecked' id='addnewuserchecked'/> Add New Driver</td> 
                </tr>
                <tr id ='newdrivertr'>
                    <td>Add New Driver : </td>
                    <td><input type='text' name='newdrivername' id='newdrivername'></td>
                </tr>
                <tr>
                    <td colspan="2"> <input type='button' class='btn btn-primary' name='map' value='Map' onclick="add_drivermapping();"  /></td>
                </tr>
            </table>    
        </form>    
    </center> 
</div>
<?php include '../panels/footer.php'; ?>
<script>
    var userids = [];
    jQuery(function () {
        $("#newdrivertr").css('display', 'none');
        $("#usertr").css('display', 'none');
        $('#clubuser').change(function () {
            if ($(this).is(":checked")) {
                $("#usertr").css('display', 'table-row');
            } else {
                $("#usertr").css('display', 'none');
            }

        });
        $('#addnewuserchecked').change(function () {
            if ($(this).is(":checked")) {
                $("#newdrivertr").css('display', 'table-row');
                $("#drivername").prop('disabled', true);
                $("#driverid").prop('disabled', true);
                $("#drivername").val('');
                $("#driverid").val('');
            } else {
                $("#newdrivertr").css('display', 'none');
                $("#drivername").prop('disabled', false);
                $("#driverid").prop('disabled', false);
            }

        });
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "../reports/autocomplete.php",
            ajaxParams: "dummydata=dummyData",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });

        $("#drivername").autocomplete({
            source: "drivermapping_ajax.php?action=driverauto",
            minLength: 1,
            select: function (event, ui) {
                jQuery('#driverid').val(ui.item.id);
            }
        });

        $("#username").autocomplete({
            source: "drivermapping_ajax.php?action=userauto&userids=" + userids,
            minLength: 1,
            select: function (event, ui) {
                jQuery('#userid').val(ui.item.id);
                userids.push($('#userid').val());
            }
        });
    });
    function fill(Value, strparam) {
        $('#vehicleno').val(strparam);
        $('#vehicleid').val(Value);
        $('#display').hide();
    }

    function add_drivermapping() {
        var username = $("#username").val();
        var userid = $("#userid").val();
        var clubuser = $('#clubuser').is(':checked');
        var vehicleno = $("#vehicleno").val();
        var vehicleid = $("#vehicleid").val();
        var drivername = $("#drivername").val();
        var driverid = $("#driverid").val();
        var addnewuserchecked = $('#addnewuserchecked').is(':checked');
        var newdrivername = $("#newdrivername").val();

        if (username == "" || userid == "") {
            $("#errspan").css('display', 'block');
            $("#errspan").css('color', 'red');
            $("#errspan").html('Please Enter Username.');
            $("#errspan").show().delay(1000).hide(0);
            return false;
        } else if (vehicleno == "" || vehicleid == "") {
            $("#errspan").css('display', 'block');
            $("#errspan").css('color', 'red');
            $("#errspan").html('Please Enter Vehicleno.');
            $("#errspan").show().delay(1000).hide(0);
            return false;
        } else if (addnewuserchecked == true) {
            if (newdrivername == "") {
                $("#errspan").css('display', 'block');
                $("#errspan").css('color', 'red');
                $("#errspan").html('Please Enter New Drivername.');
                $("#errspan").show().delay(3000).hide(0);
                return false;
            }
        } else if (addnewuserchecked == false) {
            if (drivername == "" || driverid == "") {
                $("#errspan").css('display', 'block');
                $("#errspan").css('color', 'red');
                $("#errspan").html('Please Enter Drivername.');
                $("#errspan").show().delay(3000).hide(0);
                return false;
            }
        }
        if (clubuser == false) {
            resetClubbedUsers();
        }
        var addmapping = $("#addmapping").serialize();
        jQuery.ajax({url: "drivermapping_ajax.php", type: 'POST', data: 'action=addmapping&data=' + addmapping,
            success: function (result) {
                //alert(result); 
                var obj = jQuery.parseJSON(result);
                if (obj.status === "successful") {
                    $("#errspan").css('display', 'block');
                    $("#errspan").css('color', 'green');
                    $("#errspan").html(obj.message);
                    $("#errspan").show().delay(3000).hide(0);
                    resetforminputs();
                }
            },
        });
    }

    function resetforminputs() {
        var username = $("#username").val('');
        var userid = $("#userid").val('');

        var username = $("#username2").val('');
        var userid = $("#userid2").val('');

        var username = $("#username3").val('');
        var userid = $("#userid3").val('');

        var username = $("#username4").val('');
        var userid = $("#userid4").val('');

        var vehicleno = $("#vehicleno").val('');
        var vehicleid = $("#vehicleid").val('');
        var drivername = $("#drivername").val('');
        var driverid = $("#driverid").val('');
        var newdrivername = $("#newdrivername").val('');

    }

    function resetClubbedUsers(id) {
        //Set defult flag to -1 to reset all the values
        id = id || 1;
        if (id === 1) {
            $("#userid2").val('');
            $("#userid3").val('');
            $("#userid4").val('');
            $("#username2").val('');
            $("#username3").val('');
            $("#username4").val('');
            userids = [];
        }
        else {
            var userid = $("#userid" + id).val();
            if (userid !== '') {
                userids.splice($.inArray(userid, userids), 1);
            }
            $("#userid" + id).val('');
            $("#username" + id).val('');
        }
    }

    function usernamekey(id) {
        $("#username" + id).autocomplete({
            source: "drivermapping_ajax.php?action=userauto&userids=" + userids,
            minLength: 1,
            select: function (event, ui) {
                jQuery('#userid' + id).val(ui.item.id);
                userids.push($('#userid' + id).val());
            }
        });
        var txtUsenameValue = $("#username" + id).val();
        if (jQuery.trim(txtUsenameValue).length === 0) {
            resetClubbedUsers(id);
        }
    }
</script>


<script type="text/javascript">
    var rowCount = 1;
    function addrow() {
        rowCount++;
        if (rowCount > 3) {
            $("#btnaddrow").css('display', 'none');
        } else {
            $("#btnaddrow").css('display', 'block');
        }
        var table = document.getElementById("myTable");
        var row = table.insertRow(-1);
        row.id = rowCount + "trid";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        cell1.innerHTML = "<input type=\"text\" name=\"username[]\" id=\"username" + rowCount + "\" onkeyup=\"usernamekey(" + rowCount + ");\"  > <input type=\"hidden\" name=\"userid[]\" id=\"userid" + rowCount + "\">";
        cell2.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction(" + rowCount + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a><input type='hidden' id='countrow" + rowCount + "' value='" + rowCount + "' />";
    }

    function myDeleteFunction(a) {
        var trid = '#' + a + 'trid';
        jQuery(trid).remove();
        rowCount--;
        if (rowCount > 3) {
            $("#btnaddrow").css('display', 'none');
        } else {
            $("#btnaddrow").css('display', 'block');
        }
    }
</script>