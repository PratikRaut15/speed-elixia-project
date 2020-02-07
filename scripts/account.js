var saveit;
var mandatory = [];
jQuery(document).ready(function () {
    getParentUserEdit();
    getParentGroups();
    pullVehiclesByGroup();
    jQuery("input[name*='telephone']").on("click", function (e) {
        var checkbox = $(this);
        setSMSAlertOn(this.id, e);
    });
    jQuery("input[name*='telephone']:checkbox").each(function () {
        jQuery('#' + this.id).prop('disabled', true);
    });
    /* Temp Sensor */
    jQuery("#temptelephone").prop('disabled', false);
    jQuery("#exceptionTelephone").prop('disabled', true);
    jQuery("#exceptionName").autocomplete({
        source: "autocomplete.php?action=checkpointList",
        minLength: 1,
        select: function (event, ui) {
            jQuery('#checkpointId').val(ui.item.checkpointid);
            displayCheckpointDiv(ui.item.checkpointid, ui.item.value);
            ui.item.value = '';
        }
    });
});
jQuery('#role').change(function () {
    if (this.value == 'BranchHead')
    {
        jQuery('#nation').show();
        jQuery('#state').show();
        jQuery('#district').show();
        jQuery('#city').show();
        jQuery('#group_div').show();
    } else if (this.value == 'StateHead')
    {
        jQuery('#nation').show();
        jQuery('#state').show();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else if (this.value == 'DistrictHead')
    {
        jQuery('#nation').show();
        jQuery('#state').show();
        jQuery('#district').show();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else if (this.value == 'CityHead')
    {
        jQuery('#nation').show();
        jQuery('#state').show();
        jQuery('#district').show();
        jQuery('#city').show();
        jQuery('#group_div').hide();
    } else if (this.value == 'Master')
    {
        jQuery('#nation').show();
        jQuery('#state').hide();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else if (this.value == 'sales_representative')
    {
        jQuery('#supervisor').show();
        jQuery('#salerep').hide();
        jQuery('#asm').hide();
        jQuery('#nation').hide();
        jQuery('#state').hide();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else if (this.value == 'Supervisor') {
        jQuery('#asm').show();
        jQuery('#supervisor').hide();
        jQuery('#salerep').hide();
        jQuery('#nation').hide();
        jQuery('#state').hide();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else if (this.value == 'Distributor') {
        jQuery('#salerep').show();
        jQuery('#asm').hide();
        jQuery('#supervisor').hide();
        jQuery('#nation').hide();
        jQuery('#state').hide();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').hide();
    } else
    {
        jQuery('#salerep').hide();
        jQuery('#asm').hide();
        jQuery('#supervisor').hide();
        jQuery('#nation').hide();
        jQuery('#state').hide();
        jQuery('#district').hide();
        jQuery('#city').hide();
        jQuery('#group_div').show();
    }
});
function addnewuser()
{
    var maintenance = jQuery('#is_maintenance').val();
    var customerno = jQuery('#customerno').val();
    var hierarchy = jQuery('#is_hierarchy').val();
    var role = jQuery('#role').val();
    var heirid = jQuery('#heirid').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var vehicleids = new Array();

    if (customerno == 64 || customerno == 756) {
        var selected_menu = jQuery("#event_result").val();
        if (selected_menu == "") {
            jQuery("#menus_error").show();
            jQuery("#menus_error").css('color', 'red');
            jQuery("#menus_error").html('Please select menus');
            jQuery("#menus_error").fadeOut(3000);
            return false;
        }
    }

    jQuery('.vehiclebox').each(function () {
        var str = this.id;
        var res = str.replace("to_vehicle_div_", "");
        vehicleids.push(res);
    });
    var reportIds = new Array();
    var counterReportTime = 0;
    jQuery(".reportCheck").each(function () {
        if (jQuery('#' + this.id).prop('checked') == true) {
            var str = this.id;
            var ids = str.replace('activated_', '');
            reportIds.push(ids);
        }
    });
    jQuery(".reportOn").each(function () {
        if (jQuery('#' + this.id).prop('disabled') == false) {
            if (jQuery('#' + this.id).val() == '-1') {
                counterReportTime++;
            }
        }
    });
    var getin = false;
    if (jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);
    } else if (jQuery("#nameid").val() == "")
    {
        jQuery("#errorname").show();
        jQuery("#errorname").fadeOut(3000);
    } else if (heirid == '1' || heirid)
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '2' || roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#stateid').val() == '')
        {
            jQuery("#state_error").show();
            jQuery("#state_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#districtid').val() == '')
        {
            jQuery("#district_error").show();
            jQuery("#district_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '2')
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#districtid').val() == '')
        {
            jQuery("#district_error").show();
            jQuery("#district_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '3')
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '4')
    {
        if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (role == 'Administrator' || role == 'Tracker' || role == 'Viewer' || role == 'elixir' || role == 'Master' || role == 'Custom')
    {
        getin = true;
    }

    if (getin == true && counterReportTime == 0)
    {
        if (jQuery("#email1").val() == "") {
            jQuery("#erroremail1").show();
            jQuery("#erroremail1").fadeOut(3000);
        } else if (jQuery("#email1").val() != "" && jQuery("#email1").val().match(/.+@.+\.(.+){2,}/) == null) {
            jQuery("#erroremail").show();
            jQuery("#erroremail").fadeOut(3000);
        } else if (jQuery("#password").val() == "") {
            jQuery("#errorpassword").show();
            jQuery("#errorpassword").fadeOut(3000);
        }
        /* commented because vehicle field we want as not mandatory
         else if (role == 'Custom' && vehicleids.length == 0) {
         //alert(vehicleids.length);
         jQuery("#custom").show();
         jQuery("#custom").fadeOut(3000);
         }*/
        else {
            var username = jQuery("#email1").val();
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: {
                    username: username
                },
                async: true,
                cache: false,
                success: function (cdata1) {
                    var statuscheck = cdata1;
                    if (statuscheck == "ok")
                    {
                        if (jQuery("#email1").val() != "")
                        {
                            var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
                            if (valid_email == null)
                            {
                                jQuery("#erroremail").show();
                                jQuery("#erroremail").fadeOut(3000);
                            } else if (jQuery("#phoneno").val() != '') {
                                var phone = jQuery("#phoneno").val();
                                phone = phone.replace(/[^0-9]/g, '');
                                if (phone.length != 10) {
                                    jQuery("#phoneerror").show();
                                    jQuery("#phoneerror").fadeOut(3000);
                                } else {
                                    submituserdata();
                                }
                            } else
                            {
                                submituserdata();
                            }
                        } else if (jQuery("#phoneno").val() != '') {
                            var phone = jQuery("#phoneno").val();
                            phone = phone.replace(/[^0-9]/g, '');
                            if (phone.length != 10) {
                                jQuery("#phoneerror").show();
                                jQuery("#phoneerror").fadeOut(3000);
                            } else {
                                submituserdata();
                            }
                        } else
                        {
                            submituserdata();
                        }
                    } else
                    {
                        jQuery("#errorusername").show();
                        jQuery("#errorusername").fadeOut(3000);
                    }
                }
            });
        }
    } else {
        alert('Please Fill In All Required Fields');
        return false;
    }
}
function addnewuser_hierarchy()
{
    var maintenance = jQuery('#is_maintenance').val();
    var hierarchy = jQuery('#is_hierarchy').val();
    var role = jQuery('#role').val();
    var heirid = jQuery('#heirid').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var getin = false;
    var vehicleids = new Array();
    jQuery('.vehiclebox').each(function () {
        var str = this.id;
        var res = str.replace("to_vehicle_div_", "");
        vehicleids.push(res);
    });
    if (jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);
    } else if (jQuery("#nameid").val() == "")
    {
        jQuery("#errorname").show();
        jQuery("#errorname").fadeOut(3000);
    }
    /*else if (jQuery("#chk_highuser").is(':checked') && jQuery("#higheruser").val() == "-1")
     {
     jQuery("#errorrole").show();
     jQuery("#errorrole").fadeOut(3000);
     }*/
    else
    {
        getin = true;
    }
    if (getin == true)
    {
        if (jQuery("#email1").val() == "") {
            jQuery("#erroremail1").show();
            jQuery("#erroremail1").fadeOut(3000);
        } else if (jQuery("#email1").val() != "" && jQuery("#email1").val().match(/.+@.+\.(.+){2,}/) == null)
        {
            jQuery("#erroremail").show();
            jQuery("#erroremail").fadeOut(3000);
        } else if (jQuery("#password").val() == "")
        {
            jQuery("#errorpassword").show();
            jQuery("#errorpassword").fadeOut(3000);
        } else if (role == "Custom" && vehicleids.length == 0)
        {
            jQuery("#custom").show();
            jQuery("#custom").fadeOut(3000);
        } else
        {
            var username = jQuery("#email1").val();
            jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                data: {
                    username: username
                },
                async: true,
                cache: false,
                success: function (cdata1) {
                    var statuscheck = cdata1;
                    if (statuscheck == "ok")
                    {
                        if (jQuery("#email1").val() != "")
                        {
                            var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
                            if (valid_email == null)
                            {
                                jQuery("#erroremail").show();
                                jQuery("#erroremail").fadeOut(3000);
                            } else if (jQuery("#phoneno").val() != '') {
                                var phone = jQuery("#phoneno").val();
                                phone = phone.replace(/[^0-9]/g, '');
                                if (phone.length != 10) {
                                    jQuery("#phoneerror").show();
                                    jQuery("#phoneerror").fadeOut(3000);
                                } else {
                                    submituserdata_hierarchy();
                                }
                            } else
                            {
                                submituserdata_hierarchy()();
                            }
                        } else if (jQuery("#phoneno").val() != '') {
                            var phone = jQuery("#phoneno").val();
                            phone = phone.replace(/[^0-9]/g, '');
                            if (phone.length != 10) {
                                jQuery("#phoneerror").show();
                                jQuery("#phoneerror").fadeOut(3000);
                            } else {
                                submituserdata_hierarchy();
                            }
                        } else
                        {
                            submituserdata_hierarchy();
                        }
                    } else
                    {
                        jQuery("#errorusername").show();
                        jQuery("#errorusername").fadeOut(3000);
                    }
                }
            });
        }
    }
}
function submituserdata()
{
    if (!validate_exception()) {
        return false;
    }
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var role = jQuery('#role').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var reportIds = new Array();
    jQuery(".reportCheck").each(function () {
        if (jQuery('#' + this.id).prop('checked') == true) {
            var str = this.id;
            var ids = str.replace('activated_', '');
            reportIds.push(ids);
        }
    });

    var customerno = jQuery('#customerno').val();

    var totalArray = [];
    /*
     jQuery(".menucheckbox").each(function () {
     if (jQuery('#' + this.id).prop('checked') == true){
     var menuid = jQuery('#' + this.id).val();
     var addstatus = 0;
     var editstatus = 0;
     var delstatus = 0;
     var addval=0;
     var editval=0;
     var delval=0;
     if (jQuery('#add_' + menuid).prop('checked') == true) {
     var addval = jQuery('#add_' + menuid).val();
     } else {
     viewstatus = 1;
     }
     if (jQuery('#edit_' + menuid).prop('checked') == true) {
     var editval = jQuery('#edit_' + menuid).val();
     } else {
     editstatus = 1;
     }
     if (jQuery('#delete_' + menuid).prop('checked') == true) {
     var delval = jQuery('#delete_' + menuid).val();
     } else {
     delstatus = 1;
     }
     var statuscount = viewstatus + editstatus + delstatus;


     if(statuscount!=3){
     var obj={
     "menuid" : menuid,
     "addval" : addval,
     "editval" : editval,
     "delval" : delval
     }
     }else{
     var obj={
     "menuid" : menuid,
     "addval" : 0,
     "editval" : 0,
     "delval" : 0
     }
     }
     totalArray.push(obj);

     }
     });
     */
    var jsontest = "";
    jsontest = JSON.stringify(totalArray);
    var dat = jQuery('#adduser').serialize();
    var data = dat + "&roleid=" + roleid + "&reportList=" + reportIds + "&menuconfigarr=" + jsontest;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        success: function (html)
        {
            window.location = 'users.php?id=2';
        }
    });
}
function submituserdata_hierarchy()
{
    if (!validate_exception()) {
        return false;
    }
    jQuery("input:checkbox:disabled").each(function () {
        //alert(this.id);
        jQuery('#' + this.id).prop('disabled', false);
    });




    var customerno = jQuery('#customerno').val();

    var totalArray = [];

    jQuery(".menucheckbox").each(function () {
        if (jQuery('#' + this.id).prop('checked') == true) {
            var menuid = jQuery('#' + this.id).val();
            var addstatus = 0;
            var editstatus = 0;
            var delstatus = 0;
            var addval = 0;
            var editval = 0;
            var delval = 0;
            if (jQuery('#add_' + menuid).prop('checked') == true) {
                var addval = jQuery('#add_' + menuid).val();
            } else {
                viewstatus = 1;
            }
            if (jQuery('#edit_' + menuid).prop('checked') == true) {
                var editval = jQuery('#edit_' + menuid).val();
            } else {
                editstatus = 1;
            }
            if (jQuery('#delete_' + menuid).prop('checked') == true) {
                var delval = jQuery('#delete_' + menuid).val();
            } else {
                delstatus = 1;
            }
            var statuscount = viewstatus + editstatus + delstatus;
            //alert(statuscount);
            if (statuscount != 3) {
                var obj = {
                    "menuid": menuid,
                    "addval": addval,
                    "editval": editval,
                    "delval": delval
                }
            } else {
                var obj = {
                    "menuid": menuid,
                    "addval": 0,
                    "editval": 0,
                    "delval": 0
                }
            }
            totalArray.push(obj);

        }
    });
    var jsontest = "";
    jsontest = JSON.stringify(totalArray);


    //alert("test from hierarchy");
    var role = jQuery('#role').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var dat = jQuery('#adduser').serialize();
    var data = dat + "&roleid=" + roleid + "&menuconfigarr=" + jsontest;
    //alert(data);exit;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        success: function (html)
        {
            //window.location = 'users.php?id=2';
        }
    });
}
function edituser()
{
    var maintenance = jQuery('#is_maintenance').val();
    var hierarchy = jQuery('#is_hierarchy').val();
    var role = jQuery('#role').val();
    var heirid = jQuery('#heirid').val();
    var roleid = jQuery('#' + role).attr('rel');
    var vehicleids = new Array();
    jQuery('.vehiclebox').each(function () {
        var str = this.id;
        var res = str.replace("to_vehicle_div_", "");
        vehicleids.push(res);
    });
    var getin = false;
    if (jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);
    } else if (jQuery("#nameid").val() == "")
    {
        jQuery("#errorname").show();
        jQuery("#errorname").fadeOut(3000);
    } else if (heirid == '1')
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '2' || roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#stateid').val() == '')
        {
            jQuery("#state_error").show();
            jQuery("#state_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#districtid').val() == '')
        {
            jQuery("#district_error").show();
            jQuery("#district_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '2')
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '3' || roleid == '4' || roleid == '8') && jQuery('#districtid').val() == '')
        {
            jQuery("#district_error").show();
            jQuery("#district_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '3')
    {
        if (maintenance == '1' && hierarchy == '1' && (roleid == '4' || roleid == '8') && jQuery('#cityid').val() == '')
        {
            jQuery("#city_error").show();
            jQuery("#city_error").fadeOut(3000);
        } else if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (heirid == '4')
    {
        if (maintenance == '1' && hierarchy == '1' && roleid == '8' && jQuery('#group').val() == '')
        {
            jQuery("#group_error").show();
            jQuery("#group_error").fadeOut(3000);
        } else
        {
            getin = true;
        }
    } else if (role == 'Administrator' || role == 'Tracker' || role == 'Viewer' || role == 'Master' || role == 'Custom'
            || role == 'elixir' || role == "BranchHead" || role == "CityHead" || role == "DistrictHead"
            || role == "StateHead" || role == "delivery_boy" || role == 'sales_manager' || role == 'sales_representative'
            || role == 'ASM' || role == 'Distributor' || role == 'Supervisor' || role == 'transporter' || role == 'factoryofficial' || role == 'depotofficial')
    {
        getin = true;
    }
    if (getin == true)
    {
        if (jQuery("#email1").val() != "")
        {
            var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
            if (valid_email == null) {
                jQuery("#erroremail").show();
                jQuery("#erroremail").fadeOut(3000);
            }
            /*else if (role == 'Custom' && vehicleids.length == 0) {
             jQuery("#custom").show();
             jQuery("#custom").fadeOut(3000);
             }*/
            else if (jQuery("#phoneno").val() != '') {
                var phone = jQuery("#phoneno").val();
                phone = phone.replace(/[^0-9]/g, '');
                if (phone.length != 10) {
                    jQuery("#phoneerror").show();
                    jQuery("#phoneerror").fadeOut(3000);
                } else {
                    edituserdata();
                }
            } else {
                edituserdata();
            }
        } else if (jQuery("#phoneno").val() != '') {
            var phone = jQuery("#phoneno").val();
            phone = phone.replace(/[^0-9]/g, '');
            if (phone.length != 10) {
                jQuery("#phoneerror").show();
                jQuery("#phoneerror").fadeOut(3000);
            } else {
                edituserdata();
            }
        } else
        {
            edituserdata();
        }
    }
}
function edituser_hierarchy()
{
    var maintenance = jQuery('#is_maintenance').val();
    var hierarchy = jQuery('#is_hierarchy').val();
    var role = jQuery('#role').val();
    var heirid = jQuery('#heirid').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var vehicleids = new Array();
    jQuery('.vehiclebox').each(function () {
        var str = this.id;
        var res = str.replace("to_vehicle_div_", "");
        vehicleids.push(res);
    });
    var getin = false;
    if (jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);
    } else if (jQuery("#nameid").val() == "")
    {
        jQuery("#errorname").show();
        jQuery("#errorname").fadeOut(3000);
    } else {
        getin = true;
    }
    if (getin == true)
    {
        if (jQuery("#email1").val() != "")
        {
            var valid_email = jQuery("#email1").val().match(/.+@.+\.(.+){2,}/);
            if (valid_email == null)
            {
                jQuery("#erroremail").show();
                jQuery("#erroremail").fadeOut(3000);
            }
            /*else if (role == 'Custom' && vehicleids.length == 0)
             {
             jQuery("#custom").show();
             jQuery("#custom").fadeOut(3000);
             }*/
            else if (jQuery("#phoneno").val() != '') {
                var phone = jQuery("#phoneno").val();
                phone = phone.replace(/[^0-9]/g, '');
                if (phone.length != 10) {
                    jQuery("#phoneerror").show();
                    jQuery("#phoneerror").fadeOut(3000);
                } else {
                    edituserdata_hierarchy();
                }
            } else
            {
                edituserdata_hierarchy();
            }
        } else if (jQuery("#phoneno").val() != '') {
            var phone = jQuery("#phoneno").val();
            phone = phone.replace(/[^0-9]/g, '');
            if (phone.length != 10) {
                jQuery("#phoneerror").show();
                jQuery("#phoneerror").fadeOut(3000);
            } else {
                edituserdata_hierarchy();
            }
        } else
        {
            edituserdata_hierarchy();
        }
    }
}

function edituserdata()
{
    var role = jQuery('#role').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var dat = jQuery('#edituser').serialize();
    var data = dat + "&roleid=" + roleid;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        success: function (html) {
            //window.location = 'users.php?id=2';
        }
    });
}
function edituserdata_hierarchy()
{
    var totalArray = [];
    jQuery(".menucheckbox").each(function () {
        if (jQuery('#' + this.id).prop('checked') == true) {
            var menuid = jQuery('#' + this.id).val();
            var viewstatus = 0;
            var editstatus = 0;
            var delstatus = 0;
            var addval = 0;
            var editval = 0;
            var delval = 0;
            if (jQuery('#add_' + menuid).prop('checked') == true) {
                var addval = jQuery('#add_' + menuid).val();
            } else {
                viewstatus = 1;
            }
            if (jQuery('#edit_' + menuid).prop('checked') == true) {
                var editval = jQuery('#edit_' + menuid).val();
            } else {
                editstatus = 1;
            }
            if (jQuery('#delete_' + menuid).prop('checked') == true) {
                var delval = jQuery('#delete_' + menuid).val();
            } else {
                delstatus = 1;
            }
            var statuscount = viewstatus + editstatus + delstatus;
            //alert(statuscount);
            if (statuscount != 3) {
                var obj = {
                    "menuid": menuid,
                    "addval": addval,
                    "editval": editval,
                    "delval": delval
                }
            } else {
                var obj = {
                    "menuid": menuid,
                    "addval": 0,
                    "editval": 0,
                    "delval": 0
                }
            }
            totalArray.push(obj);

        }
    });
    var jsontest = "";
    jsontest = JSON.stringify(totalArray);

    var role = jQuery('#role').val();
    var roleid = $("#role").find('option:selected').attr("rel");
    var dat = jQuery('#edituser').serialize();
    if (typeof roleid === "undefined") {
        roleid = '';
    }
    var data = dat + "&roleid=" + roleid + "&menuconfigarr=" + jsontest;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        success: function (html) {
            window.location = 'users.php?id=2';
        }
    });
}
mappedgroups();
//var h = document.getElementsByTagName('input');
//for (var i = 0; i < h.length; i++)
//{
//
//    if (h[i].type == 'hidden')
//    {
//
//        if  (h[i].id.startsWith("hid_g"))
//        {
//
//            var groupid = h[i].id;
//            groupid = groupid.substr(5);
//            var groupname = h[i].value;
//            ldgroup(groupname, groupid);
//        }
//    }
//}
function mappedgroups() {
    jQuery('.mappedgroups').each(function () {
        var groupid = jQuery(this).attr('rel');
        var groupname = jQuery(this).val();
        ldgroup(groupname, groupid);
    });
}
function ldgroup(groupname, groupid)
{
    var selected_name = groupname;
    if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehiclesByGroup(groupid);
        };
        div.className = 'recipientbox';
        div.id = 'to_group_div_' + groupid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_group_' + groupid + '" value="' + groupid + '"/>';
        jQuery('#group_list').append(div);
        jQuery(div).append(remove_image);
    }
}
function dosave() {
    if (jQuery("#oldpasswd").val() != "") {
        var oldpwd = jQuery("#oldpasswd").val();
        var newpwd = jQuery("#newpasswd").val();
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: {
                oldpwd: oldpwd,
                newpwd: newpwd
            },
            async: true,
            cache: false,
            success: function (html) {
                if (html == "newempty") {
                    jQuery("#newempty").show();
                    jQuery("#newempty").fadeOut(3000);
                } else if (html == "ok") {
                    jQuery("#perfect").show();
                    jQuery("#perfect").fadeOut(3000);
                } else {
                    jQuery("#incorrect").show();
                    jQuery("#incorrect").fadeOut(3000);
                }
            }
        });

    } else {
        jQuery("#oldempty").show();
        jQuery("#oldempty").fadeOut(3000);
    }
}
function dosaveuserdet() {
    var name = jQuery("#name").val();
    var email = jQuery("#email").val();
    var phoneno = jQuery("#phoneno").val();
    var role = jQuery("#role").val();
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: {
            name: name,
            email: email,
            phoneno: phoneno,
            role: role
        },
        async: true,
        cache: false,
        //dataType:"json",
        success: function (statuscheck) {
            if (statuscheck == "ok") {
                jQuery("#perfectinfo").show();
                jQuery("#perfectinfo").fadeOut(3000);
            } else {
                jQuery("#problem").show();
                jQuery("#problem").fadeOut(3000);
            }
        }
    });
}
function dosaveuserdet_modal() {
    var email = jQuery("#email1").val();
    var phone = jQuery("#phoneno").val()
    var params = "email=" + encodeURIComponent(jQuery("#email1").val()) +
            "&phoneno=" + encodeURIComponent(jQuery("#phoneno").val());
    params += "&alerts=true";
    // geo sms
    if (jQuery("#geosms").is(':checked')) {
        params += "&geosms=1";
    }
    // geo email
    if (jQuery("#geoemail").is(':checked')) {
        params += "&geoemail=1";
    }
    // on over speed
    if (jQuery("#ospeedsms").is(':checked')) {
        params += "&ospeedsms=1";
    }
    // on over speed email
    if (jQuery("#ospeedemail").is(':checked')) {
        params += "&ospeedemail=1";
    }
    // on power cut sms
    if (jQuery("#powercsms").is(':checked')) {
        params += "&powercsms=1";
    }
    // on power cut email
    if (jQuery("#powercemail").is(':checked')) {
        params += "&powercemail=1";
    }
    // on tamper sms
    if (jQuery("#tampersms").is(':checked')) {
        params += "&tampersms=1";
    }
    // on tamper email alert
    if (jQuery("#tamperemail").is(':checked')) {
        params += "&tamperemail=1";
    }
    if (jQuery("#chksms").is(':checked')) {
        params += "&chksms=1";
    }
    if (jQuery("#chkemail").is(':checked')) {
        params += "&chkemail=1";
    }
    if (jQuery("#acsms").is(':checked')) {
        params += "&acsms=1";
    }
    if (jQuery("#acemail").is(':checked')) {
        params += "&acemail=1";
    }
    if (jQuery("#igsms").is(':checked')) {
        params += "&igsms=1";
    }
    if (jQuery("#igemail").is(':checked')) {
        params += "&igemail=1";
    }
    if (jQuery("#tempsms").is(':checked')) {
        params += "&tempsms=1";
    }
    if (jQuery("#tempemail").is(':checked')) {
        params += "&tempemail=1";
    }
    if (jQuery("#dailyemail").is(':checked')) {
        params += "&dailyemail=1";
    }
    if (jQuery("#dailyemail_csv").is(':checked')) {
        params += "&dailyemail_csv=1";
    }
    if (jQuery("#safcsms").is(':checked')) {
        params += "&safcsms=1";
    }
    if (jQuery("#safcemail").is(':checked')) {
        params += "&safcemail=1";
    }
    if (jQuery("#saftsms").is(':checked')) {
        params += "&saftsms=1";
    }
    if (jQuery("#saftemail").is(':checked')) {
        params += "&saftemail=1";
    }
    if (jQuery("#harsh_break_sms").is(':checked')) {
        params += "&harsh_break_sms=1";
    }
    if (jQuery("#harsh_break_mail").is(':checked')) {
        params += "&harsh_break_mail=1";
    }
    if (jQuery("#high_acce_sms").is(':checked')) {
        params += "&high_acce_sms=1";
    }
    if (jQuery("#high_acce_mail").is(':checked')) {
        params += "&high_acce_mail=1";
    }
    if (jQuery("#sharp_turn_sms").is(':checked')) {
        params += "&sharp_turn_sms=1";
    }
    if (jQuery("#sharp_turn_mail").is(':checked')) {
        params += "&sharp_turn_mail=1";
    }
    if (jQuery("#towing_sms").is(':checked')) {
        params += "&towing_sms=1";
    }
    if (jQuery("#doorsms").is(':checked')) {
        params += "&doorsms=1";
    }
    if (jQuery("#dooremail").is(':checked')) {
        params += "&dooremail=1";
    }
    params += "&safcmin=" + jQuery("#safcmin").val();
    params += "&saftmin=" + jQuery("#saftmin").val();
    params += "&STime=" + jQuery("#STime").val() +
            "&ETime=" + jQuery("#ETime").val();
    if (email != '') {
        var atpos = email.indexOf("@");
        var dotpos = email.lastIndexOf(".");
        if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length)
        {
            jQuery("#emailerror").show();
            jQuery("#emailerror").fadeOut(3000);
        } else if (phone != '') {
            phone = phone.replace(/[^0-9]/g, '');
            if (phone.length != 10) {
                jQuery("#smserror").show();
                jQuery("#smserror").fadeOut(3000);
            } else {
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php?",
                    data: params,
                    async: true,
                    cache: false,
                    success: function (cdata1) {
                        var statuscheck = cdata1;
                        if (statuscheck == "ok") {
                            jQuery("#perfectinfo").show();
                            jQuery("#perfectinfo").fadeOut(3000);
                            jQuery("#perfectinfo").show();
                            jQuery("#perfectinfo").fadeOut(3000);
                            jQuery('#myModal1').modal('hide');
                            updategroupid();
                        } else {
                            jQuery("#problem").show();
                            jQuery("#problem").fadeOut(3000);
                        }
                    }
                });
            }
        } else {
            jQuery.ajax({
                type: "POST",
                url: "../../modules/user/route_ajax.php?",
                data: params,
                async: true,
                cache: false,
                success: function (cdata1) {
                    var statuscheck = cdata1;
                    if (statuscheck == "ok") {
                        jQuery("#perfectinfo").show();
                        jQuery("#perfectinfo").fadeOut(3000);
                        jQuery("#perfectinfo").show();
                        jQuery("#perfectinfo").fadeOut(3000);
                        jQuery('#myModal1').modal('hide');
                        updategroupid();
                    } else {
                        jQuery("#problem").show();
                        jQuery("#problem").fadeOut(3000);
                    }
                }
            });
        }
    } else if (phone != '') {
        phone = phone.replace(/[^0-9]/g, '');
        if (phone.length != 10) {
            jQuery("#smserror").show();
            jQuery("#smserror").fadeOut(3000);
        } else {
            jQuery.ajax({
                type: "POST",
                url: "../../modules/user/route_ajax.php?",
                data: params,
                async: true,
                cache: false,
                success: function (cdata1) {
                    var statuscheck = cdata1;
                    if (statuscheck == "ok") {
                        jQuery("#perfectinfo").show();
                        jQuery("#perfectinfo").fadeOut(3000);
                        jQuery("#perfectinfo").show();
                        jQuery("#perfectinfo").fadeOut(3000);
                        jQuery('#myModal1').modal('hide');
                        updategroupid();
                    } else {
                        jQuery("#problem").show();
                        jQuery("#problem").fadeOut(3000);
                    }
                }
            });
        }
    } else {
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php?",
            data: params,
            async: true,
            cache: false,
            success: function (cdata1) {
                var statuscheck = cdata1;
                if (statuscheck == "ok") {
                    jQuery("#perfectinfo").show();
                    jQuery("#perfectinfo").fadeOut(3000);
                    jQuery("#perfectinfo").show();
                    jQuery("#perfectinfo").fadeOut(3000);
                    jQuery('#myModal1').modal('hide');
                    updategroupid();
                } else {
                    jQuery("#problem").show();
                    jQuery("#problem").fadeOut(3000);
                }
            }
        });
    }
}
function dosave_modal() {
    if (jQuery("#newpasswd").val() != "") {
        if (jQuery("#confirm_newpasswd").val() != "") {
            if (jQuery("#confirm_newpasswd").val() == jQuery("#newpasswd").val()) {
                var params = "newpwd=" + encodeURIComponent(jQuery("#newpasswd").val());
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php?",
                    data: params,
                    async: true,
                    cache: false,
                    success: function (statuscheck) {
                        if (statuscheck == "newempty") {
                            jQuery("newempty").show();
                            jQuery("#newempty").fadeOut(3000);
                        } else if (statuscheck == "ok") {
                            jQuery("#perfect").show();
                            jQuery("#perfect").fadeOut(3000);
                            jQuery(".window").hide();
                            jQuery('#mask1').fadeOut("slow");
                            jQuery('#mask').fadeIn(1000);
                            jQuery('#mask').fadeTo("slow", 0.8);
                            jQuery('#mask').show();
                            jQuery('.window1').show();
                        } else {
                            jQuery("incorrect").show();
                            jQuery("#incorrect").fadeOut(3000);
                        }
                    }
                });
            } else {
                jQuery("#incorrect").show();
                jQuery("#incorrect").fadeOut(3000);
            }
        } else {
            jQuery("#confirmempty").show();
            jQuery("#confirmempty").fadeOut(3000);
        }
    } else {
        jQuery("#newempty").show();
        jQuery("#newempty").fadeOut(3000);
    }
}
function dosave_modalnew() {
    if (jQuery("#newpasswd").val() != "") {
        if (jQuery("#confirm_newpasswd").val() != "") {
            if (jQuery("#confirm_newpasswd").val() == jQuery("#newpasswd").val()) {
                var params = "newpwd=" + encodeURIComponent(jQuery("#newpasswd").val());
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php?",
                    data: params,
                    async: true,
                    cache: false,
                    success: function (statuscheck) {
                        if (statuscheck == "newempty") {
                            jQuery("newempty").show();
                            jQuery("#newempty").fadeOut(3000);
                        } else if (statuscheck == "ok") {
                            jQuery('#myModal').modal('hide');
                            jQuery('#myModal1').modal('show');
                        } else {
                            jQuery("incorrect").show();
                            jQuery("#incorrect").fadeOut(3000);
                        }
                    }
                });
            } else {
                jQuery("#incorrect").show();
                jQuery("#incorrect").fadeOut(3000);
            }
        } else {
            jQuery("#confirmempty").show();
            jQuery("#confirmempty").fadeOut(3000);
        }
    } else {
        jQuery("#newempty").show();
        jQuery("#newempty").fadeOut(3000);
    }
}
function showmodal() {
    jQuery('#myModal1').modal('show');
}
function addgrouptouser() {
    var groupid = jQuery('#group').val();
    if (jQuery('#to_group_div_0').val() == null) {
        if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null) {
            if (groupid == 0)
                removeallgroup();
            var selected_name = jQuery('#group option[value=' + groupid + ']').text();
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeVehiclesByGroup(groupid);
            };
            div.className = 'recipientbox';
            div.id = 'to_group_div_' + groupid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_group_' + groupid + '" value="' + groupid + '"/>';
            jQuery('#group_list').append(div);
            jQuery(div).append(remove_image);
        }
    } else {
        if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null) {
            removegroup(0);
            var selected_name = jQuery('#group option[value=' + groupid + ']').text();
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function () {
                removeVehiclesByGroup(groupid);
            };
            div.className = 'recipientbox';
            div.id = 'to_group_div_' + groupid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_group_' + groupid + '" value="' + groupid + '"/>';
            jQuery('#group_list').append(div);
            jQuery(div).append(remove_image);
        }
    }
    jQuery('group').selectedIndex = 0;

    if (jQuery('#role').val() == "Custom" || $("#customerno").val() == '64' || $("#customerno").val() == '756') {
        pullVehiclesByGroup();
    }
}
function addedgroup() {
    var groupid = 0;
    var selected_name = jQuery('#group option[value=' + groupid + ']').text();
    if (groupid > -1 && jQuery('#to_group_div_' + groupid) == null) {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removegroup(groupid);
        };
        div.className = 'recipientbox';
        div.id = 'to_group_div_' + groupid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_group_' + groupid + '" value="' + groupid + '"/>';
        jQuery('#group_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#group').selectedIndex = 0;
}
function removegroup(group_no) {
    if (group_no > -1 && jQuery('#to_group_div_' + group_no).val() != null) {
        jQuery('#to_group_div_' + group_no).remove();
    }
}
function removeallgroup() {
    //var select_box = jQuery('#group');
    jQuery("#group option").each(function (index, element) {
        var groupid = jQuery(this).val();
        removegroup(groupid);
    });
}
function getstate()
{
    var nation_id = jQuery("#nationid").val();
    if (jQuery("#nationid").val() == "")
    {
        jQuery("#nation_error").show();
        jQuery("#nation_error").fadeOut(3000);
    } else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                nation_id: nation_id
            },
            dataType: 'html',
            success: function (html) {
                jQuery("#stateid").empty();
                jQuery("#stateid").append(html);
            }
        });
        return false;
    }
}
function getdistrict()
{
    var state_id = jQuery("#stateid").val();
    if (jQuery("#stateid").val() == "")
    {
        jQuery("#state_error").show();
        jQuery("#state_error").fadeOut(3000);
    } else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                state_id: state_id
            },
            dataType: 'html',
            success: function (html) {
                jQuery("#districtid").empty();
                jQuery("#districtid").append(html);
            }
        });
        return false;
    }
}
function getcity()
{
    var district_id = jQuery("#districtid").val();
    if (jQuery("#districtid").val() == "")
    {
        jQuery("#district_error").show();
        jQuery("#district_error").fadeOut(3000);
    } else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                district_id: district_id
            },
            dataType: 'html',
            success: function (html) {
                jQuery("#cityid").empty();
                jQuery("#cityid").append(html);
            }
        });
        return false;
    }
}
function getbranch()
{
    var city_id = jQuery("#cityid").val();
    if (jQuery("#cityid").val() == "")
    {
        jQuery("#city_error").show();
        jQuery("#city_error").fadeOut(3000);
    } else
    {
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                city_id: city_id
            },
            dataType: 'html',
            success: function (html) {
                jQuery("#group").empty();
                jQuery("#group").append(html);
            }
        });
        return false;
    }
}
function getParentUser()
{
    jQuery('#group_div').show();
    var roleid = $("#role").find('option:selected').attr("rel");
    jQuery.ajax({
        url: "../account/route_ajax.php",
        type: 'POST',
        cache: false,
        data: {
            roleid: roleid
        },
        dataType: 'html',
        success: function (html) {
            if (html != '') {
                jQuery('#ParentRole').show();
                jQuery('#group_div').show();
                jQuery("#parentuser").empty();
                jQuery("#parentuser").append(html);
            } else {
                jQuery('#group_div').show();
                jQuery('#ParentRole').hide();
                jQuery("#parentuser").empty();
            }
        }
    });
    return false;
}
function getParentUserEdit()
{
    //alert('Test');group_div
    //jQuery('#ParentRole').show();
    jQuery('#group_div').show();
    var roleid = $("#role").find('option:selected').attr("rel");



    var parentid = jQuery("#heirarchy_id").val();
    //alert(roleid);
    jQuery.ajax({
        url: "../account/route_ajax.php",
        type: 'POST',
        cache: false,
        data: {
            roleid: roleid, parentid: parentid
        },
        dataType: 'html',
        success: function (html) {
            if (html != '') {
                jQuery('#ParentRole').show();
                //jQuery('#group_div').show();
                jQuery("#parentuser").empty();
                jQuery("#parentuser").append(html);
                //check for higher user is selected
                var selected_parentid = jQuery("#parentuser").find('option:selected').val();
                if (selected_parentid == '-1') {
                    jQuery("#chk_highuser").attr('checked', true);
                    EditHigherUser();
                }
            }
        }
    });
    return false;
}
function getParentGroups()
{
    //alert("test");
    var parentuser = jQuery("#parentuser").val();
    if (parentuser == '-1') {
        parentuser = jQuery("#higheruser").val();
    }
    jQuery.ajax({
        url: "../account/route_ajax.php",
        type: 'POST',
        cache: false,
        data: {
            groupparentid: parentuser
        },
        dataType: 'html',
        success: function (html) {
            if (html != '') {
                jQuery('#group_div').show();
                jQuery("#group").empty();
                jQuery("#group").append(html);
            }
        }
    });
    return false;
}
function getHigherUser() {
    var role = jQuery("#role").find('option:selected').attr("rel");
    if (jQuery("#chk_highuser").is(':checked')) {
        jQuery("#parentuser").attr('disabled', true);
        jQuery.ajax({
            url: "../account/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                role: role,
                work: "higherRoles"
            },
            dataType: 'html',
            success: function (html) {
                if (html != '') {
                    jQuery('#div_higheruser').show();
                    jQuery("#higheruser").empty();
                    jQuery("#higheruser").append(html);
                }
            }
        });
    } else {
        jQuery("#div_higheruser").hide();
        jQuery("#parentuser").attr('disabled', false);
    }
}
function EditHigherUser() {
    var role = jQuery("#role").find('option:selected').attr("rel");
    var parentid = jQuery("#heirarchy_id").val();
    if (jQuery("#chk_highuser").is(':checked')) {
        jQuery("#div_higheruser").show();
        jQuery("#parentuser").attr('disabled', true);
        jQuery.ajax({
            url: "../account/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                role: role,
                work: "higherRoles",
                parentid: parentid
            },
            dataType: 'html',
            success: function (html) {
                if (html != '') {
                    jQuery('#div_higheruser').show();
                    jQuery("#higheruser").empty();
                    jQuery("#higheruser").append(html);
                }
            }
        });
    } else {
        jQuery("#div_higheruser").hide();
        jQuery("#parentuser").attr('disabled', false);
    }
}
function removeVehiclesByGroup(group_no) {
    //alert('sdfdaf');
    if (group_no > -1 && jQuery('#to_group_div_' + group_no).val() != null) {
        jQuery('#to_group_div_' + group_no).remove();
    }
    jQuery.ajax({
        url: "../account/route_ajax.php",
        type: 'POST',
        cache: false,
        data: {
            group: group_no,
            work: "removeVehiclesByGroup"
        },
        dataType: 'html',
        success: function (data) {
            var cdata1 = jQuery.parseJSON(data);
            //var results = cdata1.result;
            jQuery.each(cdata1, function (i, device) {
                //alert(device.vehicleid);
                removeVehicleByGroup(device.vehicleid);
            });
        }
    });
    var groupids = new Array();
    jQuery('.recipientbox').each(function () {
        var str = this.id;
        var res = str.replace("to_group_div_", "");
        groupids.push(res);
    });
    //alert(groupids.length);
    if (groupids.length === 0) {
        jQuery("#group").val(jQuery("#group").data("default-value"));
        jQuery("#vehicle_list").html();
        jQuery('#vehicleids').html('');
        jQuery('#vehicle_for_user').hide();
    }
    pullVehiclesByGroup();
}
function pullVehiclesByGroup() {
    var role = jQuery('#role').val();
    if (role == 'Custom' || role == 'Head Office' || role == 'Custom' || role == 'Head Office' || role == 'Zone Manager' || role == 'Regional Manager' || role == 'Branch Manager') {
        //  jQuery('#vehicle_for_user').show();
    } else {
        jQuery('#vehicle_for_user').hide();
    }
    jQuery('#vehicleids').html();
    var groupids = new Array();
    var emptyGrparr = new Array();
    jQuery('.recipientbox').each(function () {
        //alert(this.id);
        var str = this.id;
        var res = str.replace("to_group_div_", "");
        if (res === '0') {
            emptyGrparr.push(res);
        } else {
            groupids.push(res);
        }
    });
    //alert(groupids.length)
    if (groupids.length > 0) {
        jQuery.ajax({
            url: "../account/route_ajax.php",
            type: 'POST',
            cache: false,
            data: {
                group: groupids,
                work: "pullVehiclesByGroup"
            },
            dataType: 'html',
            success: function (result) {
                if (result != '') {
                    jQuery('#vehicle_for_user').show();
                    jQuery('#vehicleids').html(result);
                } else {

                    jQuery('#vehicle_for_user').hide();
                }
            }
        });
    } else if (emptyGrparr.length > 0) {
        jQuery('#vehicle_for_user').hide();
        //   jQuery('#vehicleids').html("<option>Select Vehicles</option><option value='0'>All Vehicles</option>");
    }
}
function addVehicleByGroup() {
    var vehicleid = jQuery('#vehicleids').val();
    /* if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == 0) {
     var selected_name = jQuery('#vehicleids option:selected').text();
     var div = document.createElement('div');
     var remove_image = document.createElement('img');
     remove_image.src = "../../images/boxdelete.png";
     remove_image.className = 'clickimage';
     remove_image.onclick = function () {
     removeVehicleByGroup(vehicleid);
     };
     div.className = 'vehiclebox';
     div.id = 'to_vehicle_div_' + vehicleid;
     div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
     jQuery('#vehicle_list').append(div);
     jQuery(div).append(remove_image);
     }else
     */
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery('#vehicleids option:selected').text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicleByGroup(vehicleid);
        };
        div.className = 'vehiclebox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery('#vehicle_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid').val(0);
}
function removeVehicleByGroup(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}
function addallvehicleByGroup() {
    jQuery("#vehicleids option").each(function (index, element) {
        jQuery("#vehicleids").val(jQuery(element).val());
        addVehicleByGroup();
    });
}
function removeAllVehicleByGroup() {
    jQuery("#vehiclebox_list").html();
}
/* Telephonic Alert */
function setSMSAlertOn(id, e) {
    var oldid = id;
    var new_text = id.replace("telephone", "sms");
    if (jQuery("#" + id).prop("checked") == true) {
        //alert("Checkbox is checked.");
        jQuery('#' + new_text).prop('checked', true);
        jQuery('#' + new_text).prop('disabled', true);
    } else if (jQuery("#" + id).prop("checked") == false) {
        //alert("Checkbox is unchecked.");
        jQuery('#' + new_text).prop('checked', false);
        jQuery('#' + new_text).prop('disabled', false);
    }
}
function setSMSAlertOnException(id) {
    var oldid = id;
    var new_text = id.replace("Telephone", "Sms");
    if (jQuery("#" + id).prop("checked") == true) {
        //alert("Checkbox is checked.");
        jQuery('#' + new_text).prop('checked', true);
        jQuery('#' + new_text).prop('disabled', true);
    } else if (jQuery("#" + id).prop("checked") == false) {
        //alert("Checkbox is unchecked.");
        jQuery('#' + new_text).prop('checked', false);
        jQuery('#' + new_text).prop('disabled', false);
    }
}
function enableReportTime(reportId) {
    if (jQuery('#activated_' + reportId).prop('checked') == true) {
        jQuery('#reportTime_' + reportId).prop('disabled', false);
        if (reportId == 5) {
            jQuery('#temprepinterval').prop('disabled', false);
        }
        else if(reportId == 19){
             jQuery('#vehrepinterval').prop('disabled', false);
        }
    } else if (jQuery('#activated_' + reportId).prop('checked') == false) {
        jQuery('#reportTime_' + reportId).prop('disabled', true);
        if (reportId == 5) {
            jQuery('#temprepinterval').prop('disabled', true);
            jQuery('#temprepinterval').val(0);
        }
        else if(reportId == 19){
             jQuery('#vehrepinterval').prop('disabled', true);
            jQuery('#vehrepinterval').val(0);
        }
    }
}
function saveChkException() {
    var exceptionIds = new Array();
    jQuery(".chkexception").each(function () {
        if (jQuery("#" + this.id).prop("checked") == true) {
            var val = jQuery("#" + this.id).val();
            exceptionIds.push(val);
        }
    });
    if (exceptionIds.length > 0) {
        jQuery("#chkExAlertMapping").val(exceptionIds);
        jQuery('#chkptExModal').modal('toggle');
    } else {
        alert("Please Select Exception");
    }
}
