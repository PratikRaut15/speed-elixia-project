var ticketDetails = [];
var ticketArray = new Array(5);
var fileArray=new Array(ticketArray.length);
var i = 0;
for (i = 0; i < ticketArray.length; i++) {
  ticketArray[i] = 0;
}




console.log(ticketArray);
$('#addTicket').click(function() {
    var i;
    for (i = 0; i < ticketArray.length; i++) {
        if (ticketArray[i] == 0) {
            ticketCount = i;
            ticketArray[i] = 1;
            console.log(ticketArray);
            break;
        }
        if (i == ticketArray.length - 1) {
            alert("Only " + ticketArray.length + " can be created per docket");
            return;
        }
    }
    var str = "\
                <div class='modal' id='ticketForm" + ticketCount + "'>\
        <div class='modal-content' id='modalContent" + ticketCount + "'>\
        <span class='close' id='close" + ticketCount + "' onclick='ticket_removeModal(" + ticketCount + ")'>&times;</span>\
        <span class='close' id='close" + ticketCount + "' onclick='ticket_hideModal(" + ticketCount + ")'>-</span>\
        <form id='formId" + ticketCount + "' class='ticketForm' style=''>\
       <table>\
          <tr>\
          <input type='hidden' id='ticketId" + ticketCount + "' name='ticketId" + ticketCount + "' value=''>\
            <td style='vertical-align:middle'>\ Ticket title:<input class='formControl' id='title" + ticketCount + "' name='title" + ticketCount + "' type='text'></td>\
            <td style='vertical-align:middle'>\
                Description: <textarea class='formControl' id='description" + ticketCount + "' name='description" + ticketCount + "'></textarea>\
            </td>\
        </tr>\
        <tr>\
            <td style='vertical-align:middle'>\
                Email:<input type='text' class='formControl' id='email" + ticketCount + "' name='email" + ticketCount + "' onkeyup='getmailids(" + ticketCount + ")'><input type='hidden' id='emailList" + ticketCount + "' name='emailList" + ticketCount + "'>\
            </td>\
            <td style='vertical-align:middle'>\
                CC: <input type='text' class='formControl' id='CC"+ticketCount+"' name='CC"+ticketCount+"' onkeyup='getmailids_CC("+ticketCount+")'>\
<input type='hidden' id='CCList"+ticketCount+"' name='CCList"+ticketCount+"'>\
            </td>\
        </tr>\
        <tr>\
            <td style='vertical-align:middle'>\
                Ticket Priority: <select class='formControl' id='priority" + ticketCount + "' name='priority" + ticketCount + "'></select>\
            </td>\
            <td style='vertical-align:middle'>\
                Allot to: <select align='right ' class='formControl' id='allot" + ticketCount + "' name='allot" + ticketCount + "'></select>\
            </td>\
        </tr>\
            <td style='vertical-align:middle'>\
                Product: <select class='formControl' id='product" + ticketCount + "' name='product" + ticketCount + "'></select>\
            </td>\
            <td style='vertical-align:middle'>\
                Ticket type: <select class='formControl' id='type" + ticketCount + "' name='type" + ticketCount + "' onchange='filterTeamList("+ticketCount+")'></select>\
            </td>\
          </tr>\
          <tr></tr>\
          <tr><td colspan=2 style='text-align:center'><input type='button' id='saveTicket' onclick='refreshGrid(" + ticketCount + ");' value='Save ticket'></td></tr>\
        </table>\
        </form>\
        <table>\
        <tr>\
        <td>\
        <form id='fileForm"+ticketCount+"'><input type='file' name='file"+ticketCount+"' id='file"+ticketCount+"' onclick='fileUpload("+ticketCount+");'/></form>\
        </td>\
        <td>\
        <a href='' id='downloadLink"+ticketCount+"' download='' style='display:none;'><p>DOWNLOAD</p></a>\
        </td>\
        </tr>\
        </table>\
        </div>\
                </div>";
    $('#ticketDiv').after(str);
    loadData(ticketCount);
    var modal = document.getElementById("ticketForm" + ticketCount + "");
    modal.style.display = "block";
});

function uploadnewFile(id){

         var new_docketid = $('#docket_id').val();
         var ticketId = $('#ticketId'+id).val();

          var form;
          var fd;
          var files;
          var fileName;
          var ticketIdsArray=[];
         
            

              if(($('#file'+id).val()!=undefined)&&($('#file'+id).val()!='')){
                console.log("i= "+id);
                console.log($('#file'+id).val());
                var form = $("#fileForm"+id);
                if(files==undefined){
                  files = new FormData(form);
                }
                files.append('file'+id,$('#file'+id)[0].files[0]);

                
               
               
              }
             

        files.append("ticketids",ticketId);
        files.append("docketid",new_docketid);
        var customerno = $("#customerno").val();
        files.append("customerno",customerno);
        console.log(files);
               

        $.ajax({
                  
                  url: "ajax_php_file.php", 
                  type: "POST",             
                  data: files, 
                  contentType: false,       
                  cache: false,            
                  processData:false,         
                  success: function(data){  
                    $('#file'+id).val('');
                    downloadfile(id,ticketId);
                  }
                });
}
var teamList = [];
function loadData(ticketCount, details = {
    "priority": "1",
    "allot_to": "1",
    "prodId": "1",
    "ticket_type": "1",
    "status": "0"
}) {
    ticketArray[ticketCount]=1;
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "getDetails=1",
        success: function(data) {
            var priorities = '';
            var result = JSON.parse(data);
            $.each(result[0], function(i, text) {
                if (details.priority == text.prid) {
                    priorities = priorities + "<option value='" + text.prid + "' selected>" + text.priority + "</option>";
                } else {
                    priorities = priorities + "<option value='" + text.prid + "'>" + text.priority + "</option>";
                }
            });
            $('#priority' + ticketCount).html(priorities);

            teamList=[];
            var teamOptions='';
            $.each(result[1], function(i, text) {
                    teamMember={};
                    console.log(text);
                  teamOptions = teamOptions + "<option value='"+text.teamid+"' data-did="+text.d_id+">"+text.name+"</option>";
                  teamMember.teamid=text.teamid;
                  teamMember.name=text.name;
                  teamMember.did=text.d_id;
                  console.log("member did :"+teamMember.did);
                  teamList.push(teamMember);
            });
            $('#allot' + ticketCount).html(teamOptions);

            var products = '';
            $.each(result[2], function(i, text) {
                if (details.prodId == text.prodId) {
                    products = products + "<option value='" + text.prodId + "' selected >" + text.prodName + "</option>";
                } else {
                    products = products + "<option value='" + text.prodId + "'>" + text.prodName + "</option>";
                }

            });
            $('#product' + ticketCount).html(products);

            var ticketTypes = '';
            $.each(result[3], function(i, text) {
                if (details.ticket_type == text.typeid) {
                    ticketTypes = ticketTypes + "<option value='" + text.typeid + "' selected data-did='"+text.d_id+"'>" + text.tickettype + "</option>";
                } else {
                    ticketTypes = ticketTypes + "<option value='" + text.typeid + "' data-did='"+text.d_id+"'>" + text.tickettype + "</option>";
                }
            });
            $('#type' + ticketCount).html(ticketTypes);
            var status = '';
            $.each(result[4], function(i, text) {
                if (details.status == text.id) {
                    status = status + "<option value='" + text.id + "' selected>" + text.status + "</option>";
                } else {
                    status = status + "<option value='" + text.id + "'>" + text.status + "</option>";
                }
            });
            $('#status' + ticketCount).html(status);
        }
    });
}



function fileUpload(id){
    fileArray[id]=1;
    console.log(fileArray);
  }



function ticket_hideModal(id) {

    console.log(ticketArray);
    $("#ticketForm" + id).hide();
}

 
function ticket_removeModal(id) {
    ticketArray[id] = 0;
    console.log(ticketArray);
    $("#ticketForm" + id).remove();
}



function editTicket(id,ticketid) {

    var form=$('#ticketForm' + id);
    form.show();
    downloadfile(id,ticketid);   
}

function downloadfile(id,ticketid){

    var customerno = $("#customerno").val();
    var docketid   = $("#docket_id").val();
    var ticketid   = ticketid;
    $.ajax({
     url: 'ajaxfile.php',
     type: 'post',
     data: "customerno="+customerno+"&docketid="+docketid+"&ticketid="+ticketid,
     success: function(response){  
       if(response=='file not found'){
        $("#downloadLink"+id).hide();
       }
      else{
       $("#downloadLink"+id).show(); 
       $("#downloadLink"+id).prop("href",response);
       //$("#downloadLink"+id).prop("download",response);
         }
     }
   });   
}



function ticket_createModal(details) {
    var i;
    for (i = 0; i < ticketArray.length; i++) {
        if (ticketArray[i] == 0) {
            ticketCount = i;
            ticketArray[i] = 1;
            break;
        }
        if (i == ticketArray.length - 1) {
            alert("Only " + ticketArray.length + " can be created per docket");
            return;
        }
    }
    console.log(ticketArray);
    ////console.log(details);
    ticketDetails[ticketCount] = details;
    var str = "\
                <div class='modal' id='ticketForm" + ticketCount + "'>\
    <div class='modal-content' id='modalContent" + ticketCount + "'>\
        <span class='close' id='close" + ticketCount + "' onclick='ticket_hideModal(" + ticketCount + ")'>-</span>\
        <form id='formId" + ticketCount + "' class='ticketForm' style=''>\
       <table>\
          <tr>\
            <input type='hidden' id='createdType" + ticketCount + "' name='createdType" + ticketCount + "' value='" + details.created_type + "'>\
            <input type='hidden' id='ticketId" + ticketCount + "' name='ticketId" + ticketCount + "' value='" + details.ticketid + "'>\
            <td style='vertical-align:middle'>\ Ticket title:<input class='formControl' id='title" + ticketCount + "' name='title" + ticketCount + "' type='text' value='" + details.title + "' disabled></td>\
            <td style='vertical-align:middle'>\
                Description: <textarea class='formControl' id='description" + ticketCount + "' name='description" + ticketCount + "'>" + details.description + "</textarea>\
            </td>\
        </tr>\
        <tr>\
            <td style='vertical-align:middle'>\
                Email:<input type='text' class='formControl' id='email" + ticketCount + "' name='email" + ticketCount + "' onkeyup='getmailids(" + ticketCount + ")'><input type='hidden' id='emailList" + ticketCount + "' name='emailList" + ticketCount + "'>\
            </td>\
            <td style='vertical-align:middle'>\
                CC: <input type='text' class='formControl' id='CC"+ticketCount+"' name='CC"+ticketCount+"' onkeyup='getmailids_CC("+ticketCount+")'>\
<input type='hidden' id='CCList"+ticketCount+"' name='CCList"+ticketCount+"'>\
            </td>\
        </tr>\
                <tr>\
            <td style='vertical-align:middle'>\
                Status: <select class='formControl' id='status" + ticketCount + "' name='status" + ticketCount + "'></select>\
            </td>\
        </tr>\
        <tr>\
            <td style='vertical-align:middle'>\
                Ticket Priority: <select class='formControl' id='priority" + ticketCount + "' name='priority" + ticketCount + "'></select>\
            </td>\
            <td style='vertical-align:middle'>\
                Allot to: <select align='right ' class='formControl' id='allot" + ticketCount + "' name='allot" + ticketCount + "'></select>\
            </td>\
        </tr>\
            <td style='vertical-align:middle'>\
                Product: <select class='formControl' id='product" + ticketCount + "' name='product" + ticketCount + "'></select>\
            </td>\
            <td style='vertical-align:middle'>\
                Ticket type: <select class='formControl' id='type" + ticketCount + "' name='type" + ticketCount + "' onchange='filterTeamList("+ticketCount+")'></select>\
            </td>\
          </tr>\
          <tr></tr>\
          <tr><td colspan=2 style='text-align:center'><input type='button' id='saveTicket' onclick='refreshGrid(" + ticketCount + ");' value='Save ticket'></td></tr>\
        </table>\
        </form>\
          <table>\
        <tr>\
        <td>\
        <form id='fileForm"+ticketCount+"'><input type='file' name='file"+ticketCount+"' id='file"+ticketCount+"' onclick='fileUpload("+ticketCount+");'/></form>\
        </td>\
        <td>\
        <a href='' id='downloadLink"+ticketCount+"' download='' style='display:none;'><p>DOWNLOAD</p></a>\
        </td>\
        </tr>\
        </table>\
    </div>\
                </div>";
    loadData(ticketCount, details);
    $('#ticketDiv').after(str);
    // var modal = document.getElementById("ticketForm" + ticketCount + "");
    // modal.style.display = "block";
    return ticketCount;
}

$("#interactionId").change(function() {
    var callValue = $('#interactionId').val()

    var i = callValue;
    if (i == 1) {
        $("#c_type").show();
        $("#in_type").attr('checked', true);
        if ($('#in_type').attr('checked', true) || $('#out_type').attr('checked', true)) {
            $("#c_type1").show();
            $("#in_type_div1").show();
            $("#out_type_div1").show();
        }

        $("#in_type_div").show();
        $("#out_type_div").show();

    } else if (i == 2) {
        $("#c_type").show();
        $("#in_type").attr('checked', true);


        if ($('#in_type').attr('checked', true) || $('#out_type').attr('checked', true)) {
            $("#c_type1").show();
            $("#in_type_div1").show();
            $("#out_type_div1").show();
        }
        $("#in_type_div").show();
        $("#out_type_div").hide();

    } else if (i == 3) {
        $("#c_type").show();
        $("#in_type_div").show();
        $("#in_type").attr('checked', true);
        $("#out_type_div").show();
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();

    } else if (i == 4) {
        $("#c_type").show();
        $("#in_type_div").hide();
        $("#out_type_div").show();
        $("#out_type").attr('checked', true);
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();

    } else {
        $("#c_type").hide();
        $("#in_type_div").hide();
        $("#out_type_div").hide();
        $("#c_type1").hide();
        $("#in_type_div1").hide();
        $("#out_type_div1").hide();
    }
});

function checkForModifications(check, ticketDetails) {
    // 
    // alert("checking Form");
    //console.log("check ");
    //console.log(check);
    //console.log("details ");
    //console.log(ticketDetails);
    return true;
}

function getFormData($form) {
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function submitDocket(docketId) {
    $("#c_type").hide();
    $("#c_type1").hide();
    var data = $("#editDocket").serialize();
    var count = $("[id^='ticketForm']").length;
    var editFlag = false;
    for (i = 0; i < count; i++) {
        if ($('ticketId' + i) != '') {
            var check = getFormData($('#formId' + i))
            // //console.log(check);
            // //console.log(ticketDetails[i]);
            if (checkForModifications(check, ticketDetails[i])) {
                data = data + "&" + $('#formId' + i).serialize();
            }
        }

    }
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: data + "&edit_docket=1&numberOfTickets=" + count + "&docketid=" + docketId + "&ticketArray=" + ticketArray,
        success: function(response) {
            if (response = 'success') {
                alert("Docket generated successfully");
                //$('#editDocket')[0].reset();
            }
        }
    });
}

function getmailids(id) {
    var data = '';
    data = jQuery('#customerno').val();
    if (data == '') {
        alert('Enter valid customer');
        jQuery("#customername").focus();
        return false;
    } else {
        jQuery("#email" + id).autocomplete({
            source: "route_ajax.php?work=getmail&customerno=" + data,
            select: function(event, ui) {

                //console.log(ui);
                insertEmailDiv(ui.item.value, ui.item.eid, id);
                /*clear selected value */
                jQuery(this).val("");
                return false;
            }
        });
    }
}

    function getmailids_CC(id) {
    var data = '';
    data = jQuery('#customerno').val();
    if (data == '') {
      alert('Enter valid customer');
      jQuery("#customername").focus();
      return false;
    }
    else {
      jQuery("#CC"+id).autocomplete({
        source: "route_ajax.php?work=getmail&customerno=" + data,
        select: function (event, ui) {
          //console.log(ui);
          insertEmailDivCC(ui.item.value, ui.item.eid, id);
          /*clear selected value */
          jQuery(this).val("");
          return false;
        }
      });
    }
  }


function insertEmailDiv(selected_name, eid, id) {
    jQuery("#emailList" + id).val(function(i, val) {
        if (!val.includes(eid)) {
            return val + (!val ? '' : ',') + eid;
        } else {
            return val;
        }
    });
    if (eid != "" && $('#ticketForm' + id).find('#em_vehicle_div_' + eid).val() == null) {
        var div = document.createElement('div');
        div.id = "contain";
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removeEmailDiv(eid, id);
        };
        div.className = 'recipientbox';
        div.id = 'em_vehicle_div_' + eid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
        $("#email" + id).after(div);
        $(div).append(remove_image);
    }
}

function removeEmailDiv(eid, id) {
    var rep = "," + eid;
    $("#emailList" + id).val($("#emailList" + id).val().replace(rep, ""));
    $("#emailList" + id).val($("#emailList" + id).val().replace(eid, ""));
    $('#ticketForm' + id).find('#em_vehicle_div_' + eid).remove();
    //console.log($("#sentoEmail").val());
}

 function insertEmailDivCC(selected_name, eid,id) {
    jQuery("#CCList"+id).val(function (i, val) {
      if (!val.includes(eid)) {
        return val + (!val ? '':  ',') + eid;
      }
      else {
        return val;
      }
    });
    if (eid != "" && $('#modalForm'+id).find('#em_vehicle_div_' + eid).val() == null) {
      var div = document.createElement('div');
      div.id = "contain";
      var remove_image = document.createElement('img');
      remove_image.src = "../../images/boxdelete.png";
      remove_image.className = 'clickimage';
      remove_image.onclick = function() {
            removeEmailDivCC(eid, id);
      };
      div.className = 'recipientbox';
      div.id = 'em_vehicle_div_' + eid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
      $(div).append(remove_image);
      $("#CC"+id).after(div);
      
    }
  }
  function removeEmailDivCC(eid,id) {
    console.log('remove cc');
    var rep = "," + eid;
    $("#CCList"+id).val($("#CCList"+id).val().replace(rep, ""));
    $("#CCList"+id).val($("#CCList"+id).val().replace(eid, ""));
    console.log('#em_vehicle_div_' + eid);
    $('#ticketForm'+id).find('#em_vehicle_div_' + eid).remove();
  
  }


function filterTeamList(id){
  console.log(teamList);
  var did=$( "#type"+id+" option:selected" ).attr('data-did'); 
  console.log(did);
  var teamOptions='';
  $('#allot'+id).html('');
  $.each(teamList,function(i,member){

    if(member.did==did){
      teamOptions = teamOptions + "<option value='"+member.teamid+"' data-did="+member.did+">"+member.name+"</option>";
    }

  });
  $('#allot'+id).html(teamOptions);
}