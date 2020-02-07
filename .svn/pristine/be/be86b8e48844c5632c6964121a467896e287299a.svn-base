<?php
$accid = $_GET['accid'];
$v_id = $_GET['vehicleid'];
$battary = getbattbyid($accid);
$statusid = $battary['statusid'];

$parts = getpartslist($accid);
$tasks = gettasklist($accid);
$getDealer = getdealers();
$getpart = getpart();
$gettask = gettask();
$taxamt="0";
$acc = getaccessories_approval($accid);
$currentdate = getcurrentdate();
$today = date('d-m-Y', $currentdate);
$tyre1 = getTyretype_byvehicle($v_id);
if (!empty($tyre1)) {
    $tyre = explode(",", $tyre1);
    $lt = array();
    foreach ($tyre as $t){
        list($k, $v) = explode('-', $t);
        $lt[$k] = $v;
    }
}
$ans = '';
?>




<style>
    .selectwidth{
        width:150px;
    }
</style>

<div class="table" id="editbattery" style="top:41%; width: 51%;">
    <form class="form-horizontal" id="getbattery_edit">
        <fieldset>
            <div class="modal-header">
                <input type="button" name="back" id="back" style="float:left;" onclick="backbutton();" class="btn-info" value="<<Back"><br>
                <h4 style="color:#0679c0">Transaction Details</h4>
            </div>
            <div>
                <span id="vid_error" style="display: none;">Please Enter Vehicle In Date</span>            
                <span id="vod_error" style="display: none;">Please Enter Vehicle Out Date</span>                        
                <span id="id_error" style="display: none;">Please Enter Invoice Generation Date</span>                                                
                <span id="in_error" style="display: none;">Please Enter Invoice No</span>                                                            
                <span id="ia_error" style="display: none;">Please Enter Invoice Amount</span>                                                                        
                <span id="ia_q_error" style="display: none;">Invoice Amount cannot be greater than Quotation Amount</span>                                                                                    

                <span id="ofas_error" style="display: none;">Please Enter <?php echo $_SESSION['ref_number'] ?></span>                                                                        

                <span id="datediff_error" style="display: none;">Vehicle Out Date cannot be less than Vehicle In Date</span>
                <div>            
                    <fieldset>
                        <table class="table table-bordered table-striped">
                            <th colspan="2">Vehicle Details 
                                <?php if($statusid!=8 && $statusid!=13 ){
                                    echo $statusid;
                                    ?><span style="float: right; cursor: pointer;" data-toggle="modal" href="#vehicledetailsedit"><i class="icon-pencil"></i></span> <?php 
                                    } ?>
                            </th>
                            <tr>
                                <td width="50%">Vehicle No.</td>
                                <td><div id="batt_veh_no"><?php echo $battary['vehicleno']; ?></div></td>
                            <input type="hidden" name="cno" id="cno" value="<?php echo $battary['customerno']; ?>"/>
                            </tr>                  
                            <tr>
                                <td>Branch</td><td><div id="batt_veh_branch"><?php echo $battary['groupname']; ?></div></td>
                            </tr> 
                            <?php if ($_SESSION['use_tracking'] == '1') { ?>
                                <tr>
                                    <td>GPS Odometer Reading</td><td><div id="batt_veh_odometer"><?php echo $battary['odometer']; ?></div></td>
                                </tr>  
                            <?php } ?>
                            <tr>
                                <td>Vehicle Meter reading </td>
                                <td><div id="batt_meter_reading"><?php echo $battary['meter_reading']; ?></div></td>
                            </tr>                    
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Transaction Details  <?php if($statusid!=8 && $statusid!=13){ ?><span style="float: right; cursor: pointer;" data-toggle="modal" href="#transdetailsedit"><i class="icon-pencil"></i></span><?php } ?></th>
                            <tr>
                                <td width="50%">Transaction ID</td><td><div id="batt_transid"><?php echo $battary['transid']; ?></div></td>
                            </tr>                  
                            <tr>
                                <td>Category</td><td><div id="batt_category"><?php echo $battary['category']; ?></div></td>
                            </tr>  

                            <tr>
                                <td>Dealer name </td>
                                <td>
                                    <div id="batt_dealer"><?php echo $battary['dealername']; ?></div>                            
                                </td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>
                                    <div id="batt_notes"><?php echo $battary['notes']; ?></div>
                                </td>                        
                            <tr>
                                <td>Status</td>
                                <td><div id="batt_status"><?php echo $battary['statusname']; ?></div></td>
                            </tr>
                            </tr>                                                
                            <tr id="trans_close_date" style="display:none;">
                                <td>Transaction Close Date</td>
                                <td>
                                    <div class="input-prepend" id="trans_close_date_value">                            
                                </td>  
                            </tr>                                                                                                
                            <tr>
                                <td>Vehicle In Date</td>
                                <td><input id="batt_vehicle_in_date" name="batt_vehicle_in_date" type="text"  value="<?php echo date('d-m-Y', strtotime($battary['vehicle_in_date'])); ?>" required/></td>
                            </tr>
                            <tr>
                                <td>Vehicle Out Date</td>
                                <td><input id="batt_vehicle_out_date" name="batt_vehicle_out_date" type="text" value="<?php echo date('d-m-Y', strtotime($battary['vehicle_out_date'])); ?>" required/></td>
                            </tr> 
                            <?php
                            if ($_SESSION['customerno'] == 118 && $battary['category'] == "Battery") {
                                ?>  
                                <tr>
                                    <td>New Battery Serial No.</td>
                                    <?php if ($battary['statusid'] == 8) { ?>
                                        <td><input id="new_battsrno" name="new_battsrno" type="text" value="<?php echo $battary['srno']; ?>"></td>
                                        <?php
                                    } elseif ($battary['statusid'] == 13) {
                                        ?>
                                        <td><input id="new_battsrno" name="new_battsrno" type="text" value="<?php echo $battary['srno']; ?>" readonly=""></td>
                                        <?php
                                    }
                                    ?>
                                </tr> 
                                <?php
                            }
                            ?>
                        </table>

                        <table class="table table-bordered table-striped">
                            <th colspan="2">Quotation Details <?php if($statusid!=8 && $statusid!=13){ ?> <span style="float: right; cursor: pointer;" data-toggle="modal" href="#qtndetails"><i class="icon-pencil"></i></span><?php } ?></th>
                            <tr>
                                <td width="50%">Quotation Amount (INR)</td>
                                <td>
                                    <div id="batt_amount_quote"><?php echo $battary['amount_quote']; ?></div></td>
                            </tr>
                            <!--<tr>
                                <td>Quotation File</td>
                                <td>
                                <div class="input-prepend" id="battery_quotefile_view">                            
                                </td>                              
                            </tr>-->
                            <tr>
                                    <td>Quotation File</td>
                                    <td>
                                        
                                        <?php 
                                        $url = "../../customer/" . $_SESSION['customerno'] . "/vehicleid/" . $battary['vehicleid'] . "/";
                                        
                                        if ($battary['quote_file_name'] == "") { ?>
                                            <?php
                                            echo "NA";
                                        } else {
                                            ?>
                                            <a target="_blank" href="<?php echo $url . $battary['quote_file_name']; ?>" ><?php echo $battary['quote_file_name']; ?></a>
                                        <?php } ?>
                                    </td>
                            </tr>
                            
                            
                            
                            
                            
                            
                            <tr>
                                <td>Quotation Submission Date</td><td><div id="batt_submission_date"><?php echo $battary['submission_date']; ?></div></td>
                            </tr>
                            <tr id="quotation_approval_note" style="display: none;">
                                <td>Quotation Approval Note</td>
                                <td>
                                    <div id="quotation_approval_note_val"><?php echo $battary['approval_notes']; ?></div>
                                </td>
                            </tr>
                            <?php
                            if ($_SESSION['customerno'] != 118) {
                                ?>
                                <tr id="show_tyre_type">
                                    <td>Tyre Type</td><td><div id="batt_tyre_type"><?php echo $battary['tyre_type']; ?></div></td>
                                </tr> 
                                <?php
                            }
                            ?>
                            <?php
                            if ($_SESSION['customerno'] == 118 && $battary['categoryid'] == 1) {
                                ?>
                                <tr>
                                    <td>Tyre Repair Type</td><td><div id="tyre_repair"><?php echo $battary['repairtype']; ?></div></td>
                                </tr>
                                <?php
                                echo isset($battary['tyre_srno']) ? $battary['tyre_srno'] : '';

                                if ($battary['statusid'] == 8 && $battary['tyrerepairid'] == 1) {
                                    ?>
                                    <div id="tyre_type">
                                        <table class="table table-bordered table-striped">
                                            <tr>
                                            <span id="rf_error" style="display: none;color: #FF0000">Please Enter New Right Front Sr No.</span>
                                            <span id="rbout_error" style="display: none;color: #FF0000;">Please Enter New Right Back Out Sr No.</span>
                                            <span id="rbin_error" style="display: none;color: #FF0000;">Please Enter New Right Back In Sr No.</span>
                                            <span id="lf_error" style="display: none;color: #FF0000;">Please Enter New Left Front Sr No.</span>
                                            <span id="lbout_error" style="display: none;color: #FF0000;">Please Enter New Left Back Out Sr No.</span>
                                            <span id="lbin_error" style="display: none;color: #FF0000;">Please Enter New Left Back In Sr No.</span>
                                            <span id="st_error" style="display: none;color: #FF0000;">Please Enter New Stepney Sr No.</span>
                                            <span id="chk_error" style="display: none;color: #FF0000;">Please Tick Checkbox</span>
                                            <tr>
                                                <th colspan="100%"> New Tyre Serial No. Details</th>

                                            <tbody>
                                                <tr>    
                                                    <td>Right Front</td>
                                                        <?php
                                                        if (!empty($lt)) {
                                                            $key = 'Right Front';
                                                            if (array_key_exists($key, $lt)) {
                                                                $ans = $lt[$key];
                                                            } else {
                                                                $ans = '';
                                                            }
                                                        }
                                                        ?>   
                                                    <td><input name="oright_front" type="text"  id="oright_front" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="rf" type="checkbox" class="chk" id="rf" onclick="activetextbox();" ></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nright_front" type="text"  id="nright_front" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>    
                                                    <td>Left Front</td>
        <?php
        if (!empty($lt)) {
            $key = 'Left Front';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?> 
                                                    <td><input name="oleft_front" type="text" id="oleft_front" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="lf" type="checkbox" class="chk" id="lf" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nleft_front" type="text"  id="nleft_front" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>

                                                <tr>    
                                                    <td>Right Back Out</td>
        <?php
        if (!empty($lt)) {
            $key = 'Right Back Out';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?>
                                                    <td><input name="oright_back_out" type="text" id="oright_back_out" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="rb_out" type="checkbox" class="chk" id="rb_out" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nright_back_out" type="text"  id="nright_back_out" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>   
                                                    <td>Left Back Out</td>
        <?php
        if (!empty($lt)) {
            $key = 'Left Back Out';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?> 
                                                    <td><input name="oleft_back_out" type="text" id="oleft_back_out" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="lb_out" type="checkbox" class="chk" id="lb_out" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nleft_back_out" type="text"  id="nleft_back_out" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>    
                                                    <td>Right Back In</td>
        <?php
        if (!empty($lt)) {
            $key = 'Right Back In';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?>                         
                                                    <td><input name="oright_back_in" type="text"  id="oright_back_in" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="rb_in" type="checkbox" class="chk" id="rb_in" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nright_back_in" type="text"  id="nright_back_in" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>    
                                                    <td>Left Back In</td>
        <?php
        if (!empty($lt)) {
            $key = 'Left Back In';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?> 
                                                    <td><input name="oleft_back_in" type="text" id="oleft_back_in" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="lb_in" type="checkbox" class="chk" id="lb_in" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nleft_back_in" type="text"  id="nleft_back_in" readonly/></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr>
                                                    <td>Stepney</td>
        <?php
        if (!empty($lt)) {
            $key = 'Stepney';
            if (array_key_exists($key, $lt)) {
                $ans = $lt[$key];
            } else {
                $ans = '';
            }
        }
        ?>
                                                    <td><input name="ostepney" type="text" id="ostepney" value="<?php echo $ans; ?>" readonly/></td>
                                                    <td><input name="st" type="checkbox" class="chk" id="st" onclick="activetextbox();"></td>
                                                    <?php
                                                    if ($battary['tyrerepairid'] == 1) {
                                                        ?>
                                                        <td><input name="nstepney" type="text" id="nstepney" readonly></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
        <?php
    }
}
?>
                        </table>

                        
                            <?php
                            $partamtarr=array();
                            if(!empty($parts)){
                            ?>
                            <table id="show_parts" class="table table-bordered table-striped">
                            <th colspan="6">Parts Consumed <?php if($statusid!=8){ ?><span style="float: right; cursor: pointer;" data-toggle="modal" href="#addPartsbox" alt="Add Parts"><i class="icon-plus"></i></span><?php } ?> </th>
                            <tr>
                                <td>Part</td>
                                <td>Quantity</td>
                                <td>Cost Per Unit</td>
                                <td>Cost Per Discount</td>
                                <td>Total</td>
                                <td></td>
                            </tr>
                            <?php     
                            
                                foreach ($parts as $part) {
                                    if ($part->part_name != '') {
                                        $partamtarr[] = $part->total;
                                        ?>
                                        <tr>
                                            <td><?php echo $part->part_name; ?></td>
                                            <td><?php echo $part->qty; ?></td>
                                            <td><?php echo $part->amount; ?></td>
                                            <td><?php echo $part->discount; ?></td>
                                            <td><?php echo $part->total; ?></td>
                                            <?php if($statusid!=8){?>
                                            <td>
                                                            <span style="float: left; cursor: pointer;" data-toggle="modal" href="#editparts<?php echo $part->mid; ?>" alt="Edit Parts"><i class='icon-pencil'></i></span>
                                                            | <a onclick='return confirm("Are you sure you want to delete parts?");' 
                                                                 href='../../modules/transactions/edit_approve_ajax.php?action=deleteparts&id=<?php echo $_GET['id'];?>&accid=<?php echo $_GET['accid']; ?>&vehicleid=<?php echo $_GET['vehicleid'];?>&partid=<?php echo $part->mid; ?>'><i class="icon-trash"></i></a>

                                                            <!-- Edit parts row wise start-->
                                                            <div class="modal hide" id="editparts<?php echo $part->mid; ?>" role="dialog">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Parts</h4>
                                                                            <div align="center" style="width:100%;">
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Parts</div><div style="float:left; width:70%;"><select name="parts_select_<?php echo $part->mid; ?>" id="parts_select_<?php echo $part->mid; ?>" style="width:200px;"><option value="<?php echo $part->partid; ?>"><?php echo $part->part_name; ?></option></select></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Quantity</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)" name="parts_qty<?php echo $part->mid; ?>" id="parts_qty<?php echo $part->mid; ?>" value="<?php echo $part->qty; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style=" text-align: right; float:left; width:30%; color:#000;">Cost Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)" name="parts_amount<?php echo $part->mid; ?>" id="parts_amount<?php echo $part->mid; ?>" value="<?php echo $part->amount; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Disc Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onblur="calculatetotalpop_parts(<?php echo $part->mid; ?>)"  onkeypress="return isNumber(event)" name="parts_discs<?php echo $part->mid; ?>" id="parts_discs<?php echo $part->mid; ?>" value="<?php echo $part->discount; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Total </div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" name="parts_tot<?php echo $part->mid; ?>" id="parts_tot<?php echo $part->mid; ?>" disabled="disable" value="<?php echo $part->total; ?>"></div>
                                                                                <input type="hidden" name="pid<?php echo $part->mid; ?>" id="pid<?php echo $part->mid; ?>" value="<?php echo $part->mid; ?>">
                                                                                <input type="hidden" name="getid<?php echo $part->mid; ?>" id="getid<?php echo $part->mid; ?>" value="<?php echo $_GET['id']; ?>">
                                                                                <input type="hidden" name="tid<?php echo $part->mid; ?>" id="tid<?php echo $part->mid; ?>" value="<?php echo $_GET['accid']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="modal-body" style="text-align: center;">
                                                                            <input type="button" name="tyreedit" value="Update Part" class="btn btn-primary" onclick="editpartspop(<?php echo $part->mid; ?>);"/>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <div class="clear"></div>
                                                                            <div>
                                                                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Edit parts row wise end-->  
                                                        </td>
                                            <?php }else{ echo"<td>&nbsp;</td>"; }?>
                                        </tr>
                            <?php
                                    }
                                }
                            ?>    
                                </table>
                            <?php } ?>
                        
                            <?php
                            $taskamtarr=array();
                            if (!empty($tasks)) {
                                ?>
                            <table id="show_tasks" class="table table-bordered table-striped">
                                <th colspan="6">Task Performed 
                                    <span style="float: right; cursor: pointer;" data-toggle="modal" href="#addTaskbox" alt="Add Task"><i class="icon-plus"></i></span>
                                </th>
                                <tr>
                                    <td>Task</td>
                                    <td>Quantity</td>
                                    <td>Cost Per Unit</td>
                                    <td>Cost Per Discount</td>
                                    <td>Total</td>
                                    <td></td>
                                </tr>
    <?php
    foreach ($tasks as $task) {
        if ($task->part_name != '') {
            $taskamtarr[] = $task->total;
            ?>
                                        <tr>
                                            <td><?php echo $task->part_name; ?></td>
                                            <td><?php echo $task->qty; ?></td>
                                            <td><?php echo $task->amount; ?></td>
                                            <td><?php echo $task->discount; ?></td>
                                            <td><?php echo $task->total; ?></td>
                                            <td>
                                                            <span style="float: left; cursor: pointer;" data-toggle="modal" href="#edittasks<?php echo $task->taskid; ?>" alt="Edit Task"><i class='icon-pencil'></i></span> | <a onclick='return confirm("Are you sure you want to delete task?");' href='../../modules/transactions/edit_approve_ajax.php?action=deletetasks&id=<?php echo $_GET['id']; ?>&tid=<?php echo $_GET['accid']; ?>&vehicleid=<?php echo $_GET['vehicleid'];?>&taskid=<?php echo $task->taskid; ?>'><i class='icon-trash'></i></a>
                                                            <!-- Edit parts row wise start-->
                                                            <div class="modal hide" id="edittasks<?php echo $task->taskid; ?>" role="dialog">
                                                                <div class="modal-dialog modal-sm">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Tasks</h4>
                                                                            <div align="center" style="width:100%;">
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Tasks</div><div style="float:left; width:70%;"><select name="tasks_select_<?php echo $task->taskid; ?>" id="tasks_select_<?php echo $task->taskid; ?>" style="width:200px;"><option value="<?php echo $task->partid; ?>"><?php echo $task->part_name; ?></option></select></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Quantity</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" onkeypress="return isNumber(event)"  onblur="calculatetotalpop_tasks(<?php echo $task->taskid; ?>)"  name="tasks_qty<?php echo $task->taskid; ?>" id="tasks_qty<?php echo $task->taskid; ?>" value="<?php echo $task->qty; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style=" text-align: right; float:left; width:30%; color:#000;">Cost Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;"  onkeypress="return isNumber(event)" onblur="calculatetotalpop_tasks(<?php echo $task->taskid; ?>)" name="tasks_amount<?php echo $task->taskid; ?>" id="tasks_amount<?php echo $task->taskid; ?>" value="<?php echo $task->amount; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Disc Per Unit</div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;"  onblur="calculatetotalpop_tasks(<?php echo $task->taskid; ?>)" onkeypress="return isNumber(event)" name="tasks_discs<?php echo $task->taskid; ?>" id="tasks_discs<?php echo $task->taskid; ?>" value="<?php echo $task->discount; ?>"></div>
                                                                                <div style="clear:both;"></div><br>
                                                                                <div style="text-align: right; float:left; width:30%; color:#000;">Total </div>
                                                                                <div style="float:left; width:70%;"><input type="text" style="width:200px;" name="tasks_tot<?php echo $task->taskid; ?>" id="tasks_tot<?php echo $task->taskid; ?>" disabled="disable" value="<?php echo $task->total; ?>"></div>

                                                                                <input type="hidden" name="pid<?php echo $task->taskid; ?>" id="pid<?php echo $task->taskid; ?>" value="<?php echo $task->taskid; ?>">
                                                                                <input type="hidden" name="getid<?php echo $task->taskid; ?>" id="getid<?php echo $task->taskid; ?>" value="<?php echo $_GET['id']; ?>">
                                                                                <input type="hidden" name="tid<?php echo $task->taskid; ?>" id="tid<?php echo $task->taskid; ?>" value="<?php echo $_GET['accid']; ?>">
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="modal-body" style="text-align: center;">
                                                                            <input type="button" name="taskedit" value="Update Task" class="btn btn-primary" onclick="edittaskpopup(<?php echo $task->taskid; ?>);"/>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <div class="clear"></div>
                                                                            <div>
                                                                                <button class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Edit parts row wise end-->  
                                                        </td>
                                        </tr>
            <?php
        }
    }
    ?>
                            </table>
                                <?php
                            }
                            ?>
                        <?php
                        if (!empty($acc)) {
                            ?>
                            <table id="show_acc" class="table table-bordered table-striped">
                                <th colspan="4">Accesories</th>
                                <tr>
                                    <td>Accessory</td>
                                    <td>Quantity</td>
                                    <td>Total Cost</td>
                                </tr>
    <?php
    foreach ($acc as $ac) {
        if ($ac->name != '') {
            ?>
                                        <tr>
                                            <td><?php echo $ac->name; ?></td>
                                            <td><?php echo $ac->quantity; ?></td>

                                            <td><?php echo $ac->cost; ?></td>
                                        </tr>
            <?php
        }
    }
    ?>
                            </table>
                                <?php
                            }
                            ?>
                        <div id="invoiceno_div">
                            <table class="table table-bordered table-striped">
                                <th colspan="2">Invoice Details</th>                            
                                <tr>
                                    <td width="50%">Invoice Generation Date</td>
                                    <td><input id="batt_invoice_date" name="batt_invoice_date" type="text" value="<?php echo date('d-m-Y', strtotime($battary['invoice_date'])); ?>" required/></td>
                                </tr>
                                <tr>
                                    <td>Invoice Number</td>
                                    <td><input type="text" name="batt_invoice_no" id="batt_invoice_no" placeholder="e.g. 12586" maxlength="50" value="<?php echo $battary['batt_invoice_no']; ?>" onkeypress="return nospecialchars(event)"></td>
                                </tr>
<?php if ($battary['categoryid'] == 2 || $battary['categoryid'] == 3 ) { ?>
                                    <tr>
                                        <td>Tax Amount</td>
                                        <?php 
                                        $taxamt = isset($battary['tax'])?$battary['tax']:"0";
                                        ?>
                                        <td> <input type="text" name="p_tax" id="p_tax" placeholder="e.g. 125" value="<?php echo $battary['tax']; ?>" maxlength="10" size="8"/></td>
                                    </tr>
                                    <tr>
                                    <td>Invoice Amount (INR)</td>
                                    <td>
                                         <?php 
                                            $total_inv_amt="";
                                            $totalarr = array_merge($partamtarr,$taskamtarr);
                                            $total_inv_amt = round(array_sum($totalarr),2);
                                            
                                            if(empty($total_inv_amt)||$total_inv_amt==""){
                                                $total_inv_amt=$battary['invoice_amount'];
                                            }
                                            $total_inv_amt = $total_inv_amt +$taxamt;
                                            $total_inv_amt = round($total_inv_amt,2);
                                            $data = array('tid'=>$_GET['accid'],'invamt'=>$total_inv_amt);
                                            $invamt ="";
                                            if(isset($total_inv_amt) && !empty($total_inv_amt)){
                                                if(isset($data)){
                                                $invamt = updateinvamt_get($data);
                                                //echo $total_inv_amt;
                                                $totalinvamt = isset($invamt)?$invamt:$battary['invoice_amount'];
                                                }else{
                                                    $totalinvamt = $battary['invoice_amount'];
                                                }
                                            }else{
                                                $totalinvamt=$battary['invoice_amount'];
                                            }
                                            ?>
                                        <input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" value="<?php echo $totalinvamt; ?>" maxlength="30" size="8" >
                                        <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id" value="<?php echo $accid; ?>">
                                        <input type="hidden" name="edit_vehicle_id" id="edit_vehicle_id" value="<?php echo $v_id; ?>">                                            
                                        <input type="hidden" name="category_id" id="category_id" value="<?php echo $battary['categoryid']; ?>">                                                                            
                                    </td>
                                </tr>
                                    
<?php } ?>
<?php if ($battary['categoryid'] == 0 || $battary['categoryid'] == 1 || $battary['categoryid'] == 5 ) { ?>
                                    <tr>
                                        <td>Tax Amount</td>
                                        <?php 
                                        $taxamt = isset($battary['tax'])?$battary['tax']:"0";
                                        ?>
                                        <td> <input type="text" name="p_tax" id="p_tax" placeholder="e.g. 125" value="<?php echo $battary['tax']; ?>" maxlength="10" size="8"/></td>
                                    </tr>
                                    <tr>
                                    <td>Invoice Amount (INR)</td>
                                    <td>
                                         <?php 
                                            $total_inv_amt="";
                                            $total_inv_amt = $battary['invoice_amount']+$taxamt;
                                            ?>
                                        <input type="text" name="batt_amount_invoice" id="batt_amount_invoice" placeholder="e.g. 125" value="<?php echo $total_inv_amt; ?>" maxlength="30" size="8" >
                                        <input type="hidden" name="maintenance_edit_id" id="maintenance_edit_id" value="<?php echo $accid; ?>">
                                        <input type="hidden" name="edit_vehicle_id" id="edit_vehicle_id" value="<?php echo $v_id; ?>">                                            
                                        <input type="hidden" name="category_id" id="category_id" value="<?php echo $battary['categoryid']; ?>">                                                                            
                                    </td>
                                </tr>
                                    
<?php } ?>                                    
                                    
                                

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

                                    <td><?php echo $_SESSION['ref_number']; ?></td>


                                    <td>
                                        <div class="input-prepend" id="ofasnumber_view_value">                            
                                    </td>  

                                </tr>                                                
                                <tr id="payment_approval_date" style="display:none;">
                                    <td>Payment Approval Date</td>
                                    <td>
                                        <div class="input-prepend" id="payment_approval_date_value">                            
                                    </td>  

                                </tr>                                                                        

                                <tr id="payment_approval_note" style="display:none;">
                                    <td>Payment Approval Note</td>
                                    <td>
                                        <div class="input-prepend" id="payment_approval_note_value">                            
                                    </td>  

                                </tr>                                                                                                
                            </table>
                        </div>

                        <div class="control-group">
                            <div class="input-prepend " id="ofasnumberdiv">

                                <span class="add-on" style="color:#000000"><?php echo $_SESSION['ref_number']; ?></span>

                                <input type="text" name="ofasnumber" id="ofasnumber" placeholder="e.g. 12586" maxlength="50" onkeypress="return nospecialchars(event)">
                            </div>
                        </div>                
                </div>                                
                <br/>
                <div class="control-group">
                    <div class="input-prepend ">
                        <input type="button" id="edit_save_battery" onclick="editbattery(<?php echo $accid ?>,<?php echo $battary['vehicleid'] ?>, 10,<?php echo $battary['categoryid'] ?>,<?php echo $battary['amount_quote'] ?>);" value="Send for Final Payment Approval" class="btn btn-success">                    
                        <input type="button" id="edit_cancel_battery" onclick="editbattery(<?php echo $accid ?>,<?php echo $battary['vehicleid'] ?>, 11,<?php echo $battary['categoryid'] ?>,<?php echo $battary['amount_quote'] ?>);" value="Send for Cancellation" class="btn btn-danger">                    
                        <input type="button" id="edit_close_battery" onclick="editbattery(<?php echo $accid ?>,<?php echo $battary['vehicleid'] ?>, 14,<?php echo $battary['categoryid'] ?>,<?php echo $battary['amount_quote'] ?>);" value="Close Transaction" class="btn btn-success">                                    
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
<?php
if (!empty($battary['quote_file_name'])) {
    $quote = $battary['quote_file_name'];
} else {
    $quote = 0;
}
if (!empty($battary['invoice_file_name'])) {
    $invoice = $battary['invoice_file_name'];
} else {
    $invoice = 0;
}
?>
<!--vehicle details-->                                    
<div class="modal hide" id="vehicledetailsedit" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Vehicle Details</h4>
                <br/><br/>
                <form name="transedit2" id="transedit2" method="POST">
                    <div align="center" style="width:100%;">
                        <div style="text-align: right; float:left; width:30%; color:#000;" >Meter Reading</div><div style="float:left; width:70%;">
                            <input type="text" name="meterreading" onkeypress="return isNumber(event)" id="meterreading" value="<?php echo isset($battary['meter_reading']) ? $battary['meter_reading']: ''; ?>">
                        </div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['accid']; ?>">
                    <input type="button" name="edittrans" id="edittrans" value="Update Vehicle Details" class="btn btn-primary" onclick="updateTransactionDetails(1);"/>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <div class="clear"></div>
        <div>
            <button class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!--Transaction details -->                                    
<!--edit details notes, quotation upload, tax amt  -->
<div class="modal hide" id="transdetailsedit" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Transaction Details</h4>
                <br/><br/>
                <form name="transedit1" id="transedit1" enctype="multipart/form-data">
                    <div align="center" style="width:100%;">
                        <!--                            <div style="text-align: right; float:left; width:30%; color:#000;" >Meter Reading</div><div style="float:left; width:70%;">
                                                        <input type="text" name="meterreading" onkeypress="return isNumber(event)" id="meterreading" value="<?php echo isset($maintanacesdata[0]->meter_reading) ? $maintanacesdata[0]->meter_reading : ''; ?>">
                                                    </div>-->
                        <div style="clear:both;"></div>
                        <div style="text-align: right; float:left; width:30%; color:#000;">Dealer</div>
                        <div style="float:left; width:70%;">
                            <select name="dealer" id="dealer" width="40%">
<?php if (isset($getDealer)) { ?>
                                    <option value="0">Select Dealer</option>
                                    <?php
                                    $dealerid = $battary['dealer_id'];
                                    foreach ($getDealer as $rowdealer) {
                                        $selected = "";
                                        if ($dealerid == $rowdealer->dealerid) {
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $rowdealer->dealerid; ?>" <?php echo $selected; ?>><?php echo $rowdealer->dealername; ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div style="clear:both;"></div>
                        <div style=" text-align: right; float:left; width:30%; color:#000;">Notes</div>
                        <div style="float:left; width:70%;"><textarea name="transnotes" id="transnotes"><?php echo isset($battary['notes']) ? $battary['notes'] : ""; ?></textarea></div>
                        <div style="clear:both;"></div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['accid']; ?>">
                    <input type="button" name="edittrans" id="edittrans" value="Update Transaction Details" class="btn btn-primary" onclick="updateTransactionDetails(2);"/>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <div class="clear"></div>
        <div>
            <button class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<!-- Qtn details -->
<div class="modal hide" id="qtndetails" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Quotation Details</h4>
                <br/><br/>
                <form name="transedit3" id="transedit3" enctype="multipart/form-data">
                    <div align="center" style="width:100%;">
                        <div style="text-align: right; float:left; width:30%; color:#000;">Quotation Amount</div>
                        <div style="float:left; width:70%;"><input type="text" name="qtnamt" id="qtnamt" value="<?php echo $battary['amount_quote']; ?>"> </div>
                        <div style="clear:both;"></div>
                        <div style="text-align: right; float:left; width:30%; color:#000;">Quotation File Upload</div>
                        <div style="float:left; width:70%;"><input type="file" title="Browse File" id="file_for_quote_trans" name="file_for_quote_trans" class="file-inputs"></div>
                        <div style="clear:both;"></div>
<!--                        <div style="text-align: right; float:left; width:30%; color:#000;">Quotation Approval Note</div>
                        <div style="float:left; width:70%;"><textarea  name="qtn_approval_notes" id="qtn_approval_notes"><?php echo $battary['approval_notes']; ?></textarea> </div>-->
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['accid']; ?>">
                    <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $battary['vehicleid']; ?>">
                    <input type="hidden" name="category" id="category" value="<?php echo $battary['categoryid']; ?>">
                    <input type="hidden" name="statusid" id="statusid" value="<?php echo $battary['statusid']; ?>">
                    <input type="button" name="edittrans" id="edittrans" value="Update Quotation Details" class="btn btn-primary" onclick="updateTransactionDetails(3);"/>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <div class="clear"></div>
        <div>
            <button class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<div class="modal hide" id="addPartsbox" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Parts Details</h4>
                <br/><br/>
                <span id="parts_error"> Please select parts. </span>
                <form name="partsformdata" id="partsformdata">
                    <div id="parts_category" style="overflow:auto; height:225px;">
                        <table style="display: table; width: 100%" id="myTable">
                            <tr>
                                <th width="15%">Sr. No</th>
                                <th width="20%">Parts</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Cost Per Unit</th>
                                <th width="15%">Discount</th>
                                <th width="15%">Final Amount</th>
                                <th width="10%">
                                    <span id='btnaddrow' style="float: right;" onclick="addrow();">
                                        <a><img  src="../../images/show.png" alt="Add Row"/> </a>
                                    </span>
                                </th>
                            </tr>
                        </table>  
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    <input type="button" name="partsadd" value="Add Parts" class="btn btn-primary" onclick="addpartspopup();"/>
                </form>   
            </div>
        </div>
    </div>
    <div class="modal-body">
    </div>
    <div class="modal-footer">
        <div class="clear"></div>
        <div>
            <button class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!--Multiple service tasks dialog start here -->
<div class="modal hide" id="addTaskbox" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Tasks Details</h4>
                <br/><br/>
                <span id="tasks_error"> Please select parts. </span>
                <form name="tasksformdata" id="tasksformdata">
                    <div id="tasks_category" style="overflow:auto; height:225px;">
                        <table style="display: table; width: 100%" id="myTable1">
                            <tr>
                                <th width="15%">Sr. No</th>
                                <th width="20%">Task</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Cost Per Unit</th>
                                <th width="15%">Discount</th>
                                <th width="15%">Final Amount</th>
                                <th width="10%">
                                    <span id='btnaddrow1' style="float: right;" onclick="addrow1();">
                                        <a><img  src="../../images/show.png" alt="Add Row"/> </a>
                                    </span>
                                </th>
                            </tr>
                        </table> 
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    <input type="button" name="tasksadd" value="Add Task" class="btn btn-primary" onclick="addtaskspopup();"/>
                </form>         
            </div>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
            <div class="clear"></div>
            <div>
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
    var status = <?php echo $battary['statusid']; ?>;
    var categoryid = <?php echo $battary['categoryid']; ?>;
    var quote = '<?php echo $quote; ?>';
    var invoice = '<?php echo $invoice; ?>';
    var custno = jQuery("#cno").val();
    var roleid = '<?php echo $_SESSION["roleid"];?>'

    if (status == '8'  )
    {
        jQuery('#getbattery_edit #invoice_file_div').show();
        jQuery('#getbattery_edit #invoice_file').show();
        jQuery('#getbattery_edit #edit_save_battery').show();
        jQuery('#getbattery_edit #edit_cancel_battery').show();
        jQuery('#getbattery_edit #edit_close_battery').hide();
        jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', false);
        jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', false);
        jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', false);
        jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', false);
        jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', false);
        jQuery('#getbattery_edit #ofasnumberdiv').hide();
        jQuery('#getbattery_edit #invoice_file_view').hide();
        jQuery('#getbattery_edit #payment_approval_date').hide();
        jQuery('#getbattery_edit #payment_approval_note').hide();
        jQuery('#getbattery_edit #trans_close_date').hide();
        jQuery('#getbattery_edit #ofasnumber_view').hide();
        jQuery('#getbattery_edit #quotation_approval_note').show();

        if (categoryid != '1')
        {
            jQuery('#getbattery_edit #show_tyre_type').hide();
        }
        else
        {
            jQuery('#getbattery_edit #show_tyre_type').show();
        }
        if (categoryid != '2')
        {
            jQuery('#getbattery_edit #show_parts').hide();
            jQuery('#getbattery_edit #show_tasks').hide();
        }
        else
        {
            jQuery('#getbattery_edit #show_parts').show();
            jQuery('#getbattery_edit #show_tasks').show();
        }
        if (categoryid != '3')
        {
            jQuery('#getbattery_edit #show_acc').hide();

        }
        else
        {
            jQuery('#getbattery_edit #show_acc').show();

        }
    }
    else if (status == '13') {
        if (custno != '118') {
            jQuery('#invoiceno_view').hide();
        }
        if (categoryid != '5')
        {
            jQuery('#getbattery_edit #invoice_file').show();
            jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
            jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
            jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', false);
            jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', false);
            jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', false);
            jQuery('#getbattery_edit #invoice_file_div').hide();
            jQuery('#getbattery_edit #invoice_file_view').hide();
            jQuery('#getbattery_edit #payment_approval_date').hide();
            jQuery('#getbattery_edit #payment_approval_note').hide();
            jQuery('#getbattery_edit #trans_close_date').hide();
            jQuery('#getbattery_edit #ofasnumber_view').hide();
            jQuery('#getbattery_edit #quotation_approval_note').show();

        }
        if (status == '13' && roleid !='42'){
            jQuery('#getbattery_edit #edit_close_battery').hide();
            jQuery('#getbattery_edit #ofasnumberdiv').hide();
        }else{
            jQuery('#getbattery_edit #edit_close_battery').show();
        jQuery('#getbattery_edit #ofasnumberdiv').show();
        }
        jQuery('#getbattery_edit #edit_save_battery').hide();
        jQuery('#getbattery_edit #edit_cancel_battery').hide();
        
        
        if (categoryid != 5) {
            jQuery('#getbattery_edit #batt_file_for_invoice').hide();
        }
        if (categoryid != '1')
        {
            jQuery('#getbattery_edit #show_tyre_type').hide();
        }
        else
        {
            jQuery('#getbattery_edit #show_tyre_type').show();
        }
        if (categoryid != '2')
        {
            jQuery('#getbattery_edit #show_parts').hide();
            jQuery('#getbattery_edit #show_tasks').hide();
        }
        else
        {
            jQuery('#getbattery_edit #show_parts').show();
            jQuery('#getbattery_edit #show_tasks').show();
        }
    }
    else
    {
        jQuery('#getbattery_edit #invoice_file').hide();


        jQuery('#getbattery_edit #batt_vehicle_in_date').prop('disabled', true);
        jQuery('#getbattery_edit #batt_vehicle_out_date').prop('disabled', true);
        jQuery('#getbattery_edit #batt_invoice_date').prop('disabled', true);
        jQuery('#getbattery_edit #batt_invoice_no').prop('disabled', true);
        jQuery('#getbattery_edit #batt_amount_invoice').prop('disabled', true);
        jQuery('#getbattery_edit #invoice_file_div').hide();


        jQuery('#getbattery_edit #ofasnumberdiv').hide();
        jQuery('#getbattery_edit #edit_save_battery').hide();
        jQuery('#getbattery_edit #edit_cancel_battery').hide();
        jQuery('#getbattery_edit #edit_close_battery').hide();
        if (status == 6) {
            jQuery('#getbattery_edit #invoice_file_view').show();
        } else {
            jQuery('#getbattery_edit #invoice_file_view').hide();
        }
        jQuery('#getbattery_edit #payment_approval_date').hide();
        jQuery('#getbattery_edit #payment_approval_note').hide();
        jQuery('#getbattery_edit #trans_close_date').hide();
        jQuery('#getbattery_edit #ofasnumber_view').hide();
        if (status == 6) {
            jQuery('#getbattery_edit #quotation_approval_note').hide();
        } else {
            jQuery('#getbattery_edit #quotation_approval_note').show();
        }

        if (categoryid != '1')
        {
            jQuery('#getbattery_edit #show_tyre_type').hide();
        }
        else
        {
            jQuery('#getbattery_edit #show_tyre_type').show();
        }
        if (categoryid == '2' || categoryid == '3')
        {
            jQuery('#getbattery_edit #show_parts').show();
            jQuery('#getbattery_edit #show_tasks').show();
        }
        else
        {
            jQuery('#getbattery_edit #show_parts').hide();
            jQuery('#getbattery_edit #show_tasks').hide();

        }
    }
    if (status == 8)
    {
        //alert(quote);
        if (quote != 0) {

            checkfilename(<?php echo $accid; ?>, 0, 'quote', quote, 'battery',<?php echo $battary['vehicleid']; ?>,<?php echo $battary['customerno']; ?>);
        }
    }
    else
    {
        //alert(quote);
        if (quote != '0') {
            checkfilename(<?php echo $accid; ?>, 0, 'quote', quote, 'battery',<?php echo $battary['vehicleid']; ?>,<?php echo $battary['customerno']; ?>);
        }
        if (invoice != '0') {
            checkfilename(<?php echo $accid; ?>, 0, 'invoice', invoice, 'battery',<?php echo $battary['vehicleid']; ?>,<?php echo $battary['customerno']; ?>);
        }
    }

    function checkfilename(maintenanceid, category, type, filename, transaction, vehicleid, customerno) {
        if (filename == "" || filename == '0')
        {
            filename = "undefined";
        }
        var url = '../../customer/' + customerno + '/vehicleid/' + vehicleid + '/' + filename;
        jQuery.ajax({
            type: "HEAD",
            url: url,
            success: function (data) {
                jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("");
                jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("<a href='download.php?download_file=" + filename + "&vid=" + vehicleid + "&customerno=" + customerno + "'>Download " + type + " file here</a>");
            },
            error: function (request, status) {
                jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("");
                jQuery("#get" + transaction + "_edit #" + transaction + "_" + type + "file_view").html("No " + type + " file Uploaded");
            }
        });
    }
    
    jQuery(document).ready(function (){
        jQuery("#parts_error").hide();
        jQuery("#tasks_error").hide();
    });
    var rowCount = 1;
    var rowCount1 = 1;
    function addrow() {
        if (rowCount > 49) {
            jQuery("#btnaddrow").css('display', 'none');
        } else {
            jQuery("#btnaddrow").css('display', 'block');
        }
        var table = document.getElementById("myTable");
        var row = table.insertRow(-1);
        row.id = rowCount + "trid";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var hidden_text = "<input type=\"hidden\" id=\"parts_max_amount_hid_" + rowCount + "\" name=\"parts_max_amount_hid_" + rowCount + "\" value=\"0\">";
        cell1.innerHTML = rowCount;
        cell2.innerHTML = "<select id=\"parts_select_" + rowCount + "\" onchange=\"addrow_select(" + rowCount + ")\" class=\"partsSelect  selectwidth \" name=\"parts_select_" + rowCount + "\"><option value=\"-1\">Select Parts</option><?php
                                if (isset($getpart)) {
                                    foreach ($getpart as $accessory) {
                                        $partid = $accessory->id;
                                        $partname = $accessory->part_name;
                                        ?> <option value=\"<?php echo $partid; ?>\"><?php echo addslashes($partname); ?></option><?php
                                    }
                                }
                                ?> </select>";
        cell3.innerHTML = "<input type=\"text\" name=\"parts_qty" + rowCount + "\"  onkeypress=\"return isNumber(event)\"  onblur=\"calculatetotal_parts(" + rowCount + ")\" id=\"parts_qty" + rowCount + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"1\">";
        cell4.innerHTML = "<input type=\"text\" name=\"parts_amount" + rowCount + "\"  onkeypress=\"return isNumber(event)\" id=\"parts_amount" + rowCount + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell5.innerHTML = "<input type=\"text\" name=\"parts_discs" + rowCount + "\"  onkeypress=\"return isNumber(event)\" id=\"parts_discs" + rowCount + "\" onblur=\"calculatetotal_parts(" + rowCount + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell6.innerHTML = "<input type=\"text\" name=\"parts_tot" + rowCount + "\" onkeypress=\"return isNumber(event)\"  id=\"parts_tot" + rowCount + "\" onblur=\"calculatetotal_parts(" + rowCount + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell7.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction(" + rowCount + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a>" + hidden_text + "<input type='hidden' id='countrow" + rowCount + "' value='" + rowCount + "' />";
        rowCount++;
    }
    
    function addrow1() {
        if (rowCount1 > 49) {
            jQuery("#btnaddrow1").css('display', 'none');
        } else {
            jQuery("#btnaddrow1").css('display', 'block');
        }
        var table = document.getElementById("myTable1");
        var row = table.insertRow(-1);
        row.id = rowCount1 + "trid1";
        var cell1 = row.insertCell(0);
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var hidden_text = "<input type=\"hidden\" id=\"tasks_max_amount_hid_" + rowCount1 + "\"  name=\"tasks_max_amount_hid_" + rowCount1 + "\" value=\"0\">";
        cell1.innerHTML = rowCount1;
        cell2.innerHTML = "<select id=\"tasks_select_" + rowCount1 + "\" class=\"tasksSelect selectwidth\" onchange=\"addrow_select1(" + rowCount1 + ")\" name=\"tasks_select_" + rowCount1 + "\"><option value=\"-1\">Select Tasks</option><?php
                                if (isset($gettask)) {
                                    foreach ($gettask as $accessory) {
                                        ?> <option value=\"<?php echo $accessory->id; ?>\"><?php echo addslashes($accessory->task_name); ?></option><?php
                                    }
                                }
                                ?> </select>";
        cell3.innerHTML = "<input type=\"text\" name=\"tasks_qty" + rowCount1 + "\" onkeypress=\"return isNumber(event)\" onblur=\"calculatetotal_tasks(" + rowCount1 + ")\" id=\"tasks_qty" + rowCount1 + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"1\">";
        cell4.innerHTML = "<input type=\"text\" name=\"tasks_amount" + rowCount1 + "\" onkeypress=\"return isNumber(event)\"  id=\"tasks_amount" + rowCount1 + "\" placeholder=\"e.g. 5\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell5.innerHTML = "<input type=\"text\" name=\"tasks_discs" + rowCount1 + "\" onkeypress=\"return isNumber(event)\"  id=\"tasks_discs" + rowCount1 + "\" onblur=\"calculatetotal_tasks(" + rowCount1 + ")\" placeholder=\"e.g. 10\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell6.innerHTML = "<input type=\"text\" name=\"tasks_tot" + rowCount1 + "\" id=\"tasks_tot" + rowCount1 + "\" placeholder=\"e.g. 10\" onblur=\"calculatetotal_tasks(" + rowCount1 + ")\" maxlength=\"10\" size=\"8\" value=\"\">";
        cell7.innerHTML = "<a href=\"javascript:void(0);\" onclick=\"myDeleteFunction1(" + rowCount1 + ");\"><img src=\"../../images/hide.gif\" alt=\"Delete\"/></a>" + hidden_text + "<input type='hidden' id='countrow" + rowCount1 + "' value='" + rowCount1 + "' />";
        rowCount1++;
    }
    
    function myDeleteFunction1(a) {
        var trid1 = '#' + a + 'trid1';
        jQuery(trid1).remove();
        rowCount1--;
        if (rowCount1 > 49) {
            jQuery("#btnaddrow").css('display', 'none');
        } else {
            jQuery("#btnaddrow").css('display', 'block');
        }
    }
    
    function myDeleteFunction(a) {
        var trid = '#' + a + 'trid';
        jQuery(trid).remove();
        rowCount--;
        if (rowCount > 49) {
            jQuery("#btnaddrow").css('display', 'none');
        } else {
            jQuery("#btnaddrow").css('display', 'block');
        }
    }
    
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }

    function showtyresrno() {
        var tyrecat = jQuery("#tyrerepair").val();
    }

    function calculatetotal_parts(i) {
        var totamt = 0;
        var disc = 0;
        if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
        {
            var totamount = parseFloat(jQuery("#parts_amount" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
            disc = parseFloat(jQuery("#parts_discs" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
            totamt += totamount - disc;
            var totamount = parseFloat(totamt.toFixed(2));
            jQuery("#parts_tot" + i).val(totamount);
        }
    }

    function calculatetotalpop_parts(i) {
        var totamt = 0;
        var disc = 0;
        if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
        {
            var totamount = parseFloat(jQuery("#parts_amount" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
            disc = parseFloat(jQuery("#parts_discs" + i + "").val()) * parseFloat(jQuery("#parts_qty" + i + "").val());
            totamt += parseFloat(totamount)-parseFloat(disc);
            var totamount = parseFloat(totamt.toFixed(2));
            jQuery("#parts_tot" + i).val(totamount);
        }
    }

    function calculatetotal_tasks(i) {
        var totamt = 0;
        var disc = 0;
        if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
        {
            var totamount = parseFloat(jQuery("#tasks_amount" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
            var disc = parseFloat(jQuery("#tasks_discs" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
            totamt += parseFloat(totamount)-parseFloat(disc);
            var totamount = parseFloat(totamt.toFixed(2));
            jQuery("#tasks_tot" + i).val(totamount);
        }
    }

    function calculatetotalpop_tasks(i) {
        var totamt = 0;
        var disc = 0;
        if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
        {
            var totamount = parseFloat(jQuery("#tasks_amount" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
            var disc = parseFloat(jQuery("#tasks_discs" + i + "").val()) * parseFloat(jQuery("#tasks_qty" + i + "").val());
            totamt += parseFloat(totamount)-parseFloat(disc);
            var totamount = parseFloat(totamt.toFixed(2));
            jQuery("#tasks_tot" + i).val(totamount);
        }
    }

    function editpartspop(i) {
        calculatetotalpop_parts(i);
        var url = "edit_approve_ajax.php";
        var partid = jQuery("#parts_select_" + i).val();
        var partqty = jQuery("#parts_qty" + i).val();
        var partamount = jQuery("#parts_amount" + i).val();
        var partdisc = jQuery("#parts_discs" + i).val();
        var partstot = jQuery("#parts_tot" + i).val();
        var pid = jQuery("#pid" + i).val();
        var getid = jQuery("#getid" + i).val();
        var tid = jQuery("#tid" + i).val();
        var get_string = '';
        get_string += "&partid=" + partid.toString() + "";
        get_string += "&partqty=" + partqty.toString() + "";
        get_string += "&partamount=" + partamount.toString() + "";
        get_string += "&partdisc=" + partdisc.toString() + "";
        get_string += "&parttot=" + partstot.toString() + "";
        get_string += "&pid=" + pid.toString() + "";
        get_string += "&getid=" + getid.toString() + "";
        get_string += "&tid=" + tid.toString() + "";
        get_string += "&action=editparts";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            },
        });
    }

    function edittaskpopup(i) {
        calculatetotalpop_tasks(i);
        var url = "edit_approve_ajax.php";
        var partid = jQuery("#tasks_select_" + i).val();
        var partqty = jQuery("#tasks_qty" + i).val();
        var partamount = jQuery("#tasks_amount" + i).val();
        var partdisc = jQuery("#tasks_discs" + i).val();
        var partstot = jQuery("#tasks_tot" + i).val();
        var taskid = jQuery("#pid" + i).val();
        var getid = jQuery("#getid" + i).val();
        var tid = jQuery("#tid" + i).val();
        var get_string = '';
        get_string += "&partid=" + partid.toString() + "";
        get_string += "&partqty=" + partqty.toString() + "";
        get_string += "&partamount=" + partamount.toString() + "";
        get_string += "&partdisc=" + partdisc.toString() + "";
        get_string += "&parttot=" + partstot.toString() + "";
        get_string += "&pid=" + taskid.toString() + "";
        get_string += "&getid=" + getid.toString() + "";
        get_string += "&tid=" + tid.toString() + "";
        get_string += "&action=edittask";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            }
        });
    }

    function addpartspopup() {
        var url = "edit_approve_ajax.php";
        var get_string = '';
        var tid = jQuery("#tid").val();
        for (var i = 1; i <= 50; i++) {
            if (jQuery("#parts_select_" + i + "").val() != '-1' && (jQuery("#parts_qty" + i + "").val() == '' || jQuery("#parts_amount" + i + "").val() == '' || jQuery("#parts_qty" + i + "").val() == '0') || jQuery("#parts_amount" + i + "").val() == '0') {
                jQuery("#parts_error").show();
                jQuery("#parts_error").fadeOut(9000);
            }
        }
        var parts_list1 = [];
        var parts_total = 0;
        var parts_tax_amt = 0; // add total tax amount 
        var parts_disc_amt = 0; // parts discount amount

        for (i = 1; i <= 50; i++) {
            if (jQuery("#parts_select_" + i).val() != "-1" && jQuery("#parts_select_" + i).val() != undefined)
            {
                parts_list1.push(jQuery("#parts_select_" + i).val() + "-" + jQuery("#parts_amount" + i).val() + "-" + jQuery("#parts_qty" + i).val() + "-" + jQuery("#parts_discs" + i).val() + "-" + jQuery("#parts_tot" + i).val());
                parts_total += jQuery("#parts_amount" + i).val() * jQuery("#parts_qty" + i).val();
                parts_disc_amt += parseFloat(jQuery("#parts_discs" + i).val()) * jQuery("#parts_qty" + i).val();
                parts_tax_amt += parseFloat(jQuery("#parts_tot" + i).val());
            }
        }
        get_string += "&action=addPartspop&tid=" + tid;
        get_string += "&parts_select_array1=" + parts_list1.toString() + "";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
                //return false;
            },
        });
    }

    function addtaskspopup() {
        var tasks_total = 0;
        var tasks_disc_amt = 0;
        var tasks_tax_total = 0;
        for (var i = 1; i <= 50; i++) {
            if (jQuery("#tasks_select_" + i + "").val() != '-1' && (jQuery("#tasks_qty" + i + "").val() == '' || jQuery("#tasks_amount" + i + "").val() == '' || jQuery("#tasks_qty" + i + "").val() == '0') || jQuery("#tasks_amount" + i + "").val() == '0') {
                iserror = 1;
                jQuery("#tasks_error").show();
                jQuery("#tasks_error").fadeOut(9000);
            }
        }
        //  addTaskpop
        var url = "edit_approve_ajax.php";
        var get_string = '';
        var tid = jQuery("#tid").val();
        var tasks_list1 = [];
        for (i = 1; i <= 50; i++) {
            if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
            {
                tasks_list1.push(jQuery("#tasks_select_" + i).val() + "-" + jQuery("#tasks_amount" + i).val() + "-" + jQuery("#tasks_qty" + i).val() + "-" + jQuery("#tasks_discs" + i).val() + "-" + jQuery("#tasks_tot" + i).val());
                tasks_total += parseFloat(jQuery("#tasks_amount" + i).val()) * parseFloat(jQuery("#tasks_qty" + i).val());
                tasks_disc_amt += parseFloat(jQuery("#tasks_discs" + i).val()) * parseFloat(jQuery("#tasks_qty" + i).val());
                tasks_tax_total += parseFloat(jQuery("#tasks_tot" + i).val());
            }
        }
        get_string += "&action=addTaskpop&tid=" + tid;
        get_string += "&tasks_select_array1=" + tasks_list1.toString() + "";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            }
        });
    }

    function edittyredetails() {
        var url = "edit_approve_ajax.php";
        var old_rf = jQuery("#oright_front").val();
        var new_rf = jQuery("#nright_front").val();
        var new_rb_out = jQuery("#nright_back_out").val();
        var old_rb_out = jQuery("#oright_back_out").val();
        var new_rb_in = jQuery("#nright_back_in").val();
        var old_rb_in = jQuery("#oright_back_in").val();
        var new_lb_out = jQuery("#nleft_back_out").val();
        var old_lb_out = jQuery("#oleft_back_out").val();
        var new_lf = jQuery("#nleft_front").val();
        var old_lf = jQuery("#oleft_front").val();
        var new_lb_in = jQuery("#nleft_back_in").val();
        var old_lb_in = jQuery("#oleft_back_in").val();
        var new_st = jQuery("#nstepney").val();
        var old_st = jQuery("#ostepney").val();
        var tyrerepairtype = jQuery("#tyrerepair").val();
        var vehicleid = jQuery("#vehicleid").val();
        var tid = jQuery("#tid").val();
        var oldtyresrno_list = [];
        var newtyresrno_list = [];
        var newtyre_tyreid_srno = [];
        if (jQuery("#rf").attr("checked"))
        {
            oldtyresrno_list.push("Right Front-" + old_rf);
            newtyresrno_list.push("Right Front-" + new_rf);
            newtyre_tyreid_srno.push("1$" + new_rf);
        }
        if (jQuery("#rb_out").attr("checked")) {
            oldtyresrno_list.push("Right Back Out-" + old_rb_out);
            newtyresrno_list.push("Right Back Out-" + new_rb_out);
            newtyre_tyreid_srno.push("3$" + new_rb_out);
        }
        if (jQuery("#rb_in").attr("checked")) {
            oldtyresrno_list.push("Right Back In-" + old_rb_in);
            newtyresrno_list.push("Right Back In-" + new_rb_in);
            newtyre_tyreid_srno.push("6$" + new_rb_in);
        }
        if (jQuery("#lf").attr("checked")) {
            oldtyresrno_list.push("Left Front-" + old_lf);
            newtyresrno_list.push("Left Front-" + new_lf);
            newtyre_tyreid_srno.push("2$" + new_lf);
        }
        if (jQuery("#lb_out").attr("checked")) {
            oldtyresrno_list.push("Left Back Out-" + old_lb_out);
            newtyresrno_list.push("Left Back Out-" + new_lb_out);
            newtyre_tyreid_srno.push("4$" + new_lb_out);
        }
        if (jQuery("#lb_in").attr("checked")) {
            oldtyresrno_list.push("Left Back In-" + old_lb_in);
            newtyresrno_list.push("Left Back In-" + new_lb_in);
            newtyre_tyreid_srno.push("7$" + new_lb_in);
        }
        if (jQuery("#st").attr("checked")) {
            oldtyresrno_list.push("Stepney-" + old_st);
            newtyresrno_list.push("Stepney-" + new_st);
            newtyre_tyreid_srno.push("5$" + new_st);
        }
        var get_string = '';
        get_string += "&oldtyre_list=" + oldtyresrno_list.toString() + "";
        get_string += "&newtyre_list=" + newtyresrno_list.toString() + "";
        get_string += "&newtyre_tyreid_srno=" + newtyre_tyreid_srno.toString() + "";
        get_string += "&action=edittyre&tid=" + tid;
        get_string += "&tyrerepair=" + tyrerepairtype.toString() + "";
        get_string += "&vehicleid=" + vehicleid.toString() + "";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                //return false;        
                location.reload();
            },
        })
    }

    function editBatterydetails() {
        var old_battsrno = jQuery("#old_battsrno").val();
        var new_battsrno = jQuery("#new_battsrno").val();
        var batt_mapid = jQuery("#batt_mapid").val();
        var vehicleid = jQuery("#vehicleid").val();
        var tid = jQuery("#tid").val();
        var get_string = '';
        get_string += "&old_battsrno=" + old_battsrno.toString() + "";
        get_string += "&new_battsrno=" + new_battsrno.toString() + "";
        get_string += "&action=editbattery&tid=" + tid;
        get_string += "&vehicleid=" + vehicleid.toString() + "";
        jQuery.ajax({url: "edit_approve_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                //return false;
                location.reload();
            },
        })
    }

    function updateTransactionDetails(id) {
        var tid = "";
        var meterreading = "";
        var dealer = "";
        var transnotes = "";
        var taxamt_trans = "";
        var category = "";
        var vehicleid = "";
        var statusid = "";
        var invamt = "";
        if (id == 1) {
            var tid = "";
            var meterreading = "";
            var dealer = "";
            var transnotes = "";
            var taxamt_trans = "";
            var category = "";
            var vehicleid = "";
            var statusid = "";
            var invamt = "";
            tid = jQuery("#tid").val();
            meterreading = jQuery("#meterreading").val();
            var form_data = new FormData();
            form_data.append('tid', tid);
            form_data.append('meterreading', meterreading);
            form_data.append('action', "vehicletransedit");
            jQuery.ajax({
                url: "edit_approve_ajax.php",
                type: 'POST',
                data: form_data,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function (result) {
                    location.reload();
                },
            });
        }
        if (id == 2) {
            var tid = "";
            var dealer = "";
            var transnotes = "";
            tid = jQuery("#tid").val();
            dealer = jQuery("#dealer").val();
            transnotes = jQuery("#transnotes").val();
            var file_data_form = jQuery('#transedit2').serialize();
            var form_data2 = new FormData();
            form_data2.append('dealer', dealer);
            form_data2.append('transnotes', transnotes);
            form_data2.append('tid', tid);
            form_data2.append('action', "edittransdetails");
            jQuery.ajax({
                url: "edit_approve_ajax.php",
                type: 'POST',
                data: form_data2,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function (result) {
                    location.reload();
                },
            });
        }
        if (id == 3) {
            var tid = "";
            var meterreading = "";
            var dealer = "";
            var category = "";
            var vehicleid = "";
            var statusid = "";
            var invamt = "";
            //var qtn_approval_notes = "";
            
            tid = jQuery("#tid").val();
            statusid = jQuery("#statusid").val();
            vehicleid = jQuery("#vehicleid").val();
            category = jQuery("#category").val();
            var qtnamt = jQuery("#qtnamt").val();
            var file_data = jQuery('#file_for_quote_trans').prop('files')[0];
            var file_data_form = jQuery('#transedit3').serialize();
            var form_data3 = new FormData();
            form_data3.append('file', file_data);
            form_data3.append('qtnamt', qtnamt);
            form_data3.append('tid', tid);
            form_data3.append('statusid', statusid);
            form_data3.append('category', category);
            form_data3.append('vehicleid', vehicleid);
            form_data3.append('action', "editqtn");
            jQuery.ajax({
                url: "edit_approve_ajax.php",
                type: 'POST',
                data: form_data3,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function (result) {
                    location.reload();
                },
            });
        }
        if (id == 4) {
            var tid = "";
            var taxamt = "";
            tid = jQuery("#tid").val();
            var taxamt = jQuery("#taxamt").val();
            var form_data4 = new FormData();
            form_data4.append('tid', tid);
            form_data4.append('taxamt', taxamt);
            form_data4.append('action', "taxedit");
            jQuery.ajax({
                url: "edit_approve_ajax.php",
                type: 'POST',
                data: form_data4,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function (result) {
                    location.reload();
                },
            });
        }
        if (id == 5) {
            var tid = "";
            var invamt = "";
            tid = jQuery("#tid").val();
            invamt = parseFloat(jQuery("#invamt").val());
            var form_data5 = new FormData();
            form_data5.append('tid', tid);
            form_data5.append('invamt', invamt);
            form_data5.append('action', "invoiceamtedit");
            jQuery.ajax({
                url: "edit_approve_ajax.php",
                type: 'POST',
                data: form_data5,
                dataType: 'text',
                contentType: false,
                processData: false,
                success: function (result) {
                    location.reload();
                },
            });
        }
    }
    
    function addrow_select(id) {
        var selectid = $("#parts_select_" + id).find(":selected").val();
        if (selectid != -1) {
            var dataresult = "work=getpartsDetail&partid=" + selectid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result){
                    var obj = JSON.parse(result);
                    var unitamount1 = obj.unitamount;
                    var unitdiscount1 = obj.unitdiscount;
                    jQuery("#parts_amount" + id).val(unitamount1);
                    jQuery("#parts_discs" + id).val(unitdiscount1);
                }
            });
        }
    }

    function addrow_select1(id) {
        var selectid = $("#tasks_select_" + id).find(":selected").val();
        if (selectid != -1) {
            var dataresult = "work=gettaskDetail&taskid=" + selectid;
            jQuery.ajax({
                url: "route_ajax.php",
                type: 'POST',
                cache: false,
                data: dataresult,
                success: function (result) {
                    var obj = JSON.parse(result);
                    var unitamount1 = obj.unitamount;
                    var unitdiscount1 = obj.unitdiscount;
                    jQuery("#tasks_amount" + id).val(unitamount1);
                    jQuery("#tasks_discs" + id).val(unitdiscount1);
                }
            });
        }
    }
    
    function backbutton(){
        window.location.href = 'transaction.php?id=2';
    }
    
</script>           
