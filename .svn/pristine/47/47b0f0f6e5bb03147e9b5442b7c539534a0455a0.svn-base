// JavaScript Document
var toggle_counter = 2;
var previousid = 0;
var periodic = true;
var timeouts = [];
var hideIconCount = 0;
var refr_int_time = 5; //temperarry interval time is 5 minutes
var Minutes = 60 * refr_int_time;
var display = jQuery('#time');
var interval;
var periodictime = (Minutes * 1000) - 1000;
var refreshtime  = Minutes * 1000;
jQuery(document).ready(function () {
    //jQuery('body').chardinJs('start');
   // loadrefresh();
    //close_map();
      periodicupdateloadrefresh();
      startTimer(Minutes, display);
});
jQuery(function () {
    jQuery('#addConsignorBuble').css({"visibility": "hidden"});
    jQuery('#addConsigneeBuble').css({"visibility": "hidden"});
    jQuery('#addStatusBuble').css({"visibility": "hidden"});
    jQuery('body').click(function () {
        jQuery('#ajaxstatus').hide();
    });
    jQuery('#ajaxBstatus').hide();
    jQuery('#ajaxBstatus1').hide();
    jQuery('#ajaxBstatus2').hide();
    jQuery('#ajaxstatus').hide();
    jQuery("input[name=cdob]").datepicker({format: "dd-mm-yyyy", autoclose: true});
    jQuery("input[name=cannivrsry]").datepicker({format: "dd-mm-yyyy", autoclose: true});
    //jQuery("input[name=stockdate]").datepicker({format: "dd-mm-yyyy",autoclose:true});
    //jQuery('#STime').val('00:00');
    jQuery('#SDate').datepicker({format: "dd-mm-yyyy", autoclose: true});
    //jQuery("#STime").datepicker({showOn: 'both', format: "hh:ii:ss", autoclose: true});

    /// above line were created collapse with time and date

    //jQuery('#starttime').timepicker({format: "hh:ii:ss", autoclose: true, });
});

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
    }
    else {
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
    if (jQuery('#role').val()) {
        pullVehiclesByGroup();
    }
}
function removeallgroup() {
    //var select_box = jQuery('#group');
    jQuery("#group option").each(function (index, element) {
        var groupid = jQuery(this).val();
        removegroup(groupid);
    });
}
function removegroup(group_no) {
    if (group_no > -1 && jQuery('#to_group_div_' + group_no).val() != null) {
        jQuery('#to_group_div_' + group_no).remove();
    }
}

mappedgroups(); // called direct 
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

/*--------------------------====---------------------*/
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
    }
    pullVehiclesByGroup();
}
/*End All above new js code*/
function addtripdetails(){  
    var formdata = jQuery("#addtripform").serialize();
    if (is_empty('vehicleno')) {
        show_error('Please enter Vehicle No.');
        return false;
    }
    ajax_request(formdata + "&action=addtripdetails", 'addtripform');

 // location.reload();
}
function edittripdetails(formId){
    var formdata    = jQuery("#edittripform").serialize();
    var tripid      = jQuery("#edittripform").find("#tripid").val();
    var tripLogNo   = jQuery("#edittripform").find("#triplogno").val();
    var vehicleno   = jQuery("#edittripform").find("#vehicleno").val();
   if (tripid == "" || vehicleno == "") {
        show_error('Please enter vehicleno.');
        return false;
    }
    jQuery("#btnUpdate").prop("disabled", true);
    ajax_request(formdata + "&action=edittripdetails", 'ee');

    jQuery("#triplogno").val(tripLogNo);
    get_historydetails(tripid); 
   //location.reload();
}

function get_historydetails(tripid) {
    var data = "&action=triphistajax&tripid=" + tripid;
    jQuery.ajax({
        url: "trip_ajax.php",
        type: 'POST',
        data: data,
        success: function (jsonResult) {
            var result = jQuery.parseJSON(jsonResult);
            if(result.Status === "Success"){
                var detail = '';
                detail += "<table style='width:100%;'><tr><th>Status</th><th>Location </th><th>Status Time</th></tr>";
                jQuery(result.data).each(function (i, v) {
                    detail += "<tr><td>" + v.tripstatus + "</td><td>" + v.location + "</td><td>" + v.statustime + "</td></tr>";
                });
                detail +="</table>";
                jQuery("#tdhistory").empty();
                jQuery("#tdhistory").html(detail);
            } else {
                jQuery("#tdhistory").append(result.Error);
            }
        }
    });
}
function addstatusdata() {
    var formdata = jQuery("#statusform").serialize();
    if (is_empty('statusname')) {
        show_error('Please enter status.');
        return false;
    }
    ajax_request(formdata + "&action=addtripstatus", 'statusform');
}
function updatetripstatusdata() {
    var formdata = jQuery("#statusformedit").serialize();
    if (is_empty('statusname') || is_empty('statusid')) {
        show_error('Please enter Status.');
        return false;
    }
    ajax_request(formdata + "&action=edittripstatus", 'ee');
}
function editconsigneedata() {
    var formdata = jQuery("#consigneeformedit").serialize();
    if (is_empty('consigneename') || is_empty('consid')) {
        show_error('Please enter Consignee.');
        return false;
    }
    ajax_request(formdata + "&action=editconsignee", 'ee');
}
function editconsignordata() {
    var formdata = jQuery("#consignorformedit").serialize();
    if (is_empty('consigneername') || is_empty('consrid')) {
        show_error('Please enter consignor.');
        return false;
    }
    ajax_request(formdata + "&action=editconsignor", 'ee');
}
function deletetripstatus(a) {
    var res = confirm("Are you sure you want delete this Status.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=deltripstatus", 'ff');
        location.reload();
    } else {
        return false;
    }
}
function deleteconsignee(a) {
    var res = confirm("Are you sure you want delete this Consignee.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delconsignee", 'ff');
        location.reload();
    } else {
        return false;
    }
}
function deleteconsignor(a) {
    var res = confirm("Are you sure you want delete this Consignor.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delconsignor", 'ff');
        location.reload();
    } else {
        return false;
    }
}
function addconsigneedata() {
    var formdata = jQuery("#consigneeform").serialize();
    if (is_empty('consigneename')) {
        show_error('Please enter consignee name.');
        return false;
    }
    ajax_request(formdata + "&action=addconsignee", 'consigneeform');
}
function addconsigneerdata() {
    var formdata = jQuery("#consignorform").serialize();
    if (is_empty('consigneername')) {
        show_error('Please enter consignee name.');
        return false;
    }
    ajax_request(formdata + "&action=addconsigneer", 'consignorform');
}
function is_empty(name) {
    if (jQuery("input[name=" + name + "]").val() == '') {
        return true;
    }
    return false;
}
function ajax_request(data, fid) {
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success(obj.Msg, fid);
            } else {
                show_error(obj.Error);
            }
        },
        complete: function () {
            jQuery('#pageloaddiv').hide();
        }
    });
}
function show_error(text) {
    jQuery("#ajaxstatus").html(text);
    jQuery("#ajaxstatus").css('color', 'red');
    jQuery("#ajaxstatus").show();
}
function show_success(text, fid) {
    alert(text);
    jQuery("#ajaxstatus").html(text);
    jQuery("#ajaxstatus").css('color', 'green');
    jQuery("#ajaxstatus").show();
    jQuery('#' + fid + ' input').each(function () {
        if (jQuery(this).attr('type') != 'submit') {
            jQuery(this).val('');
        }
    });
    location.reload();
}
function hideareas() {
    var typerep = jQuery("#typerep").val();
    if (typerep == 'PJPB') {
        jQuery("#hidetr").css('display', 'none');
    } else {
        jQuery("#hidetr").css('display', 'table-row');
    }
}
function approved_stock(a) {
    var res = confirm("Are you sure you want approve this stock?.");
    if (res == true) {
        jQuery('#pageloaddiv').show();
        ajax_request("&id=" + a + "&action=approve", 'ff');
        location.reload();
    } else {
        return false;
    }
}
$(function () {
    jQuery('.bubbleclose').click(function () {
        jQuery("#ajaxBstatus2").html('');
        jQuery('#addStatusBuble').css({"visibility": "hidden"});
    });
    jQuery('.bubbleclose').click(function () {
        jQuery("#ajaxBstatus").html('');
        jQuery('#addConsignorBuble').css({"visibility": "hidden"});
    });
    jQuery('.bubbleclose').click(function () {
        jQuery("#ajaxBstatus1").html('');
        jQuery('#addConsigneeBuble').css({"visibility": "hidden"});
    });
    jQuery('.bubbleclose1').click(function () {
        jQuery("#ajaxBstatus").html('');
        jQuery('#addConsignorBuble').css({"visibility": "hidden"});
    });
    jQuery('.bubbleclose1').click(function () {
        jQuery("#ajaxBstatus1").html('');
        jQuery('#addConsigneeBuble').css({"visibility": "hidden"});
    });
    jQuery('.bubbleclose1').click(function () {
        jQuery("#ajaxBstatus2").html('');
        jQuery('#addStatusBuble').css({"visibility": "hidden"});
    });
    jQuery('#consignoraddpop').click(function () {
        jQuery("#ajaxBstatus").html('');
        jQuery("#addStatusBuble").css({"visibility": "hidden"});
        jQuery("#addConsigneeBuble").css({"visibility": "hidden"});
        jQuery("#addConsignorBuble").css({"visibility": "visible"});
    });
    jQuery('#consigneeaddpop').click(function () {
        jQuery("#ajaxBstatus1").html('');
        jQuery("#addStatusBuble").css({"visibility": "hidden"});
        jQuery("#addConsignorBuble").css({"visibility": "hidden"});
        jQuery("#addConsigneeBuble").css({"visibility": "visible"});
    });
    jQuery('#tripstatusaddpop').click(function () {
        jQuery("#ajaxBstatus2").html('');
        jQuery("#addConsignorBuble").css({"visibility": "hidden"});
        jQuery("#addConsigneeBuble").css({"visibility": "hidden"});
        jQuery("#addStatusBuble").css({"visibility": "visible"});
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
});
function fill(Value, strparam) {
    $('#vehicleno').val(strparam);
    $('#vehicleid').val(Value);
    $('#display').hide();
}
function addstatusdatapop() {
    var tripstatusp = jQuery("#tripstatusp").val();
    if (tripstatusp == "") {
        show_error_pop("Please fill mandatory fields");
        return false;
    }
    var data = "tripstatus=" + escape(tripstatusp) + "&action=addtripstatuspop";
    jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success_pop2(obj.Msg);
                            $.ajax({
                                type   : 'POST',
                                url    : 'trip_ajax.php',
                                data   : 'tripstatus',
                                success: function(result) {
                                    $('#tripstatus').html(result);
                                }
                            })
                //location.reload();
            } else {
                show_error_pop2(obj.Error);
            }
            $("#addStatusBuble").fadeOut( "slow");
        }
    });
}
function addconsignordatapop(){
    var consrname = jQuery("#consrname").val();
    var consremail = jQuery("#consremail").val();
    var consrmobile = jQuery("#consrmobile").val();
    if (consrname == "") {
        show_error_pop("Please fill mandatory fields");
        return false;
    }
    var data = "consrname=" + escape(consrname) + "&consremail=" + escape(consremail) + "&consrmobile=" + escape(consrmobile) + "&action=addconsignorpop";
    jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                        show_success_pop(obj.Msg);
                        jQuery("#consignor").autocomplete({
                            source: "trip_ajax.php?action=consignorauto", minLength: 1,
                            select: function (event, ui) {
                            jQuery('#consignorid').val(ui.item.id);
                        }
                    });
            } else {
                show_error_pop(obj.Error);
            }
        }
    });
    $("#addConsignorBuble").fadeOut( "slow");
}
function show_error_pop(text) {
    jQuery("#ajaxBstatus").html('');
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'red');
    jQuery("#ajaxBstatus").show();
}
function show_success_pop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#consrname").val('');
    jQuery("#consremail").val('');
    jQuery("#consrmobile").val('');
}
function show_error_pop1(text){
    jQuery("#ajaxBstatus1").html('');
    jQuery("#ajaxBstatus1").html(text);
    jQuery("#ajaxBstatus1").css('color','red');
    jQuery("#ajaxBstatus1").show();
}
function show_success_pop1(text) {
    jQuery("#ajaxBstatus1").html(text);
    jQuery("#ajaxBstatus1").css('color', 'green');
    jQuery("#ajaxBstatus1").show();
    jQuery("#consname").val('');
    jQuery("#consemail").val('');
    jQuery("#consmobile").val('');
}
function show_error_pop2(text) {
    jQuery("#ajaxBstatus2").html('');
    jQuery("#ajaxBstatus2").html(text);
    jQuery("#ajaxBstatus2").css('color', 'red');
    jQuery("#ajaxBstatus2").show();
}
function show_success_pop2(text) {
    jQuery("#ajaxBstatus2").html(text);
    jQuery("#ajaxBstatus2").css('color', 'green');
    jQuery("#ajaxBstatus2").show();
    jQuery("#tripstatusp").val('');
}
function addconsigneedatapop() {
    var consname = jQuery("#consname").val();
    var consemail = jQuery("#consemail").val();
    var consmobile = jQuery("#consmobile").val();
    if (consname == "") {
        show_error_pop1("Please fill mandatory fields");
        return false;
    }
    var data = "consname=" + escape(consname) + "&consemail=" + escape(consemail) + "&consmobile=" + escape(consmobile) + "&action=addconsigeepop";
    jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success_pop1(obj.Msg);
                    jQuery("#consignee").autocomplete({
            source: "trip_ajax.php?action=consigneeauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consigneeid').val(ui.item.id);
            }
        });
            } else {
                show_error_pop1(obj.Error);
            }
        }
    });
    $("#addConsigneeBuble").fadeOut("slow");
}

function get_pdfclosetripReport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDateN(sdate), parseDateN(edate));
    if (sdate == "" || sdate == undefined) {
        alert("Plaese select Start date");
        return false;
    }
     if (edate == "" || edate == undefined) {
        alert("Plaese select End date");
        return false;
    }
    // alert(sdate); alert(edate); return false;
    if (datediff < 0) {
        alert("Uh Oh!! Please select correct dates.");
        return false;
    } else if (datediff <= 30) {
        window.open('pdftest.php?report=closetripreport&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate, '_blank')
    }
}
function html2xlsclosetripReport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var dataString = '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&report=xlsclosereport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff < 0) {
        alert("Uh Oh!! Please select correct dates.");
        return false;
    }
    window.location = "savexls.php?" + dataString;
}
function daydiff(first, second) {
    return (second - first) / (1000 * 60 * 60 * 24)
}
function parseDate(str) {
    var mdy = str.split('-')
    return new Date(mdy[2], mdy[1], mdy[0] - 1);
}
function parseDateN(str) {
    var mdy = str.split('/')
    // alert(mdy[1]-1); return false;
    return new Date(mdy[2], mdy[0], mdy[1] - 1);
}
function getLocationReport(tripid, deviceid, sdate, stime, edate, etime, interval, vehicleno) {
//function getLocationReport(deviceid, mintemp, maxtemp, sdate, stime, edate, etime, interval, vehicleno) {
    //window.open('../reports/reports.php?id=16&deviceid=' + deviceid + '&tripmin=' + mintemp + '&tripmax=' + maxtemp + '&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&interval=' + interval + '&vehicleno=' + vehicleno, '_blank');
    window.open('../reports/reports.php?id=16&tripid=' + tripid + '&deviceid=' + deviceid + '&sdate=' + sdate + '&stime=' + stime + '&edate=' + edate + '&etime=' + etime + '&interval=' + interval + '&vehicleno=' + vehicleno, '_blank');
    /*
     jQuery('#centerDiv').html('');
     jQuery('#pageloaddiv').show();
     //var data = jQuery("#locationreportForm").serialize();
     jQuery.ajax({
     url:"../reports/locationreport_ajax.php",
     type: 'POST',
     data: data,
     success:function(result){
     //window.location = "../reports/reports.php?id=16";
     jQuery("#centerDiv").html(result);
     alert('testdone');
     },
     complete: function(){
     jQuery('#pageloaddiv').hide();
     }
     });
     */
}
function refreshInterval1(time1, type) {
    periodicupdateloadrefresh();
    loadrefresh();
}

function loadrefresh()
{
    clearInterval(interval);
    var ids = "";
    ids     = ids.slice(0, -1);
    var url = "trip_ajax.php";
   var typeArray = {'Available':'Avail','In Transit':'InTrans','Loading':'Load','Empty Return':'emptyR','Waiting for Trip End':'tripEnd','Under Maintenance':'UM'};
   jQuery.ajax({
        type: "POST",
        url: url,
        data : 'action=tripsDashboardData',
        cache: false,
        success: function (data){
            var type  = $("#vehicleType").html();
            var cdata = jQuery.parseJSON(data);
            jQuery.each(cdata, function (i, values) {
                console.log(values);
                if(values != null){ 
                    $("#"+i+"").html(values);    
                }
                else{
                    $("#"+i+"").html("0");
                }   
                
                if(i == "loadingVehiclesCount")
                { 
                    var loadingVehiclesCount = values;
                }
                else if(i == "intransitVehiclesCount")
                {                         
                    var intransitVehiclesCount = values;
                }
                else if(i == "availableVehiclesCount")
                { 
                    var availableVehiclesCount = values;
                }
                else if(i == "availableVehiclesCount")
                { 
                    var availableVehiclesCount = values;
                }
                else if(i == "emptyReturnCount")
                { 
                    var emptyReturnCount = values;
                }
            });

            if(type != null){
                console.log(typeArray[type]);
                vehicleListTable(typeArray[type]);
            }
            getVehicleStatusTable(loadingVehiclesCount,intransitVehiclesCount,emptyReturnCount,totalWaitingForTripEnd,availableVehiclesCount);
            getStoppageTransitCount();
            startTimer(Minutes, display);
            periodicupdateloadrefresh();
        }
    });
}

function periodicupdateloadrefresh()
{
    for (var i = 0; i < timeouts.length; i++) {
        clearTimeout(timeouts[i]);
    }
    console.log(periodictime);
    timeouts.push(setTimeout(function () {
        loadrefresh();
    }, periodictime));
}

function startTimer(duration, display) {
    clearInterval(interval);
    var timer = duration / 1000, minutes, seconds;
    interval = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
//            if (min1 != 0) {
//                timer = min1;
//            }
//            else {
            timer = duration;
//            }
        }
    }, 1000);
}
