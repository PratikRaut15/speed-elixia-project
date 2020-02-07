jQuery(document).ready(function () {

    Calendar.setup({
        inputField: "podate", // ID of the input field
        ifFormat: "%d-%m-%Y", // the date format
        button: "trigger10" // ID of the button
    });

    Calendar.setup({
        inputField: "poexp", // ID of the input field
        ifFormat: "%d-%m-%Y", // the date format
        button: "trigger11" // ID of the button
    });

    var custno = jQuery('#cid').val();
    jQuery.ajax({
        type: "POST",
        url: "contactdetails_ajax.php",
        cache: false,
        data: {cno: custno},
        success: function (res) {
            var data = jQuery.parseJSON(res);
            //console.log(data);
            details(data);
        }
    });
    getCustAddress();
    //getAcc_customername();
    viewAccountpo();
    getmapLedger();
    function details(data){
        var tags = '';
        var imgsrc = "../../images/edit.png";
        jQuery(data).each(function (i, v) {
            tags += "<tr><td>" + v.x + "</td>";
            tags += "<td>" + v.person_name + "</td>";
            tags += "<td>" + v.type + "</td>";
            tags += "<td>" + v.email1 + "</td>";
            tags += "<td>" + v.email2 + "</td>";
            tags += "<td>" + v.phone1 + "</td>";
            tags += "<td>" + v.phone2 + "</td>";
            tags += "<td><a href='edit_contactdetails.php?cpid=" + v.cpid + "'><i class='icon-pencil'></i></a></td>";
            tags += "<td colspan = '2'><a href='contactdetails_ajax.php?delcpid=" + v.cpid + "&cust=" + v.cust + "'><i class='icon-trash'></i></a></td></tr>";
        });

        if (data.length === 0) {
            var emp = '';
            emp += "<tr>";
            emp += "<td colspan=100% style='text-align:center'>No Data Found</td>";
            emp += "</tr>";
            jQuery('#demo').html(emp);

        }

        else {
            jQuery('#demo').html(tags);
        }
    }
    /* Drop down for ledger name */
    jQuery("#ledgername").autocomplete({
        source: "autocomplete_team.php?action=FindLedger",
        minLength: 1,
        select: function (event, ui) {
            jQuery('#ledgerid').val(ui.item.ledgerid);

        }
    });
    //alert(jQuery("#duration").val());
    //alert(jQuery('input[name=leaseduration]:checked').val());
    if (jQuery('input[name=duration]:checked').val() == '-3') {
        jQuery(".tr_lease").show();
    }
    if (jQuery('input[name=leaseduration]:checked').val() == '-1') {
        jQuery("#customlease").show();
    }
    /*  to hide show lease tr*/
    jQuery("input[name='duration']").click(function () {
        if (jQuery('input[name=duration]:checked').val() == '-3') {
            jQuery(".tr_lease").show();
        } else {
            jQuery(".tr_lease").hide();
        }
    });
});


function ValidateForm() {
    var uteamid = $("#uteamid").val();
    var uallotted = $("#uallotted").val();

    if (uteamid == '0') {
        alert("Please select elixir");
        return false;
    } else if (uallotted == "0" || uallotted == '-1') {
        alert("Please select unit no.");
        return false;
    } else {
        $("#registerdeviceform").submit();
    }

}

function show_routing(){
    if ($("#cdelivery").is(':checked')){
        $("#routing_tr").show();
    }
    else{
        $("#routing_tr").hide();
    }
}

function show_features(){
    if ($("#ctracking").is(':checked'))
    {
        $("#load_sensor").show();
        $("#reverse_geo").show();
        $("#ac_tr").show();
        $("#genset_tr").show();
        $("#fuel_tr").show();
        $("#door_tr").show();
        $("#temp_tr").show();
        $("#portable_tr").show();
        $("#advanced_tr").show();
        $("#panic_tr").show();
        $("#buzzer_tr").show();
        $("#immobilizer_tr").show();
        $("#salesengage_tr").show();

    }
    else
    {
        $("#load_sensor").hide();
        $("#reverse_geo").hide();
        $("#ac_tr").hide();
        $("#genset_tr").hide();
        $("#fuel_tr").hide();
        $("#door_tr").hide();
        $("#temp_tr").hide();
        $("#portable_tr").hide();
        $("#advanced_tr").hide();
        $("#panic_tr").hide();
        $("#buzzer_tr").hide();
        $("#immobilizer_tr").hide();
        $("#salesengage_tr").show();
    }
}

function show_heirarchy(){
    if (document.getElementById('cmaintenance').checked)
        jQuery('#heir_tr').show();
    else
        jQuery('#heir_tr').hide();
}


function pullunit(){
    var uteamid = jQuery('#uteamid').val();
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uteamid: uteamid},
        dataType: 'html',
        success: function (html) {
            jQuery("#uready_td").html('');
            jQuery("#uready_td").append(html);

            // Pull Simcards
            pullsimcards();
        }
    });
    return false;
}

function pullsimcards(){
    var steamid = jQuery('#uteamid').val();
    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {steamid: steamid},
        dataType: 'html',
        success: function (html) {
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);
        }
    });
    return false;
}

function pullsimcard_from_unit(){
    var uallotted = jQuery('#uallotted').val();
    var simteamid = jQuery('#uteamid').val();

    jQuery.ajax({
        url: "route_ajax.php",
        type: 'POST',
        cache: false,
        data: {uallotted: uallotted, simteamid: simteamid},
        dataType: 'html',
        success: function (html) {
            jQuery("#simready_td").html('');
            jQuery("#simready_td").append(html);
        }
    });
    return false;
}

function getCustAddress(){
    var custno = jQuery('#cid').val();
    jQuery.ajax({
        type: "POST",
        url: "inview_address.php",
        cache: false,
        data: {cno: custno,
            work: "view_acc_cust"
        },
        success: function (res) {
            var data = jQuery.parseJSON(res);
            //console.log(data);
            details_addinvoice(data);
        }
    });
}

function details_addinvoice(data){
    var tags = '';
    jQuery(data).each(function (i, v) {
        tags += "<tr><td>" + v.x + "</td>";
        tags += "<td>" + v.acc_cust + "</td>";
        tags += "<td>" + v.invmane + "</td>";
        tags += "<td>" + v.add1 + "</td>";
        tags += "<td>" + v.add2 + "</td>";
        tags += "<td>" + v.add3 + "</td>";
        tags += "<td>" + v.pan + "</td>";
        tags += "<td>" + v.cst + "</td>";
        tags += "<td>" + v.vat + "</td>";
        tags += "<td>" + v.st + "</td>";
        tags += "<td>" + v.phone + "</td>";
        tags += "<td>" + v.email + "</td>";
        tags += "<td><a href='invedit_address.php?inid=" + v.invid + "&acc_cust=" + v.acc_cust + "'><i class='icon-pencil'></i></a></td>";
        tags += "<td colspan = '2'><a href='inview_address.php?work=delete_acc_cust&delinid=" + v.invid + "&acc_cust=" + v.acc_cust + "&cust=" + v.custno + "'><i class='icon-trash'></i></a></td></tr>";
    });

    if (data.length === 0) {
        var emp = '';
        emp += "<tr>";
        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>";
        emp += "</tr>";
        jQuery('#demo_addinv').html(emp);

    }

    else {
        jQuery('#demo_addinv').html(tags);
    }
}



function addAccountCust() {
    var name = jQuery("#invname").val();
    var cno = jQuery("#cid").val();
    if (name == '') {
        jQuery("#error_name").show();
        jQuery("#error_name").fadeOut(6000);
    } else {
        var data = jQuery("#add_cust").serialize();
        var dataString = 'work=add_acc_cust&' + data;
        jQuery.ajax({
            type: "POST",
            url: "inview_address.php",
            cache: false,
            data: dataString,
            success: function () {
                jQuery("#add_cust_succ").show();
                jQuery("#add_cust_succ").fadeOut(3000);
                jQuery('#add_cust')[0].reset();
                getCustAddress();
                getAcc_customername();
            },
            error: function () {
                jQuery("#fail_add_cust").show();
                jQuery("#fail_add_cust").fadeOut(6000);
            }
        });
    }
}

function getAcc_customername() {
    var custno = jQuery('#cid').val();
    jQuery.ajax({
        type: "POST",
        url: "inview_address.php",
        cache: false,
        data: {cno: custno,
            work: "get_acc_custname"
        },
        success: function (res) {
            jQuery("#cust").val(res);
        }
    });
}

function addAccountpo() {
    var cust = jQuery("#cust_po").val();
    if (cust == '0') {
        jQuery("#error_cust").show();
        jQuery("#error_cust").fadeOut(6000);
    } else {
        var data = jQuery("#add_po").serialize();
        var dataString = 'work=add_po&' + data;
        jQuery.ajax({
            type: "POST",
            url: "po_ajax.php",
            cache: false,
            data: dataString,
            success: function () {
                jQuery("#add_po_succ").show();
                jQuery("#add_po_succ").fadeOut(3000);
                jQuery('#add_po')[0].reset();
                viewAccountpo();
            },
            error: function () {
                jQuery("#fail_add_po").show();
                jQuery("#fail_add_po").fadeOut(6000);
            }
        });
    }
}

function viewAccountpo() {
    var custno = jQuery('#cid').val();
    jQuery.ajax({
        type: "POST",
        url: "po_ajax.php",
        cache: false,
        data: {cno: custno,
            work: "view_po"
        },
        success: function (res) {
            var data = jQuery.parseJSON(res);
            //console.log(data);
            poview_html(data);
        }
    });
}

function poview_html(data)
{
    var tags = '';
    jQuery(data).each(function (i, v) {
        tags += "<tr><td>" + v.x + "</td>";
        tags += "<td>" + v.pono + "</td>";
        tags += "<td>" + v.podate + "</td>";
        tags += "<td>" + v.poexpiry + "</td>";
        tags += "<td>" + v.poamount + "</td>";
        tags += "<td>" + v.description + "</td>";
        tags += "<td><a href='po_edit.php?poid=" + v.poid + "&cust=" + v.customerno + "'><i class='icon-pencil'></i></a></td>";
        tags += "<td colspan = '2'><a href='po_ajax.php?work=delete_po&delpoid=" + v.poid + "&cust_grp=" + v.customerno + "'><i class='icon-trash'></i></a></td></tr>";
    });

    if (data.length === 0) {
        var emp = '';
        emp += "<tr>";
        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>";
        emp += "</tr>";
        jQuery('#demo_po').html(emp);

    }

    else {
        jQuery('#demo_po').html(tags);
    }
}

function Mapledger() {
    var ledgername = jQuery("#ledgername").val();
    var formdata = jQuery("#form_ledgermap").serialize();
    var dataString = "work=mapLedger&" + formdata;
    if (ledgername == '') {
        var data = 'Please Enter Ledger Name';
        jQuery("#msg_mapledger").show();
        jQuery("#msg_mapledger").append(data);
        jQuery("#msg_mapledger").fadeOut(6000);
    } else {
        jQuery.ajax({
            url: "ledger_ajax.php",
            type: 'POST',
            cache: false,
            data: dataString,
            dataType: 'html',
            success: function (html) {
                jQuery("#msg_mapledger").html('');
                jQuery("#msg_mapledger").show();
                jQuery("#msg_mapledger").append(html);
                jQuery("#msg_mapledger").fadeOut(3000);
                jQuery("#form_ledgermap")[0].reset();
                getmapLedger();
                //window.location.reload();
            }
        });
    }
}

function getmapLedger() {
    var custno = jQuery("#cust_ledger").val();
    jQuery.ajax({
        type: "POST",
        url: "ledger_ajax.php",
        cache: false,
        data: {
            work: "getMappedLedger", custno: custno
        },
        success: function (res) {
            var data = jQuery.parseJSON(res);
            //console.log(data);
            html_mappedledger(data);
        }
    });
}
function html_mappedledger(data)
{
    var tags = '';
    jQuery(data).each(function (i, v) {
        tags += "<tr><td>" + v.x + "</td>";
        tags += "<td>" + v.ledgerid + "</td>";
        tags += "<td>" + v.ledgername + "</td>";
        tags += "<td>" + v.add1 + "</td>";
        tags += "<td>" + v.add2 + "</td>";
        tags += "<td>" + v.add3 + "</td>";
        tags += "<td>" + v.pan + "</td>";
        tags += "<td>" + v.cst + "</td>";
        tags += "<td>" + v.vat + "</td>";
        tags += "<td>" + v.st + "</td>";
        tags += "<td>" + v.phone + "</td>";
        tags += "<td>" + v.email + "</td>";
        tags += "<td><a href='ledger_ajax.php?work=deleteMappedLedger&mapledid=" + v.ledgerid + "&custno=" + v.customerno + "'><i class='icon-trash'></i></a></td>";
        tags += "<td><a href='invoice_generate.php?cno="+ v.customerno +"&ledid=" + v.ledgerid +"'><i class='icon-file'></i></td></tr>";
    });

    if (data.length === 0) {
        var emp = '';
        emp += "<tr>";
        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>";
        emp += "</tr>";
        jQuery('#demo_mappedledger').html(emp);

    }

    else {
        jQuery('#demo_mappedledger').html(tags);
    }
}
jQuery("input[name='duration']").click(function () {
    if (jQuery('input[name=duration]:checked').val() == '-3') {
        jQuery(".tr_lease").show();
    } else {
        jQuery(".tr_lease").hide();
    }
});
function showLease() {
    jQuery(".tr_lease").show();
}

function showLeaseCustom() {
    jQuery("#customlease").show();
}





function validform_updateuser(){
    var name = $("#name").val();
    var username = $("#username").val();
    var email = $("#email").val();
    var password = $("#password").val();
    if (name == "") {
        alert("User name not be blank.");
        return false;
    } else if (username == "") {
        alert("Username not be blank.");
        return false;
    } else if (email == "") {
        alert("Email id not be blank.");
        return false;
    } else if (password == ""){
        alert("Password not be blank.");
        return false;
    } else {
        $("#edituserform").submit();
    }
}


    function lockstatus(lockstatus, userid,teamid,customerid) {
        jQuery.ajax({
        type: "POST",
        url: "user_ajax.php",
        cache: false,
        data: {
              userid: userid, lockstatus: lockstatus , action: 'smslockstatus' , teamid: teamid , customerid: customerid
        },
        success: function (res) {
            if(res=='ok'){
                window.location.reload();
            }
        }
    });
    }

    function lockstatusvehicle(lockstatus,vehicleid,teamid,customerno){
        jQuery.ajax({
        type: "POST",
        url: "user_ajax.php",
        cache: false,
        data: {
              vehicleid: vehicleid , lockstatusvehicle: lockstatus, action: 'smslockstatusvehicle' , teamid: teamid, customerid: customerno
        },
        success: function (res) {
            if(res=='ok'){
                window.location.reload();
            }
        }
    });
    }
    function validate_contactdetails() {

    var type = jQuery('#type').val();
    var person = jQuery('#person').val();
    var email = jQuery('#email1').val();
    var phone = jQuery('#phone1').val();
    if (type == 0) {
        jQuery('#err_type').show(3000);
        jQuery('#err_type').hide(3000);
        return false;
    } else if (person == '') {
        jQuery('#err_personname').show(3000);
        jQuery('#err_personname').hide(3000);
        return false;
    } else if (email == '') {
        jQuery('#err_email').show(3000);
        jQuery('#err_email').hide(3000);
        return false;
    } else if (phone == '') {
        jQuery('#err_phone').show(3000);
        jQuery('#err_phone').show(3000);
        return false;
    } else {
        jQuery('#detailsform').submit();
    }
}
