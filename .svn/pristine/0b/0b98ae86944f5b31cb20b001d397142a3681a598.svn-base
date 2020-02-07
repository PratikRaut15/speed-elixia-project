<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/system/Date.php");
include_once("../../lib/system/utilities.php");
include_once("../../lib/bo/DocketManager.php");
include("header.php");

$ticketid = $_GET['ticketid'];
?>
<html>
	<head>
		<link rel="stylesheet" href="../../css/ticketStyle.css">
		<style>

			.formLabel{
				float: right;
				vertical-align: middle;
				width:auto !important;
			}
			form input,select{
				width:150px;
			}
			table{
				margin-left: auto;
    			margin-right: auto;
    		}
    		tr td {
			    vertical-align: middle !important;
			    margin: 5px;
			    padding-left:10px;
			}
			.notesList {

    			table-layout: fixed;
    			width:40%;
				margin-left: auto;
    			margin-right: auto;
				border:1px solid black;
			}
			.notesList td,th{
				padding:5px;
				border:1px solid black;
			}
		</style>
	</head>
	<body>
		<div class="panel">
		    <div class="paneltitle" align="center">
				Edit Ticket
		    </div>
		    <div class="panelcontents" >
		        <form id='updateTicket' name='updateTicket'>
		        	<input type='hidden' id='ticketid' name='ticketid' value=<?php echo $ticketid;?>>
		        	<input type='hidden' id='customerno' name='customerno' value=''>
		        	<table>
		        		<tr>
		        			<td><label class='formLabel' for='customer'>Customer: </label></td>
		        			<td><input type='text' id='customer' name='customer' readonly></td>
		        			<td><label class='formLabel' for='title'>Title: </label></td>
		        			<td><input type='text' id='title' name='title' readonly></td>
		        		</tr>
		        		<tr>
		        			<td><label class='formLabel' for='description'>Description: </label></td>
		        			<td><textarea id='description' name='description' readonly></textarea></td>
		        			<td><label class='formLabel' for='product'>Product: </label></td>
		        			<td><select id='product' name='product' readonly></select></td>
        					<td><label class='formLabel' for='type'>Type: </label></td>
        					<td><select id='type' name='type' readonly></select></td>
		        		</tr>
		        		<tr>
		        			<td>
		        				<label for='ecdupdate' >E.C.D. to be updated by :</label>
		        			</td>
		        			<td>
		        				<label id='ecdupdate'></label>
		        			</td>
		        		</tr>
	        			<tr>
							<td><label class='formLabel' for='ecd'>Expected close date: </label></td>
	        				<td><input type='text' id='ecd' name='ecd'></td>
	        				<td><label class='formLabel' for='priority'>Priority: </label></td>
	        				<td><select id='priority' name='priority'></select></td>
        					<td><label class='formLabel' for='status'>Status: </label></td>
        					<td><select id='status' name='status'></select></td>
        					<td><label class='formLabel' for='type'>Allot to: </label></td>
        					<td><select id='allot' name='allot'></select></td>
	        			</tr>
	        			<tr>
	        				<td><label class='formLabel' for='email'>Email: </label></td>
	        				<td>
	        					<input type='text' id='email' name='email' onkeyup='getmailids();'>
	        					<input type='button' id='insertEmail' name='insertEmail' onclick='insertMailId();' value='Add email'>
	        				</td>
	        				<td><label class='formLabel' for='email'>CC: </label></td>
	        				<td><input type='text' id='cc' name='cc' onkeyup='getmailidsCC();'></td>
	        				<input type='hidden' id='emailList' name='emailList'>
	        				<input type='hidden' id='ccList' name='ccList'>
	        			</tr>
	        			<tr>
	        				<td><label for='additionalCharges'>Additional charges applicable?</label>
	        				<td><input type='checkbox' id='additionalCharges' name='additionalCharges' onchange='toggleChargesDiv();'></td>
	        				<td></td>
	        			</tr>
		        			<table  id='chargesDiv' style='display:none;'>
			        		<tr>
				        		<td><label for='chargeDescription'>Description: </label></td>
				        		<td><input type='text' id='chargeDescription' name='chargeDescription'></td>
				        	</tr>
				        	<tr>
				        		<td><label for='chargesField'>Charges: </label></td>
				        		<td><input type='text' id='chargesField' name='chargesField'></td>
				        	</tr>
		        		</table>
		        		<tr><td colspan=10><input type='button' id='updateTicketSubmit' name='updateTicketSubmit' value='Submit' onclick='submitTicket();' style='display: block;margin-left: auto;margin-right: auto;'></td></tr>
		        	</table>
		        	
				</form>
					
					<input type='text' id='note' name='note'>
					<input type='button' id='submitNote' name='submitNote' onclick='addNote();' value='Add note'>
					<h3 style="text-align: center;">Notes history</h3>
				<div id='notesDiv' style='overflow:auto;height:400px;'>

				</div>
			</div>
		</div>
	</body>
	<script>
	var ticketid = <?php echo $ticketid?>;
	
	</script>
	<script src='../../scripts/team/ticket_js.js'>
	</script>
</html>