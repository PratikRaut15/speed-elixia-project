<?php
$accid = $_GET['accid'];
$v_id = $_GET['vehicleid'];
$battary = get_accident_details($accid);
//print_r($battary);
?>

<?php
    $currentdate = getcurrentdate();
    $today = date('d-m-Y',$currentdate);
?>



<div class="table" id="editaccident" style="top:41%; width: 51%;">
<form class="form-horizontal" id="getaccident_edit">
<fieldset>
    <div class="modal-header">
        
        <h4 style="color:#0679c0">Accident Claim</h4>
    </div>
    <div>
           
            <span id="acc_ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number'];?></span>                                                                                    
           
	<div  >            
            <fieldset>
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Vehicle Details</th>
                        <tr>
                        <td width="50%">Vehicle No.</td>
                        <td><div id="acc_veh_no"><?php echo $battary['vehicleno'];?></div></td>
                        </tr>                  
                        <tr>
                        <td>Branch</td><td><div id="acc_veh_branch"><?php echo $battary['groupname'];?></div></td>
                        </tr>                        
                        <tr>
                        <td>GPS Odometer Reading</td><td><div id="acc_veh_odometer"><?php echo $battary['odometer'];?></div></td>
                        </tr>   
                        <tr>
                        <td>Driver Name</td><td><div id="acc_drivername"><?php echo $battary['drivername'];?></div></td>
                        </tr>                                                                                                                                                              
                        <tr>
                        <td>Driver License Validity From</td><td><div id="acc_driver_lic_from"><?php echo $battary['val_from_Date'];?></div></td>
                        </tr>                                                                                                                                                                                      
                        <tr>
                        <td>Driver License Validity To</td><td><div id="acc_driver_lic_to"><?php echo $battary['val_to_Date'];?></div></td>
                        </tr>   
                        <tr>
                        <td>Type of License</td><td><div id="acc_license_type"><?php echo $battary['licence_type'];?></div></td>
                        </tr>                                                                                                                                                                                                                                                              
                        </table>
                        
                    <table class="table table-bordered table-striped">
                        <th colspan="2">Accident Details</th>                        
                        <tr>
                        <td width="50%">Transaction ID</td><td><div id="acc_transid"><?php echo $battary['transid'];?></div></td>
                        </tr>                  
                        <tr>
                        <td width="50%">Transaction Approval Date</td><td><div id="acc_trans_app_date"><?php echo $battary['approval_date'];?></div></td>
                        </tr>                                          
                        <tr>
                        <td>Category</td><td><div id="acc_category">Accident</div></td>
                        </tr>          
                        <tr>
                        <td>Accident Date</td><td><div id="acc_date"><?php echo $battary['acc_Date'];?></div></td>
                        </tr>                                      
                        <tr>
                        <td>Accident Time</td><td><div id="acc_time"><?php echo $battary['STime'];?></div></td>
                        </tr>                                                              
                        <tr>
                        <td>Accident Location</td><td><div id="acc_location"><?php echo $battary['acc_location'];?></div></td>
                        </tr>                                                                                      
                        <tr>
                        <td>Third Party Injury / Property Damage</td><td><div id="acc_tpi"><?php echo $battary['tpi'];?></div></td>
                        </tr>                                                                                                              
                        <tr>
                        <td>Accident Description</td><td><div id="acc_description"><?php echo $battary['acc_desc'];?></div></td>
                        </tr>                                                                                                                                      
                        <tr>
                        <td>Name and Location of Workshop</td><td><div id="acc_workshop"><?php echo $battary['add_workshop'];?></div></td>
                        </tr>   
                        <tr>
                        <td>Report Sent to</td><td><div id="acc_report"><?php echo $battary['send_report'];?></div></td>
                        </tr>                                                                                                                                                                                                                                                                                      
                        <tr>
                        <td>Estimated Loss Amount (INR)</td><td><div id="acc_estimated_loss"><?php echo $battary['loss_amount'];?></div></td>
                        </tr>                                                                                                                                                                                                                                                                                                              
                        <tr>
                        <td>Settlement Amount (INR)</td><td><div id="acc_settlement_amount"><?php echo $battary['sett_amount'];?></div></td>
                        </tr>                                                                                                                                                                                                                                                                                                                                       
                        <tr>
                        <td>Actual Repair Amount (INR)</td><td><div id="acc_repair_amount"><?php echo $battary['actual_amount'];?></div></td>
                        </tr>                                                                                                                                                                                                                                                                                                                                                               
                        <tr>
                        <td>Amount Spent by <?php echo $_SESSION['customercompany'];?> (INR)</td><td><div id="acc_mahindra_amount"><?php echo $battary['mahindra_amount'];?></div></td>
                        </tr>          
                        <tr>
                        <td>File Links</td><td><div id="accident_files_view">
                               <?php
                               if(!empty($battary['files']))
                               {
                                   foreach($battary['files'] as $filetr)
                                    {
                                    ?>
                                <a href="<?php echo $filetr['url']?>"><?php echo $filetr['name'];?></a> &nbsp;&nbsp;
                                    <?php
                                    }
                               }
                               ?>
                            </div>
                        </td>
                        </tr>                                  
                        <tr>
                        <td>Approval Note</td><td><div id="acc_approval_note"><?php echo $battary['approval_notes'];?></div></td>
                        </tr>                                                          
                        <tr id="accident_ofasnumber_closed" style="display:none;">
                                                   
                        <td><?php echo $_SESSION['ref_number'];?></td><td><div id="accident_ofasnumber_value"><?php echo $battary['ofasnumber'];?></div></td>
                        
                        </tr>                                                          
                        </table>
                 
                <div class="control-group">
                <div class="input-prepend " id="ofasnumberdiv">
                                                                
                <span class="add-on" style="color:#000000"><?php echo $_SESSION['ref_number'];?></span>
               
                <input type="text" name="acc_ofasnumber" id="acc_ofasnumber" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)">
                </div>
                </div>                
                </div>                                
                    <br/>
                <div class="control-group">
                <div class="input-prepend ">
                <input type="button" id="edit_close_accident" onclick="editaccident(<?php echo $accid;?>)" value="Close Transaction" class="btn btn-success">                                    
                </div>
                </div>
                </div>
	</fieldset>
            </form>
</div>
<script type="text/javascript">
    
    
   jQuery('#getaccident_edit #accident_ofasnumber_closed').show();   
   jQuery('#edit_close_accident').hide();                        
            jQuery('#getaccident_edit #ofasnumberdiv').hide(); 
</script>