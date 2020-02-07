function onKey(evt) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	if ((key == 13))
		login();
}

function genNewPass() {
	var params = "uname=" + encodeURIComponent($("uname").value);
	new Ajax.Request('modules/user/route_ajax.php', {
		parameters: params,
		onSuccess: function (transport) {
			var statuscheck = transport.responseText;
			if (statuscheck == "ok") {
				$("message").show();
				jQuery("#message").fadeOut(3000);
			} else if (statuscheck == "notok") {
				$("wuser").show();
				jQuery("#wuser").fadeOut(3000);
			} else if (statuscheck == "noemail") {
				$("noemail").show();
				jQuery("#noemail").fadeOut(3000);
			}
		},
		onComplete: function () {}
	});
}

function login() {
	var a = "username=" + encodeURIComponent($("username").value);
	var b = "&password=" + encodeURIComponent($("password").value);
	var params = a + b;
	new Ajax.Request('modules/user/route_ajax.php', {
		parameters: params,
		onSuccess: function (transport) {
			var statuscheck = transport.responseText;
			if (statuscheck == "ok")
				$("auth").submit();
			else if (statuscheck == "notok") {
				$("incorrect").show();
				jQuery("#incorrect").fadeOut(3000);
			}
		},
		onComplete: function () {}
	});
}

function checkout() {
	if (jQuery("#ecodeid").val() != "") {
		evictMarkers();
		initialize();
		mapvehicles();
		jQuery('#ehide').fadeOut(100);
		AddItem('All', 'all');
	} else {
		$("eecode").show();
		jQuery("#eecode").fadeOut(3000);
	}
}