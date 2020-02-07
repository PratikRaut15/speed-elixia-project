var customerno;
var teamList;
var ticket;
$(function(){
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: 'getTicketData=1&ticketid='+ticketid,
        cache: false,
        success: function (json) {
            ticket = JSON.parse(json);
            $('#title').val(ticket[0].title);
            customerno = ticket[0].customerid;
            $('#customer').val(ticket[0].customerid+' - '+ticket[0].customercompany);
            $('#description').val(ticket[0].description);
            $('#ecd').val(ticket[0].eclosedate);
            $('#ecdupdate').html(ticket[0].estimateddate);
            if(ticket[0].charge_id!== null){
                $('#additionalCharges').prop('checked', true);
                $('#chargeDescription').val(ticket[0].chargeDescription);
                $('#chargesField').val(ticket[0].chargeAmount);
                toggleChargesDiv();
            }
            loadData(ticket);
            displayNotes();
        }
    });
    $('#ecd').datepicker({
        dateFormat: "dd-mm-yy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });
});
function loadData(details=ticket[0]) {
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "getDetails=1",
        success: function(data) {
            var priorities = '';
            var result = JSON.parse(data);
            $.each(result[0], function(i, text) {
                if (details[0].priority == text.prid) {
                    priorities = priorities + "<option value='" + text.prid + "' selected>" + text.priority + "</option>";
                } else {
                    priorities = priorities + "<option value='" + text.prid + "'>" + text.priority + "</option>";
                }
            });
            $('#priority').html(priorities);

            teamList=[];
            var teamOptions='';
            $.each(result[1], function(i, text) {
                    teamMember={};
                    //console.log(text);
                  teamOptions = teamOptions + "<option value='"+text.teamid+"' data-did="+text.department_id+">"+text.name+"</option>";
                  teamMember.teamid=text.teamid;
                  teamMember.name=text.name;
                  teamMember.did=text.department_id;
                  //console.log("member did :"+teamMember.did);
                  teamList.push(teamMember);

            });
            $('#allot').html(teamOptions);
            var products = '';
            $.each(result[2], function(i, text) {
                if (details[0].prodId == text.prodId) {
                    products = products + "<option value='" + text.prodId + "' selected >" + text.prodName + "</option>";
                } else {
                    products = products + "<option value='" + text.prodId + "'>" + text.prodName + "</option>";
                }

            });
            $('#product').html(products);

            var ticketTypes = '';
            $.each(result[3], function(i, text) {
                //console.log(details[0].ticket_type);
                //console.log(text.typeid);
                if (details[0].ticket_type == text.typeid) {
                    ticketTypes = ticketTypes + "<option value='" + text.typeid + "' selected data-did='"+text.department_id+"'>" + text.tickettype + "</option>";
                } else {
                    ticketTypes = ticketTypes + "<option value='" + text.typeid + "' data-did='"+text.department_id+"'>" + text.tickettype + "</option>";
                }
            });
            $('#type').html(ticketTypes);
            var status = '';
            $.each(result[4], function(i, text) {
                if (details[0].status == text.id) {
                    status = status + "<option value='" + text.id + "' selected>" + text.status + "</option>";
                } else {
                    status = status + "<option value='" + text.id + "'>" + text.status + "</option>";
                }
            });
            $('#status').html(status);
            //filterTeamList();
            $('#customerno').val(details[0].customerid);
            $('#allot').val(details[0].allot_to);
            ids=details[0].send_mail_to;
            cc=details[0].send_mail_cc;
            $.ajax({
                type: "POST",
                url: "Docket_functions.php",
                data:"ids="+ids+"&translateEmails=1"+"&CCs="+cc,
                success: function(res){
                    data = JSON.parse(res);
                    eids=data.ids;
                    if(eids!=undefined){
                        $.each(eids,function(j,val){
                            insertEmailDiv(val.emailid, val.id);
                        });
                    }
                    cc=data.cc;
                    if(cc!=undefined){
                        $.each(cc,function(j,val){
                            insertEmailDivCC(val.emailid, val.id);
                        });
                    }
                }
            });
        }
    });
}
function toggleChargesDiv(){
    var x = document.getElementById("chargesDiv");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
function getmailids() {
    var data = customerno;
    if (data == '') {
        return false;
    } else {
        jQuery("#email").autocomplete({
            source: "Docket_functions.php?work=getmailforTech&customerno=" + data,
            select: function(event, ui) {

                //console.log(ui);
                insertEmailDiv(ui.item.value, ui.item.eid);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });    
    }
}
function getmailidsCC() {
    var data = customerno;
    if (data == '') {
      return false;
    }
    else {
      jQuery("#cc").autocomplete({
        source: "Docket_functions.php?work=getmailforTech&customerno=" + data,
        select: function (event, ui) {
          //console.log(ui);
          insertEmailDivCC(ui.item.value, ui.item.eid);
          /*clear selected value */
          jQuery(this).val("");
          return false;
        }
      });
    }
}
function insertEmailDiv(selected_name, eid) {
    jQuery("#emailList").val(function(i, val) {
        if (!val.includes(eid)) {
            return val + (!val ? '' : ',') + eid;
        } else {
            return val;
        }
    });
    if (eid != "" && $('#updateTicket').find('#em_vehicle_div_' + eid).val() == null) {
        var div = document.createElement('div');
        div.id = "contain";
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removeEmailDiv(eid);
        };
        div.className = 'recipientbox';
        div.id = 'em_vehicle_div_' + eid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
        $("#email").after(div);
        $(div).append(remove_image);
    }
}
function insertEmailDivCC(selected_name, eid) {
    jQuery("#ccList").val(function (i, val) {
      if (!val.includes(eid)) {
        return val + (!val ? '':  ',') + eid;
      }
      else {
        return val;
      }
    });
    if (eid != "" && $('#updateTicket').find('#cc_em_vehicle_div_' + eid).val() == null) {
      var div = document.createElement('div');
      div.id = "contain";
      var remove_image = document.createElement('img');
      remove_image.src = "../../images/boxdelete.png";
      remove_image.className = 'clickimage';
      remove_image.onclick = function() {
            removeEmailDivCC(eid);
      };
      div.className = 'recipientbox';
      div.id = 'em_vehicle_div_' + eid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="cc_em_vehicles_' + eid + '" value="' + eid + '"/>';
      $(div).append(remove_image);
      $("#cc").after(div);
      
    }
}
function removeEmailDiv(eid) {
    var rep = "," + eid;
    $("#emailList").val($("#emailList").val().replace(rep, ""));
    $("#emailList" ).val($("#emailList").val().replace(eid, ""));
    $('#updateTicket' ).find('#em_vehicle_div_' + eid).remove();
}
function removeEmailDivCC(eid) {
    //console.log('remove cc');
    var rep = "," + eid;
    $("#ccList").val($("#ccList").val().replace(rep, ""));
    $("#ccList").val($("#ccList").val().replace(eid, ""));
    //console.log('#em_vehicle_div_' + eid);
    $('#updateTicket').find('#cc_em_vehicle_div_' + eid).remove();
}
// function filterTeamList(){
//   var did=$( "#type option:selected" ).attr('data-did'); 
//   var teamOptions='';
//   $('#allot').html('');
//   $.each(teamList,function(i,member){

//     if(member.did==did){
//       teamOptions = teamOptions + "<option value='"+member.teamid+"' data-did="+member.did+">"+member.name+"</option>";
//     }

//   });
//   $('#allot').html(teamOptions);
// }
function submitTicket(){
    var data = $('#updateTicket').serialize();
    $.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data:"updateTicket=1&"+data,
        success: function(res){
            if(res=='ok'){
                window.location="myticket.php";
            }
        }
    });
}
function displayNotes(json){
    var ticketId = $('#ticketid').val();
        jQuery.ajax({
                type: "POST",
                url: "Docket_functions.php",
                data: "pullNotes=1&ticketid="+ticketId,
                cache: false,
                success: function (json) {
                    if (json.length > 0) {
                    var count = 0;
                    var trHTML = '<table class="notesList" id="notesList"><tr><th>NOTE</th><th>CREATED BY</th></tr>';
                    var note=JSON.parse(json);
                    $.each(note, function (i, item) {
                        trHTML+='<td style="text-align: center;">'+item.note+'</td>';
                        trHTML+='<td style="text-align: center;">'+item.create_by+'</td>';
                        trHTML+='</tr>';
                    });
                    $('#notesDiv').append(trHTML);
                    $('#dataTable th').css({'background': 'white', 'border-color': 'black', 'font-weight': 'bold'});
                    $('#dataTable td').css({'border-color': 'black'});
                } else {
                    $('#historyTable').html('History not available');
                }
                    //window.location.reload(true);
                }
            });
}
function scrollSmoothToBottom (id) {
   var div = document.getElementById(id);
   $('#' + id).animate({
      scrollTop: div.scrollHeight - div.clientHeight
   }, 500);
}
function addNote(){
    var note = $('#note').val();
    var ticketId = $('#ticketid').val();
    $.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data:"insertNote=1&note="+note+"&ticketid="+ticketid,
        success: function(res){
            if(res == 'Success'){
                $('#notesDiv').html('');
                scrollSmoothToBottom('notesDiv');
                displayNotes();
            }
        }
    });
}
$( document ).ajaxStop(function() {
    //filterTeamList();
});
function insertMailId() {
    var data = '';
    data = customerno;
    var emailid1;
    var emailText1 = document.getElementById("email").value;
    var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

    if (!testEmail.test(emailText1)) {
        alert("Enter Valid Mail Id");
        return false;
    }
    else {
        jQuery.ajax({
            url: 'Docket_functions.php?work=insertmailforTech&dataTest=' + emailText1 + '&customerno1=' + data,
            type: 'post',
            success: function (data1) {
                insertEmailDiv(emailText1,data1);
            }
        });
        jQuery("#email").val("");
    }
}
