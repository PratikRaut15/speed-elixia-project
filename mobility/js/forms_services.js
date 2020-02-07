var sid = 1;
var visiting_charges = 0;
var net_amount = 0;
var ischargable;
var dsc_amt=0;
var use_forms=0;
jQuery(document).ready(function () {
    if (jQuery("#totalamount").length > 0 && jQuery("#visiting_charges").length > 0) {
        update_net_amount();

    }

	ischargable=parseInt(jQuery("#ischargable").val());
	if(ischargable==0){
		jQuery("#totalamount").val(0);	
	}
	use_forms=parseInt(jQuery("#form_type").val());
	if(jQuery("#net_amount_after_discount").html()=="NaN"){jQuery("#net_amount_after_discount").html(0);}
	
});

 


function mltabclicked(tab) {

    deactivateTab("CreateServiceCall");
    deactivateTab("ModifyServiceCall");
    deactivateTab("ViewReport");

    activateTab(tab);
    switch (tab) {
        case "CreateServiceCall":
            sid = 1;
            refreshgrid();
            break;
        case "ModifyServiceCall":
            sid = 2;
            refreshgrid();
            break;
        case "ViewReport":
            sid = 3;
            refreshgrid();
            break;
    }
}

function refreshgrid() {
    if (sid == 1) {
        var params = "serviceno=" + encodeURIComponent(sid);
        new Ajax.Updater('servicecallpage', 'addservice.php', {
            parameters: params,
            onSuccess: function (transport) {
                window.location = "service.php?sid=1";
            },
            onFailure: function () {}
        });
    }
    if (sid == 2) {
        var params = "serviceno=" + encodeURIComponent(sid);
        new Ajax.Updater('servicecallpage', 'viewservice.php', {
            parameters: params,
            onSuccess: function (transport) {
                window.location = "service.php?sid=2";
            },
            onFailure: function () {}
        });
    }
    if (sid == 3) {
        var params = "serviceno=" + encodeURIComponent(sid);
        new Ajax.Updater('servicecallpage', 'servicereport.php', {
            parameters: params,
            onSuccess: function (transport) {
                window.location = "service.php?sid=3";
            },
            onFailure: function () {}
        });
    }
}
var clientname = "";
var address1 = "";
var address2 = "";
var contactperson = "";
var phonenumber = "";
var clientextra = "";

function loaded() {
	if ($("clientbox")) {

        registerSAYT("phonename", "services", "clientbox", clientselected, noresults, haveresults, addClient);
		
    }
   // if ($("clientbox")) {

   //     registerSAYT("phonenumber", "services", "clientbox", clientselected, noresults, haveresults, addClient);
		
   // }
    if ($("clientextrabox")) {

        registerSAYT("cliextra", "servicesextra", "clientextrabox", clientextraselected, noresults, haveresults, addClient);

    }
    if ($("userfieldbox1")) {

        registerSAYT("seruf1", "seruf1", "userfieldbox1", uf1sel, noresults, haveresults, addClient);
    }
    if ($("userfieldbox2")) {

        registerSAYT("seruf2", "seruf2", "userfieldbox2", uf2sel, noresults, haveresults, addClient);
    }
}

//Event.observe(window,'load', function() { loaded(); });
jQuery(document).ready(function () {
    // Handler for .ready() called.
    loaded();
	clear_chargable();
});

function clear_chargable(){
	
jQuery("#phonenumber").keypress(function() {
									 
jQuery("#client_type_add").show();
jQuery("#client_type_show").hide();							 
});
}

function noresults() {}

function haveresults() {}

function addClient() {}

function uf1sel(selectedid, selectedtext) {
    $("seruf1").value = selectedtext;
}

function uf2sel(selectedid, selectedtext) {
    $("seruf2").value = selectedtext;
}

function clientselected(selectedid, selectedtext) {

    $("phonename").value = selectedtext;
    // We need to get more info based on the ID.
    var params = "cid=" + encodeURIComponent(selectedid);
    new Ajax.Request('getservicedetailsAjax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var json = transport.responseText.evalJSON();
            clientname = selectedtext;
            $("clientname").value = json.result[0].clientname;
            $("clientid").value = selectedid;
            $("address1").value = json.result[0].address1;
            $("address2").value = json.result[0].address2;
            $("contactperson").value = json.result[0].maincontact;
			jQuery("#client_type_add").hide();
			jQuery("#client_type_show").show();
			jQuery("#type_id_show_span").html(json.result[0].type_name);
			jQuery("#type_id_show").val(json.result[0].type_id);
			jQuery("#type_id").html(json.result[0].type_id);
			jQuery("#type_id").html(json.result[0].type_id);
			
            $("phonenumber").value = json.result[0].phone;
            $("txtcity").value = json.result[0].city;
            $("txtstate").value = json.result[0].state;
            $("txtzip").value = json.result[0].zip;
            $("email").value = json.result[0].email;
			$("ischargable").value = json.result[0].ischargable;
            $("modifyclient").value = 1;
            if ($("extra")) {
                $("extra").value = json.result[0].extra;
            }
			if(use_forms==1){
			jQuery("#form_type_id").val(json.result[0].form_type_id);
			jQuery("#form_type_id").hide();
			jQuery("#form_type_id").after(jQuery("#form_type_id option[value='"+json.result[0].form_type_id+"']").text());
			
			}
			clientid = selectedid;
            address1 = json.result[0].address1;
            address2 = json.result[0].address2;
            contactperson = json.result[0].maincontact;
            phonenumber = json.result[0].phone;
            clientextra = json.result[0].extra;
            $("hidecreate").hide();
            $("hidemodify").show();

        },
        onFailure: function () {
            enablebutton();
        }
    });
}

function clientextraselected(selectedid, selectedtext) {


    $("cliextra").value = selectedtext;
    // We need to get more info based on the ID.
    var params = "cid=" + encodeURIComponent(selectedid);
    new Ajax.Request('getservicedetailsAjax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var json = transport.responseText.evalJSON();
            clientextra = selectedtext;
            $("clientid").value = selectedid;
            $("address1").value = json.result[0].address1;
            $("address2").value = json.result[0].address2;
            $("contactperson").value = json.result[0].maincontact;
            $("phonenumber").value = json.result[0].phone;
            $("clientname").value = json.result[0].clientname;
            $("txtcity").value = json.result[0].city;
            $("txtstate").value = json.result[0].state;
            $("txtzip").value = json.result[0].zip;
            $("email").value = json.result[0].email;
			$("ischargable").value = json.result[0].ischargable;
            
            if ($("extra")) {
                $("extra").value = json.result[0].extra;
            }
            clientname = json.result[0].clientname;
            clientid = selectedid;
            address1 = json.result[0].address1;
            address2 = json.result[0].address2;
            contactperson = json.result[0].maincontact;
            phonenumber = json.result[0].phone;
            $("hidecreate").hide();
            $("hidemodify").show();


        },
        onFailure: function () {
            enablebutton();
        }
    });
}

function showcreate() {
    if ($("clientname").value == "") {
        jQuery('#Create').attr('disabled', true);
        $("manclientname").show();
        jQuery("#manclientname").fadeOut(3000);
    } else {

        jQuery('#Create').attr('disabled', false);
    }
    showmodify();
}

function showmodify() {
    if ($("clientid").value != 0 && ($("clientname").value != clientname) || ($("address1").value != address1) || ($("address2").value != address2) || ($("contactperson").value != contactperson) || ($("phonenumber").value != phonenumber)) {

    } else {
        if ($("clientid").value != 0) {

            $("hidecreate").hide();

        }
    }
}

function dosave() {
    if ($("email").value != "") {
        var valid_email = jQuery("#email").val().match(/.+@.+\.(.+){2,}/);
        if (valid_email == null) {
            $("manemail").show();
            jQuery("#manemail").fadeOut(3000);
        }
    } else {
        if ($("clientname").value == "") {
            $("manclientname").show();
            jQuery("#manclientname").fadeOut(3000);
        } else {
            if ($("trackno").value != "") {
                var params = "trackno=" + encodeURIComponent($("trackno").value);
                new Ajax.Request('chkservicetracknoAjax.php', {
                    parameters: params,
                    onSuccess: function (transport) {
                        var statuscheck = transport.responseText;
                        if (statuscheck == "ok") {
                            // Do nothing
                            $("form1").submit();
                        } else {
                            $("mantrackno").show();
                            jQuery("#mantrackno").fadeOut(3000);
                        }
                    },
                    onComplete: function () {}
                });
            } else {
                $("form1").submit();
            }
        }
    }
}

function domodify() {
    if ($("clientname").value == "") {
        $("manclientname").show();
        jQuery("#manclientname").fadeOut(3000);
    } else {
        $("modifyclient").value = 1;
        if ($("trackno").value != "") {
            var params = "trackno=" + encodeURIComponent($("trackno").value);
            new Ajax.Request('chkservicetracknoAjax.php', {
                parameters: params,
                onSuccess: function (transport) {
                    var statuscheck = transport.responseText;
                    if (statuscheck == "ok") {
                        // Do nothing
                        $("form1").submit();
                    } else {
                        $("mantrackno").show();
                        jQuery("#mantrackno").fadeOut(3000);
                    }
                },
                onComplete: function () {}
            });
        } else {
            $("form1").submit();
        }
    }
}

function dis_check() {
    jQuery("#dis_status").html("validating code");
    jQuery("#damt").val(0);
    jQuery("#dis_id").val(0);
    var action = jQuery("#d_act").val();
    if (action == "edit") {
        var sid = jQuery("#sid").val();

    }
    var clientid = jQuery("#clientid").val();
    var stime = jQuery("#stime").val();
    var dcode = jQuery("#dcode").val();

    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=20",
        async: true,
        data: {
            client: clientid,
            expiry: stime,
            code: dcode,
            action: action,
            sid: sid
        },
        cache: false,
        success: function (data) {
            var json = eval('(' + data + ')');
            if (json.error == true) {
                if (json.error_msg == "code is empty") {
                    jQuery("#dis_status").html(json.error_msg);
                } else {
                    jQuery("#dis_status").html("<span class='red'>" + json.error_msg + "</span");
                }

                jQuery("#damt").val(0);
                jQuery("#dis_id").val(0);

            } else {
                jQuery("#dis_status").html("code is valid");
                jQuery("#damt").val(json.dis_amt);
                jQuery("#dis_id").val(json.dis_id);
                if (jQuery("#apply_d")) {
                    jQuery("#apply_d").val(1);
                }
                if (json.ispercent == 1) {
                    var bill = jQuery("#net_amount").val();

                    if (bill > 0) {
                      
                        bill = bill - (bill * json.dis_percent) / 100;
                      	
                        dsc_amt = jQuery("#net_amount").val() - bill;
                        jQuery("#damt").val(Math.ceil(Math.round(dsc_amt * 100) / 100));
						
						
                    }

                } else {
                    var bill = jQuery("#net_amount").val();
                    if (jQuery("#apply_d")) {
                        jQuery("#apply_d").val(1);
                    }
                    if (bill > 0) {
						dsc_amt=json.dis_amt;
                         jQuery("#damt").val(json.dis_amt);



                        
                    }
                }
				update_net_amount();

            }
        }

    });

	
}

function dis_check_edit() {
    jQuery("#dis_status").html("validating code");
    var action = jQuery("#d_act").val();
    if (action == "edit") {
        var sid = jQuery("#sid").val();
    }
    var clientid = jQuery("#clientid").val();
    var stime = jQuery("#stime").val();
    var dcode = jQuery("#dcode").val();
    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=20",
        async: true,
        data: {
            client: clientid,
            expiry: stime,
            code: dcode,
            action: action,
            sid: sid
        },
        cache: false,
        success: function (data) {
            var json = eval('(' + data + ')');
            if (json.error === true) {
                if (json.error_msg == "code is empty") {
                    jQuery("#dis_status").html(json.error_msg);
                    jQuery("#damt").val(0);
                    jQuery("#dis_id").val(0);
                } else {
                    jQuery("#dis_status").html("<span class='red'>" + json.error_msg + "</span");
                }
                jQuery("#damt").val(0);
                jQuery("#dis_id").val(0);
            } else {
                jQuery("#dis_status").html("code is valid");
                jQuery("#damt").val(json.dis_amt);
                jQuery("#dis_id").val(json.dis_id);
                if (json.ispercent == 1) {
                    var bill = jQuery("#net_amount").val();
                    if (bill > 0) {
                        bill = bill - (bill * json.dis_percent) / 100;
                        dsc_amt = jQuery("#net_amount").val() - bill;
                        jQuery("#damt").val(Math.ceil(Math.round(dsc_amt * 100) / 100));
                    }
                } else {
                    var bill = jQuery("#net_amount").val();
                    if (bill > 0) {
                        jQuery("#damt").val(json.dis_amt);
                    }
                }
            }
        }
    });
	
	jQuery("#net_amount_after_discount").html(net_amount-parseInt(jQuery("#damt").val()));
	
}



















function selectcalled() {

    var i = document.getElementById('enterprisebox');
    var p = i.options[i.selectedIndex].value;
    var q = i.options[i.selectedIndex].text;

    if (p != 0) {
        servicedetails(p, q);
    }
    if (jQuery("#dis_id").val() != "" || jQuery("#dis_id").val() != 0) {
        dis_check();
    }

}

function domclick(value, text, y, expect) {
    var contentID = document.getElementById('domt');
    var box = document.createElement("input");
    box.setAttribute("type", "hidden");
    box.setAttribute("name", "servicelist[]");
    box.setAttribute("id", "servicelistid" + value);
    box.setAttribute("value", value);
    contentID.appendChild(box);

    jQuery("#servilistul").append('<tr id="lid_' + value + '"><th>' + text + '</th><td>' + expect + '</td><td>' + y + '</td><td><img id="rm" src="images/rem.png" onclick="removedom_add(' + value + ',' + y + ')"/></td></tr>');
	if(ischargable==1){
    jQuery("#service_amount").html(_servicelistamount);
	jQuery("#totalamount").val(_servicelistamount);
	}
	update_net_amount();
}

function removedom_add(sid, amount) {
	
	 jQuery("#servicelistid" + sid).remove();
    jQuery("#lid_" + sid).remove();
	if(ischargable==1){
    _servicelistamount = _servicelistamount - amount;
    jQuery("#service_amount").html(_servicelistamount);
	jQuery("#totalamount").val(_servicelistamount);
	}
    update_net_amount();

    if (jQuery("#dis_id").val() != "" || jQuery("#dis_id").val() != 0) {
        dis_check();
    }
}

function servicedetails(x1, x2) {
    var params = x1;
    new Ajax.Request('ajaxpulls.php?work=3&sid=' + x1, {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
           ischargable=parseInt(jQuery("#ischargable").val());
			
			
			if(ischargable==1){
			_servicelistamount = _servicelistamount + parseFloat(cdata.price);
			jQuery("#totalamount").val(_servicelistamount);
			}
			
			
            
            update_net_amount();
            domclick(x1, x2, cdata.price, cdata.expectedtime);
        },
        onComplete: function () {}
    });




}

function formsubmit() {

    $("formclient").submit();
}



function removedom_add(sid, amount) {

    jQuery("#servicelistid" + sid).remove();
    jQuery("#lid_" + sid).remove();
	if(ischargable==1){
    _servicelistamount = _servicelistamount - amount;
    jQuery("#service_amount").html(_servicelistamount);
	jQuery("#totalamount").val(_servicelistamount);
	
	}
	
	update_net_amount();
    if (jQuery("#dis_id").val() != "" || jQuery("#dis_id").val() != 0) {
        dis_check();
    }
}

function servicedetails(x1, x2) {
	
    var params = x1;
    new Ajax.Request('ajaxpulls.php?work=3&sid=' + x1, {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
			ischargable=parseInt(jQuery("#ischargable").val());
			if(ischargable==1){
            _servicelistamount = _servicelistamount + parseFloat(cdata.price);
            jQuery("#totalamount").val(_servicelistamount);
			}
			update_net_amount();
            domclick(x1, x2, cdata.price, cdata.expectedtime);
        },
        onComplete: function () {}
    });
}

/// edit section
function updatetotal() {
	if(ischargable==1){
    _servicelistamount = parseFloat(document.getElementById('totalamount').value);
    jQuery("#service_amount").html(_servicelistamount);
	}
	update_net_amount();
    //alert(_servicelistamount);
}

function selectcallededit() {
ischargable=parseInt(jQuery("#ischargable").val());
    var i = document.getElementById('enterprisebox');
    var p = i.options[i.selectedIndex].value;
    var q = i.options[i.selectedIndex].text;
    if (p != 0) {
        servicedetailsedit(p, q);
    }
    if (jQuery("#dis_id").val() != "" || jQuery("#dis_id").val() != 0) {
        dis_check();
    }

}

function domclickedit(value, text, y, x) {
    var contentID = document.getElementById('domt');
    var box = document.createElement("input");
    box.setAttribute("type", "hidden");
    box.setAttribute("name", "servicelistedit[]");
    box.setAttribute("id", "servicelistid" + value);
    box.setAttribute("value", value);
    contentID.appendChild(box);

    jQuery("#servilistul").append('<tr id="lid_' + value + '"><th>' + text + '</th><td>' + y + '</td><td>' + y + '</td><td><img id="rm" src="images/rem.png" onclick="removedomedit2(' + value + ',' + y + ')"/></td></tr>');
	if(ischargable==1){
    jQuery("#service_amount").html(_servicelistamount);
	}
	update_net_amount();

}

function servicedetailsedit(x1, x2) {

    var params = x1;
    new Ajax.Request('ajaxpulls.php?work=3&sid=' + x1, {
        parameters: params,
        onSuccess: function (transport) {

            var cdata = transport.responseText.evalJSON();
			ischargable=parseInt(jQuery("#ischargable").val());
			if(ischargable==1){
            _servicelistamount = _servicelistamount + parseInt(cdata.price);
			jQuery("#totalamount").val(_servicelistamount);
			}
			update_net_amount();

            domclickedit(x1, x2, cdata.price, cdata.expectedtime);
        },
        onComplete: function () {}
    });

}

function removedomedit2(esid, amount, mid) {
    jQuery("#servicelistid" + esid).remove();
    if (jQuery("#lids_" + mid)) {
        jQuery("#lids_" + mid).remove();
    }
    if (jQuery("#lid_" + esid)) {
        mid = esid;
        jQuery("#lid_" + esid).remove();
    }
	if(ischargable==1){
    _servicelistamount = _servicelistamount - parseFloat(amount);
    jQuery("#service_amount").html(_servicelistamount);
    jQuery("#totalamount").val(_servicelistamount);
	}
	update_net_amount();
    _del_serviceid.push(mid);
    //alert(_del_serviceid.toString());
    var contentID = document.getElementById('domte');
    var box = document.createElement("input");
    box.setAttribute("type", "hidden");
    box.setAttribute("name", "delete_service[]");
    box.setAttribute("id", "delete_serviceid" + esid);
    box.setAttribute("value", esid);
    contentID.appendChild(box);
    if (jQuery("#dis_id").val() != "" || jQuery("#dis_id").val() != 0) {
        dis_check();
    }
}


function update_delete_service_ajax() {
    if (_del_serviceid.length > 0) {
        //alert("before for "+ret1);	
        for (i = 0; i < _del_serviceid.length; i++) {
            ret1 = ajaxtransport(_del_serviceid[i]);
            jQuery("#canc").val(ret1);
        }
        return jQuery("#canc").val();
    } else {
        ret1 = true;
    }
    //alert("The Service Call has changed");
    return ret1;
}

function ajaxtransport(sid_d) {
    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=7&manageid=" + sid_d,
        async: true,
        cache: false,
        success: function (data) {
        }
    });
    return true;
}

function removedomedit(esid, amount, mid) {
    var params;
    new Ajax.Request('ajaxpulls.php?work=7&manageid=' + mid, {
        parameters: params,
        onSuccess: function (transport) {
            jQuery("#servicelistid" + esid).remove();
            jQuery("#lid_" + mid).remove();
			if(ischargable==1){
            _servicelistamount = _servicelistamount - parseFloat(amount);
            jQuery("#service_amount").html(_servicelistamount);
            jQuery("#totalamount").val(_servicelistamount);
			}
			update_net_amount();
        },
        onComplete: function () {}
    });

}


function update_net_amount() {
    if (jQuery("#visiting_charges").val() !== "" && parseFloat(jQuery("#visiting_charges").val()) > 0) {
        visiting_charges = parseFloat(jQuery("#visiting_charges").val());
        net_amount = parseFloat(jQuery("#totalamount").val()) + visiting_charges;
        jQuery("#net_amount").val(net_amount);
        jQuery("#net_amount_html").html(net_amount);
		jQuery("#net_amount_after_discount").html(net_amount-parseInt(jQuery("#damt").val()));
    } else {
        visiting_charges = 0;
        jQuery("#visiting_charges").val(0);
        net_amount = parseFloat(jQuery("#totalamount").val()) + visiting_charges;
        jQuery("#net_amount").val(net_amount);
        jQuery("#net_amount_html").html(net_amount);
		jQuery("#net_amount_after_discount").html(net_amount-parseInt(jQuery("#damt").val()));
    }
}


function on_changes_visiting_charges() {

    if (parseFloat(jQuery("#visiting_charges").val()) < 0) {
        jQuery("#visiting_charges").val(0);
    } else {
        jQuery("#visiting_charges").val(parseFloat(jQuery("#visiting_charges").val()));
    }
    update_net_amount();
    dis_check();

}