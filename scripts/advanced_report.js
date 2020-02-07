function daydiff(first, second) {
    return (second-first)/(1000*60*60*24)
}
function parseDate(str) {
    var mdy = str.split('-')
    return new Date(mdy[2], mdy[1], mdy[0]-1);
}
function validate_advanced_inputs(sdate, edate){
    var datediff = daydiff(parseDate(sdate), parseDate(edate));
    if(datediff < 0){
        jQuery('#error1').show();jQuery('#error1').fadeOut(3000);return false;
    }
    else if(datediff > 30){
        jQuery('#error2').show();jQuery('#error2').fadeOut(3000);return false;
    }
    else if(datediff <= 30){
        return true;
    }
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
    return pattern.test(emailAddress);
}
function get_advanced_pdfreport(report){
    var sdate=jQuery("#SDate").val();
    var edate=jQuery("#EDate").val();
    
    if(validate_advanced_inputs(sdate, edate)){
        window.open('pdftest.php?report='+report+'&sdate='+sdate+'&edate='+edate,'_blank')
    }
}
function get_advanced_xlsreport(report){
    var sdate=jQuery("#SDate").val();
    var edate=jQuery("#EDate").val();
    
    if(validate_advanced_inputs(sdate, edate)){
        window.location='savexls.php?report='+report+'&sdate='+sdate+'&edate='+edate,'_blank';
    }
}
function get_advanced_print(report){
    var sdate=jQuery("#SDate").val();
    var edate=jQuery("#EDate").val();
    
    if(validate_advanced_inputs(sdate, edate)){
        window.print();
    }
}

function send_advanced_mail(customerno,reporttype){
    var sdate = jQuery( "#SDate" ).val();
    var edate = jQuery( "#EDate" ).val();
    var emailid = jQuery( "#sentoEmail" ).val();
    var mailType = jQuery('input[name=emailtype]:checked').val();
    
    if(validate_advanced_inputs(sdate, edate)){
        if(emailid === ''){ jQuery('#mailStatus').html('Please Enter Email Id'); return false;}
        else if(!isValidEmailAddress(emailid)){ jQuery('#mailStatus').html('Email Id is invalid'); return false;}
    
        var dataString = 'customerno='+customerno+'&emailid='+emailid+'&mailType='+mailType+'&sdate=' + sdate + '&edate=' + edate + '&report='+reporttype;
        jQuery.ajax({
            url:"report_mail_ajax.php",
            type: 'POST',
            data: dataString,
            success:function(result){
                jQuery("#mailStatus").html(result);
            },
        });
    }
}
