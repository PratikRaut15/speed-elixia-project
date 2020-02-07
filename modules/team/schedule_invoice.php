<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include("header.php");

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<style>
			table tr td{
				padding-left:10px;
				vertical-align: middle;
			}
			label{
				padding: 5px;
			}
		</style>
		<link rel="stylesheet" href="../../css/docketStyle.css">
	</head>
	<body>
		<div class="panel">
	        <div class="paneltitle" align="center">Schedule Invoices
	        </div>
        	<div class="panelcontents">
        		<form id="scheduleInvoice" name="scheduleInvoice">
        			<table>
	        			<tr>
	        				<label for="customername">Customer No.</label>
	        				<input type="text" id="customername" name="customername" onkeypress="getCustomer();" placeholder="Customer No./Name">
	        				<input type="hidden" id="customerno" name="customerno">
	        				<label for="product">Product</label>
	        				<SELECT name="product" id="product" >
	        				</SELECT>
	        				<label for="ledgerno">Ledger No.</label>
	        				<SELECT  name="ledgerno" id="ledgerno">
	        				</SELECT>
	        				<input type="hidden" id="ledgerid" name="ledgerid"><br>
	        				<td>
	        					<label for="remarks">Remarks</label>
		        			</td>
		        			<td>
	        					<textarea id="remarks" name="remarks" placeholder="Remarks"></textarea>
	        			</td>
		        		</tr>
		        		<tr>
		        			<td>
		        				<label for="invoiceOT">OT</label>
		        			</td>
		        			<td>
		        				<input type="radio" name="invoice" id="invoiceOT" value="OT" >
		        			</td>
		        		</tr>
		        		<tr>
		        			<td>
		        				<label for="invoiceSAAS">SAAS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
		        			</td>
		        			<td>
		        				<input type="radio" name="invoice" id="invoiceSAAS" value="SAAS" >
		        			</td>
		        		</tr>
	        			<tr class="OT" id="OT" name="OT" style="display:none;">
	        				<td>
	        					<label for="OTAmount">OT Amount</label>
	        				</td>
	        				<td>
	        					<input type="text" id="OTAmount" name="OTAmount">
	        				</td>
	        				<td>
		        				<label for="OTAmc"> AMC amount </label>
	        				</td>
	        				<td>
	        					<input type="text" name="OTpercent" id="OTpercent" size="10;" placeholder="%">
	        				</td>
	        				<td>
		        				<input type="text" name="OTAmc" id="OTAmc" size="10;" placeholder="Amount">
	        				</td>
	        			</tr>
	        			<tr class="SAAS" id="SAAS" name="SAAS" style="display:none;">
	        				<td>
	        					<label for="SAASAmount">SAAS Amount</label>
	        				</td>
	        				<td>
		        				<input type="text" name="SAASAmount" id="SAASAmount">
	        				</td>
	        				<td>
		        				<label for="period">Period</label>
	        				</td>
	        				<td>
		        				<select id="period" name="period">
		        					
		        				</select>
	        				</td>
	        			</tr>
	        			<tr id="beforeRows">
	        				<td style="padding-top: 20px;"><input type="button" id="addReminder" name="addReminder" value="Add Invoice Reminders"></td>
	        			</tr>
	        			<tr >
	        				<td></td>
	        				<td></td>
	        				<td><input type="button" id="submit" name="submit" value="Submit"></td>
	        				<td></td>	
	        			</tr>
	        		</table>
        		</form>
        	</div>
	    </div>
	    <div style='width:80%;margin:0 auto;padding-bottom: 100px;'>
	   		<?php include('invoiceList.php');?>
		</div>
	</body>
	<footer>
		<script>
			var str='';

			$(document).ready(function(){
			    $("#addReminder").trigger("click");
			    $("#delete0").attr("disabled",true);
			    jQuery.ajax({
			        type: "POST",
			        url: "invoice_functions.php",
			        data: "fetchSAASCycles=1",
			        success: function(data) {
			        	var data = JSON.parse(data);
			        	$.each(data,function(i,record){
			        		//console.log(record);
			        		str+='<option value='+record.cycle_id+'>'+record.cycle_name+'</option>';
			        	});
			        	$("#period").append(str);
					},
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
					},
				});
			});
			$("#ledgerno").on("change",function(){
				
				$("#ledgerid").val($("#ledgerno").val());
				//console.log($("#ledgerid").val());
			});
			function getCustomer() {
			    jQuery("#customername").autocomplete({
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
			                $('#ledgerno').html("");
			                $('#ledgerno').append('<option value = '+"0"+' selected>'+"Select Ledger"+'</option>');
			                $.each(data ,function(i,text){
			                  	$('#ledgerno').append('<option value = '+text.ledgerid+'>'+text.ledgerid+'-'+text.ledgername+'</option>');
			                  	$("#ledgerno")[0].selectedIndex=0;
			                });
				    	}
		    		});
			      }
			    });
			}

			$("#OTpercent").on('keyup',function(){
				var percent=0,amount=0;
				var perField = parseFloat($(this).val().replace('%',""));
				var totalAmount = parseFloat($("#OTAmount").val());
				if($(this).val()){
					if(!$("#OTAmount").val()){
						alert("Please enter a value for One Time Amount");
					}else{
						amount = (perField/100)*totalAmount;
						$("#OTAmc").val(amount);
						$(this).val(perField+'%');
					}
				}
			});

			$("#OTAmc").on('keyup',function(){
				var percent=0,amount=0;
				var amtField = parseFloat($(this).val());
				var totalAmount = parseFloat($("#OTAmount").val());
				if($(this).val()){
					if(!$("#OTAmount").val()){
						alert("Please enter a value for One Time Amount");
					}else{
						precent = (amtField/totalAmount)*100;
						$("#OTpercent").val(precent+'%');
						//$(this).val(perField+'%');
					}
				}
			});
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

			var invCount = 0;

			$("#addReminder").on("click",function(){
				var str = " <tr id='invoiceRow"+invCount+"'>\
								<td>\
									<b>(#"+(invCount+1)+")</b><label for='eid"+invCount+"'>Expected Invoice Date</label>\
								</td>\
								<td>\
									<input type='text' id='eid"+invCount+"' name='eid"+invCount+"'>\
								</td>\
								<td>\
									<label for='desc"+invCount+"'>Invoice Description</label>\
								</td>\
								<td>\
									<input type='text' id='desc"+invCount+"' name='desc"+invCount+"'>\
								</td>\
								<td>\
								<input type='button' value='delete' id='delete"+invCount+"' name='delete"+invCount+"' onclick='removeRow("+invCount+")'>\
								</td>\
						    </tr>";
		        $("#beforeRows").before(str);
		            jQuery('#eid'+invCount).datepicker({
				        dateFormat: "yy-mm-dd",
				        language: 'en',
				        autoclose: 1,
				        startDate: Date()
				    });
		        invCount++;
			});

			$("input[name='invoice']").on('change',function(element){
				//console.log(element);
				var checked = $("input[name='invoice']:checked");
				var type = checked.val();
				if(type=='OT'){
					$("#OT").show();
					$("#SAAS").hide();
				}else if(type=='SAAS'){
					$("#SAAS").show();
					$("#OT").hide();
				}
			});

			function removeRow(id){

				$("#invoiceRow"+id).remove();
			}

			$("#submit").on('click',function(){
				var checked = $("input[name='invoice']:checked");
				var type = checked.val();
				var reminderCount = $("input[id^='delete']").length;
				console.log(reminderCount);
				var i;
				if($("#customerno").val()==''){
					alert("Please select a customer");
					return;
				}else if($("#ledgerid").val()==''){
					alert("Please select a ledger");
					return;
				}else if((type=='OT')&&($("#OTAmount").val()=='')){
					alert("Please enter one time amount");
					return;
				}else if((type=='SAAS')&&($("#SAASAmount").val()=='')){
					alert("Please enter SAAS amount");
					return;
				}else{
					for(i=0;i<reminderCount;i++){
						console.log($("#eid"+i).val());
						if($("#eid"+i).val()==''){
							alert("Please select expected invoice date for reminder #"+(i+1));
							return;
						}
					}
				}
				var formData = $("#scheduleInvoice").serialize();
				
				formData+="&submitScheduleInvoice=1&count="+reminderCount;
			    jQuery.ajax({
			        type: "POST",
			        url: "invoice_functions.php",
			        data: formData,
			        success: function(data) {
			        	if(data=='ok'){
			        		window.location="schedule_invoice.php";
			        	}
					},
				});
			});


		</script>
	</footer>
</html>