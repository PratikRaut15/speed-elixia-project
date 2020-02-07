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
<!DOCTYPE html>
<html>
	<style>
		.labelClass{
			display: inline !important;
			padding-left: 10px;
			font-weight: 600 !important;
			font-size: 14px !important;

			}
	</style>
	<head>
		<meta charset="utf-8">
		<script src="https://cdn.ckeditor.com/4.10.1/standard/ckeditor.js"></script>
	</head>

	<body>
		<link rel="stylesheet" href="../../css/newsLetter_Content.css">
		<div class="panel" style=''>
			<div class="paneltitle" align="center">News Letter Content</div>		
			<form id="email_contents" name="email_contents" enctype="multipart/form-data">
				<lable class="labelClass">Title of Content<span style="color: #FF0000"> *</span></lable>
				<input type="text" name="title" id="title" placeholder="Enter Title" required>(for future reference)
				<br>
				<lable class="labelClass">Email Subject<span style="color: #FF0000"> *</span></lable>
				<input type="text" name="email_subject" size=50 id="email_subject" placeholder="Enter Email Subject" required>
				<br>
				<label class="labelClass">Email Body<span style="color: #FF0000"> *</span></label>
				<textarea name="editor1"></textarea>
				<script>
					CKEDITOR.replace( 'editor1' );
				</script>
					<input type="file" name="file" id="file" value=''/>
				<br>
				<input type="button" value="Submit"  style="margin:0 40%;" onclick="submitContent();">
			</form>
		</div>
	</body>		
</html>

<script>
	function submitContent(){
      	var email_body = CKEDITOR.instances['editor1'].getData();
      	var title = $("#title").val();
      	var email_subject = $("#email_subject").val();
      	
		var formData = new FormData("#email_contents");
		
     	if($('#file').val()!=undefined && $('#file').val()!=''){
			var fileSize = 0;
			fileSize = $("#file")[0].files[0].size
			fileSize = fileSize / 2000000;

			var fileInput = document.getElementById('file');
			var filePath = fileInput.value;
			var allowedExtensions = /(\.pdf)$/i;

			if(fileSize>2){
	      		alert("fileSize cannot be greater than 2MB.");
	      		return false;
      		}

			if(!allowedExtensions.exec(filePath)){
				alert('Please upload file having extensions PDF only.');
				fileInput.value = '';
				return false;
			}
			formData.append('file',$('#file')[0].files[0]);

     	}
     	if(title==''){
      		alert("Please Enter Title");
      		$("#title").focus();
      		return false;
      	}
      	else if(email_subject==''){
      		alert("Please Enter Email Subject");
      		$("#email_subject").focus();
      		return false;
      	}    	
  		else{
			formData.append('insert_email_data',1);
			formData.append('email_subject',email_subject);
			formData.append('email_body',email_body);
			formData.append('title',title);
  			
			jQuery.ajax({
				type: "POST",
				enctype: 'multipart/form-data',
				url: "email_functions.php",
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				success: function(data){
					var result = JSON.parse(data);
					if(result>0){
						alert("News Letter Content Added Successfully.");
						window.location.href = 'view_newsLetterContent.php'; 
					}
					else{
						alert("Please Try Again.");
					}
				}
				    
            
			});
		}
	}
</script>