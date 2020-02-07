<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include("header.php");
include("../../lib/bo/invoiceManager.php");
if((checkUserType(speedConstants::TEAM_DEPARTMENT_ACCOUNTS,speedConstants::TEAM_ROLE_HEAD))||(checkUserType(speedConstants::TEAM_DEPARTMENT_MANAGEMENT))){
	$authorized = 1;
}
$invoiceId = $_GET["invoiceId"];
$invMgr = new invoiceManager();
$data = $invMgr->fetchInvoiceReminders($invoiceId);
$data = $data[0];
//print_r($data);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<style>
			table tr td{
				padding-left:10px;
				vertical-align: middle;
			}
		</style>
		<link rel="stylesheet" href="../../css/docketStyle.css">
	</head>
	<body>
		<div class="panel">
	        <div class="paneltitle" align="center">Approve Invoice
	        </div>
        	<div class="panelcontents" align="center">
        		<form id='approveInvoice'>
        			<table>
        				<tr>
			        		<td>
			        			<label>Customer: </label>
			        			<label><?php echo $data['customercompany']?></label>
		        			</td>
			        		<td>
			        			<label>Ledger Name:</label>
			        			<label><?php echo $data['ledgername']?></label>
		        			</td>
			        		<td>
			        			<label>Expected Invoice Date:</label>
			        			<label id="expectedInvoiceDate"><?php echo $data['expectedInvDate']?></label>
			        		</td>
		        		</tr>
		        		<tr>
			        		<td>
			        			<label>Invoice Type:</label>
			        			<label><?php echo $data['inv_type_name']?></label>
			        		</td>
			        		<td></td>
			        		<td>
			        			<label>Reminder Date:</label>
			        			<label id="reminderdate"><?php echo $data['reminder_date']?></label>
			        		</td>
			        	</tr>
			        	
					<?php if($data["inv_type_name"]=="One Time"||$data["inv_type_name"]=="AMC"){
					?>
		        		<tr>
		        			<td>
		        				<label>One Time Amount: </label>
		        				<label id='ot'><?php echo $data['amount']?></label>
		        			</td>
		        			<td>
		        				<label>AMC: </label>
		        				<label id="amc"><?php echo $data['amc_amount']?></label>
		        			</td>
		        			<td>
		        				<label>Change:</label>
		        				<input type="text" name="changeReminder" id="changeReminder" value="<?php echo $data['reminder_date']?>">
		        			</td>
	        		<?php
					}if($data["inv_type_name"]=="SAAS"){
					?>
		        		<tr>
		        			<td>
		        				<label>SAAS Amount: </label>
		        				<label id="saas"><?php echo $data['invoiceAmount']?></label>
		        			</td>
		        			<td>
		        				<label>SAAS Period: </label>
		        				<label id="saasPeriod"><?php echo $data['cycle_name']?></label>
		        			</td>
		        			<td>
		        				<label>Change:</label>
		        			</td>
		        			<td>
		        				<input type="text" name="changeReminder" id="changeReminder" value="<?php echo $data['reminder_date']?>">
		        			</td>
					<?php
					}?>
        				</tr>
        				<tr>
        					<td style="padding-top:20px;padding-bottom:20px;" >
			        			<label>Invoice Amount:</label>
			        			<label><?php echo $data['invoiceAmount']?></label>
			        		</td>
			        	</tr>
					<?php if($authorized){
					?>
						<tr>
							<td>
								<label for="sDate">Invoice start date: </label>
								<input type="text" id="sDate" name="sDate">
							</td>
							<td>
								<label for="eDate">Invoice end date: </label>
								<input type="text" id="eDate" name="eDate">
							</td>
						</tr>
						<tr>
							<td>
								Reschedule: <input type="checkbox" id="rescheduleCheck" value="Reschedule" style="background-color: orange;color:white">
							</td>
						</tr>

						<tr id='rescheduleRow' style='display:none;'>
							<td>
								<label>Reschedule Date:</label>
							</td>
							<td>
								<input type='text' id='rescheduleDate' name='rescheduleDate'>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="button" id="approve" value="Approve" style="background-color: green;color:white">
								<input type="button" id="reschedule" value="Reschedule" style="background-color: orange;color:white;display:none">
							</td>
							<td>
								
							</td>
						</tr>
					<?php
					}?>
	        		</table>
        		</form>
			</div>
		</div>
	</body>
	<footer>
		<script type="text/javascript">
            jQuery('#rescheduleDate').datepicker({
		        dateFormat: "yy-mm-dd",
		        language: 'en',
		        autoclose: 1,
		        startDate: Date()
		    });
            jQuery('#changeReminder').datepicker({
		        dateFormat: "yy-mm-dd",
		        language: 'en',
		        autoclose: 1,
		        startDate: Date()
		    });
            $("#sDate").on("change",function(){
            	var inv_type;
				if(parseInt($("#amc").html())>0){
					inv_type=2;
				}else if(parseInt($("#ot").html())>0){
					inv_type = 1;
				}else if(parseInt($("#saas").html())>0){
					inv_type = 3;
				}
				var saasPeriod = $("#saasPeriod").html();
				if(saasPeriod){
					saasPeriod = saasPeriod.replace("SAAS - ","");
				}
			    $.ajax({
			        type: "POST",
			        url: "invoice_functions.php",
			        data: "addMonths=1&sDate="+$("#sDate").val()+"&inv_type="+inv_type+"&saasPeriod="+saasPeriod,
			        success: function(res){
			        	$("#eDate").val(res);
			        }
			    });
            });

			jQuery('#sDate').datepicker({
		        dateFormat: "yy-mm-dd",
		        language: 'en',
		        autoclose: 1,
		        startDate: Date()
		    });

			jQuery('#eDate').datepicker({
		        dateFormat: "yy-mm-dd",
		        language: 'en',
		        autoclose: 1,
		        startDate: Date()
		    });

			$("#reschedule").on("click",function(){
				if(confirm("Are you sure you want to reschedule the invoice?")){
					var invId = <?php echo $invoiceId;?>;
					var invDate = $("#rescheduleDate").val();
					var data = "rescheduleInvoice=1&invId="+invId+"&invDate="+invDate;
				    $.ajax({
				        type: "POST",
				        url: "invoice_functions.php",
				        data: data,
				        success: function(res){
				            if(res=='ok'){
				            	window.location="schedule_invoice.php";
				            }
				        }
				    });
				}
			});

			$("#approve").on("click",function(){
				var amc,saas;
				var inv_type;
				if(parseInt($("#amc").html())>0){
					inv_type=2;
				}else if(parseInt($("#ot").html())>0){
					inv_type = 1;
				}else if(parseInt($("#saas").html())>0){
					inv_type = 3;
				}
				if(confirm("Are you sure you want to raise the invoice?")){
					var reminderdate = $("#changeReminder").val();
					var invId = <?php echo $invoiceId;?>;
					var invDate = $("#reminderdate").html();
					var period = "&startDate="+$('#sDate').val()+"&endDate="+$('#eDate').val();
					if(reminderdate!=null){
						invDate = reminderdate;
					}
					var saasPeriod = $("#saasPeriod").html();
					if(saasPeriod){
						saasPeriod = saasPeriod.replace("SAAS - ","");
						//console.log(saasPeriod);
					}
					var data = "approveInvoice=1&invId="+invId+"&invDate="+invDate+"&inv_type="+inv_type+"&saasPeriod="+saasPeriod+period;
				    $.ajax({
				        type: "POST",
				        url: "invoice_functions.php",
				        data: data,
				        success: function(res){
				        	var resp = JSON.parse(res);
				            if(resp.status=='ok'){
				            	window.location="../download/erp_invoice.php?invoiceid="+resp.invoiceId;
				            }
				        }

				            	
				    });
				}
			});

			$("#rescheduleCheck").on("click",function(){
				$("#reschedule").toggle();
				$("#approve").toggle();
				$("#rescheduleRow").toggle();
			});
		</script>
	</footer>
</html>