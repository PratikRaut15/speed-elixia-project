<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include("header.php");
include("../../lib/bo/invoiceManager.php");

$invoiceId = $_GET["invoiceId"];
$invMgr = new invoiceManager();
$data = $invMgr->fetchInvoiceReminders($invoiceId);
$data=$data[0];
//echo "<pre>";
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
	        <div class="paneltitle" align="center">Edit Scheduled Invoice
	        </div>
        	<div class="panelcontents" align="center">
				<form id="editInvoice" name="editInvoice">
					<table>
						<tr>
							<td>
								<label for="customer">Customer:</label>
							</td>
							<td>
								<input type="text" id="customer" name="customer" placeholder="Customer no." onkeypress="getCustomer();">
								<input type="hidden" name="customerno" id="customerno">
							</td>
							<td>
								<label for="ledger">Ledger:</label>
							</td>
							<td>
								<select id="ledger" name="ledger" placeholder="Ledger no."></select>
								<input type="hidden" name="ledgerno" id="ledgerno">
							</td>
							<td>
								<label for="product">Product:</label>
							</td>
							<td>
								<select id="product" name="product">
                                </select>
							</td>
						</tr>
						<tr>
							<td>
								<label for="amount">Amount</label>
							</td>
							<td>
								<input type="text" name="amount" id="amount" value="<?php echo $data['invoiceAmount']?>">
							</td>
							<td>
								<label for="desc">Description</label>
							</td>
							<td>
								<input type="text" id="desc" name="desc" value="<?php echo $data['inv_desc'];?>">
							</td>
						</tr>
						<tr>
							<td></td><td></td><td></td>
							<td><input type="button" value="Submit" onclick="submitInvoice();"></td>
						</tr>
					</table>
				</form>
        	</div>
        </div>
	</body>
	<footer>
		<script type="text/javascript">
			var invRemId = <?php echo $invoiceId?>;
			function submitInvoice(){
				var formData =  $("#editInvoice").serialize()+"&invRemId="+invRemId+"&editInvRem=1";
				//console.log(formData);
				jQuery.ajax({
		        type: "POST",
		        url: "invoice_functions.php",
		        data: formData,
		        success: function(data) {
		        	if(data == 'ok'){
		        		window.location="schedule_invoice.php";
		        	}
				},
			});

			}
			var product =  <?php echo $data['productId'] ?>;
			$(document).ready(function(){
				$("#customer").val("<?php echo $data['customerno']." - ".$data['customercompany']?>");
				$("#customerno").val("<?php echo $data['customerno'];?>");
				var customerno = <?php echo $data['customerno'] ?>;
				//console.log(product);
				jQuery.ajax({
	                type: "POST",
	                url: "ledger_ajax.php",
	                cache: false,
	                data: {
	                    work: "getMappedLedger", custno: customerno
	                },
	                success: function(data){
		                var data=JSON.parse(data);
		                $('#ledger').html("");
		                $('#ledger').append('<option value = '+"0"+' selected>'+"Select Ledger"+'</option>');
		                $.each(data ,function(i,text){
		                  	$('#ledger').append('<option value = '+text.ledgerid+'>'+text.ledgerid+'-'+text.ledgername+'</option>');
		                  	//$("#ledger")[0].selectedIndex=0;
		                  	$("#ledgerno").val(<?php echo $data['ledgerno']?>);
		                  	$("#ledger").val(<?php echo $data['ledgerno']?>);
		                });
			    	}
	    		});
			});
			jQuery.ajax({
		        type: "POST",
		        url: "invoice_functions.php",
		        data: "fetchProducts=1",
		        success: function(data) {
		        	var data = JSON.parse(data);
		        	str='';
		        	$.each(data,function(i,record){
		        		//console.log(record);
		        		str+='<option value='+record.prod_id+'>'+record.prod_name+'</option>';
		        	});
		        	$("#product").append(str);
		        	$("#product").val(product);
				},
			});
			$("#ledger").on("change",function(){
				$("#ledgerno").val($("#ledger").val());
				//console.log($("#ledgerid").val());
			});
			function getCustomer() {
			    jQuery("#customer").autocomplete({
			      type:  "post",
			      source: "Docket_functions.php?get_customer=1",
			      select: function (event, ui) {
			        jQuery(this).val(ui.item.customername+" "+ui.item.value);
			        jQuery('#customerno').val(ui.item.customerno);
			        //console.log(ui.item);
                    jQuery.ajax({
		                type: "POST",
		                url: "ledger_ajax.php",
		                cache: false,
		                data: {
		                    work: "getMappedLedger", custno: ui.item.customerno
		                },
		                success: function(data){
			                var data=JSON.parse(data);
			                $('#ledger').html("");
			                $('#ledger').append('<option value = '+"0"+' selected>'+"Select Ledger"+'</option>');
			                $.each(data ,function(i,text){
			                  	$('#ledger').append('<option value = '+text.ledgerid+'>'+text.ledgerid+'-'+text.ledgername+'</option>');
			                  	$("#ledger")[0].selectedIndex=0;
			                });
				    	}
		    		});
			      }
			    });
			}
			function getLedger(){

                jQuery.ajax({
	                type: "POST",
	                url: "ledger_ajax.php",
	                cache: false,
	                data: {
	                    work: "getMappedLedger", custno: customerno
	                },
	                success: function(data){
		                var data=JSON.parse(data);
		                //console.log(data);
			    	}
	    		});
			}
		</script>
	</footer>
</html>