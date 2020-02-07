<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

include("header.php");
?>
<link rel="stylesheet" href="../../css/timesheet.css">
<div class="panel">
  <div class="paneltitle" align="center">Task Assignment</div> 
    <div class="panelcontents">
      <div class="center">
        <form name="add_task" id="add_task">
          <div class="rows" >
            <div class="column">  
              <label>Customer</label>
                <input type="text" name="customername" id="customername" size="30" value="" autocomplete="off" placeholder="Enter Customer Name or Number" onkeypress="getCustomer();" required/>
                <input type="hidden" name="customerno" id="customerno"/>
              <label>Product</label>
                    <select name="product" id="product">
                    </select>
              </div>
              <div class="column">
              <label>Estimated Time</label>
                      <input type="text" name="estimated_time" id="estimated_time" placeholder="Enter Hours.Minutes"/>
              <label>Estimated Date</label>
                    <input type="text" name="estimated_date" id="estimated_date" value="<?php echo $today; ?>"/>
            </div>
          </div>
          <div class="rows" >
            <div class="column"> 
              <label>Task Name</label>
                    <input type="text" name="task_name" id="task_name" placeholder="Enter Task Name" required/>

              <label>Task Description</label>
                    <textarea name="task_description" id="task_description" placeholder="Enter Task Description"></textarea>
            </div>
            <div class="column"> 
              <label>Developer</label>
                    <select name="developer" id="developer">
                      <option value="0">Select Developer</option>
                    </select>
              <label>Tester</label>
                    <select name="tester" id="tester">
                      <option value="0">Select Tester</option>
                    </select>
            </div>
          </div> 
          <input type="button" name="sumbit_task" id="sumbit_task" align="center" style="margin:0 45%;" value="Submit" onclick="submitTask();">
        </form>
      </div>     
    </div>
</div>
<script>
  $(document).ready(function(){
    jQuery.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: "fetchProducts=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#product').html("");
        $('#product').append('<option value = '+"0"+'>'+"Select Product"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
        $('#product').append('<option value = '+text.prodId+'>'+text.prodName+'</option>');
        $("#product").selectedIndex=0;
        });
      }
    });
    jQuery.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: "fetchDevelopers=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#developer').html("");
        $('#developer').append('<option value = '+"0"+'>'+"Select Developer"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
        $('#developer').append('<option value = '+text.teamid+'>'+text.name+'</option>');
        $("#developer").selectedIndex=0;
        });
      }
    });
    jQuery.ajax({
      type: "POST",
      url: "timesheet_functions.php",
      data: "fetchTesters=1",
      success: function(data){
        var data=JSON.parse(data);
        $('#tester').html("");
        $('#tester').append('<option value = '+"0"+'>'+"Select Tester"+'</option>');
        //<-------- add this line
        $.each(data ,function(i,text){
        $('#tester').append('<option value = '+text.teamid+'>'+text.name+'</option>');
        $("#tester").selectedIndex=0;
        });
      }
    });
    jQuery('#estimated_date').datepicker({
          dateFormat: "dd-mm-yy",
          language: 'en',
          autoclose: 1,
          startDate: Date()
    });
  });
  function getCustomer() {
    jQuery("#customername").autocomplete({
            type:  "post",
            source: "timesheet_functions.php?get_customer=1",
            select: function (event, ui) {
              var customerno = ui.item.customerno;
              $("#customerno").val(customerno);
            }
    });
  }

  function submitTask(){
    var data = $("#add_task").serialize();
    var product = $("#product").val();
    var task_name = $("#task_name").val();
    var customerno = $("#customerno").val();

    if(customerno==0 || customerno==''){
      alert("Select Customer Please");
      $('#customername').focus();
      return false;
    }

    else if(product==0 || product==''){
      alert("Select Product Please");
      return false;
    }

    else if(task_name==''){
      alert("Enter Task Name Please");
      $('#task_name').focus();
      return false;
    }

    else{
      jQuery.ajax({
                  type: "POST",
                  url: "timesheet_functions.php",
                  data: "&new_task=1&"+data,
                  success: function(data){
                    var result=JSON.parse(data);
                    if(result.taskId>0){
                      alert("Task Successfully Created");
                      window.location.href = 'view_task.php'; 
                    }
                    else{
                     alert("Task Not Created.Please Try Again");
                    }
                  }
      });
    }
  }

  $('#estimated_time').on('input', function() {
          this.value = this.value
          .replace(/[^\d.]/g, '')             // numbers and decimals only
          .replace(/(\..*)\./g, '$1')         // decimal can't exist more than once
          .replace(/(\.[\d]{2})./g, '$1');    // not more than 2 digits after decimal
  });


</script>
