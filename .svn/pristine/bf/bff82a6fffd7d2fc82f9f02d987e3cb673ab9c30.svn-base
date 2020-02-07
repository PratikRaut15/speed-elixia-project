var text = '<tr class="replica">\n\
<td><input type="text" class="input-mini" data-date="#stTime#" name="pop[stTime][]" value="#stTime#" /></td>\n\
<td><input type="text" class="input-mini" data-date="#edTime#" name="pop[edTime][]" value="#edTime#"  /></td>\n\
<td><input  type="text" size="20" value="#vehno#" readonly>\n\
<input type="hidden" name="pop[vehlist][]" size="20" value="#vehid#" />\n\
<td><button type="button" class="btn btn-primary" onclick="remove_tr(this);">Remove</button></td></tr>';
jQuery(function () {
    if (typeof dateIdArray != "undefined" && dateIdArray != null && dateIdArray != undefined) {
        jQuery.each(dateIdArray, function (i) {
            jQuery('#' + dateIdArray[i]).timepicker({
                minuteStep: 1,
                showMeridian: false,
                defaultTime: jQuery('#' + dateIdArray[i]).data('date')
            });
            jQuery(document).click(function () {
                jQuery('#' + dateIdArray[i]).timepicker('hide');
            });
        });
    }
    jQuery('.input-mini').click(function () {
        var inputId = this.id;
        jQuery.each(dateIdArray, function (i) {
            if (dateIdArray[i] != inputId) {
                jQuery('#' + dateIdArray[i]).timepicker('hide');
            }
        });
    });
    jQuery('.specRadio').click(function () {
        jQuery('#messageSpan').html('');
    });
    jQuery('.allRadio').click(function () {
        jQuery(this).nextAll('span:first').html(allText);
        var name = jQuery(this).attr('name');
        var stName = name.replace('veh', 'stTime');
        var endName = name.replace('veh', 'edTime');
        jQuery("input[name='" + stName + "']").prop("readonly", false);
        jQuery("input[name='" + endName + "']").prop("readonly", false);
    });
    $("#vehiclenoA").autoSuggest({
        ajaxFilePath: "../reports/autocomplete.php",
        ajaxParams: "dummydata=alertPop",
        autoFill: false,
        iwidth: "auto",
        opacity: "0.9",
        ilimit: "10",
        idHolder: "id-holder",
        match: "contains"
    });
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

    jQuery("input[name*='telephone']:checkbox:checked").each(function () {
        var smsid = this.id.replace("telephone", "sms");
        jQuery('#' + smsid).prop('disabled', true);
    });

    jQuery('input[name=isAdvTempConfRange]').change(function () {
        if (this.value == '1') {
            jQuery('#AdvTempConfRange').show();
        } else if (this.value == '0') {
            jQuery('#AdvTempConfRange').hide();
        }
    });

    $('.closetempRangeModal').click(function () {
        window.location.reload();
    });
});

window.onload = pageLoadScript;

function pageLoadScript() {
    if (jQuery('input[name=isAdvTempConfRange]:checked').val() == 1) {
        jQuery('#AdvTempConfRange').show();
    } else if (jQuery('input[name=isAdvTempConfRange]').val() == 0) {
        jQuery('#AdvTempConfRange').hide();
    }
}

function fill_pop(Value, strparam) {
    jQuery('#vehiclenoA').val(strparam);
    jQuery('#vehicleidA').val(Value);
    jQuery('#displayA').hide();
    jQuery('.ajax_response_2').html('');
}
function dosave() {
    if (jQuery("#name").val() != "") {
        if (jQuery("#email").val() != "") {
            var valid_email = jQuery("#email").val().match(/.+@.+\.(.+){2,}/);
            if (valid_email == null) {
                jQuery("#erroremail").show();
                jQuery("#erroremail").fadeOut(3000);
            } else {
                jQuery("input:checkbox:disabled").each(function () {
                    jQuery('#' + this.id).prop('disabled', false);
                });
                jQuery("#edituser").submit();
            }
        } else {
            jQuery("input:checkbox:disabled").each(function () {
                jQuery('#' + this.id).prop('disabled', false);
            });
            jQuery("#edituser").submit();
        }
    }
}
function dosave_customize() {
    var params = "custom=true";
    var customname1 = jQuery("#customname_1").val();
    var customname2 = jQuery("#customname_2").val();
    var customname3 = jQuery("#customname_3").val();
    var customname4 = jQuery("#customname_4").val();
    var customname5 = jQuery("#customname_5").val();
    var customname6 = jQuery("#customname_6").val();
    var customname7 = jQuery("#customname_7").val();
    var customname8 = jQuery("#customname_8").val();
    var customname9 = jQuery("#customname_9").val();
    var customname10 = jQuery("#customname_10").val();
    var customname11 = jQuery("#customname_11").val();
    var customname12 = jQuery("#customname_12").val();
    var customname13 = jQuery("#customname_13").val();
    var customname14 = jQuery("#customname_14").val();
    var customname15 = jQuery("#customname_15").val();
    var customname16 = jQuery("#customname_16").val();
    var customname17 = jQuery("#customname_17").val();
    var customname18 = jQuery("#customname_18").val();
    var customname19 = jQuery("#customname_19").val();
    var customname20 = jQuery("#customname_20").val();
    var customname21 = jQuery("#customname_21").val();
    var customname22 = jQuery("#customname_22").val();
    var customname23 = jQuery("#customname_23").val();
    var customname24 = jQuery("#customname_24").val();
    var customname25 = jQuery("#customname_25").val();
    // Use Custom
    if (jQuery("#usecustom_1").prop("checked"))
        params += "&usecustom_1=1";
    else
        params += "&usecustom_1=0";
    params += "&customname_1=" + customname1;
    if (jQuery("#usecustom_2").prop("checked"))
        params += "&usecustom_2=1";
    else
        params += "&usecustom_2=0";
    params += "&customname_2=" + customname2;
    if (jQuery("#usecustom_3").prop("checked"))
        params += "&usecustom_3=1";
    else
        params += "&usecustom_3=0";
    params += "&customname_3=" + customname3;
    if (jQuery("#usecustom_4").prop("checked"))
        params += "&usecustom_4=1";
    else
        params += "&usecustom_4=0";
    params += "&customname_4=" + customname4;
    if (jQuery("#usecustom_5").prop("checked"))
        params += "&usecustom_5=1";
    else
        params += "&usecustom_5=0";
    params += "&customname_5=" + customname5;
    if (jQuery("#usecustom_6").prop("checked"))
        params += "&usecustom_6=1";
    else
        params += "&usecustom_6=0";
    params += "&customname_6=" + customname6;
    if (jQuery("#usecustom_7").prop("checked"))
        params += "&usecustom_7=1";
    else
        params += "&usecustom_7=0";
    params += "&customname_7=" + customname7;
    if (jQuery("#usecustom_8").prop("checked"))
        params += "&usecustom_8=1";
    else
        params += "&usecustom_8=0";
    params += "&customname_8=" + customname8;
    if (jQuery("#usecustom_9").prop("checked"))
        params += "&usecustom_9=1";
    else
        params += "&usecustom_9=0";
    params += "&customname_9=" + customname9;
    if (jQuery("#usecustom_10").prop("checked"))
        params += "&usecustom_10=1";
    else
        params += "&usecustom_10=0";
    params += "&customname_10=" + customname10;
    if (jQuery("#usecustom_11").prop("checked"))
        params += "&usecustom_11=1";
    else
        params += "&usecustom_11=0";
    params += "&customname_11=" + customname11;
    if (jQuery("#usecustom_12").prop("checked"))
        params += "&usecustom_12=1";
    else
        params += "&usecustom_12=0";
    params += "&customname_12=" + customname12;
    if (jQuery("#usecustom_13").prop("checked"))
        params += "&usecustom_13=1";
    else
        params += "&usecustom_13=0";
    params += "&customname_13=" + customname13;
    if (jQuery("#usecustom_14").prop("checked"))
        params += "&usecustom_14=1";
    else
        params += "&usecustom_14=0";
    params += "&customname_14=" + customname14;
    if (jQuery("#usecustom_15").prop("checked"))
        params += "&usecustom_15=1";
    else
        params += "&usecustom_15=0";
    params += "&customname_15=" + customname15;
    if (jQuery("#usecustom_16").prop("checked"))
        params += "&usecustom_16=1";
    else
        params += "&usecustom_16=0";
    params += "&customname_16=" + customname16;
    if (jQuery("#usecustom_17").prop("checked"))
        params += "&usecustom_17=1";
    else
        params += "&usecustom_17=0";
    params += "&customname_17=" + customname17;
    if (jQuery("#usecustom_18").prop("checked"))
        params += "&usecustom_18=1";
    else
        params += "&usecustom_18=0";
    params += "&customname_18=" + customname18;
    if (jQuery("#usecustom_19").prop("checked"))
        params += "&usecustom_19=1";
    else
        params += "&usecustom_19=0";
    params += "&customname_19=" + customname19;
    if (jQuery("#usecustom_20").prop("checked"))
        params += "&usecustom_20=1";
    else
        params += "&usecustom_20=0";
    params += "&customname_20=" + customname20;
    if (jQuery("#usecustom_21").prop("checked"))
        params += "&usecustom_21=1";
    else
        params += "&usecustom_21=0";
    params += "&customname_21=" + customname21;

    if (jQuery("#usecustom_22").prop("checked"))
        params += "&usecustom_22=1";
    else
        params += "&usecustom_22=0";
    params += "&customname_22=" + customname22;
    if (jQuery("#usecustom_23").prop("checked"))
        params += "&usecustom_23=1";
    else
        params += "&usecustom_23=0";
    params += "&customname_23=" + customname23;
    if (jQuery("#usecustom_24").prop("checked"))
        params += "&usecustom_24=1";
    else
        params += "&usecustom_24=0";
    params += "&customname_24=" + customname24;
    if (jQuery("#usecustom_25").prop("checked"))
        params += "&usecustom_25=1";
    else
        params += "&usecustom_25=0";
    params += "&customname_25=" + customname25;

    if (jQuery("#EModify").val() == "Modify") {
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: params,
            cache: false,
            success: function (html) {
                jQuery("#saved").show();
                jQuery("#saved").fadeOut(3000);
            }
        });

    } else {
        jQuery("#error").show();
        jQuery("#error").fadeOut(3000);
    }

}
function dosave_alerts(uid) {
    var params = "alerts=true";
    // geo sms
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    if (jQuery("#geosms").prop("checked"))
        params += "&geosms=1";
    // geo email
    if (jQuery("#geoemail").prop("checked"))
        params += "&geoemail=1";
    // geo email
    if (jQuery("#geotelephone").prop("checked"))
        params += "&geotelephone=1";
    // on over speed
    if (jQuery("#ospeedsms").prop("checked"))
        params += "&ospeedsms=1";
    // on over speed email
    if (jQuery("#ospeedemail").prop("checked"))
        params += "&ospeedemail=1";
    // on power cut sms
    if (jQuery("#powercsms").prop("checked"))
        params += "&powercsms=1";
    // on power cut email
    if (jQuery("#powercemail").prop("checked"))
        params += "&powercemail=1";
    // on tamper sms
    if (jQuery("#tampersms").prop("checked"))
        params += "&tampersms=1";
    // on tamper email alert
    if (jQuery("#tamperemail").prop("checked"))
        params += "&tamperemail=1";
    // on chk sms
    if (jQuery("#chksms").prop("checked"))
        params += "&chksms=1";
    // on chk email
    if (jQuery("#chkemail").prop("checked"))
        params += "&chkemail=1";
    // on chk email
    if (jQuery("#chktelephone").prop("checked"))
        params += "&chktelephone=1";
    // on ac sms
    if (jQuery("#acsms").prop("checked"))
        params += "&acsms=1";
    // on ac email
    if (jQuery("#acemail").prop("checked"))
        params += "&acemail=1";
    // on ignition sms
    if (jQuery("#igsms").prop("checked"))
        params += "&igsms=1";
    // on ignition email
    if (jQuery("#igemail").prop("checked"))
        params += "&igemail=1";
    // on temp sms
    if (jQuery("#tempsms").prop("checked"))
        params += "&tempsms=1";
    // on temp email
    if (jQuery("#tempemail").prop("checked"))
        params += "&tempemail=1";
    if (jQuery("#EModify").val() == "Modify") {
        params += "&uid=" + uid;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: params,
            cache: false,
            success: function (html) {
                jQuery("#saved").show();
                jQuery("#saved").fadeOut(3000);
                location.reload();
            }
        });
    } else {
        jQuery("#error").show();
        jQuery("#error").fadeOut(3000);
    }
}
/*dt: 4th oct 14, ak added*/
function dosave_advanced_alerts() {
    var params = "advanced_alerts=true";
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var data = jQuery("#advancedalerts").serialize();
    if (data != '') {
        params += "&" + data;
    }
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route_ajax.php",
        data: params,
        cache: false,
        success: function (html) {
            jQuery("#advance_saved").html(html);
            jQuery("#advance_saved").show();
            jQuery("#advance_saved").fadeOut(3000);
            location.reload();
        }
    });
}
function do_save_timae_alerts_ajax() {
    var params = "time_alerts=true";
    if ($("acisms").checked == true) {
        params += "&acisms=1";
    }
    if ($("aciemail").checked == true) {
        params += "&aciemail=1";
    }
    if (jQuery("select#aciselect").val() != "") {
        params += "&aciselect=" + jQuery("select#aciselect").val();
    }
    new Ajax.Request('../../modules/user/route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var statuscheck = transport.responseText;
            if (statuscheck == "ok") {
                jQuery("#perfectinfo").show();
                jQuery("#perfectinfo").fadeOut(3000);
            } else {
                jQuery("#problem").show();
                jQuery("#problem").fadeOut(3000);
                location.reload();
            }
        },
        onComplete: function () {
            jQuery("#saved_t").show();
            jQuery("#saved_t").fadeOut(3000);
        }
    });
}
function Save_Cron_Emails(uid) {
    var params = "email_alerts=true";
    if (jQuery("#dailyemail").prop("checked"))
        params += "&dailyemail=1";
    if (jQuery("#dailyemail_csv").prop("checked"))
        params += "&dailyemail_csv=1";
    var dataString = params;
    dataString += "&uid=" + uid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route_ajax.php",
        data: dataString,
        cache: false,
        success: function (html) {
            jQuery("#saved_t").show();
            jQuery("#saved_t").fadeOut(3000);
            location.reload();
        }
    });
}
function Save_Veh_movement_status(uid) {
    var status = 0;
    if ($('#vehmovalert').is(":checked"))
    {
        status = 1;
    }
    var params = "vehicle_alerts_status=true";
    params += "&vmastatus=" + status;
    var dataString = params;
    dataString += "&uid=" + uid;
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route_ajax.php",
        data: dataString,
        cache: false,
        success: function (html) {
            jQuery("#saved_vma").show();
            jQuery("#saved_vma").fadeOut(3000);
            location.reload();
        }
    });
}


function Save_Alert_Timebased(uid) {
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route_ajax.php",
        data: {alert_timebased: true, STime: jQuery('#STime').val(), ETime: jQuery('#ETime').val(), uid: uid},
        cache: false,
        success: function (html) {
            jQuery("#saved_time").show();
            jQuery("#saved_time").fadeOut(3000);
            location.reload();
        }
    });
}
function showValue(newValue)
{
    //document.getElementById("range").innerHTML=newValue;
    document.getElementById("range").value = newValue;
}
function dosave_stoppage_alerts(uid) {
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var params = "stoppagealerts=true";
    if ($("#safcsms").is(':checked')) {
        params += "&safcsms=1";
    } else {
        params += "&safcsms=0";
    }
    if ($("#safcemail").is(':checked')) {
        params += "&safcemail=1";
    } else {
        params += "&safcemail=0";
    }
    if ($("#safctelephone").is(':checked')) {
        params += "&safctelephone=1";
    } else {
        params += "&safctelephone=0";
    }
    if ($("#saftsms").is(':checked')) {
        params += "&saftsms=1";
    } else {
        params += "&saftsms=0";
    }
    if ($("#saftemail").is(':checked')) {
        params += "&saftemail=1";
    } else {
        params += "&saftemail=0";
    }
    if ($("#safttelephone").is(':checked')) {
        params += "&safttelephone=1";
    } else {
        params += "&safttelephone=0";
    }
    params += "&safcmin=" + jQuery('#safcmin').val();
    params += "&saftmin=" + jQuery('#saftmin').val();
    if (jQuery("#samodify").val() == "Modify") {
        params += "&uid=" + uid;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: params,
            cache: false,
            success: function (html) {
                jQuery("#saved_s").show();
                jQuery("#saved_s").fadeOut(3000);
                location.reload();
            }
        });
    } else {
        jQuery("#error_s").show();
        jQuery("#error_s").fadeOut(3000);
    }
}
function dosave_fuel_alerts(uid) {
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var params = "fuelalert=true";
    if (jQuery("#fuelsms").prop("checked")) {
        params += "&fuelsms=1";
    } else {
        params += "&fuelsms=0";
    }
    if (jQuery("#fuelemail").prop("checked")) {
        params += "&fuelemail=1";
    } else {
        params += "&fuelemail=0";
    }
    if (jQuery("#fueltelephone").prop("checked")) {
        params += "&fueltelephone=1";
    } else {
        params += "&fueltelephone=0";
    }
    if (jQuery("#range").val()) {
        params += "&fuelrange=" + jQuery("#range").val();
    }
    if (jQuery("#fuelmodify").val() == "Modify") {
        params += "&uid=" + uid;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: params,
            cache: false,
            success: function (html) {
                jQuery("#saved_s1").show();
                jQuery("#saved_s1").fadeOut(3000);
                location.reload();
            }
        });
    } else {
        jQuery("#error_s1").show();
        jQuery("#error_s1").fadeOut(3000);
    }
}
function clear_all() {
    jQuery('.replica').remove();
    display_veh_count('selected');
}
function remove_tr(data) {
    jQuery(data).closest('tr').remove();
    display_veh_count('selected');
}
function text_changes(stTime, edTime, vehid, vehno) {
    var final = text.replace('#stTime#', stTime);
    final = final.replace('#stTime#', stTime);
    final = final.replace('#edTime#', edTime);
    final = final.replace('#edTime#', edTime);
    final = final.replace('#vehid#', vehid);
    final = final.replace('#vehno#', vehno);
    return final;
}
function check_spec_radio() {
    var idVal = jQuery('#radioButtonId').val();
    jQuery('#' + idVal).prop('checked', true);
}
function display_veh_count(text) {
    var idVal = jQuery('#radioButtonId').val();
    var veh_count = jQuery('#popTable tbody tr').length - 2; //2 is the top 2 tr
    var plural = (veh_count > 1) ? 's' : '';
    var disp_text = veh_count + ' vehicle' + plural + ' ' + text;
    jQuery('#' + idVal).nextAll('span:first').html(disp_text);
}
function add_row() {
    var stTime = jQuery('#popStartTime').val();
    var edTime = jQuery('#popEndTime').val();
    var vehid = jQuery('#vehicleidA').val();
    var vehno = jQuery('#vehiclenoA').val();
    if (vehno == '' || vehid == '') {
        jQuery('#messageSpan').html('Please Enter Vehicle No.');
        return false;
    }
    if (!validate_vehicles(vehid)) {
        jQuery('#messageSpan').html('"' + vehno + '" already selected.');
        return false;
    }
    var final = text_changes(stTime, edTime, vehid, vehno);
    jQuery('#popTable').append(final);
    jQuery('#vehicleidA').val('');
    jQuery('#vehiclenoA').val('');
    jQuery('#messageSpan').html('');
    display_veh_count('selected');
}
function validate_vehicles(vehid) {
    var valid = true;
    jQuery('input[name="pop[vehlist][]"]').each(function () {
        if (jQuery(this).val() == vehid) {
            valid = false;
        }
    });
    return valid;
}
function check_vehicles() {
    var vehs = new Array();
    var i = 0;
    jQuery('input[name="pop[vehlist][]"]').each(function () {
        vehs[i] = jQuery(this).val();
        i++;
    });
    if (i == 0) {
        return 0;
    } else {
        return vehs;
    }
}
function add_specific_vehicles() {
    var vehs = check_vehicles();
    if (vehs === 0) {
        jQuery('#messageSpan').html('Please Add Minimum 1 vehicle.');
        return false;
    }
    check_spec_radio();
    add_vehicles();
    display_veh_count('added');
    jQuery('#popClose').trigger('click');
}
function assign_radio_id(id, main) {
    var idVal = id;
    var title = jQuery('#' + id).closest('tr').children('td :first').text();
    jQuery('#radioButtonId').val(idVal);
    jQuery('#popAlertType').val(main);
    jQuery('.replica').remove();
    jQuery('#poptitle').html(' (' + title + ')');
    var params = "toDo=getAlerts&popAlertType=" + main + '&uid=' + uid;
    jQuery.ajax({
        type: "POST",
        url: def_url,
        data: params,
        cache: false,
        success: function (html) {
            var data = jQuery.parseJSON(html);
            var stTime = '';
            var edTime = '';
            var vehid = '';
            var vehno = '';
            var main = '';
            jQuery.each(data, function (i) {
                stTime = data[i].start;
                edTime = data[i].end;
                vehid = data[i].vehicleid;
                vehno = data[i].vehno;
                main += text_changes(stTime, edTime, vehid, vehno);
            });
            jQuery('#popTable').append(main);
        }
    });
}
function submitVehicleAlert() {
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var params = jQuery('#vehicleAlertForm').serialize();
    params += '&uid=' + uid;
    jQuery.ajax({
        type: "POST",
        url: def_url,
        data: params,
        success: function (html) {
            location.reload();
        }
    });
}
function submitAdvancedAlert() {
    jQuery("input:checkbox:disabled").each(function () {
        jQuery('#' + this.id).prop('disabled', false);
    });
    var params = jQuery('#advancedalerts').serialize();
    params += '&uid=' + uid;
    jQuery.ajax({
        type: "POST",
        url: def_url,
        data: params,
        success: function (html) {
            location.reload();
        }
    });
}
function add_vehicles() {
    var params = jQuery('#popTableForm').serialize();
    params += "&toDo=specVehicles";
    params += '&uid=' + uid;
    jQuery.ajax({
        type: "POST",
        url: def_url,
        data: params,
        success: function (html) {
            location.reload();
        }
    });
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
function modifyUserReports(userId) {
    var reportIds = new Array();
    var counterReportTime = 0;
    jQuery("#userReportMapping input:checkbox:checked").each(function () {
        var str = this.id;
        var ids = str.replace('activated_', '');
        reportIds.push(ids);
    });
    jQuery("#userReportMapping select:enabled").each(function () {
        var strselect = this.id;
        if (jQuery('#' + this.id).val() == '-1') {
            counterReportTime++;
        }
    });
    if (counterReportTime > 0) {
        alert("Please Select Report Time");
    } else {
        var dat = jQuery('#userReportMapping').serialize();
        var data = dat + "&userid=" + userId + "&reportList=" + reportIds + "&work=modifyUserReports";
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            data: data,
            cache: false,
            success: function (html)
            {
                window.location = 'users.php?id=2';
            }
        });
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
function refreshFun(userid) {
    var temp_sensor = $('#temp_sensors').val();
    jQuery("#vehicleText").autocomplete({
        source: "route_ajax.php?work=getvehicle&userid=" + userid,
        select: function (event, ui) {
            for (var i = 0; i < temp_sensor; i++) {
                var temp_min_sms = "ui.item.temp" + (i + 1) + "_min_sms";
                var temp_max_sms = "ui.item.temp" + (i + 1) + "_max_sms";
                var temp_min_email = "ui.item.temp" + (i + 1) + "_min_email";
                var temp_max_email = "ui.item.temp" + (i + 1) + "_max_email";
                $('#temp' + (i + 1) + '_min_sms').val(eval(temp_min_sms));
                $('#temp' + (i + 1) + '_max_sms').val(eval(temp_max_sms));
                $('#temp' + (i + 1) + '_min_email').val(eval(temp_min_email));
                $('#temp' + (i + 1) + '_max_email').val(eval(temp_max_email));
            }
            jQuery(this).val(ui.item.value);
            $('#vehicle_ids').val(ui.item.vid);
            return false;
        }
    });
}

function clear_all_adv_temp() {
    $('#popTempTableForm')[0].reset();
    $('#vehicle_ids').val('');
}

function add_advance_temp_range() {
    
    var vehLIst = $('#vehicle_ids').val();
    var temp_sensors = $('#temp_sensors').val();
    for(var i = 1; i <= parseFloat(temp_sensors); i++){
        if($('#temp'+ i +'_min_sms').val() == ''){
            alert('Please fill all fields');
            $('#temp'+ i +'_min_sms').focus();
            return false;
        }
        if($('#temp'+ i +'_max_sms').val() == ''){
            alert('Please fill all fields');
            $('#temp'+ i +'_max_sms').focus();
            return false;
        }
        if($('#temp'+ i +'_min_email').val() == ''){
            alert('Please fill all fields');
            $('#temp'+ i +'_min_email').focus();
            return false;
        }
        if($('#temp'+ i +'_max_email').val() == ''){
            alert('Please fill all fields');
            $('#temp'+ i +'_max_email').focus();
            return false;
        }
    }
    
    if (vehLIst != '') {
        var method = 'method=add_adv_temp&';
        var data = $('#popTempTableForm').serialize();
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: method + data,
            cache: false,
            success: function (html) {
                if (html == 1) {
                    jQuery('#error1').show();
                    jQuery("#error1").fadeOut(3000);
                    $('#popTempTableForm')[0].reset();
                    $('#vehicle_ids').val('');
                } else {
                    jQuery('#error2').show();
                    jQuery("#error2").fadeOut(3000);
                    return false;
                }
            }
        });
    } else {
        jQuery('#error3').show();
        jQuery("#error3").fadeOut(3000);
    }
}

function checkRange(a,b) {
    var sms_min = '';
    var sms_max = '';
    var email_min = ''; 
    var email_max = '';
    var value = '';
    var method = 'method=check_temp_range&nomen=' + b + '&';
    var data = $('#popTempTableForm').serialize();
    if(a.indexOf("sms") != -1) {
        var min = $('#temp'+b+'_min_email').val();
        var max = $('#temp'+b+'_max_email').val();
        if(a.indexOf("min") != -1) {
            value = $('#temp'+b+'_'+a).val();
            sms_max = $('#temp'+b+'_max_sms').val();
            if(min != '' && max != '' && sms_max != '' && value != '') {
                if(parseFloat(value) >= parseFloat(sms_max)){
                    alert('Wrong Ranges');
                    $('#temp'+b+'_min_sms').val('');
                    $('#temp'+b+'_max_sms').val('');
                    $('#temp'+b+'_min_sms').focus();
                    return false;
                }
                if(overlap(value,sms_max,min,max)){
                    alert('Range already exist for email');
                    $('#temp'+b+'_'+a).val('');
                    return false;
                } 
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php",
                    data: method + data,
                    cache: false,
                    success: function (html) {
                        if(html == '1'){
                            alert('This is the basic ranges for which alerts already going.');
                            $('#temp'+b+'_'+a).val('');
                            return false;
                        }
                    }
                });
            }
        } else if(a.indexOf("max") != -1) {
            value = $('#temp'+b+'_'+a).val();
            sms_min = $('#temp'+b+'_min_sms').val();
            if(min != '' && max != '' && sms_min != '' && value != ''){
                if(parseFloat(sms_min) >= parseFloat(value)){
                    alert('Wrong Ranges');
                    $('#temp'+b+'_min_sms').val('');
                    $('#temp'+b+'_max_sms').val('');
                    $('#temp'+b+'_min_sms').focus();
                    return false;
                }
                if(overlap(sms_min,value,min,max)){
                    alert('Range already exist for email');
                    $('#temp'+b+'_'+a).val('');
//                    $('#temp'+b+'_'+a).focus();
                    return false;
                }
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php",
                    data: method + data,
                    cache: false,
                    success: function (html) {
                        if(html == '1'){
                            alert('This is the basic ranges for which alerts already going.');
                            $('#temp'+b+'_'+a).val('');
                            return false;
                        }
                    }
                });
            }
        }
    } else if(a.indexOf("email") != -1) {
        var min = $('#temp'+b+'_min_sms').val();
        var max = $('#temp'+b+'_max_sms').val();
        if(a.indexOf("min") != -1) {
            value = $('#temp'+b+'_'+a).val();
            email_max = $('#temp'+b+'_max_email').val();
            if(min != '' && max != '' && email_max != '' && value != ''){
                if(parseFloat(value) >= parseFloat(email_max)){
                    alert('Wrong Ranges');
                    $('#temp'+b+'_min_email').val('');
                    $('#temp'+b+'_max_email').val('');
                    $('#temp'+b+'_min_email').focus();
                    return false;
                }
                if(overlap(value,email_max,min,max)){
                    alert('Range already exist for SMS');
                    $('#temp'+b+'_'+a).val('');
//                    $('#temp'+b+'_'+a).focus();
                    return false;
                } 
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php",
                    data: method + data,
                    cache: false,
                    success: function (html) {
                        if(html == '1'){
                            alert('This is the basic ranges for which alerts already going.');
                            $('#temp'+b+'_'+a).val('');
                            return false;
                        }
                    }
                });
            }
        } else {
            value = $('#temp'+b+'_'+a).val();
            email_min = $('#temp'+b+'_min_email').val();
            if(min != '' && max != '' && email_min != '' && value != ''){
                if(parseFloat(email_min) >= parseFloat(value)){
                    alert('Wrong Ranges');
                    $('#temp'+b+'_min_email').val('');
                    $('#temp'+b+'_max_email').val('');
                    $('#temp'+b+'_min_email').focus();
                    return false;
                }
                if(overlap(email_min,value,min,max)){
                    alert('Range already exist for SMS');
                    $('#temp'+b+'_'+a).val('');
//                    $('#temp'+b+'_'+a).focus();
                    return false;
                } 
                jQuery.ajax({
                    type: "POST",
                    url: "../../modules/user/route_ajax.php",
                    data: method + data,
                    cache: false,
                    success: function (html) {
                        if(html == '1'){
                            alert('This is the basic ranges for which alerts already going.');
                            $('#temp'+b+'_'+a).val('');
                            return false;
                        }
                    }
                });
            }
        }
    }
}

function isVehicleSelected() {
    var vehLIst = $('#vehicle_ids').val();
    if(vehLIst == '') {
        jQuery('#error3').show();
        jQuery("#error3").fadeOut(3000);
        $(this).val('');
        $('#vehicleText').focus();
        return false;
    }
}

function overlap(x1,x2,y1,y2){
    var max = (parseFloat(x1)<parseFloat(y1))?y1:x1;
    var min = (parseFloat(x2)<parseFloat(y2))?x2:y2;
    if(parseFloat(max) < parseFloat(min)){
        return true;
    } else if(parseFloat(max) >= parseFloat(min)) {
        return false;
    }
}

function delete_advance_temp(vehicleid) {
    if (confirm('Do you want delete advance temperature ranges for the vehicle ?')) {
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route_ajax.php",
            data: {
                'del_adv_temp': vehicleid
            },
            cache: false,
            success: function (html) {
                if (html == 1) {
                    window.location.reload();
                }
            }
        });
    } else {
        return false;
    }
}

function edit_advance_temp(vehicleid, userid, temp_sensors) {
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/route_ajax.php",
        data: {
            'edit_advance_temp': vehicleid
            , 'edit_advance_temp_userid': userid
        },
        cache: false,
        success: function (response) {
            var data1 = jQuery.parseJSON(response);
            for (var i = 0; i < temp_sensors; i++) {
                var temp_min_sms = "data1[0].temp" + (i + 1) + "_min_sms";
                var temp_max_sms = "data1[0].temp" + (i + 1) + "_max_sms";
                var temp_min_email = "data1[0].temp" + (i + 1) + "_min_email";
                var temp_max_email = "data1[0].temp" + (i + 1) + "_max_email";
                $('#temp' + (i + 1) + '_min_sms').val(eval(temp_min_sms));
                $('#temp' + (i + 1) + '_max_sms').val(eval(temp_max_sms));
                $('#temp' + (i + 1) + '_min_email').val(eval(temp_min_email));
                $('#temp' + (i + 1) + '_max_email').val(eval(temp_max_email));
            }
            jQuery('#vehicleText').val(data1[0].vehicleno);
            $('#vehicle_ids').val(data1[0].vehicleid);
        }
    });
}
function addNewAdvTempRange() {
    $('#popTempTableForm')[0].reset();
    $('#vehicle_ids').val('');
}