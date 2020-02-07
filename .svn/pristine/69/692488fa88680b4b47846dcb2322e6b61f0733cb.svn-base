<?php
include_once("session.php");
include("loginorelse.php");
include("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
include("header.php");
$apt_date = date('d-m-Y', strtotime("+ 1 day"));
?>
<style>
    .panel{
    width:1224px !important;
    }
    .paneltitle{
    width:1208px !important;
    }
    .close{
      font-size: 30px !important;    
    }
    </style>
<link rel="stylesheet" href="../../css/docketStyle.css">
<div class="panel" style=''>
  <div class="paneltitle" align="center">DOCKET
  </div>
  <div class="panelcontents" style="height:100%;width:100%;">
    <div class="center">
      <form name="addDocket" id="addDocket" method="POST">
        <div class="rows">
          <div class="column">
            <label>Customer
            </label>
            <input type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or number" onkeypress="getCustomer();" />
            <input type="hidden" id="customerno" name="customerno">
            <br>
            <div class="PurposeDropdown" id="PurposeDropdown">
             <dl class="dropdown1"> 
                 <dt>
                  <a href="#">
                  <span class="hida" style="color:#000000;">Purpose<i id="arrowdown" class='material-icons' style="position: absolute;left:110px;top:2px;">
                    keyboard_arrow_down
                    </i><i id="arrowup" class='material-icons' style="position: absolute;left:110px;top:2px;display: none;">
                    keyboard_arrow_up
                    </i> </span> 
                   
                  <p class="multiSel"></p>  
                  </a>
                  </dt>
                <dd>
                <div class="mutliSelect">
                  <ul name="purposeId" id="purposeId" class='testing'>
                  </ul>
                </div>
                </dd>
              </dl>
            </div>
            <br>
            <label>Date</label>
            <input type="text" name="raiseondate" id="raiseondate" placeholder="dd-mm-yyyy" value="<?php echo $today; ?>" required>
            <label>Time</label>
            <input id="raiseontime" name="raiseontime" type="text" class="input-mini" value="<?php echo $time; ?>" required>
          </div>
          <div class="column">
            <label>CRM</label>
            <input type="text" id="crmmanager" disabled>
            <div class="interaction_type_div">
              <label>Interaction Type
              </label>
              <select name="interactionId" id="interactionId">
              </select>
              <br><br>
              <div id="c_type" style="display: none;float:left;padding-right: 10px;">
                <label>Call Type
                </label>
                <div id="in_type_div" style="display: none; width:150px !important;padding: 0 10px 0 0 !important;">
                  <input name="i_type" id="in_type" type="radio" value="in" /> Inbound &nbsp;&nbsp;&nbsp;
                </div>
                <div id="out_type_div" style="display: none;">
                  <input name="i_type" id="out_type" type="radio" value="out" /> Outbound
                </div>
              </div>
              <div id="c_type1" style="display: none;float:right;">
                <label>Response
                </label>
                <div id="in_type_div1" style="display: none; width:150px !important;padding: 0 0 0 0 !important;">
                  <input name="r_type" id="in_type1" type="radio" value="answered" /> Answered
                </div>
                <div id="out_type_div1" style="display: none;">
                  <input name="r_type" id="out_type1" type="radio" value="unanswered" /> Unanswered
                </div>
              </div>
            </div>
          </div>
        </div>
        <input type='hidden' id="teamid" name="teamid">
        <div id='ticketDiv' name='ticketDiv' style="max-width:100%!important;">
        </div>
        <div id='bucketDiv' name='bucketDiv' style="max-width:100%!important;">
        </div>
        <input type="button" name="insert_docket" id="insert_docket" onclick="submitDocket();" value="Submit" style='margin-left:40%;background-color: #3b5998;
    color: #ffffff;'>
        <div class='row'>
          <div class='column' id='tickets' style='margin-left: 20px;border:1px #888 solid;display:none;'>
            <h4>Tickets
            </h4>
            <input type='button' id='addTicket' name='addTicket' value='Add Ticket'>
          </div>
          <div class='column' id='buckets' style='margin-left: 20px;border:1px #888 solid;display:none;'>
            <?php
$apt_date = date('d-m-Y', strtotime("+ 1 day"));
?>
            <h4>Buckets
            </h4>
            <input type='button' id='addBucket' name='addBucket' value='Add Bucket'>
          </div>
        </div>
        <br>
      </form>
    </div>
  </div>
<script>
  var teamList = [];
  var bucketArray = new Array(30);
  var i =0;
  for(i=0;i<bucketArray.length;i++){
    bucketArray[i] = 0;
  }
  var ticketArray =new Array(5);
  for(i=0;i<ticketArray.length;i++){
    ticketArray[i]=0;
  }
  $('#addTicket').click(function(){
    var i;
    for(i=0;i<ticketArray.length;i++){
      if(ticketArray[i]==0){
        count=i;
        ticketArray[i]=1;
        //console.log(ticketArray);
        break;
      }
      if(i==ticketArray.length-1){
        alert("Only "+ticketArray.length+" can be created per docket");
        return;
      }
    }
    
    var str="\
<div class='modal' id='modalForm"+count+"'>\
<div class='modal-content' id='modalContent"+count+"'>\
<span class='close' id='close"+count+"' onclick='ticket_hideModal("+count+")'>&times;</span>\
<span class='close' id='close"+count+"' onclick='ticket_minimizeModal("+count+")'>-</span>\
<form id='formId"+count+"' class='ticketForm' style=''>\
<table>\
<tr>\
<td> Ticket title:</td>\
<td ><input class='formControl' id='title"+count+"' name='title"+count+"' type='text'></td>\
<td>Description:</td>\
<td >\
 <textarea class='formControl' id='description"+count+"' name='description"+count+"'></textarea>\
  </td>\
  </tr>\
<tr>\
<td>Email:</td>\
<td >\
<input type='text' class='formControl' id='email"+count+"' name='email"+count+"' onkeyup='getmailids("+count+")'>\
<input type='hidden' id='emailList"+count+"' name='emailList"+count+"'>\
  <input type='hidden' id='emailIds"+count+"' name='emailIds"+count+"'>\
  </td>\
  <td> <input type='button' onclick='insertMailId("+count+")' style='background-color:#3b5998;color:#ffffff;' value='Add Mail Id' name='addMail'></td>\
  </tr>\
 <tr>\
 <td>CC:</td>\
<td >\
 <input type='text' class='formControl' id='CC"+count+"' name='CC"+count+"' onkeyup='getmailids_CC("+count+")'>\
<input type='hidden' id='CCList"+count+"' name='CCList"+count+"'>\
  <input type='hidden' id='emailIdsCC"+count+"' name='emailIdsCC"+count+"'>\
  </td>\
  </tr>\
<tr>\
<td>Ticket Priority: </td>\
<td >\
<select class='formControl' id='priority"+count+"' name='priority"+count+"'></select>\
  </td>\
<td>Ticket type: </td>\
<td>\
<select class='formControl' id='type"+count+"' name='type"+count+"' onchange='filterTeamList("+count+")'></select> </td>\
  </tr>\
<tr>\
<td>Product: </td>\
<td>\
<select class='formControl' id='product"+count+"' name='product"+count+"'></select>\
  </td>\
<input type='hidden' name='did"+count+"' id='did"+count+"'>\
<td>Allot to:</td>\
<td >\
 <select align='right ' class='formControl' id='allot"+count+"' name='allot"+count+"'></select>\
  </td>\
  </tr>\
<tr></tr>\
<td></td>\
</tr>\
<br>\
  </table>\
  <input type='button' id='saveTicket' style='margin-left:40%;background-color:#3b5998;color:#ffffff;'onclick='ticket_minimizeModal("+count+")' value='Save ticket'></td></tr>\
  </form>\
    <form id='fileForm"+count+"'><input type='file' name='file"+count+"' id='file"+count+"' onclick='fileUpload("+count+");' accept='application/zip,application/rar' value=''/></form>\
  </div>\
  </div>";
    $('#ticketDiv').after(str);
    jQuery.ajax({
      type: "POST",
      url: "Docket_functions.php",
      data: "getDetails=1",
      success: function(data){
        var priorities='';
        var result = JSON.parse(data);
        $.each(result[0],function(i,text){
          priorities = priorities + "<option value='"+text.prid+"'>"+text.priority+"</option>";
        });
        $('#priority'+count).html(priorities);
        
        teamList = [];
        teamMember={};
        var teamOptions='';
        $.each(result[1],function(i,text){

          teamMember={};
          console.log(text);
          teamOptions = teamOptions + "<option value='"+text.teamid+"' data-did="+text.department_id+">"+text.name+"</option>";
          teamMember.teamid=text.teamid;
          teamMember.name=text.name;
          teamMember.did=text.department_id;
          console.log("member did :"+teamMember.did);
          teamList.push(teamMember);
        });
        $("#did"+count).val(0);
        $('#allot'+count).html(teamOptions);

        var products = '';
        $.each(result[2],function(i,text){
          products = products + "<option value='"+text.prodId+"'>"+text.prodName+"</option>";
        });
        $('#product'+count).html(products);
        var ticketTypes='';
        $.each(result[3],function(i,text){
          ticketTypes = ticketTypes + "<option value='"+text.typeid+"' data-did='"+text.department_id+"'>"+text.tickettype+"</option>";
        });
        $('#type'+count).html(ticketTypes);
        $('#type'+count)[0].selectedIndex=4;
      }
    });
    var modal = document.getElementById("modalForm"+count+"");
    modal.style.display = "block";
  });
  function fileUpload(id){
    fileArray[id]=1;

  }
  function ticket_hideModal(id){
    if($('#title'+id).val()!=''){
      if(confirm('Are you sure you want to cancel this ticket?')){
        ticket_removeModal(id);
      }
    }
    else{
      ticket_removeModal(id);
    }
  }
  function ticket_removeModal(id){
    ticketArray[id]=0;
    //console.log(ticketArray);
    $("#modalForm"+id).remove();
    $("#minimizedTicket"+id).remove();
  }
  function ticket_minimizeModal(id)
  {
    if($('#title'+id).val()==''){
      alert("Please enter ticket title");
      return;
    }
  
    
    
      
            
var text=$('#title'+id).val();
if($('#minimizedTicket'+id).length==0){
var str="<div class='minimizedTicket' id='minimizedTicket"+id+"'>\
Ticket Title:  "+text+"\
<span onclick='editTicket("+id+");' style='float:right;text-decoration: none;cursor: pointer;'>\
<i class='material-icons'>\
edit\
  </i>\
  </span>\
<span onclick='ticket_removeModal("+id+");' style='float:right;text-decoration: none;cursor: pointer;'>\
<i class='material-icons'>\
delete\
  </i>\
  </span>\
  </div>";
      $('#modalForm'+id).hide();
      $('#addTicket').before(str);
    }
    else{
      var minTicket = $('#minimizedTicket'+id);
      minTicket.html("Ticket Title:  "+text+" \
<span onclick='editTicket("+id+");' style='float:right;text-decoration: none;cursor: pointer;'>\
<i class='material-icons'>edit\
  </i>\
  </span>\
<span onclick='ticket_removeModal("+id+");' style='float:right;text-decoration: none;cursor: pointer;'>\
<i class='material-icons'>delete\
  </i>\
  </span>\
");
      $('#modalForm'+id).hide();
    }
  }

  function editTicket(id){
    $('#modalForm'+id).show();
  }
var purposes='';
  jQuery(document).ready(function () {
    jQuery.ajax({
      type: "POST",
      url: "Docket_functions.php",
      data: "get_interaction=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#interactionId').html("");
        $('#interactionId').append('<option value = '+"0"+'>'+"Select Interaction Type"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
          $('#interactionId').append('<option value = '+text.interaction_id+'>'+text.interaction_type+'</option>');
          $("#interactionId")[0].selectedIndex=1;
        });
      }
    });

   jQuery.ajax({
                    type: "POST",
                    url: "Docket_functions.php",
                    data: "get_purpose=1",
                   success: function(data){
                    var data=JSON.parse(data);
                    $('#purposeId').html("");
                  //<-------- add this line
                $.each(data ,function(i,text){
                $('#purposeId').append("<li ><label><input type='checkbox' class='checkbox1' id='checkbox"+i+"' name='checkbox"+i+"' value='"+text.purpose_id+"'>"+text.purpose_type+"<label></li>");
            });

                     $('#purposeId input[type=checkbox]').change(function(event) {
                        if($('#checkbox0').is(":checked")|| $('#checkbox2').is(":checked")||$('#checkbox3').is(":checked")||$('#checkbox4').is(":checked"))
                        {  
                          $('#tickets').show();
                        }
                        if($('#checkbox0').is(':not(:checked)') && $('#checkbox2').is(':not(:checked)') && $('#checkbox3').is(':not(:checked)')&& $('#checkbox4').is(':not(:checked)'))
                        {  
                          $('#tickets').hide();
                        }
                        if($('#checkbox1').is(":checked"))
                        {  
                          $('#buckets').show();
                        }
                        if($('#checkbox1').is(':not(:checked)'))
                        {  
                          $('#buckets').hide();
                        }
                       
                    });
            }
          
    });


$('#PurposeDropdown').on('click', function() {
        var chkd = $('input:checkbox:checked');
        purposes='';
        $.each(chkd,function(i,checkbox){
          if(purposes==''){
            if(!(purposes.includes(checkbox.value))){
              purposes = checkbox.value;
            }
          }else{
            purposes += ","+checkbox.value;
          }
        });
        console.log(purposes);
    });

    jQuery('#raiseondate').datepicker({
        dateFormat: "dd-mm-yy",
        language: 'en',
        autoclose: 1,
        startDate: Date()
    });
    jQuery('#raiseontime').timepicker({'timeFormat': 'H:i'});
  
  });

    function insertMailId(id) {
        var data = '';
        data = jQuery('#customerno').val();
        var emailid1;
        var emailText1 = document.getElementById("email"+id).value;
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;

        if (!testEmail.test(jQuery("#email"+id).val())) {
            alert("Enter Valid Mail Id");
            return false;
        }
        else {
            jQuery.ajax({
                url: 'Docket_functions.php?work=insertmailforTech&dataTest=' + emailText1 + '&customerno1=' + data,
                type: 'post',
                success: function (data1) {
                    insertEmailDiv(emailText1,data1,id);
                }
            });
            jQuery("#email"+id).val("");
        }
    }

  function getCustomer() {
    jQuery("#customername").autocomplete({
      type:  "post",
      source: "Docket_functions.php?get_customer=1",
      select: function (event, ui) {
        jQuery(this).val(ui.item.customerno+" "+ui.item.value);
        jQuery('#customerno').val(ui.item.customerno);
        //console.log(ui.item);
        $("#customername").change(function(){
          if($('#customername').val()=="")
          {
            $('#crmmanager').val('');
            $('#teamid').val('');
          }
        });
        if(ui.item.rel_manager==0){
          alert("No crm manager has been assigned for the selected customer");
        }
        $('#teamid').val(ui.item.allot_to);
        $('#crmmanager').val(ui.item.name);
      }
    });
  }
  jQuery(document).ready(function () {
    $("#c_type").show();
    $("#in_type").attr('checked', true);
    $("#c_type1").show();
    $('#in_type1').attr('checked',true);
    $("#in_type_div1").show();
    $("#out_type_div1").show();
    $("#in_type_div").show();
    $("#out_type_div").show();
  });

  $("#interactionId").change(function(){
    var callValue = $('#interactionId').val()
    var i = callValue;
    if (i == 1)
    {
      $("#c_type").show();
      $("#in_type").attr('checked', true);
      if($('#in_type').attr('checked',true) || $('#out_type').attr('checked',true))
      {
        $("#c_type1").show();
        $('#in_type1').attr('checked',true);
        $("#in_type_div1").show();
        $("#out_type_div1").show();
      }
      $("#in_type_div").show();
      $("#out_type_div").show();
    }
    else if (i == 2)
    {
      $("#c_type").show();
      $("#in_type").attr('checked', true);
      if($('#in_type').attr('checked',true) || $('#out_type').attr('checked',true))
      {
        $("#c_type1").show();
        $('#in_type1').attr('checked',true);
        $("#in_type_div1").show();
        $("#out_type_div1").show();
      }
      $("#in_type_div").show();
      $("#out_type_div").hide();
    }
    else if (i == 3)
    {
      $("#c_type").show();
      $("#in_type_div").show();
      $("#in_type").attr('checked', true);
      $("#out_type_div").show();
      $("#c_type1").hide();
      $("#in_type_div1").hide();
      $("#out_type_div1").hide();
    }
    else if (i == 4)
    {
      $("#c_type").show();
      $("#in_type_div").hide();
      $("#out_type_div").show();
      $("#out_type").attr('checked', true);
      $("#c_type1").hide();
      $("#in_type_div1").hide();
      $("#out_type_div1").hide();
    }
    else{
      $("#c_type").hide();
      $("#in_type_div").hide();
      $("#out_type_div").hide();
      $("#c_type1").hide();
      $("#in_type_div1").hide();
      $("#out_type_div1").hide();
    }
  });
  var fileArray=new Array(ticketArray.length);
  function submitDocket(){
    if($("#customerno").val()==''){
        alert("No customer selected");
        return false;
    }
     
    else {
      var result = "";
      result = confirm("Are you sure you want to submit the docket ?");
      if(result==true) {
       
        var i =0;
        var count=0;

        var form;
        var data = $("#addDocket").serialize();
        console.log(data);
        for(i=0;i<ticketArray.length;i++){
          if(ticketArray[i]==1){
            data  = data + "&" + $('#formId'+i).serialize();
            count++;
          }
        }
       
        data = data + "&numberOfTickets=" + count;
        count=0;
        for(i=0;i<bucketArray.length;i++){
          if(bucketArray[i]==1){
              data = data + "&" + $('#bucketFormId'+i).serialize();
              count++;
          }
        }
          data = data + "&numberOfBuckets=" + count;
          jQuery.ajax({
          type: "POST",
          url: "Docket_functions.php",
          data: data+"&insert_docket=1&ticketArray="+ticketArray+"&bucketArray="+bucketArray+"&purposes="+purposes,
          success: function (response) {

           

             var result = JSON.parse(response);
             var new_docketid = result.docketid;
             var ticketIds = result.TicketIds;
             
             var i =0;
             console.log();
             var numberOfTickets = result.numberOfTickets;
              var form;
              var fd;
              var files;
              var fileName;
              var ticketIdsArray=[];
              for(i=0;i<ticketArray.length;i++){
                
                

                  if(($('#file'+i).val()!=undefined)&&($('#file'+i).val()!='')){
                    console.log("i= "+i);
                    ticketIdsArray.push(ticketIds['ticketid'+i]);
                    console.log($('#file'+i).val());
                    var form = $("#fileForm"+i);

                    if(files==undefined){
                      files = new FormData(form);
      
                      }
                    files.append('file'+i,$('#file'+i)[0].files[0]);

                  console.log(ticketIdsArray);
                  files.append("ticketids",ticketIdsArray);
                  files.append("docketid",new_docketid);
                  var customerno = $("#customerno").val();
                  files.append("customerno",customerno);
                  console.log(files); 
                   
                  }
                  
              }
            $.ajax({
                      
                      url: "uploadFile.php", 
                      type: "POST",             
                      data: files, 
                      contentType: false,       
                      cache: false,            
                      processData:false,         
                      success: function(data){  
                        
                        alert("Docket generated successfully");
                        window.location.href = 'mydockets.php'; 
                      }
                    });
          }
          });
      }
      else{
        return false;
      }
    }
  }

  function getmailids(id) {
    var data = '';
    data = jQuery('#customerno').val();
    if (data == '') {
      alert('Enter valid customer');
      jQuery("#customername").focus();
      return false;
    }
    else {
      jQuery("#email"+id).autocomplete({
        source: "route_ajax.php?work=getmail&customerno=" + data,
        select: function (event, ui) {
          insertEmailDiv(ui.item.value, ui.item.eid, id);
          //insertEmailIDs(ui.item.value,id);
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
          //insertEmailIDs_CC(ui.item.value,id);
          //insertEmailIDs(ui.item.value);
          /*clear selected value */
          jQuery(this).val("");
          return false;
        }
      });
    }
  }



  function insertEmailDiv(selected_name, eid,id) {
    jQuery("#emailList"+id).val(function (i, val) {
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
      remove_image.title='remove email ?';
      remove_image.onclick = function () {
        removeEmailDiv(selected_name,eid,id);
      };
      div.className = 'recipientbox';
      div.id = 'em_vehicle_div_' + eid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
      $("#email"+id).after(div);
      $(div).append(remove_image);
    }
  }

  function insertEmailIDs(selected_name,id)
  {
      jQuery("#emailIds"+id).val(function (i, val) {      //This function is used to set the emails 
      if (!val.includes(selected_name)) {
        return val + (!val ? '':  ',') + selected_name;
      }
      else {
        return val;
      }
    });
  }

  function removeEmailDiv(selected_name,eid,id) {
    var rep = "," + eid;
    $("#emailList"+id).val($("#emailList"+id).val().replace(rep, ""));
    $("#emailList"+id).val($("#emailList"+id).val().replace(eid, ""));
    $("#emailIds"+id).val($("#emailIds"+id).val().replace(selected_name, ""));
    $('#modalForm'+id).find('#em_vehicle_div_' + eid).remove();
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
      remove_image.title='remove email ?';
      remove_image.onclick = function () {
        removeEmailDivCC(selected_name,eid,id);
      };
      div.className = 'recipientbox';
      div.id = 'em_vehicle_div_' + eid;
      div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="em_vehicles_' + eid + '" value="' + eid + '"/>';
      $("#CC"+id).after(div);
      $(div).append(remove_image);
    }
  }

  function insertEmailIDs_CC(selected_name,id)  {
    {
      jQuery("#emailIdsCC"+id).val(function (i, val) {    //This function is used to set the emails 
      if (!val.includes(selected_name)) {
        return val + (!val ? '':  ',') + selected_name;
      }
      else {
        return val;
      }
    });
  } 
  }

  function removeEmailDivCC(selected_name,eid,id) {
    var rep = "," + eid;
    $("#CCList"+id).val($("#CCList"+id).val().replace(rep, ""));
    $("#CCList"+id).val($("#CCList"+id).val().replace(eid, ""));
    $("#emailIdsCC"+id).val($("#emailIdsCC"+id).val().replace(selected_name, ""));
    $('#modalForm'+id).find('#em_vehicle_div_' + eid).remove();
  
  }

</script>
<script>
  var bucketArray = new Array(5);
  var i =0;
  for(i=0;i<bucketArray.length;i++){
    bucketArray[i] = 0;
  }


  $('#addBucket').click(function(){

    if($('#customerno').val()==""){
      alert("Please Enter Customeno");
      return false;
  }
  else{
    for(i=0;i<bucketArray.length;i++){
      if(bucketArray[i]==0){
        bucketArray[i]=1;
       
        bucketCount=i;
        break;
      }
      if(i==bucketArray.length-1){
        alert("Only 30 buckets can be created per docket");
        return;
      }
    }

      var str="\
<div class='bucket' id='bucketForm"+bucketCount+"'>\
<div class='bucket-content' id='bucketContent"+bucketCount+"'>\
<span class='close' id='close"+bucketCount+"' onclick='Bucket_hideModal("+bucketCount+")'>&times;</span>\
<span class='close' id='close"+bucketCount+"' onclick='Bucket_minimizeModal("+bucketCount+")'>-</span>\
<form id='bucketFormId"+bucketCount+"' class='bucketForm' style=''>\
<div>\
<b>Bucket Type </b>\
<select name='OperationType"+bucketCount+"' id='OperationType"+bucketCount+"' onchange='operation_type("+bucketCount+")' >\
<option value=0>Select</option>\
<option value=1>Installation</option>\
<option value=2>Repair</option>\
<option value=4>Replacement</option>\
<option value=5>Reinstall</option>\
<option value=3 >Removal</option>\
</select>\
</div>\
<br>\
<table>\
<tr name='r_vehicle' id='r_vehicle' style='display:none;'>\
<td><label>Vehicle No</label></td>\
<td>\
<input  type='text' name='vehicleno"+bucketCount+"' id='vehicleno"+bucketCount+"' size='20' placeholder='Enter Vehicle No' onkeyup='getVehicle("+bucketCount+");'/></td>\
<td><input name = 'deviceid"+bucketCount+"' id='deviceid"+bucketCount+"' type='hidden'></td>\
<td><input name = 'simcardid"+bucketCount+"' id='simcardid"+bucketCount+"' type='hidden'></td>\
</tr>\
<?php
$apt_date = date('d-m-Y', strtotime("+ 1 day"));
?>\
<tr>\
<td><label>Appointment Date</label></td>\
<td> <input name='sapt_date"+bucketCount+"' id='sapt_date"+bucketCount+"' type='text' value='<?php echo $apt_date; ?>'  onclick='Bucketdate("+bucketCount+")'/>\
</td>\
<td><label>Priority</label></td>\
<td><select name='spriority"+bucketCount+"' id='spriority"+bucketCount+"'>\
<option value=1>High</option>\
<option value=2>Medium</option>\
<option value=3>Low</option>\
</select>\
</td>\
</tr>\
<tr name='i_bucket' id='i_bucket' style='display:none;'>\
<td><label>No. of Installations</label></td>\
<td><select name='no_inst"+bucketCount+"' id='no_inst"+bucketCount+"' onchange='no_inst_check("+bucketCount+")'>\
<option value=1 selected>1</option>\
<option value=2 >2</option>\
<option value=3 >3</option>\
<option value=4 >4</option>\
<option value=5 >5</option>\
<option value=6 >6</option>\
<option value=7 >7</option>\
<option value=8 >8</option>\
<option value=9 >9</option>\
<option value=10 >10</option>\
</select>\
</td>\
<td><label>New Vehicle No.</label></td>\
<td><input name = 'vehno"+bucketCount+"' id='vehno"+bucketCount+"' type='text' placeholder='Enter Vehicle No.'></td>\
</tr>\
<tr>\
<td><label>Location</label></td>\
<td><input name = 'slocation"+bucketCount+"' id='slocation"+bucketCount+"' type='text' placeholder='Enter Location'></td>\
<td><label>Timeslot</label>\
</td>\
<td>\
<select align='right ' class='formControl' id='stimeslot"+bucketCount+"' name='stimeslot"+bucketCount+"'></select>\</td>\
</tr>\
<tr>\
<td><label>Details</label></td>\
<td><input name = 'sdetails"+bucketCount+"' id='sdetails"+bucketCount+"' type='text' placeholder='Enter Details'></td>\
</tr>\
<tr>\
<td><label>Co-ordinator</label></td>\
<td><select align='right ' class='formControl' id='scoordinator"+bucketCount+"' name='scoordinator"+bucketCount+"' >\
<option value='0'>Select a Co-ordinator</option>\
</select>\</td>\
 <td>\
 <label>OR</label><input name = 'checkbox"+bucketCount+"' id='checkbox"+bucketCount+"' type='checkbox' onchange='checkBoxToggle("+bucketCount+")'></td\
</td>\
<td>New Co-ordinator Name <input name='coname"+bucketCount+"' id='coname"+bucketCount+"' type='text' placeholder='Enter Name' disabled >\
<br>New Co-ordinator Phone <input name='cophone"+bucketCount+"' id='cophone"+bucketCount+"' type='text' placeholder='Enter Number' disabled>\
</td>\
</tr>\
<tr name='r_email' id='r_email' style='display:none;'>\
<td><label>Send Mail</label></td>\
<td><input id='sendmailsuspect"+bucketCount+"' type='checkbox' value=1 name='sendmailsuspect"+bucketCount+"'></td>\
<td><label>Comments</label></td>\
<td><input name = 'scomments"+bucketCount+"' id='scomments"+bucketCount+"' type='text' placeholder='Enter Comments'></td>\
</tr>\
</div>\
 </table>\
<input type='button' id='saveBucket' style='margin-left:40%;background-color:#3b5998;color:#ffffff;' onclick='Bucket_minimizeModal("+bucketCount+")' value='Save Bucket'>\
  </form>\
  </div>\
  </div>";
        var Timeslot = '';
      jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "getTimeslot=1",
        success: function(data){
          var result = JSON.parse(data);
          Timeslot = '';
          $.each(result,function(i,text){
            Timeslot = Timeslot + "<option value='"+text.tsid+"'>"+text.timeslot+"</option>";
          });
          $('#stimeslot'+bucketCount).html(Timeslot);
        }
      });
      var Cordinator = '';
      var data = $('#customerno').val();
      jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "customerno="+data+"&getCordinator=1",
        success: function(data){
          var result = JSON.parse(data);
          //console.log(data);
          Cordinator = Cordinator + '<option value = '+"0"+'>'+"Select Cordinator"+'</option>';
          $.each(result,function(i,text){
            Cordinator = Cordinator + "<option value='"+text.cpdetailid+"'>"+text.person_name+"</option>";
          });
          $('#scoordinator'+bucketCount).html(Cordinator);
        }
      });
      $('#bucketDiv').after(str);
      var bucket = document.getElementById("bucketForm"+bucketCount+"");
      bucket.style.display = "block";

    }
    });


  function operation_type(id){
    var i = $("#OperationType"+id).val();
    if(i==0){
      $("#i_bucket").hide();
      $("#i_vehicle").hide();
      $("#r_email").hide();
      $("#r_comments").hide();
      $("#r_vehicle").hide();
      $("reins_vehicle").hide();
    }
    else if(i==1){
      $("#i_bucket").show();
      $("#i_vehicle").show();
      $("#r_email").hide();
      $("#r_comments").hide();
      $("#r_vehicle").hide();
      $("reins_vehicle").hide();
    }
    else if(i==3){
      $("#i_bucket").hide();
      $("#i_vehicle").hide();
      $("#reins_vehicle").show();
      $("#r_email").show();
      $("#r_comments").show();
      $("#r_vehicle").show();
     
    }
    else{
      $("#i_bucket").hide();
      $("#i_vehicle").hide();
      $("#r_email").show();
      $("#r_comments").show();
      $("#r_vehicle").show();
      $("reins_vehicle").hide();
    }
  }
  function Bucket_hideModal(id){
    if($('#title'+id).val()!=''){
      if(confirm('Are you sure you want to cancel this bucket?')){
        Bucket_removeModal(id);
      }
    }
    else{
      Bucket_removeModal(id);
    }
  }
 function checkBoxToggle(id){
  var val = $('#checkbox'+id).val();
  if(val==0){
    $('#coname'+id).prop('disabled',true);
    $('#cophone'+id).prop('disabled',true);
    $('#scoordinator'+id).prop('disabled',false);
    val=1;
  }else{
    $('#coname'+id).prop('disabled',false);
    $('#cophone'+id).prop('disabled',false);
    $('#scoordinator'+id).prop('disabled',true);
    val=0;
    }
    
    $('#checkbox'+id).val(val);
  
  }
   
  
  function Bucket_removeModal(id){
    bucketArray[id]=0;
    //console.log("form ID : "+id);
    $("#bucketForm"+id).remove();
    $("#minimizedBucket"+id).remove();
  }

  function editBucket(id){
    $('#bucketForm'+id).show();
  }

  function no_inst_check(id){
    if($('#no_inst'+id+ ' option:selected').text()>1){
      $('#vehno'+id).prop('disabled','disabled');
    }
    else{
      $('#vehno'+id).prop('disabled',false);
    }
  }

  function Bucketdate(id){
    jQuery('#sapt_date'+id).datepicker({
      dateFormat: "dd-mm-yy",
      language: 'en',
      autoclose: 1,
      startDate: Date()
    });
  }

  function Bucket_minimizeModal(id) {
    if ($('#OperationType' + id).val() == 0) {
        alert("Please select type of bucket");
        return;
    }

     if ($('#vehicleno' + id).val() == '' && $('#OperationType' + id).val() != 1) {
        alert("Please Enter Vehilce Number");
        return;
    }

   
    var no_inst = $('#no_inst' + id + ' option:selected').text();
    if ($('#OperationType' + id + ' option:selected').val() == 1) {
        var text = $('#OperationType' + id + ' option:selected').text();
        text += no_inst;
        if ($('#minimizedBucket' + id).length == 0) {
            var str = "<div class='minimizedBucket' id='minimizedBucket" + id + "'>\
    Bucket Title:  " + text + "\
    <span onclick='editBucket(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>\
    edit\
      </i>\
      </span>\
    <span onclick='Bucket_removeModal(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>\
    delete\
      </i>\
      </span>\
      </div>";
            $('#bucketForm' + id).hide();
            $('#addBucket').before(str);
        } else {
            var minBucket = $('#minimizedBucket' + id);
            minBucket.html("Bucket Title:  " + text + " \
    <span onclick='editBucket(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>edit\
      </i>\
      </span>\
    <span onclick='Bucket_removeModal(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>delete\
      </i>\
      </span>\
    ");
            $('#bucketForm' + id).hide();
        }
    } else {
        var text = $('#OperationType' + id + ' option:selected').text();
        if ($('#minimizedBucket' + id).length == 0) {
            var str = "<div class='minimizedBucket' id='minimizedBucket" + id + "'>\
    Bucket Title:  " + text + "\
    <span onclick='editBucket(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>\
    edit\
      </i>\
      </span>\
    <span onclick='Bucket_removeModal(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
    <i class='material-icons'>\
    delete\
      </i>\
      </span>\
      </div>";
            $('#bucketForm' + id).hide();
            $('#addBucket').before(str);
        } else {
            var minBucket = $('#minimizedBucket' + id);
            minBucket.html("Bucket title:  " + text + " \
                <span onclick='editBucket(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
                <i class='material-icons'>edit\
                  </i>\
                  </span>\
                <span onclick='Bucket_removeModal(" + id + ");' style='float:right;text-decoration: none;cursor: pointer;'>\
                <i class='material-icons'>delete\
                  </i>\
                  </span>\
            ");
            $('#bucketForm' + id).hide();
          }
      }
    }

  function getVehicle(id) {

        var vehicleno = '';
        var data = $('#customerno').val();
        jQuery("#vehicleno"+id).autocomplete({
            source: "Docket_functions.php?customerno=" + data+"&getVehicle=1",
            select: function (event, ui) {
                jQuery(this).val(ui.item.value); 
                jQuery('#deviceid'+id).val(ui.item.uid);
                jQuery('#simcardid'+id).val(ui.item.simcardid);

            }
        });
    }



var flag=0;
$(".dropdown1 dt a").on('click', function() {
  $(".dropdown1 dd ul").slideToggle('fast'); 
  if(flag==0){
  $("#arrowdown").hide();
  $("#arrowup").show();
  flag=1;
  }
  else if(flag==1){
  $("#arrowdown").show();
  $("#arrowup").hide();
  flag=0;
  }
});
</script>
<script>
function filterTeamList(id){
  var did=$( "#type"+id+" option:selected" ).attr('data-did'); 
  var x=0;
  var teamOptions='';
  $('#allot'+id).html('');
  if(did==8){
    $.each(teamList,function(i,member){x++;
      teamOptions = teamOptions + "<option value='"+member.teamid+"' data-did="+member.did+">"+member.name+"</option>";
  });
  }else{
    $.each(teamList,function(i,member){
      if(member.did==did){
        teamOptions = teamOptions + "<option value='"+member.teamid+"' data-did="+member.did+">"+member.name+"</option>";
      }
    });
  }
  $('#allot'+id).html(teamOptions);
}
  </script>