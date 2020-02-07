// JavaScript Document
var toggle_counter = 2;
var previousid = 0;

function call_row(id) {
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
		success: function (data) {
			jQuery('#rem_' + id).remove();
			jQuery("#" + id).after("<tr id='rem_" + id + "'><td  colspan='100%'>" + data + "</td></tr>");
		}
	});
}
Calendar.setup(
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
});
jQuery('#report').change(function(){
    if(this.value == 'Temperature')
    {
    jQuery('.tr').show();
    jQuery('.td').hide();
    }
    else if(this.value == 'TemperatureDaily'){
    jQuery('.td').hide();  
    jQuery('.tr').hide();      
    }
    else {
    jQuery('.tr').hide();
    jQuery('.td').show();
    }

});

function get_pdfreport(){
var vehicleid=jQuery("#vehicleid").val();
var sdate=jQuery("#SDate").val();
var edate=jQuery("#EDate").val();
var geocode=jQuery("#geocode").val();
if(vehicleid == ''){
    jQuery('#error3').show();jQuery('#error3').fadeOut(3000);
}
else if(sdate>edate){
    jQuery('#error').show();jQuery('#error').fadeOut(3000);
}
else
window.open('pdftest.php?vehicleid='+vehicleid+'&sdate='+sdate+'&edate='+edate+'&geocode='+geocode,'_blank')
}

function generatereport(){
		if(jQuery("#routeid").val() == "")
		{
			jQuery("#error4").show();
			jQuery("#error4").fadeOut(3000);                 
		}
                else
                    {
                        var data = jQuery('#routerepform').serialize();
                        jQuery.ajax({
                                        type: "POST",
                                        url: "route_report_ajax.php",
                                        data: data,
                                        cache: false,
                                        success: function(html)
                                        { 
                                        jQuery("#routereportdiv").html(html);
                                        //route_report_hist($routeid, $startdate, $enddate);
                                        }
                                    });
                                
                    }
	} 

function generatetripreport(){
		if(jQuery("#routeid").val() == "")
		{
			jQuery("#error4").show();
			jQuery("#error4").fadeOut(3000);                 
		}
                else
                    {
                        var data = jQuery('#routerepform').serialize();
                        jQuery.ajax({
                                        type: "POST",
                                        url: "route_report_ajax.php",
                                        data: data,
                                        cache: false,
                                        success: function(html)
                                        { 
                                        jQuery("#routereportdiv").html(html);
                                        //route_report_hist($routeid, $startdate, $enddate);
                                        }
                                    });
                                
                    }
	} 
// JavaScript Document