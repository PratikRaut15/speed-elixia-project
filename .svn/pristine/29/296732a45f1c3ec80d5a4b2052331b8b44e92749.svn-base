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
$teamId = GetLoggedInUserId();
include("header.php");
?>
<style>
    .titleClass{
        font-size:16px;
        font-weight:600;
        margin:2% 0 2% 0;
     }
    .switch-field {
      font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
      overflow: hidden;
      margin: 0 43%;
      width: 200px;
      text-align: left;
    }

    .switch-title {
      font-size: 20px;
      font-weight: 500;
      margin-bottom: 6px;
    }

    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }

    .switch-field label {
      float: left;
    }

    .switch-field label {
      padding:5px;
      display: inline-block;
      width: auto;
      background-color: #e4e4e4;
      color: rgba(0, 0, 0, 0.6);
      font-size: 15px;
      font-weight: 500;
      text-align: center;
      text-shadow: none;
      border: 1px solid rgba(0, 0, 0, 0.2);
      -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
      -webkit-transition: all 0.1s ease-in-out;
      -moz-transition:    all 0.1s ease-in-out;
      -ms-transition:     all 0.1s ease-in-out;
      -o-transition:      all 0.1s ease-in-out;
      transition:         all 0.1s ease-in-out;
    }

    .switch-field label:hover {
        cursor: pointer;
    }

    #switch_left:checked + label {
      color:white;
      font-weight: 700;
      background-color: #0F9D58;
      -webkit-box-shadow: none;
      box-shadow: none;
    }
    #switch_right:checked + label {
      color:white;
      font-weight: 700;
      background-color: #DB4437;
      -webkit-box-shadow: none;
      box-shadow: none;
    }

    .switch-field label:first-of-type {
      border-radius: 4px 0 0 4px;
    }

    .switch-field label:last-of-type {
      border-radius: 0 4px 4px 0;
    }


    input[type="button"] {
      background-color: #3b5998;
      border: 0;
      margin:5% 45% 5% 45%;
      text-align: center;
      color: #fff;
      font-weight: bold;
    }

    input[type="button"]:active {
      transform: translateY(4px);
    }
</style>
<div class="panel">
  <div class="paneltitle" align="center">Attendance Status</div> 
  <div class="panelcontents"> 
    <div class="center">  
      <form name="team_attendance" id="team_attendance">
         <label id="current_status" name="current_status"></label>
         <label for="status" href="#" title="Status Information" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Set Busy only when you are not free to take calls or in meeting." style="float: right;"><i class="material-icons">info</i></label>
          <div class="switch-field" >
            <div class="switch-title">Please Select Status</div><br/>
            <input type="radio" id="switch_left" name="statusType" value="1" />
            <label for="switch_left">Free</label>
            <input type="radio" id="switch_right" name="statusType" value="3" />
            <label for="switch_right">Busy</label>
            <input type="hidden" name="lat" id="lat" value="0.00"> 
            <input type="hidden" name="lng" id="lng" value="0.00"> 
            <input type="hidden" name="team_Id" id="team_Id" value="<?php echo $teamId;?>">
          </div>    
            <input type="button" name="attendance_status" id="attendance_status" title="Click to submit" value="Submit"onclick="changeStatus()">
       </form>
    </div>
  </div>
</div> 
<script>
    var teamId =<?php echo $teamId; ?>;
    var result='';
    jQuery(document).ready(function () {  
        get_busy_status();  
        getLocation();
        $('[data-toggle="popover"]').popover();   

        function getLocation() {
          navigator.geolocation.getCurrentPosition(showPosition);  
        }

        function showPosition(position) {
          $("#lat").val(position.coords.latitude);
          $("#lng").val(position.coords.longitude);
        }
    });

    function get_busy_status(){
        $('#switch_left').attr('disabled',false);
        $('#switch_right').attr('disabled',false);
        jQuery.ajax({
                    type: "POST",
                    url: "route_team.php",
                    data: "get_busy_status=1&team_id="+teamId,
                    success: function(data){
                       result =JSON.parse(data);
                       if(result==1){
                        $("#switch_right").prop("checked","checked");
                        $('#switch_right').css('background-color','#DB4437');
                        $('#switch_right').attr('disabled',true);
                        $("#current_status").html("Current Status: <b>BUSY</b>");
                       }
                       if(result==2){
                        $("#switch_left").prop("checked","checked");
                        $('#switch_left').css('background-color','#0F9D58');
                        $('#switch_left').attr('disabled',true);
                        $("#current_status").html("Current Status: <b>FREE</b>");
                       }
                       if(result==0){
                        $("#current_status").html("");
                       }

                    }
        });
    }

    function changeStatus(){

      if($('#switch_left').is(":not(:checked)") && $('#switch_right').is(":not(:checked)")) {
        alert("Please Select One Status"); 
        return;
      }
      else{
          var data = $("#team_attendance").serialize();
          jQuery.ajax({
            type: "POST",
            url: "route_team.php",
            data: "insert_attendance_status=1&"+data,
            success: function(data){
              var result = JSON.parse(data);
              if(result==1){
                alert("Status set to Free.");                
              }
              else if(result==2){
                alert("Status set to Busy.");
              }
              else{
                 alert("Please change the status.");
              }
              get_busy_status();
            }
          });
        }
    }

    
</script>
