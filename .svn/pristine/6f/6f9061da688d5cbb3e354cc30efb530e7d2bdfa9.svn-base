<?php
$accid = $_GET['accid'];
$v_id = $_GET['vehicleid'];
$battary = getbattbyid($accid);
$parts = getpartslist($accid);
$tasks = gettasklist($accid);
?>

<?php
    $currentdate = getcurrentdate();
    $today = date('d-m-Y',$currentdate);
?>



<div class="table" id="editbattery" style="top:41%; width: 51%;">
<form class="form-horizontal" id="getbattery_edit">
 
<fieldset>
    <div class="modal-header">
       <input type="button" name="back" id="back" style="float:left;" onclick="backbutton();" class="btn-info" value="<<Back"><br>
       
        <h4 style="color:#0679c0">Transaction Details</h4>
    </div>
    <div >
            <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
            <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        
            <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
            <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
            <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
            <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    
          
            <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number'];?></span>                          
            <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
	<div >            
            <fieldset>
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Vehicle Details</th>
                        <tr>
                        <td width="50%">Vehicle No.</td>
                        <td><div id="batt_veh_no"><?php echo $battary['vehicleno'];?></div></td>
                        </tr>                  
                        <tr>
                        <td>Branch</td><td><div id="batt_veh_branch"><?php echo $battary['groupname'];?></div></td>
                        </tr>                        
                        <tr>
                        <td>GPS Odometer Reading</td><td><div id="batt_veh_odometer"><?php echo $battary['odometer'];?></div></td>
                        </tr>                                                
                        <tr>
                        <td>Vehicle Meter reading </td>
                        <td><div id="batt_meter_reading"><?php echo $battary['meter_reading'];?></div></td>
                        </tr>                    
                    </table>
                
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Transaction Details</th>
                        <tr>
                        <td width="50%">Transaction ID</td><td><div id="batt_transid"><?php echo $battary['transid'];?></div></td>
                        </tr>                  
                        <tr>
                        <td>Category</td><td><div id="batt_category"><?php echo $battary['category'];?></div></td>
                        </tr>
                        <?php if($_SESSION['customerno']== 118 && $battary['category']=="Battery" )
                        {
                        ?>  
                        <tr>
                        <td>New Battery Serial No.</td><td><div id="batt_srno"><?php echo $battary['srno'];?></div></td>
                        </tr> 
                        <?php
                        }
                        ?>
                        <tr>
                        <td>Dealer name </td>
                       <td>
                            <div id="batt_dealer"><?php echo $battary['dealername'];?></div>                            
                        </td>
                        </tr>
                        <tr>
                        <td>Notes</td>
                        <td>
                            <div id="batt_notes"><?php echo $battary['notes'];?></div>
                        </td>                        
                        <tr>
                        <td>Status</td>
                        <td><div id="batt_status"><?php echo $battary['statusname'];?></div></td>
                        </tr>
                        </tr>                                                
                        <tr id="trans_close_date" style="display:none;">
                             <td>Transaction Close Date</td>
                        <td>
                            <?php echo date('d-m-Y',  strtotime($battary['timestamp']));?>                       
                        </td>  
                            
                        </tr>                                                                                                
                        <tr>
                            <td>Vehicle In Date</td>
                            <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text" disabled="" value="<?php echo date('d-m-Y',  strtotime($battary['vehicle_in_date']));?>" required/></td>
                        </tr>
                        <tr>
                            <td>Vehicle Out Date</td>
                            <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" disabled=""  value="<?php echo date('d-m-Y',strtotime($battary['vehicle_out_date']));?>" required/></td>
                        </tr>
                        <?php
                            if ($_SESSION['customerno'] == 118 && $battary['category'] == "Battery") {
                                ?>  
                                <tr>
                                    <td>New Battery Serial No.</td>
                                    <td><input id="new_battsrno" name="new_battsrno" type="text" value="<?php echo $battary['srno']; ?>" readonly></td>
                                </tr> 
                                <?php
                            }
                            ?>
                    </table>
                
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Quotation Details</th>                            
                        <tr>
                        <td width="50%">Quotation Amount (INR)</td>
                        <td>
                        <div id="batt_amount_quote"><?php echo $battary['amount_quote'];?></div></td>
                        </tr>
                        <tr>
                             <td>Quotation File</td>
                        <td>
                        <div class="input-prepend" id="battery_quotefile_view">                            
                        </td>                              
                        </tr>               
                        <tr>
                        <td>Quotation Submission Date</td><td><div id="batt_submission_date"><?php echo $battary['submission_date'];?></div></td>
                        </tr>
                        <tr id="quotation_approval_note">
                        <td>Quotation Approval Note</td>
                        <td>
                            <div id="quotation_approval_note_val"><?php echo  $battary['approval_notes'];?></div>
                        </td>
                        </tr> 
                        <?php
                        if($_SESSION['customerno']!=118)
                        {
                        ?>
                        <tr id="show_tyre_type">
                        <td>Tyre Type</td><td><div id="batt_tyre_type"><?php echo $battary['tyre_type'];?></div></td>
                        </tr> 
                        <?php
                        }
                        ?>
                        <?php
                            if ($_SESSION['customerno'] == 118 && $battary['categoryid']==1) {
                                ?>
                                <tr>
                                <td>Tyre Repair Type</td><td><?php echo $battary['repairtype'] ;?></td>
                                </tr>
                            <?php  
                            if($battary['tyrerepairid'] ==1){
                                echo isset($battary['tyre_srno']) ? $battary['tyre_srno'] : '';
                            }
                            }
                            ?>
                        <!--
                        <tr id="show_parts">
                        <td>Parts Consumed</td><td><div id="batt_parts"><?php echo $battary['partsnew'];?></div></td>
                        </tr>                        
                        <tr id="show_tasks">
                        <td>Tasks Performed</td><td><div id="batt_tasks"><?php echo $battary['tasksnew'];?></div></td>
                        </tr>
                        -->
                    </table>
                      <?php
                    if(!empty($parts)){
                        ?>
                <table id="show_parts" class="table table-bordered table-striped">
                                <th colspan="4">Parts Consumed</th>
                               <tr>
                                   <td>Part</td>
                                   <td>Quantity</td>
                                   <td>Cost Per Unit</td>
                                   <td>Discount Per Unit</td>
                                   <td>Total</td>
                               </tr>
                               <?php
                               foreach($parts as $part){
                                   if($part->part_name!=''){
                                       ?>
                                       <tr>
                                           <td><?php echo $part->part_name; ?></td>
                                           <td><?php echo $part->qty;?></td>
                                           <td><?php echo $part->amount; ?></td>
                                           <td><?php echo $part->discount; ?></td>
                                           <td><?php echo $part->total;?></td>
                                       </tr>
                                       <?php
                                   }
                               }
                               ?>
                           </table>
                        <?php
                    }
                    
                    if(!empty($tasks)){
                        ?>
                <table id="show_tasks" class="table table-bordered table-striped">
                                <th colspan="4">Task Performed</th>
                               <tr>
                                   <td>Task</td>
                                   <td>Quantity</td>
                                   <td>Cost Per Unit</td>
                                   <td>Discount Per Unit</td>
                                   <td>Total</td>
                               </tr>
                               <?php
                               foreach($tasks as $task){
                                   if($task->part_name!=''){
                                       ?>
                                       <tr>
                                           <td><?php echo $task->part_name; ?></td>
                                           <td><?php echo $task->qty;?></td>
                                           <td><?php echo $task->amount; ?></td>
                                           <td><?php echo $task->discount; ?></td>
                                           <td><?php echo $task->total;?></td>
                                       </tr>
                                       <?php
                                   }
                               }
                               ?>
                           </table>
                        <?php
                    }
                    ?>
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Invoice Details</th>                            
                        <tr>
                            <td width="50%">Invoice Generation Date</td>
                            <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y',  strtotime($battary['invoice_date']));?>" required/></td>
                        </tr>
                        <tr>
                            <td>Invoice Number</td>
                            <td><input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50" value="<?php echo $battary['batt_invoice_no'];?>" onkeypress="return nospecialchars(event)"></td>
                        </tr>
                        <tr>
                            <td>Tax Amount</td>
                            <td><input type="text" name="p_tax" id="p_tax" placeholder="e.g. 12586" maxlength="10" value="<?php echo $battary['tax'];?>" onkeypress="return nospecialchars(event)" readonly></td>
                        </tr>
                        <tr>
                            <td>Invoice Amount (INR)</td>
                            <td><input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" value="<?php echo $battary['invoice_amount'];?>" maxlength="10" size="8" >
                                <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id" value="<?php echo $accid;?>">
                                <input type="hidden" name="edit_vehicle_id" id="edit_vehicle_id" value="<?php echo $v_id;?>">                                            
                                <input type="hidden" name="category_id" id="category_id" value="<?php echo $battary['categoryid'];?>">                                                                            
                            </td>
                        </tr>                            
                        <tr id="invoice_file">
                             <td>Invoice File</td>
                        <td>
                            <input type="file" title="Browse File" id="batt_file_for_invoice" name="batt_file_for_invoice" class="file-inputs">                            
                            <div class="input-prepend" id="battery_invoicefile_view">                            
                        </td>  
                            
                        </tr>
                        <tr id="invoice_file_view" style="display:none;">
                             <td>Invoice File</td>
                        <td>
                            <div class="input-prepend" id="battery_invoicefile_view">                            
                        </td>  
                            
                        </tr>                        
                        <tr id="ofasnumber_view" style="display:none;">                        
                        <td><?php echo $_SESSION['ref_number'];?></td>
                           
                        <td>
                            <div class="input-prepend" id="ofasnumber_view_value"> <?php echo $battary['ofasnumber'];?></div>                           
                        </td>  
                            
                        </tr>                                                
                        <tr id="payment_approval_date" style="display:none;">
                             <td>Payment Approval Date</td>
                        <td>
                            <div class="input-prepend" id="payment_approval_date_value">  <?php echo $battary['payment_approval_date'];?> </div>                          
                        </td>  
                            
                        </tr>                                                                        

                                                                                                                      
                        </table>
                
                <div class="control-group">
                <div class="input-prepend " id="ofasnumberdiv">
               
                <span class="add-on" style="color:#000000"><?php echo $_SESSION['ref_number'];?></span>
                
                <input type="text" name="ofasnumber" id="ofasnumber" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)">
                </div>
                </div>                
                </div>                                
                    <br/>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" id="edit_save_battery" onclick="editbattery(<?php echo $accid?>,<?php echo $battary['vehicleid']?>,10,<?php echo $battary['categoryid']?>,<?php echo $battary['amount_quote']?>);" value="Send for Final Payment Approval" class="btn btn-success">                    
                <input type="button" id="edit_cancel_battery" onclick="editbattery(<?php echo $accid?>,<?php echo $battary['vehicleid']?>,11,<?php echo $battary['categoryid']?>,<?php echo $battary['amount_quote']?>);" value="Send for Cancellation" class="btn btn-danger">                    
                <input type="button" id="edit_close_battery" onclick="editbattery(<?php echo $accid?>,<?php echo $battary['vehicleid']?>,14,<?php echo $battary['categoryid']?>,<?php echo $battary['amount_quote']?>);" value="Close Transaction" class="btn btn-success">                                    
                </div>
                </div>
                </div>
	</fieldset>
            </form>
</div>

<?php
if(!empty($battary['quote_file_name'])){ $quote = $battary['quote_file_name']; } else { $quote = 0; }
if(!empty($battary['invoice_file_name'])){ $invoice = $battary['invoice_file_name']; } else { $invoice = 0; }
?>
 <script type="text/javascript">
    var status = <?php echo $battary['statusid'];?>;
    var categoryid = <?php echo $battary['categoryid'];?>;
    var quote = '<?php echo $quote; ?>';
    var invoice = '<?php echo $invoice; ?>';
   
                        jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', true);    
                        jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', true);
                        jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', true);                                                        
                        jQuery('#getbattery_edit #invoice_file').hide();                                                                                    
                        jQuery('#getbattery_edit #invoice_file_view').show();                                                                                                            
                        jQuery('#getbattery_edit #payment_approval_date').show();  
                        jQuery('#getbattery_edit #payment_approval_note').show();                                                                                                                                                                                        
                        jQuery('#getbattery_edit #trans_close_date').show();                                                                                                                                                            
                        jQuery('#getbattery_edit #ofasnumberdiv').hide();                                                                                                                                                                                                    
                        jQuery('#getbattery_edit #ofasnumber_view').show();                                                                                                                                                                                                                            
                        jQuery('#getbattery_edit #edit_save_battery').hide();                                                                                    
                        jQuery('#getbattery_edit #edit_cancel_battery').hide();                                                                                    
                        jQuery('#getbattery_edit #edit_close_battery').hide(); 
                        jQuery('#getbattery_edit #quotation_approval_note').show();                                                                                       
                                                                                                             
                        if(categoryid != 1)
                        {
                            jQuery('#getbattery_edit #show_tyre_type').hide();                                                             
                        }
                        else
                        {
                            jQuery('#getbattery_edit #show_tyre_type').show();                                 
                        }           
                        if(categoryid != 2 && categoryid != 3)
                        {
                            jQuery('#getbattery_edit #show_parts').hide();                                 
                            jQuery('#getbattery_edit #show_tasks').hide();                                                                 
                        }
                        else
                        {
                            jQuery('#getbattery_edit #show_parts').show();                                 
                            jQuery('#getbattery_edit #show_tasks').show();                                                                 
                        }                                                     
                       
                       if(quote!='0'){
                            checkfilename(<?php echo $accid;?>,0,'quote',quote,'battery',<?php echo $battary['vehicleid'];?>,<?php echo $battary['customerno'];?>);                          
                            }
                            if(invoice !='0'){
                            checkfilename(<?php echo $accid;?>,0,'invoice',invoice,'battery',<?php echo $battary['vehicleid'];?>,<?php echo $battary['customerno'];?>);
                            }
                        
                        
    function checkfilename(maintenanceid,category,type,filename,transaction,vehicleid,customerno){
                if(filename == "" || filename == '0')
                {
                    filename = "undefined";
                }
                var url = '../../customer/'+customerno+'/vehicleid/'+vehicleid+'/'+filename;
                jQuery.ajax({
                    type: "HEAD",
                    url: url,
                    success: function (data) {
                        jQuery("#get"+transaction+"_edit #"+transaction+"_"+type+"file_view").html("");
                        jQuery("#get"+transaction+"_edit #"+transaction+"_"+type+"file_view").html("<a href='download.php?download_file="+filename+"&vid="+vehicleid+"&customerno="+customerno+"'>Download "+type+" file here</a>");
                    },
                    error: function (request, status) {
                        jQuery("#get"+transaction+"_edit #"+transaction+"_"+type+"file_view").html("");
                        jQuery("#get"+transaction+"_edit #"+transaction+"_"+type+"file_view").html("No "+type+" file Uploaded");
                    }
                });
            }
            
            function backbutton(){
                window.location.href = 'transaction.php?id=2';
            }

</script>           
            