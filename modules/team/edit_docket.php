<?php
include_once("session.php");
include("loginorelse.php");
include("../../lib/bo/DocketManager.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/datagrid.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/components/gui/objectdatagrid.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i"); 
include("header.php");
$docketid=$_GET['docketid'];

$dm=new DocketManager();
$docketArray=$dm->fetchDockets(GetLoggedInUserId(),$docketid);
$purposes=explode(",",$docketArray[0]['purpose_id']);
$docketArray=$docketArray[0];
$datetime=explode(' ',$docketArray['raiseondate']);
$raiseondate=$datetime[0];
$raiseontime=$datetime[1];
$teamid=GetLoggedInUserId();
    ?>
    <link rel="stylesheet" href="../../css/docketStyle.css">
    <style>
        .panel{
    width:1274px !important;
    }
    .paneltitle{
    width:1258px !important;
    }
    .close{
      font-size: 30px !important;    
    }
    </style>
    <div class="panel" style=''>
        <div class="paneltitle" align="center">Edit docket</div>
        <div class="panelcontents" style="height:100%;width:100%;">
            <div class="center">
                <form name="editDocket" id="editDocket" method="POST">
                    <input type="hidden" id="docket_id" name="docket_id" value="<?php echo $docketid ?>">
                    <div class="rows">
                        <div class="column">
                            <label>Customer </label>
                            <input type="text" name="customername" id="customername" size="30" value='<?php echo $docketArray['customerno']."-".$docketArray['customername']?>'autocomplete="off" placeholder="Enter Customer Name or number" disabled />
                            <input type="hidden" id="customerno" name="customerno" value='<?php echo $docketArray['customerno']?>'>
                            <br>
            <!-- <div class="PurposeDropdown" id="PurposeDropdown">
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
            </div> -->
                            <br>
                            <label>Date</label>
                            <input type="text" name="raiseondate" id="raiseondate" placeholder="dd-mm-yyyy" value="<?php echo $today; ?>" disabled>
                            <label>Time</label>
                            <input id="raiseontime" name="raiseontime" type="text" class="input-mini" value="<?php echo $time; ?>" disabled>
                        </div>
                        <div class="column">
                            <label>CRM</label><input type="text" id="crmmanager" value="<?php echo $docketArray['name']; ?>" disabled>
                            <div class="interaction_type_div">
                                <label>Interaction Type</label>
                                <select name="interactionId" id="interactionId" disabled>
                            </select>
                            <br><br>
                                  <div id="c_type" style="float:left;padding-right: 10px;">
                                    <label>Call Type</label>
                                    <br>
                                    <div id="in_type_div" style="display:none;width:auto !important;margin:10px 0 0 0 !important;">
                                        <b style="font-size: 13px;">Inbound</b>
                                        <img src="../../images/inbound_call.png"  width="18" height="18">
                                    </div>
                                    <div id="out_type_div" style="display:none;width:auto !important;margin:10px 0 0 0 !important;">
                                        <b style="font-size: 13px;">Outbound</b>
                                        <img src="../../images/outbound_call.png" width="18" height="18">
                                    </div>
                                </div>
                                <div id="c_type1" style="float:right;">
                                    <label>Response</label>
                                    <div id="in_type_div1" style="display:none;width:auto !important;margin:10px 0 0 0 !important;">
                                        <b style="font-size: 13px;">Answered</b>
                                        <img src="../../images/success.png" width="20" height="24">
                                    </div>
                                    <div id="out_type_div1" style="display:none;width:auto !important;margin:10px 0 0 0 !important;">
                                        <b style="font-size: 13px;">Unanswered</b>
                                        <img src="../../images/fail.png" width="20" height="24">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type='hidden' id="teamid" name="teamid" value='<?php echo $docketArray['team_id']?>'>
                    <div id='ticketDiv' name='ticketDiv' style="max-width:100%!important;">
                    </div>
                     <div id='bucketDiv' name='bucketDiv' style="max-width:100%!important;">
                    </div>

                    <input type="button" name="edit_docket" id="edit_docket" value="Submit" style='margin-left:40%;' onclick="mydockets()">
                    <div class='row'>
                    <div class='column' id='tickets' style='margin-top:2%;margin-left:4%;border:1px #888 solid;display: none;width:auto;'>
                            <h4>Tickets</h4>
                            <br>
                            <div id="ticketGrid" style="height: 131px; width:1150px;margin:2px;z-index: 0;" class="ag-theme-blue" ui-grid="gridOptions" ui-grid-exporter ui-grid-selection>
                            </div>
                            <input type='button' id='addTicket' name='addTicket' value='Add Ticket'>
                        </div>
                        <br>
                    <div class='column' id='buckets' style='margin-top:2%;margin-left:4%;border:1px #888 solid;display: none;width:auto;'>
                            <h4>Buckets</h4>
                            <br>
                            <div id="bucketGrid" style="height: 131px; width:1150px;margin:2px;" class="ag-theme-blue">
                            </div>
                        <input type='button' id='addBucket' name='addBucket' value='Add Bucket'>
                        </div>
                            
                        </div>
                    </div>
                    <br>
                </form>
                <div id="docketGrid" style="height: 131px; width:80%; margin: 0 auto; display: none" class="ag-theme-blue" ></div>
            </div>
            </div>
        </div>
        <!-- The Modal -->
        <div id="historyModal" class="modal">

          <!-- Modal content -->
          <div class="history-content" id='historyTable'>
            <span class="close" id='closeModal'>&times;</span>
          </div>

        </div>
    
            
<style>
.bucketHistory tr,td{
    text-align: center;
}


}
</style>
<script src='../../scripts/team/docket.js'></script>
<script src='../../scripts/team/bucket.js'></script>
<script src="https://unpkg.com/ag-grid/dist/ag-grid.min.js"></script>
<script>
function loadDockets(){

    var docketCols = [
        {headerName: "Docket id", field: "docketid", filter: "agNumberColumnFilter" },
        {headerName: "Customer", field: "customerno", filter: "agNumberColumnFilter"},
        {headerName: "Purpose", field: "purpose"},
        {headerName: "Interaction", field: "interaction"}
    ];
    var docketRows=[];
    var record={};
    var i =0;

    //console.log(array);
    for(i=0;i<array.length;i++){
        record= {
                docketid:Number(array[i].docketid),
                customerno: Number(array[i].customerno),
                purpose: array[i].purpose_type,
                interaction: "<input type='button' onclick='alert();'>"
            };
        docketRows[i]=record;
    }

    // let the grid know which columns and what data to use
    var gridOptions = {
        columnDefs: docketCols,
        rowData: docketRows,
        enableFilter: true,
        onGridReady: function (params) {
            params.api.sizeColumnsToFit();
        }
    };
    var eGridDiv = document.querySelector('#docketGrid');
    // create the grid passing in the div to use together with the columns & data we want to use
    new agGrid.Grid(eGridDiv, gridOptions); 
}

function loadBuckets(){
   
    var bucketCols = [
        {headerName: "Bucket id", field: "bucketid", sort: 'asc', filter: "agNumberColumnFilter"},
        {headerName: "Status", field: "status", filter: "agTextColumnFilter"},
        {headerName: "FE" ,field:"feName"},
        {headerName: "Appointment date", field:"aptDate"},
        {headerName: "Purpose", field: "purpose_type"},
        {headerName: "Timeslot", field: "timeslot"},
        {headerName: "Edit Bucket", cellRenderer:edit_bucket},
        {headerName: "Bucket history", cellRenderer:bucketHistory}
    ];
    var bucketRows=[];
    var record={};
    var i =0;
    var result;
    var flag=false;


    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "getBuckets=1&docketId="+<?php echo $docketid?>,
        success: function(data) {
            var priorities = '';
            //console.log(data);
            var result = JSON.parse(data);
            $.each(result, function(i, text) {
                //console.log(text);
                    if(text.purposeid==1)
                    {
                        text.purpose_type = "Installation";
                    }
                   else if(text.purposeid==2)
                    {
                        text.purpose_type = "Repair";
                    }
                   else if(text.purposeid==3)
                    {
                        text.purpose_type = "Removal";
                    }
                   else if(text.purposeid==4)
                    {
                        text.purpose_type = "Replacement";
                    }
                   else if(text.purposeid==5)
                    {
                        text.purpose_type = "Reinstall";
                    }
                     
                     var date = text.apt_date;
                     date = date.split("-");                //This is done because the date is stored 
                     text.apt_date= date[2]+ "-" +date[1]+ "-" +date[0];// as yyy-mm-dd
                    
                    flag=false;
                    var e = $("input[id^='BucketId']");
                    $.each(e,function(i,bucketid){
                    if(bucketid.value==text.bucketid){
                        flag=true;
                    }else{
                    }
                    });
                if(flag==false){
                    id=bucket_createModal(text);
                }else{
                    loadData_bucket(i,text);
                }

                record= {
                    bucketid:Number(text.bucketid),
                    status: text.status,
                    purpose_type: text.purpose_type,
                    timeslot: text.timeslot,
                    statusid : text.statusID,
                    feName : text.feName,
                    aptDate : text.apt_date,
                    prevBucketId : text.prevBucketId
                };
             
                bucketRows[i]=record;

            });
            var bucketGridOptions = {
                columnDefs: bucketCols,
                rowData: bucketRows,
                enableFilter: true,
                onGridReady: function (params) {
                    params.api.sizeColumnsToFit();
                }
            };
            var bucketGridDiv = document.querySelector('#bucketGrid');
            new agGrid.Grid(bucketGridDiv, bucketGridOptions);
        }
    });   
}
function bucketHistory(params){
    if(params.data.prevBucketId!==null){
        return "<a href='#' onclick='pullBucketHistory("+params.data.bucketid+")'>History</a>";
    }
}
function edit_bucket(params){
    if(params.data.statusid==0||params.data.statusid==4){
        var html="<a href='#' onclick=editBucket("+params.rowIndex+","+params.data.bucketid+")>Edit</a>";
    }
    return html;
}
$('#closeModal').on('click',function(){
    $('#historyModal').hide();
});
function pullBucketHistory(bucketid){
    $.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "pullBucketHistory=1&bucketid="+bucketid,
        success: function(response){
            result = JSON.parse(response);
            var table = $('#bucketHistory');
            if(table.length>0){
                $('#bucketHistory').remove();
            }
            modal=$('#historyModal');
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            var str="<table class='bucketHistory' id ='bucketHistory' border='1' align='center'><tr><th>Sr no.</th><th>Bucket ID</th><th>Remarks</th></tr>";
            $.each(result,function(i,bucket){
                var j = i+1;
                str+="<tr>";
                str+="<td>"+j+"</td>";
                str+="<td>"+bucket.bucketid+"</td>";
                str+="<td>"+bucket.remarks+"</td>";
                str+="</tr>";
            });
            str+="</table>";
            $('#closeModal').after(str);
            $('#historyModal').show();
        }
    });
}

function edit_ticket(params){
    //console.log(params);
    var html="<a href='#' onclick=editTicket("+params.rowIndex+","+params.data.ticketid+")>Edit</a>";
    return html;
}
var ticketGridOptions;
function loadTickets(){

    var ticketCols = [

        {headerName: "Ticket id", field: "ticketid", filter: "agNumberColumnFilter" },
        {headerName: "Title", field: "ticket_title"},
        {headerName: "Type", field: "ticket_type"},
        {headerName: "Priority", field: "prname"},
        {headerName: "Status", field: "ticket_status"},
        {headerName: "Allot To", field: "allot"},
        {headerName: "Product", field: "product"},
        {headerName: "Edit Ticket", cellRenderer:edit_ticket}
    ];
    var ticketRows=[];
    var record={};
    var i =0;
    var result;
    var eids;
    var flag=false;
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: "docketId="+<?php echo $docketid?>+"&fetchTickets=1",
        success: function(data){

            result = JSON.parse(data);
            $.each(result, function(i, text) {
                var ids = text.send_mail_to;
                var cc = text.send_mail_cc;
                //console.log("ids");
                //console.log(ids);
                if(text.send_mail_to!=''||text.send_mail_cc!=''){
                    $.ajax({
                        type: "POST",
                        url: "Docket_functions.php",
                        data:"ids="+ids+"&translateEmails=1"+"&CCs="+cc,
                        success: function(res){
                            data = JSON.parse(res);
                            eids=data.ids;
                            //console.log(eids+" for "+i);
                            if(eids!=undefined){
                                $.each(eids,function(j,val){
                                    insertEmailDiv(val.emailid, val.id, i);
                                });
                            }
                            cc=data.cc;
                            //console.log(cc+" for "+i);
                            if(cc!=undefined){
                                $.each(cc,function(j,val){
                                    insertEmailDivCC(val.emailid, val.id, i);
                                });
                            }
                        }
                    });
                }
                var e = $("input[id^='ticketId']");
                $.each(e,function(i,element){

                    if(element.value==text.ticketid){
                        flag=true;
                    }
                });
                if(flag==false){
                    id=ticket_createModal(text);
                    //console.log("Not found, creating.");
                }else{
                    //console.log("found, updating text.");
                    loadData(i,text);
                }
                record= {
                    ticketid:Number(text.ticketid),
                    customerid: text.customercompany,
                    ticket_type: text.tickettype,
                    ticket_status:text.ticketStatus,
                    allot:text.allot_to,
                    product:text.prod_id,
                    ticket_title: text.title,
                    prname: text.prname,
                };
                ticketRows[i]=record;
            });
            ticketGridOptions = {
                columnDefs: ticketCols,
                rowData: ticketRows,
                enableFilter: true,
                onGridReady: function (params) {
                    params.api.sizeColumnsToFit();
                }
            };
            var ticketGridDiv = document.querySelector('#ticketGrid');
            new agGrid.Grid(ticketGridDiv, ticketGridOptions);
            
        }
    });   
  
}

function refreshGrid(id){

    var teamid=<?php echo GetLoggedInUserId();?>;
    //console.log(ticketArray);
    data1=$('#formId'+id).serialize();
    //console.log(data1);
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: data1 + "&edit_ticket=1&customerno="+$('#customerno').val()+"&id="+id+"&teamid="+teamid+"&docketId="+<?php echo $docketid?>+"&purposes="+purposes,
        success: function(data) {
            var result = JSON.parse(data);
            if($('#ticketId'+id).val()==''){
                $('#ticketId'+id).val(result.ticketid);
            }
            $("#title"+id).prop("disabled", true);
            ticket_hideModal(id);
            $('#ticketGrid').html('');
            $('#ticketForm'+id).hide();            
            loadTickets();
            uploadnewFile(id);
           

        }
    });
    
}

function refreshGrid_bucket(id){
    
    if ($('#OperationType' + id).val() == 0) {
        alert("Please select type of bucket");
        return;
    }

     if ($('#vehicleno' + id).val() == '' && $('#OperationType' + id).val() != 1) {
        alert("Please Enter Vehilce Number");
        return;
    }

    var teamid=<?php echo GetLoggedInUserId();?>;
    //console.log(bucketArray);
    $('#OperationType'+id).attr('disabled',false);
    data1=$('#bucketFormId'+id).serialize();
    //console.log(data1);
    jQuery.ajax({
        type: "POST",
        url: "Docket_functions.php",
        data: data1 + "&edit_bucket=1&customerno="+$('#customerno').val()+"&id="+id+"&teamid="+teamid+"&docketId="+<?php echo $docketid?>,
        success: function(data) {
            if(IsJsonString(data)){
                var response = JSON.parse(data);
                $('#BucketId'+id).val(response.bucketID);
            }
            if($('#BucketId'+id).val()==''){
                $('#BucketId'+id).val(data);
            }
            //Bucket_hideModal(id);

            
            $("#coname"+id).val('');   //reset and removeAttr are used to reset respective'inputs'
            $("#cophone"+id).val('');
            $("#reschedule_date"+id).val(''); //to reset value after bucket is rescheduled
            $('#sstatus'+id).val(0);
            $('#newCord'+id).removeAttr('checked');
            $('#scoordinator'+id).removeAttr('disabled');
            $('#bucketGrid').html('');
            $('#bucketForm'+id).hide();
            changeFunc(id);              //to hide the divisions.
            loadBuckets();
        }
    });
    
}
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
    var ticketCount = 0;
    jQuery(document).ready(function () {
        //loadDockets();
        loadBuckets();
        loadTickets();
        var purposeArray=<?php echo json_encode($purposes);?>
    });
</script>
<script>
    
    //loads docket data
    jQuery(document).ready(function () {

        var callType = '<?php echo $docketArray['i_type']; ?>'   
        if(callType==1)
        {
            $("#in_type_div").show();
        } 
        if(callType==0)
        {
            $("#out_type_div").show();
        }


        var ResponseType = '<?php echo $docketArray['response']; ?>'   
        if(ResponseType==1)
        {
            $("#in_type_div1").show();
        } 
        if(ResponseType==0)
        {
            $("#out_type_div1").show();
        }


    
    });



    //loads docket data
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
                //console.log('<?php echo $docketArray['interaction_type'];?>');
                 if (text.interaction_id=='<?php echo $docketArray['interaction_id'];?>'){
                    $('#interactionId').append('<option value = '+text.interaction_id+' selected>'+text.interaction_type+'</option>');
                }else{
                    $('#interactionId').append('<option value = '+text.interaction_id+'>'+text.interaction_type+'</option>');
                }
            });
            }
          
        });
 var flag=0
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

function getSelectedValue(id) {
  return $("#" + id).find("dt a span.value").html();
}
        jQuery.ajax({
                    type: "POST",
                    url: "Docket_functions.php",
                    data: "get_purpose=1",
                   success: function(data){
                    var data=JSON.parse(data);
                    $('#purposeId').html("");
                  //<-------- add this line
                $.each(data ,function(i,text){
                $('#purposeId').append("<li ><input type='checkbox' class='checkbox1' id='checkbox"+i+"' name='checkbox"+i+"' value='"+text.purpose_id+"' disabled><label>"+text.purpose_type+"<label></li>");
            });
                    var array = <?php echo json_encode($purposes);?>;
                    $.each(array,function(i,purpose){
                        var options = $("#purposeId").find('input').filter("[value="+purpose+"]");
                        //console.log(options);
                        options.prop('checked','true');
                        options.prop('disabled','true');
                        if(purpose==1||purpose==3||purpose==4||purpose==5){
                            $('#tickets').show();
                        }else if(purpose==2){
                            $('#buckets').show();
                        }

                    });
                    $('#purposeId input[type=checkbox]').change(function(event) {
                        if ( $( this ).is( ":checked" ) ){
                          if($(this).val()==2){
                            $('#buckets').show();
                          }
                            else if($(this).val()==1){
                            $('#tickets').show();

                          }
                            //str1+=','+$(this).next('label').text();
                        }
                       

                        else{
                            if($(this).val()==2){
                            $('#buckets').hide();
                          }
                            else if($(this).val()==1){
                            $('#tickets').hide();
                            
                          }
                            
                            //str1=str1.replace(','+$(this).next('label').text(), "");
                        }
                        //console.log(str1);
                    });
            }
          
    });

    jQuery('#raiseondate').datepicker({
            dateFormat: "dd-mm-yy",
            language: 'en',
            autoclose: 1,
            startDate: Date()
        });

        jQuery('#raiseontime').timepicker({'timeFormat': 'H:i'});

      
});
    var purposes='';
$('#PurposeDropdown').on('click', function() {
        var chkd = $('input:checkbox:checked');
        purposes = chkd.map(function() {
            return this.value;
         })
        .get().join(',');
    });
</script>
<script>
function mydockets(){
     window.location.href = 'mydockets.php'; 
}

</script>