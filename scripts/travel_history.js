// JavaScript Document
var toggle_counter = 2;
var previousid = 0;
var vehicle_validation_msg = "Please Select Vehicle / WareHouse";
var group_validation_msg = "Please Select Group";

function call_row(id) {
    if (jQuery('#rem_' + id).length != 0) {
        jQuery('#rem_' + id).remove();
    } else {
        var vehicleid = jQuery("#vehicle" + id).val();
        var unitno = jQuery("#unitno" + id).val();
        var date = jQuery("#date" + id).val();
        var timestamp = jQuery("#timestamp" + id).val();
        jQuery.ajax({
            type: "POST",
            url: "route_ajax_history.php",
            async: true,
            data: {
                vehicleid: vehicleid,
                date: date,
                timestamp: timestamp,
                unitno: unitno
            },
            cache: false,
            success: function(data) {
                jQuery('#rem_' + id).remove();
                jQuery("#" + id).after("<tr id='rem_" + id + "'><td  colspan='100%'>" + data + "</td></tr>");
            }
        });
    }
}
/*Calendar.setup(
 {
 inputField : "SDate", // ID of the input field
 ifFormat : "%d-%m-%Y", // the date format
 button : "Strigger" // ID of the button
 });
 Calendar.setup(
 {
 inputField : "EDate", // ID of the input field
 ifFormat : "%d-%m-%Y", // the date format
 button : "Etrigger" // ID of the button
 });
 Calendar.setup(
 {
 inputField : "STdate", // ID of the input field
 ifFormat : "%d-%m-%Y", // the date format
 button : "SDate" // ID of the button
 });
 Calendar.setup(
 {
 inputField : "EDdate", // ID of the input field
 ifFormat : "%d-%m-%Y", // the date format
 button : "EDate" // ID of the button
 });*/
jQuery('#report').change(function() {
    if (this.value == 'Temperature') {
        jQuery('.tr').show();
        jQuery('.td').hide();
        jQuery('#EDate').hide();
        jQuery('.time').hide();
        //jQuery('#speed_limit').hide();
    } else if (this.value == 'TemperatureDaily') {
        jQuery('.td').hide();
        jQuery('.tr').hide();
        jQuery('#EDate').show();
        jQuery('.time').hide();
        //jQuery('#speed_limit').hide();
    } else if (this.value == 'Overspeed') {
        jQuery('.tr').hide();
        jQuery('.td').show();
        jQuery('#EDate').show();
        jQuery('.time').hide();
        jQuery('#speed_limit').show();
    } else {
        jQuery('.tr').hide();
        jQuery('.td').show();
        jQuery('#EDate').show();
        jQuery('.time').show();
        //jQuery('#speed_limit').hide();
    }
});
jQuery('#alerttype').change(function() {
    if (this.value == '2') {
        jQuery('#chkid').show();
        jQuery('#fenceid').hide();
    } else if (this.value == '3') {
        jQuery('#fenceid').show();
        jQuery('#chkid').hide();
    } else {
        jQuery('#chkid').hide();
        jQuery('#fenceid').hide();
    }
});
//function alertchange(){
//    var alerttype = jQuery("#alerttype").val();
//    if(alerttype == '2')
//        {
//        jQuery('#chkid').show();
//        jQuery('#fenceid').hide();
//        }
//        else if(alerttype == '3'){
//        jQuery('#fenceid').show();
//        jQuery('#chkid').hide();
//        }
//        else {
//        jQuery('#chkid').hide();
//        jQuery('#fenceid').hide();
//        }
//}
function parseDate(str) {
    var mdy = str.split('-');
    return new Date(mdy[2], mdy[1] - 1, mdy[0]);
}

function daydiff(first, second) {
    return ((second - first) / (1000 * 60 * 60 * 24));
}

function get_pdfreport() {
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var geocode = jQuery("#geocode").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=travelhist&vehicleid=' + vehicleid + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&geocode=' + geocode + '&vehicleno=' + vehicleno, '_blank')
}

function get_analyticpdfreport(customerno, userid, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var userid = jQuery("#userid").val();
    var customerno = jQuery("#customerno").val();
    window.open('pdftest.php?report=analytic&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno + "&userid=" + userid + "&groupid=" + groupid, '_blank');
}

function get_inactivepdfreport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=inactive&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function get_inactivexlsreport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('savexls.php?report=inactive&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function get_pdfreportGenset(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var geocode = jQuery("#geocode").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=genset&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}
/*Genset summary report */
function get_pdfreportGenset2(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var geocode = jQuery("#geocode").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=gensetsummary&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}
/* Genset pdf report details -ganesh*/
function get_pdfreportGenset1(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var gensetSensor = jQuery("#gensetSensor").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var geocode = jQuery("#geocode").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=gensetdetails&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&gensetSensor=' + gensetSensor, '_blank')
}

function get_pdfreportExtra(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var geocode = jQuery("#geocode").val();
    var extraget = jQuery("#extra").val();
    var extra = extraget.split("|");
    var extraid = extra[0];
    var extraval = extra[1];
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=extra&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&extraid=' + extraid + '&extraval=' + extraval, '_blank')
}

function get_pdfreportDoor() {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=doorHist&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function get_pdfreportRouteSmry() {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 31) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 31) window.open('pdftest.php?report=routeSmry&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function get_pdfreportRouteTatSmry() {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var grpid = jQuery("#groupid").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 31) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 31) window.open('pdftest.php?report=routeTatSmry&sdate=' + sdate + '&edate=' + edate + '&grpid=' + grpid, '_blank')
}

function get_pdfreportTemp(customerno, switchto) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var customMinTemp = jQuery("#customMinTemp").val();
    var customMaxTemp = jQuery("#customMaxTemp").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=temperature&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&switchto=' + switchto + '&tempselect=' + tempselect + '&customMinTemp=' + customMinTemp + '&customMaxTemp=' + customMaxTemp, '_blank')
}

function get_pdfreportHumidity(customerno, switchto) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=humidity&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&switchto=' + switchto, '_blank')
}

function get_pdfreportTempExcep(customerno, switchto) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var tempselect = jQuery("#tempsel").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=tempexception&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&tempselect=' + tempselect + '&switchto=' + switchto, '_blank')
}

function get_pdfFuelConsumption_new() {
    var vehicleid = jQuery("#vehicleid").val();
    var vno = jQuery('#vehicleno').val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=FuelConsumptionNew&vehicleid=' + vehicleid + '&vehicleno=' + vno + '&EDdate=' + edate + '&ETime=' + etime + '&STdate=' + sdate + '&STime=' + stime + '&interval=' + interval, '_blank');
    }
}

function html2xlsFuelConsumption_new() {
    var vehicleid = jQuery("#vehicleid").val();
    var vno = jQuery('#vehicleno').val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else {
        window.open('savexls.php?report=FuelConsumptionNew&vehicleid=' + vehicleid + '&vehicleno=' + vno + '&EDdate=' + edate + '&ETime=' + etime + '&STdate=' + sdate + '&STime=' + stime + '&interval=' + interval, '_blank');
    }
}

function generatereport() {
    if (jQuery("#routeid").val() == "") {
        jQuery("#error4").show();
        jQuery("#error4").fadeOut(3000);
    } else {
        var data = jQuery('#routerepform').serialize();
        jQuery.ajax({
            type: "POST",
            url: "route_report_ajax.php",
            data: data,
            cache: false,
            success: function(html) {
                jQuery("#routereportdiv").html(html);
                //route_report_hist($routeid, $startdate, $enddate);
            }
        });
    }
}

function generatetripreport() {
    if (jQuery("#routeid").val() == "") {
        jQuery("#error4").show();
        jQuery("#error4").fadeOut(3000);
    } else {
        var data = jQuery('#routerepform').serialize();
        jQuery.ajax({
            type: "POST",
            url: "route_report_ajax.php",
            data: data,
            cache: false,
            success: function(html) {
                jQuery("#routereportdiv").html(html);
                //route_report_hist($routeid, $startdate, $enddate);
            }
        });
    }
}

function html2xls(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&report=travelhist';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsRouteSmry() {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var dataString = 'sdate=' + sdate + '&edate=' + edate + '&report=routeSmry';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 31) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 31) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsRouteTatSmry() {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var grpid = jQuery("#groupid").val();
    var routelist = jQuery("#routelist").val();
    var dataString = 'sdate=' + sdate + '&edate=' + edate + "&routelist=" + routelist + "&grpid=" + grpid + '&report=routeTatSmry';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 31) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 31) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xls_analyticreport(customerno, userid, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var dataString = 'customerno=' + customerno + '&edate=' + edate + '&sdate=' + sdate + '&userid=' + userid + '&groupid=' + groupid + '&report=analytic';
    window.location = "savexls.php?" + dataString;
}

function html2xlsgenset(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=genset';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}
/*Genset excel details report*/
function html2xlsgenset1(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var gensetSensor = jQuery("#gensetSensor").val();
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&gensetSensor=' + gensetSensor + '&report=gensetdetails';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}
/*Genset excel summary report*/
function html2xlsgenset2(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=gensetsummary';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsextra(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var extraget = jQuery("#extra").val();
    var extra = extraget.split("|");
    var extraid = extra[0];
    var extraval = extra[1];
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&extraid=' + extraid + '&extraval=' + extraval + '&report=extra';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsDoor() {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var dataString = '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=doorHist';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid === '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsTemp(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vno = jQuery("#vno").val();
    var vno1 = jQuery("#vehicleno").val();
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    var vehicleno = '';
    if (vno1 == '') {
        vehicleno = vno;
    } else {
        vehicleno = vno1;
    }
    var interval = jQuery("#interval").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&switchto=' + switchto + '&report=temperature' + '&tempselect=' + tempselect;
    //alert(vehicleno);exit;
    // alert(dataString); return false;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsHumidity(customerno, switchto) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var interval = jQuery("#interval").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&switchto=' + switchto + '&report=humidity';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsTempHumidity(customerno, switchto) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var interval = jQuery("#interval").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&switchto=' + switchto + '&report=temphumidity';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function html2xlsTempExcep(customerno, switchto) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var interval = jQuery("#interval").val();
    var tempselect = jQuery("#tempsel").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&tempselect=' + tempselect + '&switchto=' + switchto + '&report=tempException';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function replaceimg(selid) {
    //    jQuery('#iv_' + selid).attr("src", "../../images/check.png");
}

function selecttype() {
    if (jQuery('#frequency').val() == '1') {
        jQuery('#intervalid').show();
        jQuery('#distanceid').hide();
    }
    if (jQuery('#frequency').val() == '2') {
        jQuery('#intervalid').hide();
        jQuery('#distanceid').show();
    }
}

function get_pdfreportLocation(customerno, userid) {
    tripid = '';
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var tripid = jQuery("#tripid").val();
    var triplogno = jQuery("#triplogno").val();
    var userkey = jQuery("#userkey").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (jQuery("#frequency").val() == '1') {
        var interval = jQuery("#interval").val();
    } else {
        if (jQuery("#frequency").val() == '2') {
            var distance = jQuery("#distance").val();
        }
    }
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=location&triplogno=' + triplogno + '&userkey=' + userkey + '&tripid=' + tripid + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&distance=' + distance + '&userid=' + userid, '_blank')
}

function html2xlslocation(customerno, userid) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var tripid = jQuery("#tripid").val();
    var triplogno = jQuery("#triplogno").val();
    var userkey = jQuery("#userkey").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (jQuery("#frequency").val() == '1') {
        var interval = jQuery("#interval").val();
    } else {
        if (jQuery("#frequency").val() == '2') {
            var distance = jQuery("#distance").val();
        }
    }
    //var vehicleno = jQuery( "#"+vehicleid ).attr('rel');
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&userkey=' + userkey + '&triplogno=' + triplogno + '&tripid=' + tripid + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&interval=' + interval + '&distance=' + distance + '&userid=' + userid + '&report=location';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function get_pdfreportStoppage(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=stoppage&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval, '_blank')
}

function standardized_print(printTitle) {
    var data = jQuery('#centerDiv').html();
    var printStyle = '<style type="text/css" >.newTableSubHeader {float: left;width: 50%;}.newTableSubHeaderLeft {float: left;padding: 0 0 5px 2%;text-align: left;width: 55%;}.newTableSubHeader {float: left;width: 50%;}.newTableSubHeaderRight {float: right;padding: 0 2% 5px 0;text-align: left;width: 50%;word-wrap: break-word;}.table {margin-bottom: 18px;width: 100%;font-family: Arial,Helvetica,sans-serif;font-size: 14px;}.table th {background: none repeat scroll 0 0 #cccccc;color: #3b3b3b;}newTable, .newTable th, .newTable td {border: 1px solid;}table {background-color: transparent;border-collapse: collapse;border-spacing: 0;max-width: 100%;}</style>';
    var mywindow = window.open('', 'Print', '');
    mywindow.document.write('<html><head><title>' + printTitle + '</title>');
    mywindow.document.write(printStyle);
    mywindow.document.write('</head><body><center>');
    mywindow.document.write(data);
    mywindow.document.write('</center></body></html>');
    mywindow.print();
    mywindow.close();
}

function get_stoppage_print(printTitle) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(printTitle);
    }
}

function get_speed_print(printTitle) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(printTitle);
    }
}

function get_temp_print(printTitle) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(printTitle);
    }
}

function get_temp_humidity_print(printTitle) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(printTitle);
    }
}

function get_acsensor_print(title) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(title);
    }
}

function get_extra_print(title) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(title);
    }
}

function isValidEmailAddress(emailAddress) {
    emailAddress = emailAddress.replace(/[\s,\s]+/g, ',');
    var arrayToEmailIds = emailAddress.split(",");
    arrayToEmailIds = arrayToEmailIds.filter(function(emailId) {
        return emailId !== '';
    });
    var isValid = true;
    for (i = 0; i < arrayToEmailIds.length; i++) {
        var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
        isValid = isValid && pattern.test(arrayToEmailIds[i]);
    }
    return isValid;
}

function html2xlsstoppage(customerno) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var vehicleno = jQuery("#vehicleno").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&interval=' + interval + '&report=stoppage';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function get_pdfreportOverspeed(customerno) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=overspeed&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsoverspeed(customerno) {
    //alert(dataa);
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleno = jQuery("#vehicleno").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&report=overspeed';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}
//html to pdf for Alert History
function get_pdfreportAlerthist(customerno, switchto) {
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleid option:selected").html();
    //var start=jQuery("#s_start").val();
    //var end=jQuery("#e_end").val();
    var alerttype = jQuery("#alerttype").val();
    var alertmode = jQuery("#alerttype option:selected").html();
    var chkpointid = jQuery("#chkid").val();
    var fenceid = jQuery("#fenceid").val();
    var sdate = jQuery("#SDate").val();
    //alert(alerttype);
    //var datediff = daydiff(parseDate(sdate), parseDate(end));
    /*
     *
     if(vehicleid == ''){
     jQuery('#error4').show();jQuery('#error4').fadeOut(3000);
     }
     *
     */
    if (sdate == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=alerthist&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&alerttype=' + alerttype + '&sdate=' + sdate + '&chkid=' + chkpointid + '&fenceid=' + fenceid + '&alertmode=' + alertmode + '&switchto=' + switchto, '_blank')
    }
}
// html to xls for alert history
function html2xlalerthist(customerno, switchto) {
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleid option:selected").html();
    var alerttype = jQuery("#alerttype").val();
    var alertmode = jQuery("#alerttype option:selected").html();
    var chkpointid = jQuery("#chkid").val();
    var fenceid = jQuery("#fenceid").val();
    var sdate = jQuery("#SDate").val();
    //alert(alerttype);
    var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&alerttype=' + alerttype + '&sdate=' + sdate + '&chkid=' + chkpointid + '&fenceid=' + fenceid + '&alertmode=' + alertmode + '&switchto=' + switchto + '&report=alerthistory';
    if (sdate == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else {
        window.location = "savexls.php?" + dataString;
    }
}
/*dt: 28th oct 2014, ak added, for all vehicle report*/
function get_pdfreportchk_all(customerno, temp_sensors) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error3').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=checkpointAll&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&temp_sensors=' + temp_sensors, '_blank')
}

function html2xlschk_all(customerno, temp_sensors) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var dataString = 'customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&temp_sensors=' + temp_sensors + '&report=checkpointAll';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}
/**/
function get_pdfFuelConsumption(customerno, use_maintainance, use_hierarchy, groupid) {
    var vehicleid = jQuery("#vehicleid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if (sdate == edate) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=FuelConsumption&vehicleid=' + vehicleid + '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsFuelConsumption(customerno, use_maintainance, use_hierarchy, groupid) {
    //alert(dataa);
    var vehicleid = jQuery("#vehicleid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    //var data = "sdate="+sdate;
    var dataString = 'vehicleid=' + vehicleid + '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=FuelConsumption';
    //alert(vehicleno);exit;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if (sdate == edate) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function get_pdfDistanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var vehicleid = jQuery("#vehicleid").val();
    //alert(vehicleid); exit;
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //console.log(edate);
    if (vehicleid == '') {
        //alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=DistanceReport&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function get_pdfVehicleDistanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    //alert(vehicleno); return false;
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(datediff); return false;
    //console.log(edate);
    if (vehicleid == '') {
        //alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=VehicleDistanceReport&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&edate=' + edate, '_blank')
}

function html2xlsDistanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=DistanceReport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}
/* Single Vehicle report in Excel */
function html2xlsVehicleDistanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    //alert("hello"); return false;
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&report=VehicleDistanceReport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '' || vehicleid == undefined) {
        // alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30)
        /*console.log(dataString);
         alert(dataString); return false;*/
        window.location = "savexls.php?" + dataString;
}

function get_pdfIdeltimeReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=IdeltimeReport&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsIdeltimeReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=IdeltimeReport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfGensetReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=GensetReport&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsGensetReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=GensetReport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfOverspeed_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=Overspeed_Report&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsOverspeed_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=Overspeed_Report';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfFence_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=Fence_Report&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsFence_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=Fence_Report';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfLocation_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=Location_Report&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsLocation_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=Location_Report';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfFuel_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=Fuel_Report&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsFuel_Report(customerno, use_maintainance, use_hierarchy, groupid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&report=Fuel_Report';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.location = "savexls.php?" + dataString;
}

function get_pdfTrip_Report(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#vehicleid").val();
    var chktype = jQuery("#chktype").val();
    if (chktype == '1') {
        var chkpt_start = jQuery("#checkpoint_start").val();
        var chkpt_end = jQuery("#checkpoint_end").val();
        var chkpt_via = jQuery("#checkpoint_via").val();
    } else if (chktype == '2') {
        var chkpt_start = jQuery("#checkpointtype_start").val();
        var chkpt_end = jQuery("#checkpointtype_end").val();
        var chkpt_via = jQuery("#checkpointtype_via").val();
    }
    var report_type = jQuery("#report").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) {
        window.open('pdftest.php?report=Trip_Report&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&chk_start=' + chkpt_start + '&chk_end=' + chkpt_end + '&chk_via=' + chkpt_via + '&reporttype=' + report_type + '&chktype=' + chktype + "&vehicleid=" + vehicleid, '_blank');
    }
}

function html2xlsTrip_Report(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#vehicleid").val();
    var chktype = jQuery("#chktype").val();
    if (chktype == '2') {
        var chkpt_start = jQuery("#checkpointtype_start").val();
        var chkpt_end = jQuery("#checkpointtype_end").val();
        var chkpt_via = jQuery("#checkpointtype_via").val();
    } else {
        var chkpt_start = jQuery("#checkpoint_start").val();
        var chkpt_end = jQuery("#checkpoint_end").val();
        var chkpt_via = jQuery("#checkpoint_via").val();
    }
    var report_type = jQuery("#report").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&chk_start=' + chkpt_start + '&chk_end=' + chkpt_end + '&chk_via=' + chkpt_via + '&reporttype=' + report_type + '&report=Trip_Report' + '&chktype=' + chktype + '&vehicleid=' + vehicleid;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function trans_hist_maintenance() {
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var category = jQuery("#categoryid").val();
    var statusid = jQuery("#statusid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('savexls.php?report=transHistMaintenance&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer + '&category=' + category + '&status=' + statusid, '_blank');
    }
}

function trans_hist_maintenance_pdf() {
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var category = jQuery("#categoryid").val();
    var statusid = jQuery("#statusid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var customerno = jQuery("#customerno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=transHistMaintenancePdf&customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer + '&category=' + category + '&status=' + statusid, '_blank');
    }
}

function trans_hist_maintenance_mail(customerno) {
    var vehicleno = jQuery("#vehicleno").val();
    var vehicleid = jQuery("#vehicleid").val();
    var dealer = jQuery("#dealerid").val();
    var category = jQuery("#categoryid").val();
    var statusid = jQuery("#statusid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        var dataString = 'report=transHistMaintenanceMail&customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehno=' + vehicleno + '&EDdate=' + edate + '&STdate=' + sdate + '&dealer=' + dealer + '&category=' + category + '&status=' + statusid + '&customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            }
        });
    }
}

function get_pdfreportTempHumidity(customerno, switchto) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.open('pdftest.php?report=temphumidity&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&switchto=' + switchto, '_blank');
    }
}

function send_travelHist_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var geocode = jQuery("#geocode").val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&geocode=' + geocode + '&report=travelHistMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_gensetHist_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html('');
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=gensetHistMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_gensetHistdetails_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&report=gensetHistDetailMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_gensetHistsummary_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=gensetHistSummaryMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_doorHist_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=doorHistMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_temp_mail(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&switchto=' + switchto + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&interval=' + interval + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&report=tempMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}
// email sent to in pdf and excel for temperature and humidity
function send_tempandhumidity_mail(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&interval=' + interval + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&switchto' + switchto + '&report=tempHumidityMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}
// sent email to humidity
function send_humidity_mail(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&interval=' + interval + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&switchto' + switchto + '&report=HumidityMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_tempExcep_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var tempselect = jQuery("#tempsel").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&interval=' + interval + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&tempselect=' + tempselect + '&report=tempExcepMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_stoppage_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&interval=' + interval + '&report=stoppageMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_overspeed_mail(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (vehicleid === '' || vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&etime=' + etime + '&stime=' + stime + '&interval=' + interval + '&report=overspeedMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function send_location_historydetails_mail(customerno) {
    var tripid = jQuery("#tripid").val();
    var triplogno = jQuery("#triplogno").val();
    var userkey = jQuery("#userkey").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var frequency = jQuery("#frequency").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (jQuery("#frequency").val() == '1') {
        var interval = jQuery("#interval").val();
    } else {
        if (jQuery("#frequency").val() == '2') {
            var distance = jQuery("#distance").val();
        }
    }
    if (vehicleno === '') {
        jQuery('#mailStatus').html(vehicle_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'tripid=' + tripid + '&triplogno=' + triplogno + '&customerno=' + customerno + '&stime=' + stime + '&etime=' + etime + '&frequancy=' + frequency + '&interval=' + interval + '&distance=' + distance + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&report=getLocationMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function get_pdfreportLoginhistory(customerno, userid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var customerno = jQuery("#customerno").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('../reports/pdftest.php?report=loginhistory&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate, '_blank')
}

function html2xlsloginhistory(customerno, userid) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var customerno = jQuery("#customerno").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&report=loginhistory';
        window.location = "../reports/savexls.php?" + dataString;
    }
}

function send_ToggleHistory_Mail(customerno) {
    var vehicleid = jQuery("#vehicleid").val();
    if (vehicleid == undefined) {
        vehicleid = '';
    }
    var vehicleno = jQuery("#vehicleno").val();
    if (vehicleno == undefined) {
        vehicleno = '';
    }
    var STdate = jQuery("#SDate").val();
    var EDdate = jQuery("#EDate").val();
    var STime = jQuery("#STime").val();
    var ETime = jQuery("#ETime").val();
    var groupid = jQuery("#groupid").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(STdate), parseDate(EDdate));
    if (datediff < 0 || datediff == undefined) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate + '&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&report=toggleswitchhistory';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}

function get_pdfreportTemp_nestle(customerno, switchto) {
    var vehicleid = jQuery("#deviceid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=temperature_nestle&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&switchto=' + switchto + '&tempselect=' + tempselect, '_blank')
}

function html2xlsTemp_nestle(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#deviceid").val();
    var vno = jQuery("#vno").val();
    var vno1 = jQuery("#vehicleno").val();
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    var vehicleno = '';
    if (vno1 == '') {
        vehicleno = vno;
    } else {
        vehicleno = vno1;
    }
    var interval = jQuery("#interval").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&switchto=' + switchto + '&report=temperature_nestle' + '&tempselect=' + tempselect;
    //alert(vehicleno);exit;
    // alert(dataString); return false;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}

function getSmsPdfReport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=smsStore&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function getSmsXlsReport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('savexls.php?report=smsStore&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function get_inactive_vehiclepdfreport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('pdftest.php?report=inactiveVehicle&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function get_inactive_vehiclexlsreport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    if (sdate == '') {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (edate == '') {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else {
        window.open('savexls.php?report=inactiveVehicle&sdate=' + sdate + '&edate=' + edate + "&customerno=" + customerno, '_blank');
    }
}

function getPowerStatusPDFReport() {
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=powerStatus&vehicleid=' + vehicleid + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&vehicleno=' + vehicleno, '_blank')
}

function getPowerStatusExcelReport(customerno) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var vehicleid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    var dataString = 'customerno=' + customerno + '&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&report=travelhist';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.open('savexls.php?report=powerStatus&vehicleid=' + vehicleid + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&vehicleno=' + vehicleno + '&customerno=' + customerno, '_blank')
    }
}

function getDoorSensorPDFReport(customerno) {
    var deviceid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var doorsensor = jQuery("#doorsensor").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.open('pdftest.php?report=doorSensor&vehicleno=' + vehicleno + '&deviceid=' + deviceid + '&sdate=' + sdate + '&edate=' + edate + '&doorsensor=' + doorsensor, '_blank')
    }
}

function getDoorSensorExcelReport(customerno) {
    var deviceid = jQuery("#vehicleid").val();
    var vehicleno = jQuery("#vehicleno").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var doorsensor = jQuery("#doorsensor").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    var calculateddate = jQuery("#calculateddate").val();
    if (isVariableDefined(calculateddate) && calculateddate != '' && (parseDate(sdate) < parseDate(calculateddate))) {
        sdate = calculateddate;
    }
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.open('savexls.php?report=doorSensor&vehicleno=' + vehicleno + '&deviceid=' + deviceid + '&sdate=' + sdate + '&edate=' + edate + '&doorsensor=' + doorsensor, '_blank')
    }
}

function html2xlsDriverPerformanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    //alert("hello"); return false;
    var driverid = jQuery("#driverid").val();
    var drivername = jQuery("#drivername").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var dataString = '&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&edate=' + edate + '&driverid=' + driverid + '&drivername=' + drivername + '&report=DriverPerformanceReport';
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (driverid == '' || driverid == undefined) {
        // alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30)
        /*console.log(dataString);
         alert(dataString); return false;*/
        window.location = "savexls.php?" + dataString;
}

function get_pdfDriverPerformanceReport(customerno, use_maintainance, use_hierarchy, groupid) {
    var driverid = jQuery("#driverid").val();
    var drivername = jQuery("#drivername").val();
    //alert(vehicleno); return false;
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var s_start = jQuery("#s_start").val();
    var e_end = jQuery("#e_end").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(datediff); return false;
    //console.log(edate);
    if (driverid == '') {
        //alert(vehicleid);
        jQuery('#error8').show();
        jQuery('#error8').fadeOut(5000);
        return false;
    }
    if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error4').show();
        jQuery('#error4').fadeOut(3000);
    } else if ((s_start != undefined && e_end != undefined) && (parseDate(sdate) < parseDate(s_start) || parseDate(edate) > parseDate(e_end))) {
        jQuery('#error6').show();
        jQuery('#error6').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=DriverPerformanceReport&customerno=' + customerno + '&use_maintainance=' + use_maintainance + '&use_heirarchy=' + use_hierarchy + '&groupid=' + groupid + '&sdate=' + sdate + '&driverid=' + driverid + '&drivername=' + drivername + '&edate=' + edate, '_blank')
}

function get_pdfreportGroupwiseTemp(customerno, switchto) {
    var groupid = jQuery("#groupid").val();
    var groupname = jQuery("#groupname").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var customMinTemp = jQuery("#customMinTemp").val();
    var customMaxTemp = jQuery("#customMaxTemp").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (groupid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) window.open('pdftest.php?report=temperature&groupid=' + groupid + '&group_name='+groupname+'&customerno=' + customerno + '&etime=' + etime + '&stime=' + stime + '&sdate=' + sdate + '&edate=' + edate + '&interval=' + interval + '&switchto=' + switchto + '&tempselect=' + tempselect + '&customMinTemp=' + customMinTemp + '&customMaxTemp=' + customMaxTemp, '_blank')
}


function html2xlsGroupwiseTemp(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var groupid = jQuery("#groupid").val();
    var groupname = jQuery("#groupname").val();
    var vno = jQuery("#vno").val();
    var vno1 = jQuery("#vehicleno").val();
    /*if(switchto==3){
     var tempselect = "4-all";
     }else */
    if (jQuery("#tempsel").val() != undefined) {
        var tempselect = jQuery("#tempsel").val();
    } else {
        var tempselect = "null";
    }
    
    var interval = jQuery("#interval").val();
    //var data = "sdate="+sdate;
    var dataString = 'customerno=' + customerno + '&groupid=' + groupid + '&groupname=' + groupname + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&interval=' + interval + '&switchto=' + switchto + '&report=temperature' + '&tempselect=' + tempselect;
    //alert(vehicleno);exit;
    // alert(dataString); return false;
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    //alert(daydiff(parseDate(sdate), parseDate(edate)));
    if (groupid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        //alert(datediff);
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        window.location = "savexls.php?" + dataString;
    }
}
function send_groupwise_temp_mail(customerno, switchto) {
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var stime = jQuery("#STime").val();
    var etime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var groupid = jQuery("#groupid").val();
    var groupname = jQuery("#groupname").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    if (groupid === '' || groupname === '') {
        jQuery('#mailStatus').html(group_validation_msg);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#mailStatus').html('Please Check The Date');
    } else if (datediff > 30) {
        jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
    } else if (emailid === '') {
        jQuery('#mailStatus').html('Please Enter Email Id');
    } else if (!isValidEmailAddress(emailid)) {
        jQuery('#mailStatus').html('Email Id is invalid');
    } else if (datediff <= 30) {
        var dataString = 'customerno=' + customerno + '&switchto=' + switchto + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType + '&groupid=' + groupid + '&groupname=' + groupname + '&interval=' + interval + '&sdate=' + sdate + '&edate=' + edate + '&stime=' + stime + '&etime=' + etime + '&report=tempMail';
        jQuery.ajax({
            url: "report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success: function(result) {
                jQuery("#mailStatus").html(result);
            },
        });
    }
}
function get_groupwise_temp_print(printTitle) {
    var vehicleid = jQuery("#deviceid").val();
    var sdate = jQuery("#SDate").val();
    var edate = jQuery("#EDate").val();
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if (vehicleid == '') {
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000);
    } else if ((sdate == undefined || sdate == '') || (edate == undefined || edate == '') || (datediff < 0)) {
        jQuery('#error1').show();
        jQuery('#error1').fadeOut(3000);
    } else if (datediff > 30) {
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000);
    } else if (datediff <= 30) {
        standardized_print(printTitle);
    }
}
// JavaScript Document
