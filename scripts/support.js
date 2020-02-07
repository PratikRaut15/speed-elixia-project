/*
 function addsupport()
 {
 if(jQuery("#notes_support").val() == "")
 {
 jQuery("#notescomp").show();
 jQuery("#notescomp").fadeOut(3000);                 
 }
 else
 {
 jQuery("#createsupport").submit();
 }
 }
 */
jQuery(document).ready(function () {
    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate() + 1;
    var currentYear = date.getFullYear();
    jQuery('#SDate').datepicker({
        format: "dd-mm-yyyy",
        language: 'en',
        autoclose: 1,
        startDate: new Date(currentYear, currentMonth, currentDate),
    });

});

function addsupport1()
{
    var name = jQuery("#file_upload").val();
    var ar_name = name.split('.');
    console.log(ar_name[1]);

    var issuetitle = jQuery("#issuetitle").val();
    var tickettype = jQuery("#tickettype").val();
    if (issuetitle == "")
    {
        jQuery("#tickettitle").show();
        jQuery("#tickettitle").fadeOut(3000);
        return false;

    } else if (tickettype == "0") {
        jQuery("#issuetype").show();
        jQuery("#issuetype").fadeOut(3000);
        return false;

    } else if (jQuery("#notes_support").val() == "")
    {
        jQuery("#notescomp").show();
        jQuery("#notescomp").fadeOut(3000);
        return false;

    } else if (name != "") {
        if (ar_name[1] != 'zip') {
            jQuery("#file_upload").val('');
            jQuery("#file_error").show();
            jQuery("#file_error").fadeOut(3000);
            return false;
        }
    } else {
        jQuery("#createsupport").submit();
    }
}



function editsupport() {
    var issuetitle = jQuery("#issuetitle").val();
    var tickettype = jQuery("#tickettype").val();

    if (jQuery("#notes_support").val() == "")
    {
        jQuery("#notescomp").show();
        jQuery("#notescomp").fadeOut(3000);
        return false;

    } else if (issuetitle == "")
    {
        jQuery("#tickettitle").show();
        jQuery("#tickettitle").fadeOut(3000);
        return false;

    } else if (tickettype == "0") {
        jQuery("#issuetype").show();
        jQuery("#issuetype").fadeOut(3000);
        return false;

    } else {
        jQuery("#modifysupport").submit();
    }
}

function getmailids() {
    var data = '';
    data = jQuery('#customerno').val();
    if (data == '') {
        alert('Enter valid customer');
        return false;
    } else {
        jQuery("#ticketmailid").autocomplete({
            source: "route.php?work=getmail&customerno=" + data,
            select: function (event, ui) {
                insertEmailDiv(ui.item.value, ui.item.eid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
}

function insertEmailDiv(selected_name, eid) {
    val=$("#sentoEmail").val();
    $str = val[0];
    if($str==','){
        $("#sentoEmail").val(val.substr(1));
        val=$("#sentoEmail").val();
    }
    $("#sentoEmail").val();
    if(val==","||val==""||val==" "){
        $("#sentoEmail").val(selected_name);
    }else{
        var lastChar = val[val.length -1];
        if(lastChar==','){
            $("#sentoEmail").val(val+selected_name);
        }
        else{
            if(val.includes(selected_name)){
                return;
            }else{  
            $("#sentoEmail").val(val+","+selected_name);
            }
        }
    }

    if (eid != "" && jQuery('#em_vehicle_div_' + eid).val() == null) {
        var div = document.createElement('div');
        div.id = "contain";
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';

        remove_image.onclick = function () {
            removeEmailDiv(selected_name, eid);
        };

        div.className = 'recipientbox';
        div.id = 'em_vehicle_div_' + eid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
        jQuery("#listemailids").append('');
        jQuery("#listemailids").append(div);
        jQuery(div).append(remove_image);
    }
}
function removeEmailDiv(selected_name, eid) {
    $("div#listemailids:last").find('br').remove();
    var rep = "," + selected_name;
    $("#sentoEmail").val($("#sentoEmail").val().replace(rep, ""));
    $("#sentoEmail").val($("#sentoEmail").val().replace(selected_name, ""));
    $('#em_vehicle_div_' + eid).remove();
    console.log($("#sentoEmail").val());
}

function insertMailId() {
    var data = '';
    data = jQuery('#customerno').val();
    var emailText1 = document.getElementById("ticketmailid").value;
    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

    if (!testEmail.test(jQuery("#ticketmailid").val())) {
        alert("Enter Valid Mail Id");
        return false;
    }
    else {
        $.ajax({
            url: 'route.php?work=insertmail&dataTest=' + emailText1 + '&customerno1=' + data,
            type: 'post',
            success: function (data1) {
                jQuery('#ticketmailid').val('');
                insertEmailDiv(emailText1, data1);
            }
        });
        jQuery("#ticketmailid").val("");
    }
}
function view_note_list(id) {
    var data = "history_note=" + id;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        dataType: "json",
        success: function (json) {
            if (json.length > 0) {
                var count = 0;
                var trHTML = '<table id="dataTable" style="width:100%;" border="1"><tr><th>Sr No</th><th>Note</th><th>Added By</th><th>Time</th></tr>';
                $.each(json, function (i, item) {
                    count++;
                    trHTML += '<tr><td>' + count
                            + '</td><td>' + item.note
                            + '</td><td>' + item.name
                            + '</td><td>' + item.time
                            + '</td></tr>';
                });
                $('#historyTable').html(trHTML);
                $('#dataTable th').css({'background': 'white', 'border-color': 'black', 'font-weight': 'bold'});
                $('#dataTable td').css({'border-color': 'black'});
            } else {
                $('#historyTable').html('History not available');
            }
        }
    });
    jQuery('#noteTab').modal('show');
    jQuery('#ticketModal').val(id);
}
function clearModal(){
    $("#historyTable:last").find('table').remove();
}
function submitNote() {
    var id      = jQuery('#ticketModal').val();
    var note    = jQuery('#noteModal').val();
    var data    = "add_note=" + id + "&note=" + note;
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        dataType: "text",
        success: function (json) {
            var jsonArray = JSON.parse(json);
            if (jsonArray.length > 0) {
                var count = 0;
                var trHTML = '<table id="dataTable" style="width:100%;" border="1"><tr><th>Sr No</th><th>Note</th><th>Added By</th><th>Time</th></tr>';
                        $.each(jsonArray, function (i, item) {
                    count++;
                     trHTML += '<tr><td>' + count
                            + '</td><td>' + item.note
                            + '</td><td>' + item.name
                            + '</td><td>' + item.time
                            + '</td></tr>';
                        });
                $('#historyTable').html(trHTML);
                $('#dataTable th').css({'background': 'white', 'border-color': 'black', 'font-weight': 'bold'});
                $('#dataTable td').css({'border-color': 'black'});
                jQuery('#noteModal').val('');
            } else {
                $('#historyTable').html('History not available');
            }
        }
    });
}	
function getuserlist() {
    var data = '';
    data = jQuery('#customerno').val();
    if (data == '') {
        alert('Enter valid customer');
        return false;
    } else {
        jQuery("#ticketuserid").autocomplete({
            source: "route.php?work=getuser&customerno=" + data,
            select: function (event, ui) {
                insertUserDiv(ui.item.value, ui.item.uid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }  
}

function insertUserDiv(selected_name, uid) {
   
    val=$("#showtoUser").val();
    $str = val[0];
    if($str==','){
        $("#showtoUser").val(val.substr(1));
        val=$("#showtoUser").val();
    }
    $("#showtoUser").val();
    if(val==","||val==""||val==" "){
        $("#showtoUser").val(selected_name);
    }else{
        var lastChar = val[val.length -1];
        if(lastChar==','){
            $("#showtoUser").val(val+selected_name);
        }
        else{
            if(val.includes(selected_name)){
                return;
            }else{  
            $("#showtoUser").val(val+","+selected_name);
            }
        }
    }

    if (uid != "" && jQuery('#um_vehicle_div_' + uid).val() == null) {
        var div  = document.createElement('div');
        div.id   = "contain";
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';

        remove_image.onclick = function () {
            removeUserDiv(selected_name, uid);
        };

        div.className   = 'recipientbox';
        div.id          = 'um_vehicle_div_' + uid;
        div.innerHTML   = '<span>' + selected_name + '</span><input type="hidden" class="v_list_users" name="um_vehicles_' + uid + '" value="' + uid + '"/>';
        jQuery("#listuserids").append(' ');
        jQuery("#listuserids").append(div);
        jQuery(div).append(remove_image);
    }
}

function removeUserDiv(selected_name, uid) {
    $("div#listuserids:last").find('br').remove();
    var rep     = "," + selected_name;
    $("#showtoUser").val($("#showtoUser").val().replace(rep, ""));
    $("#showtoUser").val($("#showtoUser").val().replace(selected_name, ""));
    $('#um_vehicle_div_' + uid).remove();
}