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
<style>
  div.email_body_div{
    width: auto;
    height: 100%;
    border: 1px solid #ccc;
    padding: 5px;
    background-color: #fff;
  }
  .labelClass{
      display: inline !important;
      padding-left: 10px;
      font-weight: 600 !important;
      font-size: 14px !important;

      }
</style>
<link rel="stylesheet" href="../../css/newsLetter_Content.css">
<div class="panel">
  <div class="paneltitle" align="center">News Letter Content</div> 
    <div class="panelcontents" >
      <div class="center">
        <lable class="labelClass">Title of Content</lable>
        <input type="text" name="content_name" id="content_name"  class="searchArea" value="" autocomplete="off" placeholder="Search Content" onkeypress="get_newsLetter_ContentName();" required/>
        <lable class="labelClass">Email Subject</lable>
        <input type="text" name="email_subject" id="email_subject" size=50 readonly>
        <input type="hidden" name="content_id_email" id="content_id_email">
        <input type="hidden" name="attachmentLink" id="attachmentLink">
        <input type="hidden" name="filename" id="filename">
        <br>
        <label class="labelClass">Email Body</label>
        <div id="email_body_div" class="email_body_div"></div>
        <input type="hidden" name="email_body_hidden" id="email_body_hidden">
        <br>
        <embed src="" type="application/pdf"  height="300px" width="50%" id="pdfPreviewLink"/>
        <br>
        <input type="button" name="send_email" id="send_email" value="Send Email" style="margin:0 40%;" onclick="sendBulkEmail();">
    </div>
  </div>
<script>

  var email_body_contents_html='';
  function get_newsLetter_ContentName() {
      $("#noAttachmentLink").hide();
      jQuery("#content_name").autocomplete({
            type:  "post",
            source: "email_functions.php?get_ContentName=1",
            select: function (event, ui) {
               var contentId = ui.item.contentId;

                if(contentId!=''){  
                $("#content_id_email").val(contentId);
                  
                jQuery.ajax({
                  type: "POST",
                  url: "email_functions.php",
                  data:  "newsLetterContent=1&content_id="+contentId,
                  success: function(data){
                    var result = JSON.parse(data);
                    var final_result = result;
                    $("#email_body_div").html(final_result.email_body);
                    $("#email_body_hidden").val(final_result.email_body);
                    $("#email_subject").val(final_result.email_subject);
                    email_body_contents_html=final_result.email_body;
                    var file_preview='';
                    file_preview=final_result.filepath+final_result.filename;

                    if(file_preview!=0){
                      $("#pdfPreviewLink").prop("src",file_preview);
                      $("#attachmentLink").val(final_result.filepath);
                      $("#filename").val(final_result.filename);
                    }
                    else{
                      $("#pdfPreviewLink").prop("src","");
                      $("#attachmentLink").val('');
                      $("#filename").val('');
                    }
                  }
                });
              }
          }
      });
  }
  function sendBulkEmail(){
    var attachment_Link = $("#attachmentLink").val();
    var emailSubject= $("#email_subject").val();
    var filename = $("#filename").val();
    if(content_id_email==''){
      alert("Please select a news Letter");
      return false;
    }
    else{
      jQuery.ajax({
        type: "POST",
        url: "email_functions.php",
        data:  "send_bulk_email=1&attachment_Link="+attachment_Link+"&filename="+filename+"&email_subject="+emailSubject+"&emailBody="+email_body_contents_html,
        success: function(data){
          alert("Email sent successfully");
        }
      });
    }
  }
</script>