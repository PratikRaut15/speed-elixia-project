function addvehicle(vehicleid,selected_name) {
//	var vehicleid = jQuery('#vehicleid').val();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
//		var selected_name = jQuery('#vehicleid option:selected').text();

		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery('#vehicle_list').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery("#vehicleno").val('');
}

function removeVehicle(checkpoint_no) {
	jQuery('#to_vehicle_div_' + checkpoint_no).remove();
}

function addallvehicle() {

	jQuery("#vehicleroute option").each(function (index, element) {
            addvehicle(jQuery(element).val(),jQuery(element).text());
    });



}
function parseDate(str) {
    var mdy = str.split('-')
    return new Date(mdy[2], mdy[1] - 1, mdy[0]);
}
function daydiff(first, second) {
    return (second-first)/(1000*60*60*24)
}

function chksubmit()
{
    var startdate = jQuery("#SDate").val();
    var enddate = jQuery("#EDate").val();
    var STime = jQuery('#STime').val();
    var ETime = jQuery('#ETime').val();
    var days = jQuery('#days').val();

    var dateAr1 = startdate.split('-');
    var dateAr2 = enddate.split('-');
    var stDate  = dateAr1[2] + '-' + dateAr1[1] + '-' + dateAr1[0].slice(-2);
    var edDate  = dateAr2[2] + '-' + dateAr2[1] + '-' + dateAr2[0].slice(-2);

    var datetimestart   = stDate + " " + STime + ":" + "00";
    var datetimeend     = edDate + " " + ETime + ":" + "00";


    //alert(datetimestart +"---"+ datetimeend);
    var datediff = daydiff(parseDate(stDate), parseDate(edDate));
    var timeDiff = daydiff(parseDate(datetimestart), parseDate(datetimeend));
    //var diff = ( new Date( startdate + " " + STime) - new Date( enddate + " " + ETime) ) / 1000 / 60 / 60;  
 var d2 = new Date(datetimestart);
 var d1 = new Date(datetimeend);

 var seconds =  (d1- d2)/1000;

    var vehiclearray = new Array();
    var email=jQuery("#email").val();
    var phone=jQuery("#sms").val();

    jQuery(".recipientbox").each(function() {
       vehiclearray.push(this.id);
    });
    if(vehiclearray == ''){
        jQuery("#vehiclearray").show();
        jQuery("#vehiclearray").fadeOut(4000);
    }else if(seconds > 0)
    {
        if(email != ''){
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        }
                        else if(phone != ''){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length != 10) {
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            }
                            else{
                            submitecodedata();
                            }
                        }
                        else{
                        submitecodedata();
                        }
            }
        else if(phone != ''){
            phone = phone.replace(/[^0-9]/g, '');
                        if(phone.length != 10) {
                        jQuery("#smserror").show();
                        jQuery("#smserror").fadeOut(3000);
                        }
                        else{
                        submitecodedata();
                        }
            }
        else{
        submitecodedata();
        }
    }
    else
    {
         jQuery("#error").show();
        jQuery("#error").fadeOut(3000);
    }
}

function submitecodedata()
{
    var data = jQuery('#getecode').serialize();
    console.log(data);
    jQuery.ajax({
                type: "POST",
                url: "route.php",
                data: data,
                cache: false,
                success: function(html)
                {
                    var txt;
                    var msg = confirm("Client Code Generated sucessfully.");
                    if (msg == true) {
                       window.location.href = 'ecode.php?id=2';
                    }
                }
            });
}

function chksubmitupdate()
{

    var startdate = jQuery("#SDate").val();
    var enddate = jQuery("#EDate").val();
    var STime = jQuery('#STime').val();
    var ETime = jQuery('#ETime').val();
    var days = jQuery('#days').val();
    var datetimestart = startdate + " " + STime + ":" + "00";
    var datetimeend = enddate + " " + ETime + ":" + "00";
    var vehiclearray = new Array();
    var email=jQuery("#email").val();
    var phone=jQuery("#sms").val();

    var dateAr1 = startdate.split('-');
    var dateAr2 = enddate.split('-');
    var stDate  = dateAr1[2] + '-' + dateAr1[1] + '-' + dateAr1[0].slice(-2);
    var edDate  = dateAr2[2] + '-' + dateAr2[1] + '-' + dateAr2[0].slice(-2);

    var datetimestart   = stDate + " " + STime + ":" + "00";
    var datetimeend     = edDate + " " + ETime + ":" + "00";


    //alert(datetimestart +"---"+ datetimeend);
    var datediff = daydiff(parseDate(stDate), parseDate(edDate));
    var timeDiff = daydiff(parseDate(datetimestart), parseDate(datetimeend));
    //var diff = ( new Date( startdate + " " + STime) - new Date( enddate + " " + ETime) ) / 1000 / 60 / 60;  
    var d2 = new Date(datetimestart);
    var d1 = new Date(datetimeend);

    var seconds =  (d1- d2)/1000;

    var datediff = daydiff(parseDate(startdate), parseDate(enddate));
    jQuery(".recipientbox").each(function() {
       vehiclearray.push(this.id);
    });
    if(vehiclearray == ''){
                jQuery("#vehiclearray").show();
                jQuery("#vehiclearray").fadeOut(4000);
    }
    else if(days != '' && days == -1){
                jQuery("#dayserror").show();
                jQuery("#dayserror").fadeOut(4000);
    }
    else if(seconds > 0)
    {
        if(email != ''){
                        var atpos=email.indexOf("@");
                        var dotpos=email.lastIndexOf(".");
                        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)
                        {
                        jQuery("#emailerror").show();
                        jQuery("#emailerror").fadeOut(3000);
                        }
                        else if(phone != ''){
                            phone = phone.replace(/[^0-9]/g, '');
                            if(phone.length != 10) {
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
                            }
                            else{
                            updatecodedata();
                            }
                        }
                        else{
                        updatecodedata();
                        }
            }
        else if(phone != ''){
            phone = phone.replace(/[^0-9]/g, '');
                        if(phone.length != 10) {
                        jQuery("#smserror").show();
                        jQuery("#smserror").fadeOut(3000);
                        }
                        else{
                        updatecodedata();
                        }
            }
        else{
        updatecodedata();
        }
    }
    else
    {
         jQuery("#error").show();
        jQuery("#error").fadeOut(3000);
    }
}

function updatecodedata()
{
    var data = jQuery('#updatecode').serialize();
    //alert(data);
    jQuery.ajax({
                type: "POST",
                url: "route.php?update=1",
                data: data,
                cache: false,
                success: function(html)
                {
                    window.location.href = 'ecode.php?id=2';
                }
        });

}
