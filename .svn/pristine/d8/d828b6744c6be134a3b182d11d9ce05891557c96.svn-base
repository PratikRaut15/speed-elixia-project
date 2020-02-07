<?php
include_once("session.php");
include_once("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");
$isHead = checkUserType('',speedConstants::TEAM_ROLE_HEAD);
include("header.php");

?>

    <!-- Bootstrap -->
    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		table,th{
			text-align:center;
		}
		
	</style>
  
  
  <div class="row"> 
	<div class="container"> 
		<div class="col-md-2"></div>
		<div class="col-md-8 well">
			<h3 class="h3"> Upload File </h3>
			<form action="uploadExcelFile_code.php" method="post" enctype="multipart/form-data">
			 
			  <div class="form-group">
				<label for="exampleInputFile">Choose File</label>
				<input type="file" name="file" id="file">
				<p class="help-block">Please Note : upload Excel File only</p>
			  </div>
			  <button type="submit" id="btnsubmit" class="btn btn-primary" name="submit">Submit</button>
			</form>
			
		</div>
		<div class="col-md-2"></div>
	</div>
</div>



  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
	
	