jQuery(function () {
    jQuery('body').click(function () {
        jQuery('#ajaxstatus').hide();
    });

    jQuery('.showtable').click(function () {
        jQuery('#ajaxBstatus').hide();
    });

    jQuery('#cbirthdate').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#eocd').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery('#activitytime').datepicker({format: 'dd-mm-yyyy', autoclose: true});
    jQuery("#reminder").css("display", "none");
    jQuery("#stage").css("display", "none");
    jQuery("#lost_notes").css("display","none");
    jQuery("#lost_category").css("display","none");
    jQuery('#addtemplateBuble').css({"visibility": "hidden"});
    jQuery('#edittemplateBuble').css({"visibility": "hidden"});
    jQuery('#addClientBuble').css({"visibility": "hidden"});
    jQuery('#addOrderBuble').css({"visibility": "hidden"});
    jQuery("#addStageBuble").css({"visibility": "hidden"});
    jQuery("#addReminderBuble").css({"visibility": "hidden"});
    jQuery("#addProductBuble").css({"visibility": "hidden"});
    jQuery("#addTemplateformBuble").css({"visibility": "hidden"});
    jQuery("#addActivityformBuble").css({"visibility": "hidden"});
    jQuery("#addSourceorderformBuble").css({"visibility": "hidden"});
    jQuery("#addLostBuble").css({"visibility": "hidden"});

    jQuery('.bubbleclose').click(function () {
        jQuery('#addLostBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addActivityformBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addClientBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclosetemp').click(function () {
        jQuery('#addtemplateBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#edittemplateBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addOrderBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addStageBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addReminderBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addProductBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addSourceorderformBuble').css({"visibility": "hidden"});
    });

    jQuery('.bubbleclose').click(function () {
        jQuery('#addTemplateformBuble').css({"visibility": "hidden"});
    });

    jQuery('#preview').click(function () {
        jQuery("#addtemplateBuble").css({"visibility": "visible"});
        var emailtemplate = tinymce.get('emailtemplate').getContent();
        var emailsub = jQuery("#emailsubject").val();
        var subject = "<strong>Subject</strong> ";
        jQuery("#emailsubjectpreview").html(subject + ': ' + emailsub);
        jQuery("#emailcontent").html(emailtemplate);
    });

    jQuery('#clientaddpop').click(function () {
        jQuery("#addOrderBuble").css({"visibility": "hidden"});
        jQuery("#addClientBuble").css({"visibility": "visible"});
    });

    jQuery('#editpreview').click(function () {
        jQuery("#edittemplateBuble").css({"visibility": "visible"});
        var emailtemplate = tinymce.get('emailtemplate').getContent();
        var emailsub = jQuery("#emailsubject").val();
        var subject = "<strong>Subject</strong> ";
        jQuery("#emailsubjectpreview").html(subject + ': ' + emailsub);
        jQuery("#emailcontent").html(emailtemplate);
    });


    jQuery("#stageid1").change(function () {
        var test = jQuery("#stageid1 :selected").text();
        if (test == "Lost") {
            jQuery("#lost_notes").css("display", "table-row");
            jQuery("#lost_category").css("display", "table-row");
        } else {
            jQuery("#lost_notes").css("display", "none");
            jQuery("#lost_category").css("display", "none");
        }
    });
});

function addactivity(id) {
    jQuery("#addActivityformBuble").css({"visibility": "visible"});
    jQuery("orderid").val(id);
}

function addclient() {
    jQuery("#addClientBuble").css({"visibility": "visible"});
}

function addstage() {
    jQuery("#addStageBuble").css({"visibility": "visible"});
}

function addlost(){
    jQuery("#addLostBuble").css({"visibility": "visible"});
}

function addreminder() {
    jQuery("#addReminderBuble").css({"visibility": "visible"});
}

function addorder() {
    jQuery("#addOrderBuble").css({"visibility": "visible"});
    jQuery("#addClientBuble").css({"visibility": "hidden"});
}

function addproduct() {
    jQuery("#addProductBuble").css({"visibility": "visible"});
}

function addtemplate() {
    jQuery("#addTemplateformBuble").css({"visibility": "visible"});
}

function addsourceorder() {
    jQuery("#addSourceorderformBuble").css({"visibility": "visible"});
}


function addactivitydatapop() {
    var remid = jQuery("#remid").val();
    var notes = jQuery("#notes").val();
    var activitytime = jQuery("#activitytime").val();
    var STime = jQuery("#STime").val();
    var activityrduration = jQuery("#activityrduration").val();
    var emailreq = jQuery("#emailreq").is(':checked') ? 1 : 0;
    var smsreq = jQuery("#smsreq").is(':checked') ? 1 : 0;
    var paymentamt = jQuery("#paymentamt").val();
    var activitytype = jQuery(':radio[name="activitytype"]').filter(':checked').val();
    var orderid = jQuery("#orderid").val();
    var data = "remid=" + remid + "&notes=" + notes + "&activitytime=" + activitytime + "&STime=" + STime + "&activityrduration=" + activityrduration + "&emailreq=" + emailreq + "&smsreq=" + smsreq + "&paymentamt=" + paymentamt + "&activitytype=" + activitytype + "&orderid=" + orderid + "&action=addactivity";

    if (remid == "") {
        show_error('Please filled mandatory fields');
        return false;
    } else {
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_sucess_activitypop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }

}

function addtemplatedatapop() {
    var emailtemplate = tinymce.get('emailtemplate').getContent();
    var templatetype = jQuery(':radio[name="templatetype"]').filter(':checked').val();
    var remid = jQuery("#remid").val();
    var stageid = jQuery("#stageid").val();
    var emailsubject = jQuery("#emailsubject").val();
    var smstemplate = jQuery("#smstemplate").val();
    var rtype = jQuery(':radio[name="rtype"]').filter(':checked').val();
    var formdata = "templatetype=" + templatetype + "&remid=" + remid + "&stageid=" + stageid + "&emailsubject=" + escape(emailsubject) + "&emailtemplate=" + escape(emailtemplate) + "&smstemplate=" + escape(smstemplate) + "&rtype=" + rtype + "&action=addtemplate";
    if (is_empty('remid') || is_empty('stageid')) {
        show_error('Please filled mandatory fields');
        return false;
    } else {
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: formdata,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_sucess_templatepop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}


function addproductdatapop() {
    var pname = jQuery("#pname").val();
    var unitprice = jQuery("#unitprice").val();
    if (pname == "") {
        show_error_pop('Please enter product name');
        return false;
    } else {
        var data = "pname=" + pname + "&unitprice=" + unitprice + "&action=addproduct";
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_success_productpop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}

function addstagedatapop() {
    var stagename = jQuery("#stagename").val();
    if (stagename == "") {
        show_error_pop('Please enter stage name');
        return false;
    } else {
        var data = "stagename=" + escape(stagename) + "&action=addstage";
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_success_stagepop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}

function addlostdatapop(){
    var lostreason = jQuery("#lostreason").val();
    if (lostreason == "") {
        show_error_pop('Please enter Lost reason');
        return false;
    } else {
        var data = "lostreason=" + escape(lostreason) + "&action=addlost";
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_success_lostpop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}


function addordersourcepop() {
    var ordersource = jQuery("#order_source").val();
    if (ordersource == "") {
        show_error_pop('Please enter Order Source type');
        return false;
    } else {
        var data = "ordersource=" + escape(ordersource) + "&action=addordersource";
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_success_ordersourcepop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}

function addreminderdatapop() {
    var rname = jQuery("#rname").val();
    if (rname == "") {
        show_error_pop('Please enter reminder name');
        return false;
    } else {
        var data = "rname=" + escape(rname) + "&action=addreminder";
        jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.Status === "Success") {
                    show_success_reminderpop(obj.Msg);
                    location.reload();
                } else {
                    show_error_pop(obj.Error);
                }
            }
        });
    }
}

function addorderdatapop() {
    var clientnameauto = jQuery("#clientnameauto").val();
    var ordersource = jQuery("#ordersource").val();
    var clientid = jQuery("#clientid").val();
    var stageid = jQuery("#stageid").val();
    var eocd = jQuery("#eocd").val();
    var emailchk = jQuery("#emailchk").is(':checked') ? 1 : 0;
    var smschk = jQuery("#smschk").is(':checked') ? 1 : 0;
    var additionalcost = jQuery("#additionalcost").val();
    var totalcost = jQuery("#totalcost").val();
    var checkboxes = document.getElementsByName('productlist');
    var selected = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            selected.push(checkboxes[i].value);
        }
    }
    var productlist = selected.join(', ');

    if (clientid == "" || stageid == "") {
        show_error_pop("Please add the client");
        return false;
    }
    var data = "clientnameauto=" + clientnameauto + "&ordersource=" + ordersource + "&clientid=" + clientid + "&stageid=" + stageid + "&eocd=" + eocd + "&emailchk=" + emailchk + "&smschk=" + smschk + "&additionalcost=" + escape(additionalcost) + "&totalcost=" + escape(totalcost) + "&productlist=" + escape(productlist) + "&action=addorder";
    jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success_orderpop(obj.Msg);
                location.reload();
            } else {
                show_error_pop(obj.Error);
            }
        }
    });
}

function addclientdatapop() {
    var clname = jQuery("#clname").val();
    var cemail = jQuery("#cemail").val();
    var cmobile = jQuery("#cmobile").val();
    var caddress = jQuery("#caddress").val();
    var cbirthdate = jQuery("#cbirthdate").val();

    if (clname == "" && cemail == "" && cmobile == "") {
        show_error_pop("Please fill mandatory fields");
        return false;
    }
    var data = "clname=" + escape(clname) + "&caddress=" + escape(caddress) + "&cemail=" + escape(cemail) + "&cmobile=" + escape(cmobile) + "&cbirthdate=" + escape(cbirthdate) + "&action=addclient";
    jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
        success: function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.Status === "Success") {
                show_success_pop(obj.Msg);
                location.reload();
            } else {
                show_error_pop(obj.Error);
            }
        }

    });
}

function editclientdata() {
    var formdata = jQuery("#editclientmasterform").serialize();
    if (is_empty('clname') || is_empty('cemail') || is_empty('cmobile')) {
        show_error('Please enter mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=editclient", 'editclientmasterform');
}

function deleteclient(a) {
    var res = confirm("Are you sure you want delete this client.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delclient", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletesrcorder(a) {
    var res = confirm("Are you sure you want delete this Source order.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delsrcorder", 'ff');
        location.reload();
    } else {
        return false;
    }
}


function editproductdata() {
    var formdata = jQuery("#editproductmasterform").serialize();
    if (is_empty('pname')) {
        show_error('Please enter product name');
        return false;
    }
    ajax_request(formdata + "&action=editproduct", 'editproductmasterform');
}

function editordersourcedata() {
    var formdata = jQuery("#editsourceordform").serialize();
    if (is_empty('order_source')) {
        show_error('Please enter Order Source type');
        return false;
    }
    ajax_request(formdata + "&action=editordersource", 'editsourceordform');
}


function deleteproduct(a) {
    var res = confirm("Are you sure you want delete this product.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delproduct", 'ff');
        location.reload();
    } else {
        return false;
    }
}


function editstagedata() {
    var formdata = jQuery("#editstagemasterform").serialize();
    if (is_empty('stagename')) {
        show_error('Please enter stage name');
        return false;
    }
    ajax_request(formdata + "&action=editstage", 'editstagemasterform');
}

function editlostdata() {
    var formdata = jQuery("#editlostmasterform").serialize();
    if (is_empty('lostreason')) {
        show_error('Please enter lost reason name');
        return false;
    }
    ajax_request(formdata + "&action=editlost", 'editlostmasterform');
}

function deletestage(a) {
    var res = confirm("Are you sure you want delete this Stage.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delstage", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletelost(a){
    var res = confirm("Are you sure you want delete this Lost Reason.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=dellost", 'ff');
        location.reload();
    } else {
        return false;
    }
}




function editreminderdata() {
    var formdata = jQuery("#editremindermasterform").serialize();
    if (is_empty('rname')) {
        show_error('Please enter reminder name');
        return false;
    }
    ajax_request(formdata + "&action=editreminder", 'editremindermasterform');
}


function editorderdata() {
    var formdata = jQuery("#editorderform").serialize();
    if (is_empty('clientid1') || is_empty('stageid1')) {
        show_error('Please enter reminder name');
        return false;
    }
    ajax_request(formdata + "&action=editorder", 'editorderform');
}

function deleteorder(a) {
    var res = confirm("Are you sure you want delete this order.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delorder", 'ff');
        location.reload();
    } else {
        return false;
    }
}


function edittemplatedata() {
    var emailtemplate = tinymce.get('emailtemplate').getContent();
    var templatetype = jQuery(':radio[name="templatetype1"]').filter(':checked').val();
    var remid = jQuery("#remid").val();
    var stageid = jQuery("#stageid").val();
    var emailsubject = jQuery("#emailsubject").val();
    var smstemplate = jQuery("#smstemplate").val();
    var rtype = jQuery(':radio[name="rtype"]').filter(':checked').val();
    var templateid = jQuery("#templateid").val();
    var formdata = "templateid=" + templateid + "&templatetype1=" + templatetype + "&remid=" + remid + "&stageid=" + stageid + "&emailsubject=" + escape(emailsubject) + "&emailtemplate=" + escape(emailtemplate) + "&smstemplate=" + escape(smstemplate) + "&rtype=" + rtype + "";
    if (is_empty('remid') || is_empty('stageid')) {
        show_error('Please filled mandatory fields');
        return false;
    }
    ajax_request(formdata + "&action=edittemplate", 'edittemplatemasterform');
}


function editactivitydata() {
    var formdata = jQuery("#editactivitymasterform").serialize();
    if (is_empty('remid')) {
        show_error('Please filled reminder name');
        return false;
    }
    ajax_request(formdata + "&action=editactivity", 'editactivitymasterform');
}



jQuery("#clientnameauto").autocomplete({
    source: "salesengage_ajax.php?action=clientauto", minLength: 1,
    select: function (event, ui) {
        jQuery('#clientid').val(ui.item.id);
    }
});

jQuery("#stagenameauto").autocomplete({
    source: "salesengage_ajax.php?action=stageauto", minLength: 1,
    select: function (event, ui) {
        jQuery('#stageid').val(ui.item.id);
    }
});

jQuery("#clientnameauto1").autocomplete({
    source: "salesengage_ajax.php?action=clientauto", minLength: 1,
    select: function (event, ui) {
        jQuery('#clientid1').val(ui.item.id);
    }
});

jQuery("#stagenameauto1").autocomplete({
    source: "salesengage_ajax.php?action=stageauto", minLength: 1,
    select: function (event, ui) {
        jQuery('#stageid1').val(ui.item.id);
    }
});

jQuery("#remindernameauto").autocomplete({
    source: "salesengage_ajax.php?action=reminderauto", minLength: 1,
    select: function (event, ui) {
        jQuery('#remid').val(ui.item.id);
    }
});


function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}

idsc1 = new Array();
jQuery("#productauto1").autocomplete({
    source: "salesengage_ajax.php?action=productauto",
    minLength: 2,
    select: function (event, ui) {
        var terms1 = split(this.value);
        idsc1.push(ui.item.id);
        terms1.pop();
        terms1.push(ui.item.value);
        terms1.push("");
        this.value = terms1.join(", ");
        return false;
    }
});


function deletereminder(a) {
    var res = confirm("Are you sure you want delete this reminder.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=delreminder", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deletetemplate(a) {
    var res = confirm("Are you sure you want delete this template.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=deletetemplate", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function deleteactivity(a) {
    var res = confirm("Are you sure you want delete this activity.");
    if (res == true) {
        ajax_request("&id=" + a + "&action=deleteactivity", 'ff');
        location.reload();
    } else {
        return false;
    }
}

function is_empty(name) {
    if (jQuery("input[name=" + name + "]").val() == '') {
        return true;
    }
    return false;
}
function ajax_request(data, fid) {
    jQuery('#pageloaddiv').show();
    jQuery.ajax({url: "salesengage_ajax.php", type: 'POST', data: data,
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
    jQuery("#ajaxstatus").html(text);
    jQuery("#ajaxstatus").css('color', 'green');
    jQuery("#ajaxstatus").show();
    jQuery('#' + fid + ' input,textarea').each(function () {
        if (jQuery(this).attr('type') != 'submit') {
            jQuery(this).val('');
        }
    });
}

function show_error_pop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'red');
    jQuery("#ajaxBstatus").show();
}

function show_success_pop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#clname").val('');
    jQuery("#caddress").val('');
    jQuery("#cemail").val('');
    jQuery("#cmobile").val('');
    jQuery("#cbirthdate").val('');
}

function show_success_orderpop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#clientnameauto").val("");
    jQuery("#clientid").val("");
    jQuery("#stageid").val("");
    jQuery("#eocd").val("");
    jQuery("#emailchk").val("");
    jQuery("#smschk").val("");
    jQuery("#additionalcost").val("");
    jQuery("#totalcost").val("");
    jQuery("#productlist").val("");
}

function show_success_stagepop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#stagename").val("");
}

function show_success_lostpop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#lostreason").val("");
}

function show_success_ordersourcepop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#order_source").val("");
}


function show_success_reminderpop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#rname").val("");
}

function show_success_productpop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#pname").val("");
    jQuery("#unitprice").val("");
}

function show_sucess_templatepop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#emailsubject").val("");
    jQuery("#emailtemplate").val("");
    jQuery("#smstemplate").val("");
}

function show_sucess_activitypop(text) {
    jQuery("#ajaxBstatus").html(text);
    jQuery("#ajaxBstatus").css('color', 'green');
    jQuery("#ajaxBstatus").show();
    jQuery("#remid").val("");
    jQuery("#notes").val("");
    jQuery("#activitytime").val("");
    jQuery("#STime").val("");
    jQuery("#activityrduration").val("");
    jQuery("#paymentamt").val("");
}

jQuery(':radio[name="templatetype"]').change(function () {
    var template = jQuery(this).filter(':checked').val();
    if (template == 1) {
        jQuery("#reminder").css("display", "table-row");
        jQuery("#stage").css("display", "none");
        jQuery("#stageid").val("");
    }
    if (template == 2) {
        jQuery("#stage").css("display", "table-row");
        jQuery("#reminder").css("display", "none");
        jQuery("#remid").val("");
    }

});


jQuery(':radio[name="templatetype1"]').change(function () {
    var template = jQuery(this).filter(':checked').val();
    if (template == 1) {
        jQuery("#reminder1").css("display", "table-row");
        jQuery("#stage1").css("display", "none");
        jQuery("#stageid").val("");
    }
    if (template == 2) {
        jQuery("#stage1").css("display", "table-row");
        jQuery("#reminder1").css("display", "none");
        jQuery("#remid").val("");
    }

});

jQuery('#smstemplate').keyup(function(){
var chars = this.value.length;
var messages = Math.ceil(chars / 160);
var remaining = messages * 160 - (chars % (messages * 160) || messages * 160);
 jQuery("#remaining").text(remaining + ' characters remaining');
 jQuery("#messages").text(messages + ' message(s)');
});









