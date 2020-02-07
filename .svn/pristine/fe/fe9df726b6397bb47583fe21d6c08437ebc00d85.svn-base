var type = 0;
function add_fuelcon(vehicleid, type) {
    jQuery("#vehicleid").val(vehicleid);
    alert(vehicleid);
    if (type == 3)
        jQuery('#fuelpost_' + vehicleid).modal('show');

}
function callTicket() {
    jQuery('#Ticket').modal('show');
}
function use_maintenance(cid)
{
    if (cid == 3) {
        var data = "setsession=" + 3;
    }
    else {
        var data = "setsession=" + 1;
    }
    //var data = "setsession=" + 1;
    //alert("test");
    jQuery.ajax({
        type: "POST",
        url: "../user/route.php",
        data: data,
        cache: false,
        success: function (html) {
            //window.location.reload();
            if (cid == 1) {
                window.location.href = "../../modules/approvals/approvals.php?id=2";
            }
            if (cid == 2) {
                window.location.href = "../../modules/transactions/transaction.php?id=2";
            }
            if (cid == 3) {
                window.location.href = "../../modules/realtimedata/warehouse.php";
            }

        }
    });
}
function remove_maintenance(cid)
{
    if (cid == 3) {
        var data = "removesession=" + 3;
    }
    else {
        var data = "removesession=" + 1;
    }
    //alert("test");
    jQuery.ajax({
        type: "POST",
        url: "../user/route.php",
        data: data,
        cache: false,
        success: function (html) {
            //window.location.reload();
            if (cid == 1) {
                window.location.href = "../../modules/delivery/delivery.php";
            }
            if (cid == 2) {
                window.location.href = "../../modules/realtimedata/realtimedata.php";
            }
            if (cid == 3) {
                window.location.href = "../../modules/realtimedata/warehouse.php";
            }


        }
    });
}
function addtransaction_view(vehicleid, type)
{
    jQuery("#vehicle_id").val(vehicleid);
    jQuery(".tyre_field").hide();
    jQuery("#expandable").html('');
    jQuery("#category_id").val(type);
    switch (type.toString()) {
        case'0':
            // battery
            //  getbattery(vehicleid);
            jQuery("#head_fortransac").html('Quotation for Battery Replacement');
            get_dealer(vehicleid, type);

            break;
        case'1':
            // battery
            jQuery("#head_fortransac").html('Quotation for Tyre Replacement');
            jQuery(".tyre_field").show();
            get_dealer(vehicleid, type);
            jQuery("#expandable").html(jQuery("#tyre_fields").html());
            break;
        case'2':
            // battery
            jQuery("#head_fortransac").html('Quotation for Repair / Service');
            get_dealer(vehicleid, type);
            jQuery(".tyre_field").show();

            jQuery("#expandable").html(jQuery("#parts_service_category").html());
            break;
        case'3':
            // battery
            jQuery("#vehicle_id1").val(vehicleid);
            jQuery("#category_id1").val(type);
            jQuery("#accident_head_fortransac").html('Accident Event Entry / Insurance Claim');
//               getinsurance(vehicleid);
            break;
        case'5':
            // battery
            jQuery("#head_fortransac").html('Quotation for Accessories');
            get_dealer(vehicleid, type);
            jQuery("#expandable").html(jQuery("#accessory_category").html());
            break;

    }
    if (type == 3)
        jQuery('#accidentview_approval').modal('show');
    else
        jQuery('#addview_approval').modal('show');

    jQuery("#transaction_msg").html('');
    jQuery("#transaction_msg").removeClass("alert alert-danger");
    jQuery("#transaction_msg").removeClass("alert alert-info");
}
function get_dealer(vehicleid, type)
{
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {dealer_vehicle_id: vehicleid, vehicle_type: type},
        dataType: "html",
        success: function (html) {
            jQuery("#dealerid").empty();
            jQuery("#dealerid").append(html);
        }
    });
    return false;



}
function getinsurance(vehicle_id)
{
    jQuery('#getaccident_approval #radio_show').hide();
    jQuery('#getaccident_approval #insurance_div').show();
    jQuery('#getaccident_approval #insurance_status').html('Insurance Status : No');
    if (vehicle_id != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {insurance_vehicle_id: vehicle_id},
            dataType: "json",
            success: function (data) {
                jQuery('#getaccident_approval #insurance_value').val(data.value);
                jQuery('#getaccident_approval #premium_value').val(data.premium);
                jQuery('#getaccident_approval #StartDate').val(data.start_date);
                jQuery('#getaccident_approval #EndDate').val(data.end_date);
                jQuery('#getaccident_approval #ins_amount').val(data.amount);
                jQuery('#getaccident_approval #ins_notes').val(data.notes);
                jQuery('#getaccident_approval #edit_insurance_company').val(data.companyid);
                jQuery('#getaccident_approval #near_place').val(data.claim_place);
                jQuery('#getaccident_approval #insurance1').attr('checked', 'true');
                jQuery('#radio_show input').attr('readonly', 'readonly');
                jQuery('#radio_show select').attr('readonly', 'readonly');
                jQuery('#getaccident_approval textarea').attr('readonly', 'readonly');
                jQuery('#getaccident_approval #insurance_status').html('Insurance Status : Yes');
                jQuery('#getaccident_approval #insurance_div').hide();
                jQuery('#getaccident_approval #radio_show').show();
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}
function getbattery(vehicle_id)
{
    if (vehicle_id != '') {

        jQuery.ajax({
            url: "route_ajax.php",
            type: 'POST',
            cache: false,
            data: {batt_vehicle_id: vehicle_id,
                type: '1'},
            dataType: 'html',
            success: function (html) {
                jQuery("#battery_body").html('');
                jQuery("#battery_body").append(html);
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}
function addbattery_approval()
{
    var vehicleid = jQuery('#vehicle_id').val();
    if (vehicleid != '') {
        var data = jQuery('#getbattery_approval').serialize() + '&battery_vehicleid=' + vehicleid;
        jQuery.ajax({
            url: "../vehicle/route_ajax.php",
            type: 'POST',
            cache: false,
            data: data,
            success: function (statuscheck) {
                if (statuscheck == "notok") {
                }
                else if (statuscheck == "ok") {
                    jQuery('#addbattery_approval').modal('hide');
                    jQuery("#battery_approval_success").show();
                    jQuery("#battery_approval_success").fadeOut(3000);
                }
            }
        });
        return false;
    }
    else {
        alert('please fill up general details first!!');
    }

}

jQuery.noConflict();
jQuery(document).ready(function () {
    /*
     setTimeout(function () {
     pullnf();
     }, 15000);
     */
    submenu_show();
    var id = '#dialog';
    //Get the screen height and width
    var maskHeight = jQuery(document).height();
    var maskWidth = jQuery(window).width();
    //Set heigth and width to mask to fill up the whole screen
    jQuery('#mask').css({
        'width': maskWidth,
        'height': maskHeight
    });
    jQuery('#mask1').css({
        'width': maskWidth,
        'height': maskHeight
    });
    //transition effect
    jQuery('#mask1').fadeIn(1000);
    jQuery('#mask1').fadeTo("slow", 0.8);
    //Get the window height and width
    var winH = jQuery(window).height();
    var winW = jQuery(window).width();
    //Set the popup window to center
    jQuery(id).css('top', winH / 2 - jQuery(id).height() / 2);
    jQuery(id).css('left', winW / 2 - jQuery(id).width() / 2);
    //transition effect
    jQuery(id).fadeIn(2000);
    //if close button is clicked
    jQuery('.window #close_popup').click(function (e) {
        //Cancel the link behavior
        e.preventDefault();
        jQuery('.window').hide();
        jQuery('#mask1').fadeOut("slow");
        jQuery('#mask').fadeIn(1000);
        jQuery('#mask').fadeTo("slow", 0.8);
        jQuery('#mask').show();
        jQuery('.window1').show();
    });
    jQuery('.window1 #close_popup1').click(function (e) {
        //Cancel the link behavior
        e.preventDefault();
        jQuery('#mask').hide();
        //jQuery('#mask1').hide();
        //jQuery('.window').hide();
        jQuery('.window1').hide();
    });
    jQuery('#mask1').click(function () {
        jQuery(this).hide();
        jQuery('#mask1').fadeOut("slow");
        jQuery('.window').hide();
        jQuery('.window1').show();
        jQuery('#mask').fadeIn(1000);
        jQuery('#mask').show();
    });
    //if mask is clicked
    jQuery('#mask').click(function () {
        jQuery(this).hide();
        jQuery('#mask1').hide();
        //jQuery('.window').hide();
        jQuery('.window1').hide();
    });

    jQuery(".editbox").hide();
    jQuery(".edit_td").dblclick(function () {
        var ID = jQuery(this).attr('id');
        jQuery("#vehicleno_" + ID).hide();


        jQuery("#vehicleno_input_" + ID).show();

        jQuery("#vehicleno_input_" + ID).focus();
    }).change(function () {
        var ID = jQuery(this).attr('id');
        var vehicleno = jQuery("#vehicleno_input_" + ID).val();
        var dataString = 'vehicleid=' + ID + '&vehicleno=' + vehicleno;
        jQuery("#vehicleno_" + ID).html('<img src="load.gif" />'); // Loading image
        if (vehicleno.length > 0) {
            jQuery.ajax({
                type: "POST",
                url: "../vehicle/route_ajax.php",
                data: dataString,
                cache: false,
                success: function (html) {
                    jQuery("#vehicleno_" + ID).html(vehicleno);
                }
            });
        }
        else {
            alert('Enter something.');
        }
    });
    //edit td
    jQuery(".edit_td1").dblclick(function () {
        var ID = jQuery(this).attr('id');
        jQuery("#drivername_" + ID).hide();
        jQuery("#drivername_input_" + ID).show();
        jQuery("#driverno_" + ID).hide();
        jQuery("#driverno_input_" + ID).show();
        jQuery("#driverno_input_" + ID).focus();
    }).change(function () {
        var ID = jQuery(this).attr('id');
        var drivername = jQuery("#drivername_input_" + ID).val();
        var driverno = jQuery("#driverno_input_" + ID).val();
        var dataString = 'vehicleid=' + ID + '&drivername=' + drivername + '&driverno=' + driverno;
        jQuery("#drivername_" + ID).html('<img src="load.gif" />'); // Loading image
        jQuery("#driverno_" + ID).html('<img src="load.gif" />'); // Loading image
        if (drivername.length > 0 && driverno.length > 0) {
            jQuery.ajax({
                type: "POST",
                url: "../vehicle/route_ajax.php",
                data: dataString,
                cache: false,
                success: function (html) {
                    jQuery("#drivername_" + ID).html(drivername);
                    jQuery("#driverno_" + ID).html(driverno);
                }
            });
        }
        else {
            alert('Enter something.');
        }
    });
    // Edit input box click action
    jQuery(".editbox").mouseup(function () {
        return false
    });
    jQuery('.editbox').keypress(function (e) {
        if (e.which == 13) {
            //alert('The enter key was pressed!');
            jQuery(".editbox").hide();
            jQuery(".text").show();
        }
    });
    // Outside click action
    jQuery(document).mouseup(function () {
        jQuery(".editbox").hide();
        jQuery(".text").show();
    });

});
function changeaccount(account) {

    if (account != '0') {
        var data = "account=" + account;
        //alert(account);
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route.php",
            data: data,
            cache: false,
            success: function (html) {
                window.location.reload();
            }
        });
    }

}
// JavaScript Document
function updategroupid() {
    var groupid = jQuery('#grouplist').val();
    if (groupid != 'add') {
        var data = "groupid=" + groupid;
        jQuery.ajax({
            type: "POST",
            url: "../../modules/user/route.php",
            data: data,
            cache: false,
            success: function (html) {
                window.location.reload();
            }
        });
    }
    else {
        window.location.href = "../../modules/group/group.php";
    }
}
function submenu_show() {
    jQuery("#horizontalmenu1").click(function () { //if mouse leaves the submenu
        //hide_all();
        jQuery("#submenu").toggle(); //hide the open submenu (this is what isn't working)
        //$(this).css('background-color','red'); //this works
    })
}
/*jQuery(document).click(
 function (e) {
 if (e.target.className == "horizontalmenu1") {
 if (jQuery("#submenu").css("display") == 'undefined' || jQuery("#submenu").css("display") == 'block') {
 jQuery("#submenu").css("display", "block");
 } else {
 jQuery("#submenu").css("display", "none");
 }
 //jQuery("#submenu").css("display","block");
 //jQuery("#submenu").toggle();
 } else {
 jQuery("#submenu").hide();
 //jQuery('.always_show').tipsy('hide');
 }

 );
 */
//$.noConflict();
function pullnf()
{
    if (type == 3)
    {
        type = 1;
    }
    else
    {
        type = type + 1;
    }

    jQuery.ajax({
        type: "POST",
        url: "../notification/pullnf_ajax.php",
        data: {type: type},
        async: true,
        cache: false,
        dataType: "json",
        success: function (cdata1) {

            var results = cdata1.result;
            if (results)
            {
                jQuery.each(results, function (i, queue) {
                    if (queue.type == 0)
                    {
                        jQuery.sticky("<font color='#FF0000'>Alert:</font><br/>" + queue.notif, {autoclose: 10000, position: "top-left", type: "st-topleft1"});
                    }
                    if (queue.type == 1)
                    {
                        jQuery.sticky("News: " + queue.notif, {autoclose: 10000, position: "top-left", type: "st-topleft2"});
                    }
                    if (queue.type == 2)
                    {
                        jQuery.sticky("<font color='#0193CC'>Tip: </font><br/>" + queue.notif, {autoclose: 10000, position: "top-left", type: "st-topleft3"});
                    }
                    if (queue.type == 3)
                    {
                        jQuery.sticky("<font color='#006633'>Quick Link: </font><br/>" + queue.notif, {autoclose: 10000, position: "top-left", type: "st-topleft4"});
                    }
                });
            }

            refreshmin();
        }
    });



}
function refreshmin() {
    /*
     setTimeout(function () {
     pullnf();
     }, 60000);
     */

}
function PrintElem(elem) {
    Popup(jQuery(elem).html());
}
function Popup(data)
{
    //alert(data);
    var mywindow = window.open('', 'Report', '');
    mywindow.document.write('<html><head><title>Graphical Report</title>');
    /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
    mywindow.document.write('</head><body style="width: 90%; Height: 768px;"><br><br><br><br><br>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
    mywindow.print();
    mywindow.close();
    return true;
}
function generate_report_type(rep_val) {
    if (rep_val !== '') {
        var unit = '';
        switch (rep_val) {
            case 'distance':
                unit = 'KM';
                break;
            case 'avg_speed':
                unit = 'KM/Hour';
                break;
            case 'idle_time':
                unit = 'Hour';
                break;
            case 'genset_avg':
                unit = 'Mins';
                break;
        }
        jQuery('#reportUnit').html(unit);
    }

}
function addExceptionAlert(from) {
    if (from == 'div') {
        var data = jQuery('#exceptionAdd :input').serialize();
    }
    else {
        var data = jQuery('#exceptionAdd').serialize();
    }
    data += "&todo=addException";
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/exception_ajax.php",
        data: data,
        cache: false,
        success: function (html) {
            jQuery("#excepResult").html(html);
        }
    });

}
function delete_exception(id) {
    var data = "excep_id=" + id + "&todo=deleteEception";
    if (!confirm('Confirm to delete this Alert?')) {
        return false;
    }
    jQuery.ajax({
        type: "POST",
        url: "../../modules/user/exception_ajax.php",
        data: data,
        cache: false,
        success: function (html) {
            jQuery("#messageSpanExceptionPop").html(html);
            jQuery("." + id).hide();
            //location.reload();
        }
    });
}
function validate_exception() {
    var e_sms = jQuery('#exceptionSms').is(':checked');
    var e_mail = jQuery('#exceptionEmail').is(':checked');
    var inp_val = jQuery('#report_type_input').val();
    if (e_sms === true || e_mail === true) {
        if (jQuery.trim(inp_val) === '') {
            alert('Please enter Trip Exception Value.');
            return false;
        }
        if (!jQuery.isNumeric(inp_val)) {
            alert('Trip Exception Value should be Numeric.');
            return false;
        }
        ;
        var s_check = jQuery('#checkpoint_start').val();
        var e_check = jQuery('#checkpoint_end').val();
        if (s_check == e_check) {
            alert('Start and End Checkpoint should be different');
            return false;
        }
    }
    return true;
}

jQuery(function () {
    jQuery('body').click(function () {
        jQuery('#success_status').hide();
    });
});

function show_error(text) {
    var conf = "<b style='color:red;font-weight:bold;'>" + text + "</b>";
    jQuery('#StatusTD').show();
    jQuery('#Status').html(conf);
}
function validate_upload(fileInput) {
    var fileName = jQuery('#' + fileInput).val();

    var validExtensions = valid_file_xls;
    var fileExt = fileName.substr(fileName.lastIndexOf('.') + 1);
    if (jQuery.inArray(fileExt, validExtensions) == -1) {
        show_error("Invalid file type");
        return false;
    }
    var size = jQuery("#" + fileInput)[0].files[0].size;
    var valid_size = valid_size_xls;//2 mb
    if (size > valid_size) {
        show_error("File Size cannot cannot exceed 2 MB");
        return false;
    }
    return true;
}

function countChar(inputCtrl) {
    var maxlength = (inputCtrl.maxLength > 0) ? inputCtrl.maxLength : 50;
    var len = inputCtrl.value.length;
    if (len >= maxlength) {
        inputCtrl.value = inputCtrl.value.substring(0, maxlength);
    }
    else {
        var left = maxlength - len;
        $('#charNum').text(left + ' characters left');
    }
}

function GetDateString(date, format) {
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    getPaddedComp = function (comp) {
        return ((parseInt(comp) < 10) ? ('0' + comp) : comp)
    };
    formattedDate = format;
    o = {
        "y+": date.getFullYear(), // year
        "M+": months[date.getMonth()], //month
        "d+": getPaddedComp(date.getDate()), //day
        "D+": days[date.getDay()], //weekday
        "h+": getPaddedComp((date.getHours() > 12) ? date.getHours() % 12 : date.getHours()), //hour
        "H+": getPaddedComp(date.getHours()), //hour
        "m+": getPaddedComp(date.getMinutes()), //minute
        "s+": getPaddedComp(date.getSeconds()), //second
        "S+": getPaddedComp(date.getMilliseconds()), //millisecond,
        "b+": (date.getHours() >= 12) ? 'PM' : 'AM'
    };

    for (var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            formattedDate = formattedDate.replace(RegExp.$1, o[k]);
        }
    }
    return formattedDate;
}
;
