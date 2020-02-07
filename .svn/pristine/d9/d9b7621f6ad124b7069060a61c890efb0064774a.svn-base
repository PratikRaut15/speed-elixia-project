var exportPage = 'exportReports.php';
function export_ToggleHistoryReport(customerno, type) {
    var vehicleid = jQuery("#vehicleid").val();
    if(vehicleid == undefined){
        vehicleid = '';
    }
    var vehicleno = jQuery("#vehicleno").val();
    if(vehicleno == undefined){
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
    var dataString = 'report=toggleswitchhistory&type='+type+'&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid
    if(type == 'EMAIL'){
        if (datediff < 0 || datediff == undefined) {
            jQuery('#mailStatus').html('Please Check The Date');
        }
        else if (datediff > 30) {
            jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
        }
        else if (emailid === '') {
            jQuery('#mailStatus').html('Please Enter Email Id');
        }
        else if (!isValidEmailAddress(emailid)) {
            jQuery('#mailStatus').html('Email Id is invalid');
        }
        else if (datediff <= 30) {
            //var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType+'&report=toggleswitchhistory';
            dataString = dataString+'&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
            jQuery.ajax({
                url: exportPage,
                type: 'POST',
                data: dataString,
                success: function (result) {
                    jQuery("#mailStatus").html(result);
                },
            });
        }
    }else{
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
        }
        else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
        }
        else if (datediff <= 30){
            window.open(exportPage+"?"+dataString, '_blank')
        }
    }
}
function export_SummaryReport(customerno, type) {
    var vehicleid = jQuery("#vehicleid").val();
    if(vehicleid == undefined){
        vehicleid = '';
    }
    var vehicleno = jQuery("#vehicleno").val();
    if(vehicleno == undefined){
        vehicleno = '';
    }
    var STdate = jQuery("#SDate").val();
    var EDdate = jQuery("#EDate").val();
    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(STdate), parseDate(EDdate));
    var dataString = 'report=vehicleSummary&type='+type+'&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&STdate=' + STdate + '&EDdate=' + EDdate
    if(type == 'EMAIL'){
        if (datediff < 0 || datediff == undefined) {
            jQuery('#mailStatus').html('Please Check The Date');
        }
        else if (datediff > 30) {
            jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
        }
        else if (emailid === '') {
            jQuery('#mailStatus').html('Please Enter Email Id');
        }
        else if (!isValidEmailAddress(emailid)) {
            jQuery('#mailStatus').html('Email Id is invalid');
        }
        else if (datediff <= 30) {
            //var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType+'&report=toggleswitchhistory';
            dataString = dataString+'&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
            jQuery.ajax({
                url: exportPage,
                type: 'POST',
                data: dataString,
                success: function (result) {
                    jQuery("#mailStatus").html(result);
                },
            });
        }
    }else{
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
        }
        else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
        }
        else if (datediff <= 30){
            window.open(exportPage+"?"+dataString, '_blank')
        }
    }
}

function export_CheckpointReport(customerno, type) {
    var vehicleid = jQuery("#vehicleid").val();
    if(vehicleid == undefined){
        vehicleid = '';
    }
    var vehicleno = jQuery("#vehicleno").val();
    if(vehicleno == undefined){
        vehicleno = '';
    }
    var STdate = jQuery("#SDate").val();
    var EDdate = jQuery("#EDate").val();
    var STime = jQuery("#STime").val();
    var ETime = jQuery("#ETime").val();
    var reportSpecificCondition = jQuery("#routetype").val();
    var chkPtId = 0;
    if(reportSpecificCondition == 2) {
         chkPtId = jQuery("#chkId").val();
    }

    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(STdate), parseDate(EDdate));
    var dataString = 'report=checkpointReport&type='+type+'&vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&STdate=' + STdate + '&EDdate=' + EDdate + '&STime=' + STime + '&ETime=' + ETime + "&reportSpecificCondition="+reportSpecificCondition +"&chkPtId="+chkPtId
    if(type == 'EMAIL'){
        if (datediff < 0 || datediff == undefined) {
            jQuery('#mailStatus').html('Please Check The Date');
        }
        else if (datediff > 30) {
            jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
        }
        else if(reportSpecificCondition == 2 && chkPtId == 0) {
            jQuery('#error8').show();
            jQuery('#error8').fadeOut(3000);
        }
        else if (emailid === '') {
            jQuery('#mailStatus').html('Please Enter Email Id');
        }
        else if (!isValidEmailAddress(emailid)) {
            jQuery('#mailStatus').html('Email Id is invalid');
        }
        else if (datediff <= 30) {
            //var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType+'&report=toggleswitchhistory';
            dataString = dataString+'&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
            jQuery.ajax({
                url: exportPage,
                type: 'POST',
                data: dataString,
                success: function (result) {
                    jQuery("#mailStatus").html(result);
                },
            });
        }
    }else{
        if (datediff < 0) {
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
        }
        else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error3').show();
            jQuery('#error3').fadeOut(3000);
        }
        else if(reportSpecificCondition == 2 && chkPtId == 0) {
            jQuery('#error8').show();
            jQuery('#error8').fadeOut(3000);
        }
        else if (datediff <= 30){
            window.open(exportPage+"?"+dataString, '_blank')
        }
    }
}


function export_InstallDeviceReport(customerno, userid, $reportId, type) {

    var dataString = 'report=installDeviceReport&type='+type+'&customerno='+customerno+'&userid='+userid+"&reportId="+$reportId
    window.open("../reports/"+exportPage+"?"+dataString, '_blank');

}

function export_StoppageAnalysisReport(customerno, userid, type) {

    var vehicleid = jQuery("#vehicleid").val();
    if(vehicleid == undefined){
        vehicleid = '';
    }
    var vehicleno = jQuery("#vehicleno").val();
    if(vehicleno == undefined){
        vehicleno = '';
    }
    var STdate = jQuery("#SDate").val();
    var EDdate = jQuery("#EDate").val();
    var STime = jQuery("#STime").val();
    var ETime = jQuery("#ETime").val();
    var interval = jQuery("#interval").val();
    var groupid = jQuery("#groupid").val();

    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(STdate), parseDate(EDdate));
    var dataString = 'report=stoppageanalysis&type='+type+'&customerno=' + customerno+'&userid=' + userid +'&groupid=' + groupid+ '&STdate=' + STdate + '&EDdate=' + EDdate+ '&STime=' + STime + '&ETime=' + ETime+ '&interval=' + interval
    if(type == 'EMAIL'){
        if (datediff < 0 || datediff == undefined) {
            jQuery('#mailStatus').html('Please Check The Date');
        }
        else if (datediff > 30) {
            jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
        }
        else if (emailid === '') {
            jQuery('#mailStatus').html('Please Enter Email Id');
        }
        else if (!isValidEmailAddress(emailid)) {
            jQuery('#mailStatus').html('Email Id is invalid');
        }
        else if (datediff <= 30) {
            //var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType+'&report=toggleswitchhistory';
            dataString = dataString+'&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
            jQuery.ajax({
                url: exportPage,
                type: 'POST',
                data: dataString,
                success: function (result) {
                    jQuery("#mailStatus").html(result);
                },
            });
        }
    }else{
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
        }
        else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
        }
        else if (datediff <= 30){
            window.open(exportPage+"?"+dataString, '_blank')
        }
    }

}

function export_VehicleInOutReport(customerno, type) {
    var chkptId = jQuery("#chkptId").val();
    var STdate = jQuery("#SDate").val();
    var EDdate = jQuery("#EDate").val();
    var STime = jQuery("#STime").val();
    var ETime = jQuery("#ETime").val();
    var reportName = jQuery("#report").val();

    var emailid = jQuery("#sentoEmail").val();
    var mailcontent = jQuery("#mailcontent").val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    var datediff = daydiff(parseDate(STdate), parseDate(EDdate));
    var dataString = 'report=vehicleInOut&type='+type+'&chkptId=' + chkptId + '&customerno=' + customerno + '&STdate=' + STdate + '&EDdate=' + EDdate + '&STime=' + STime + '&ETime=' + ETime + "&reportName="+reportName ;
    if(type == 'EMAIL'){
        if (datediff < 0 || datediff == undefined) {
            jQuery('#mailStatus').html('Please Check The Date');
        }
        else if (datediff > 30) {
            jQuery('#mailStatus').html('Please Select Dates With Difference Of Not More Than 30 Days');
        }

        else if (emailid === '') {
            jQuery('#mailStatus').html('Please Enter Email Id');
        }
        else if (!isValidEmailAddress(emailid)) {
            jQuery('#mailStatus').html('Email Id is invalid');
        }
        else if (datediff <= 30) {
            //var dataString = 'vehicleid=' + vehicleid + '&vehicleno=' + vehicleno + '&customerno=' + customerno + '&ETime=' + ETime + '&STime=' + STime + '&STdate=' + STdate + '&EDdate=' + EDdate +'&groupid=' + groupid + '&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType+'&report=toggleswitchhistory';
            dataString = dataString+'&emailid=' + emailid + '&mail_content=' + mailcontent + '&mailType=' + mailType;
            jQuery.ajax({
                url: exportPage,
                type: 'POST',
                data: dataString,
                success: function (result) {
                    jQuery("#mailStatus").html(result);
                },
            });
        }
    }else{
        if (datediff < 0) {
            jQuery('#error1').show();
            jQuery('#error1').fadeOut(3000);
        }
        else if (datediff > 30) {
            //alert(datediff);
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000);
        }

        else if (datediff <= 30){
            window.open(exportPage+"?"+dataString, '_blank')
        }
    }
}
