/*var mandatory = ["txtfullname"]    
        
function validate(evt) {
  var theEvent = evt || window.event;
  var key = theEvent.keyCode || theEvent.which;
  if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}*/
function dosave() {
	/*
    mandatory.each(function(fieldname){
        var manerr = "error"+fieldname.substring(5);
        if( Trim( $(fieldname).getValue())=="")
        {
            $(manerr).show();
            jQuery("#errorname").fadeOut(3000);
        }
        else
        {
            $(manerr).hide();
        }
    } );
    */
	if ($("name").value != "") {
		if ($("email").value != "") {
			var valid_email = jQuery("#email").val().match(/.+@.+\.(.+){2,}/);
			if (valid_email == null) {
				$("erroremail").show();
				jQuery("#erroremail").fadeOut(3000);
			} else {
				$("edituser").submit();
			}
		} else {
			$("edituser").submit();
		}
	}
}

function dosave_alerts() {
	var params = "alerts=true";
	// geo sms
	if ($("geosms").checked == true) {
		params += "&geosms=1";
	}
	// geo email
	if ($("geoemail").checked == true) {
		params += "&geoemail=1";
	}
	// on over speed 
	if ($("ospeedsms").checked == true) {
		params += "&ospeedsms=1";
	}
	// on over speed email 
	if ($("ospeedemail").checked == true) {
		params += "&ospeedemail=1";
	}
	// on power cut sms 
	if ($("powercsms").checked == true) {
		params += "&powercsms=1";
	}
	// on power cut email 
	if ($("powercemail").checked == true) {
		params += "&powercemail=1";
	}
	// on tamper sms 
	if ($("tampersms").checked == true) {
		params += "&tampersms=1";
	}
	// on tamper email alert 
	if ($("tamperemail").checked == true) {
		params += "&tamperemail=1";
	}
	if ($("chksms").checked == true) {
		params += "&chksms=1";
	}
	if ($("chkemail").checked == true) {
		params += "&chkemail=1";
	}
	if ($("acsms").checked == true) {
		params += "&acsms=1";
	}
	if ($("acemail").checked == true) {
		params += "&acemail=1";
	}
	if ($("igsms").checked == true) {
		params += "&igsms=1";
	}
	if ($("igemail").checked == true) {
		params += "&igemail=1";
	}
	if ($("tempsms").checked == true) {
		params += "&acemail=1";
	}
	if ($("tempemail").checked == true) {
		params += "&tempemail=1";
	}
	if ($("EModify").value == "Modify") {
		new Ajax.Request('../../modules/user/route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				var statuscheck = transport.responseText;
				if (statuscheck == "ok") {
					$("perfectinfo").show();
					jQuery("#perfectinfo").fadeOut(3000);
				} else {
					$("problem").show();
					jQuery("#problem").fadeOut(3000);
				}
			},
			onComplete: function () {
				$("saved").show();
				jQuery("#saved").fadeOut(3000);
			}
		});
	} else {
		$("error").show();
		jQuery("#error").fadeOut(3000);
	}
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
				$("perfectinfo").show();
				jQuery("#perfectinfo").fadeOut(3000);
			} else {
				$("problem").show();
				jQuery("#problem").fadeOut(3000);
			}
		},
		onComplete: function () {
			$("saved_t").show();
			jQuery("#saved_t").fadeOut(3000);
		}
	});
}

function Save_Cron_Emails() {
	var params = "email_alerts=true";
	if ($("thistemail").checked == true) {
		params += "&thistemail=1";
	}
	new Ajax.Request('../../modules/user/route_ajax.php', {
		parameters: params,
		onSuccess: function (transport) {
			var statuscheck = transport.responseText;
			if (statuscheck == "ok") {
				$("perfectinfo").show();
				jQuery("#perfectinfo").fadeOut(3000);
			} else {
				$("problem").show();
				jQuery("#problem").fadeOut(3000);
			}
		},
		onComplete: function () {
			$("saved_t").show();
			jQuery("#saved_t").fadeOut(3000);
		}
	});
}

function dosave_stoppage_alerts() {
	var params = "stoppagealerts=true";
	if ($("safcsms").checked == true) {
		params += "&safcsms=1";
	}
	if ($("safcemail").checked == true) {
		params += "&safcemail=1";
	}
	if ($("saftsms").checked == true) {
		params += "&saftsms=1";
	}
	if ($("saftemail").checked == true) {
		params += "&saftemail=1";
	}
        params += "&safcmin="+$("safcmin").value;
        params += "&saftmin="+$("saftmin").value;
        
	if ($("samodify").value == "Modify") {
		new Ajax.Request('../../modules/user/route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				var statuscheck = transport.responseText;
				if (statuscheck == "ok") {
					$("perfectinfo").show();
					jQuery("#perfectinfo").fadeOut(3000);
				} else {
					$("problem").show();
					jQuery("#problem").fadeOut(3000);
				}
			},
			onComplete: function () {
				$("saved_s").show();
				jQuery("#saved_s").fadeOut(3000);
			}
		});
	} else {
		$("error_s").show();
		jQuery("#error_s").fadeOut(3000);
	}
}
