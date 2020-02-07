<?php
$maintanace_obj = new MaintananceManager($_SESSION['customerno']);
$maintanaces = $maintanace_obj->get_approval_form_by_vehicleid($_GET['tid']);
$maintanacesdata = $maintanace_obj->get_approval_data_all_by_mainid($_GET['tid']);
$vehicleid = $maintanacesdata[0]->vehicleid;
$category = $maintanacesdata[0]->category;
$statusid = $maintanacesdata[0]->statusid;
$tyrerepairid = isset($maintanacesdata[0]->tyrerepairid) ? $maintanacesdata[0]->tyrerepairid : "";
$dealerid = isset($maintanacesdata[0]->dealerid) ? $maintanacesdata[0]->dealerid : "";
$tyre_repair = getTyreRepairType();

$getDealer = $maintanace_obj->getDealerList();

$tyre = getTyreTypedata($vehicleid);
$tyre = getTyredata($vehicleid, $_GET['tid']);
$tyre = explode(",", $tyre);

$tyreold = getTyredataOld($vehicleid, $_GET['tid']);
$tyreold = explode(",", $tyreold);

$datatyre = array();
if (isset($tyre) && !empty($tyre)) {
    for ($i = 0; $i < count($tyre); $i++) {
        $tyre[$i] = explode('-', $tyre[$i]);
        $datatyre[] = array(
            'type' => isset($tyre[$i][0]) ? $tyre[$i][0] : "",
            'serialno' => isset($tyre[$i][1]) ? $tyre[$i][1] : ""
        );
    }
}

$datatyreold = array();
if (isset($tyreold) && !empty($tyreold)) {
    for ($i = 0; $i < count($tyreold); $i++) {
        $tyreold[$i] = explode('-', $tyreold[$i]);
        $datatyreold[] = array(
            'type' => isset($tyreold[$i][0]) ? $tyreold[$i][0] : "",
            'serialno' => isset($tyreold[$i][1]) ? $tyreold[$i][1] : ""
        );
    }
}

$battdata = getbatteryno_byvehicle($vehicleid);
$getpart = getpart();
$gettask = gettask();

$battdataold = getbatteryno_byvehicle_history($vehicleid, $_GET['tid']);
?>
<style>
    .selectwidth{
        width:150px;
    }

</style>
<!--Multiple Approval Modal -->
<div class="modal hide" id="editTyreDetails" role="dialog" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Tyre Details</h4>
            </div>
            <div class="modal-body">
                <div class="clear"></div>
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
                            <th colspan="100%">Tyre Serial No. Details</th>
                            <?php
                            $srno = '';
                            $ins = '';
                            $srnoold = '';
                            $insold = '';
                            $tyre = $datatyre;
                            $tyreold = $datatyreold;
                            ?>

                        <tbody>
                            <tr>    
                                <td>Right Front</td>
                                <?php
                                if (isset($tyre) && !empty($tyre)) {
                                    $chrf = "";
                                    $chrf_read = "readonly";
                                    $key = searchForId("Right Front", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chrf = "checked";
                                        $chrf_read = "";
                                    } else {
                                        $srno = '';
                                        $chrf = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chrf = "";
                                }

                                if (isset($tyre) && !empty($tyreold)) {
                                    $key = searchForId("Right Front", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?>   
                                <td><input name="oright_front" type="text"  id="oright_front" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="rf" type="checkbox" class="chk" id="rf" <?php echo $chrf; ?> onclick="activetextbox();" ></td>
                                <td><input name="nright_front" type="text" class="txtsrno" id="nright_front" value="<?php echo $srno; ?>" <?php $chrf_read; ?> /></td>
                            </tr>
                            <tr>    
                                <td>Left Front</td>
                                <?php
                                $chlf = "";
                                $chlf_read = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Left Front", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chlf = "checked";
                                        $chlf_read = "";
                                    } else {
                                        $srno = '';
                                        $chlf = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chlf = "";
                                }


                                if (!empty($tyreold)) {
                                    $key = searchForId("Left Front", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?> 
                                <td><input name="oleft_front" type="text" id="oleft_front" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="lf" type="checkbox" class="chk" id="lf" <?php echo $chlf; ?> onclick="activetextbox();"></td>
                                <td><input name="nleft_front" type="text" class="txtsrno" id="nleft_front" value="<?php echo $srno; ?>" <?php echo $chlf_read; ?>/></td>
                            </tr>

                            <tr>    
                                <td>Right Back Out</td>
                                <?php
                                $chk_rb_out = "";
                                $chk_rb_out_readonly = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Right Back Out", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chk_rb_out = "checked";
                                        $chk_rb_out_readonly = "";
                                    } else {
                                        $srno = '';
                                        $chk_rb_out = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chk_rb_out = "";
                                }

                                if (!empty($tyreold)) {
                                    $key = searchForId("Right Back Out", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                        //  $insold = $tyreold[$key]['installedon'];
                                    } else {
                                        $srnoold = '';
                                        // $insold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                    //$insold = '';
                                }
                                ?>
                                <td><input name="oright_back_out" type="text" id="oright_back_out" value="<?php echo $srno; ?>" readonly/></td>
                                <td><input name="rb_out" type="checkbox" class="chk" id="rb_out" <?php echo $chk_rb_out; ?> onclick="activetextbox();"></td>
                                <td><input name="nright_back_out" type="text" class="txtsrno" id="nright_back_out"  value="<?php echo $srnoold; ?>" <?php echo $chk_rb_out_readonly; ?>  /></td>
                            </tr>
                            <tr>   
                                <td>Left Back Out</td>
                                <?php
                                $chk_lb_out = "";
                                $chk_lb_out_read = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Left Back Out", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chk_lb_out = "checked";
                                        $chk_lb_out_read = "";
                                    } else {
                                        $srno = '';
                                        $chk_lb_out = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chk_lb_out = "";
                                }

                                if (!empty($tyreold)) {
                                    $key = searchForId("Left Back Out", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?> 
                                <td><input name="oleft_back_out" type="text" id="oleft_back_out" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="lb_out" type="checkbox" class="chk" id="lb_out" <?php echo $chk_lb_out; ?> onclick="activetextbox();"></td>
                                <td><input name="nleft_back_out" type="text" class="txtsrno" id="nleft_back_out" value="<?php echo $srno; ?>" <?php echo $chk_lb_out_read; ?>  /></td>
                            </tr>
                            <tr>    
                                <td>Right Back In</td>
                                <?php
                                $chk_rb_in = "";
                                $chk_rb_in_read = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Right Back In", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chk_rb_in = "checked";
                                        $chk_rb_in_read = "";
                                    } else {
                                        $srno = '';
                                        $chk_rb_in = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chk_rb_in = "";
                                }

                                if (!empty($tyreold)) {
                                    $key = searchForId("Right Back In", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?>                         
                                <td><input name="oright_back_in" type="text"  id="oright_back_in" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="rb_in" type="checkbox" class="chk" id="rb_in" <?php echo $chk_rb_in; ?> onclick="activetextbox();"></td>
                                <td><input name="nright_back_in" type="text" class="txtsrno" id="nright_back_in" value="<?php echo $srno; ?>" <?php echo $chk_rb_in_read; ?> /></td>
                            </tr>
                            <tr>    
                                <td>Left Back In</td>
                                <?php
                                $chk_lb_in = "";
                                $chk_lb_in_read = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Left Back In", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chk_lb_in = "checked";
                                        $chk_lb_in_read = "";
                                    } else {
                                        $srno = '';
                                        $chk_lb_in = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chk_lb_in = "";
                                }


                                if (!empty($tyreold)) {
                                    $key = searchForId("Left Back In", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?> 
                                <td><input name="oleft_back_in" type="text" id="oleft_back_in" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="lb_in" type="checkbox" class="chk" id="lb_in" <?php echo $chk_lb_in; ?> onclick="activetextbox();"></td>
                                <td><input name="nleft_back_in" type="text" class="txtsrno" id="nleft_back_in" value="<?php echo $srno; ?>" <?php echo $chk_lb_in_read; ?>/></td>

                            </tr>
                            <tr>
                                <td>Stepney</td>
                                <?php
                                $chk_st = "";
                                $chk_st_read = "readonly";
                                if (!empty($tyre)) {
                                    $key = searchForId("Stepney", $tyre, "type");
                                    if ($key !== null) {
                                        $srno = $tyre[$key]['serialno'];
                                        $chk_st = "checked";
                                        $chk_st_read = "";
                                    } else {
                                        $srno = '';
                                        $chk_st = "";
                                    }
                                } else {
                                    $srno = '';
                                    $chk_st = "";
                                }

                                if (!empty($tyreold)) {
                                    $key = searchForId("Stepney", $tyreold, "type");
                                    if ($key !== null) {
                                        $srnoold = $tyreold[$key]['serialno'];
                                    } else {
                                        $srnoold = '';
                                    }
                                } else {
                                    $srnoold = '';
                                }
                                ?>
                                <td><input name="ostepney" type="text" id="ostepney" value="<?php echo $srnoold; ?>" readonly/></td>
                                <td><input name="st" type="checkbox" class="chk" id="st" <?php echo $chk_st; ?> onclick="activetextbox();"></td>
                                <td><input name="nstepney" type="text" class="txtsrno" id="nstepney" value="<?php echo $srno; ?>" <?php echo $chk_st_read; ?>  ></td>
                            </tr>
                        </tbody>
                        <input type="hidden" name="tyrerepair" id="tyrerepair" value="<?php echo $tyrerepairid; ?>">
                        <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $vehicleid; ?>">
                        <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    </table>
                </div>
                <input type="button" name="tyreedit" value="Update Tyre Details" class="btn btn-primary" onclick="edittyredetails();"/>
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
<!--Multiple Approval Modal Battery -->
<div class="modal hide" id="editBatteryDetails" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Battery Details</h4>
                <br>
                <?php
                $battsrnonew = '';
                if (!empty($battdata)) {
                    $battsrnonew = $battdata[0]->srno;
                    $batt_mapid = $battdata[0]->batt_mapid;
                }
                ?>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>Old Battery Serial No.</td>
                        <td><input type="text" name="old_battsrno" id="old_battsrno" value="<?php echo $battdataold; ?>" readonly></td>
                        <td>
                            <input type="checkbox" name="newbatt_chkbox" id="newbatt_chkbox" onclick="activetextbox();"/>
                        </td>
                        <td>
                            <input type="text" name="new_battsrno" id="new_battsrno" readonly value="<?php echo $battsrnonew; ?>">
                            <input type="hidden" name="batt_mapid" id="batt_mapid" value="<?php echo $batt_mapid; ?>">
                        </td>
                    </tr> 
                </table>
            </div>
            <div class="modal-body">
                <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $vehicleid; ?>">
                <input type="button" name="tyreedit" value="Update Battery Details" class="btn btn-primary" onclick="editBatterydetails();"/>
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
<!--Multiple service parts dialog start here -->
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
<!--edit details meter reading, dealer name, notes, quotation upload, tax amt  -->
<div class="modal hide" id="addPartsbox" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Transaction Details</h4>
                <br/><br/>
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
                    <input type="button" name="partsadd" value="Add Parts" class="btn btn-primary" onclick="addtransdetails();"/>
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
                                    foreach ($getDealer as $rowdealer) {
                                        $selected = "";
                                        if ($dealerid == $rowdealer['dealerid']) {
                                            $selected = "selected";
                                        }
                                        ?>
                                        <option value="<?php echo $rowdealer['dealerid']; ?>" <?php echo $selected; ?>><?php echo $rowdealer['dealername']; ?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div style="clear:both;"></div>
                        <div style=" text-align: right; float:left; width:30%; color:#000;">Notes</div>
                        <div style="float:left; width:70%;"><textarea name="transnotes" id="transnotes"><?php echo isset($maintanacesdata[0]->transnotes) ? $maintanacesdata[0]->transnotes : ""; ?></textarea></div>
                        <div style="clear:both;"></div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
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
                            <input type="text" name="meterreading" onkeypress="return isNumber(event)" id="meterreading" value="<?php echo isset($maintanacesdata[0]->meter_reading) ? $maintanacesdata[0]->meter_reading : ''; ?>">
                        </div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
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
                        <div style="float:left; width:70%;"><input type="text" name="qtnamt" id="qtnamt" value="<?php echo $maintanacesdata[0]->qtnamt; ?>"> </div>
                        <div style="clear:both;"></div>
                        <div style="text-align: right; float:left; width:30%; color:#000;">Quotation File Upload</div>
                        <div style="float:left; width:70%;"><input type="file" title="Browse File" id="file_for_quote_trans" name="file_for_quote_trans" class="file-inputs"></div>
                        <div style="clear:both;"></div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo $vehicleid; ?>">
                    <input type="hidden" name="category" id="category" value="<?php echo $category; ?>">
                    <input type="hidden" name="statusid" id="statusid" value="<?php echo $statusid; ?>">
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
<!-- Edit tax  -->
<div class="modal hide" id="edittax" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Tax Amount </h4>
                <br/><br/>
                <form name="transedit4" id="transedit4" enctype="multipart/form-data">
                    <div align="center" style="width:100%;">
                        <div style="text-align: right; float:left; width:30%; color:#000;">Tax Amount</div>
                            <div style="float:left; width:70%;">
                                <input type="text" name="taxamt" id="taxamt" value="<?php echo $maintanacesdata[0]->tax; ?>"> 
                            </div>
                        <div style="clear:both;"></div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    <input type="button" name="edittrans" id="edittrans" value="Update Tax" class="btn btn-primary" onclick="updateTransactionDetails(4);"/>
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
<!--Edit invoice amount -->
<div class="modal hide" id="editinvoice" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Invoice Amount </h4>
                <br/><br/>
                <form name="transedit5" id="transedit5" enctype="multipart/form-data">
                    <div align="center" style="width:100%;">
                        <div style="text-align: right; float:left; width:30%; color:#000;">Invoice Amount</div>
                        <div style="float:left; width:70%;"><input type="text" name="invamt" id="invamt" value="<?php echo $maintanacesdata[0]->invoice_amount; ?>"> </div>
                        <div style="clear:both;"></div>
                    </div>
                    <br/>
                    <input type="hidden" name="tid" id="tid" value="<?php echo $_GET['tid']; ?>">
                    <input type="button" name="edittrans" id="edittrans" value="Update Invoice Amount" class="btn btn-primary" onclick="updateTransactionDetails(5);"/>
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


<!-- Edit Invoice amount  -->


<script>
    jQuery(document).ready(function () {
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
                                        ?> <option value=\"<?php echo $accessory->id; ?>\"><?php echo addslashes($accessory->part_name); ?></option><?php
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
        var url = "edit_apporval_ajax.php";
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
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            },
        });
    }

    function edittaskpopup(i) {
        calculatetotalpop_tasks(i);
        var url = "edit_apporval_ajax.php";
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
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            }
        });
    }

    function addpartspopup() {
        var url = "edit_apporval_ajax.php";
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
                calculatetotal_parts(i);
                parts_list1.push(jQuery("#parts_select_" + i).val() + "-" + jQuery("#parts_amount" + i).val() + "-" + jQuery("#parts_qty" + i).val() + "-" + jQuery("#parts_discs" + i).val() + "-" + jQuery("#parts_tot" + i).val());
                parts_total += jQuery("#parts_amount" + i).val() * jQuery("#parts_qty" + i).val();
                parts_disc_amt += parseFloat(jQuery("#parts_discs" + i).val()) * jQuery("#parts_qty" + i).val();
                parts_tax_amt += parseFloat(jQuery("#parts_tot" + i).val());
            }
        }
        get_string += "&action=addPartspop&tid=" + tid;
        get_string += "&parts_select_array1=" + parts_list1.toString() + "";
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
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
        var url = "edit_apporval_ajax.php";
        var get_string = '';
        var tid = jQuery("#tid").val();
        var tasks_list1 = [];
        for (i = 1; i <= 50; i++) {
            if (jQuery("#tasks_select_" + i).val() != "-1" && jQuery("#tasks_select_" + i).val() != undefined)
            {
                calculatetotal_tasks(i);
                tasks_list1.push(jQuery("#tasks_select_" + i).val() + "-" + jQuery("#tasks_amount" + i).val() + "-" + jQuery("#tasks_qty" + i).val() + "-" + jQuery("#tasks_discs" + i).val() + "-" + jQuery("#tasks_tot" + i).val());
                tasks_total += parseFloat(jQuery("#tasks_amount" + i).val()) * parseFloat(jQuery("#tasks_qty" + i).val());
                tasks_disc_amt += parseFloat(jQuery("#tasks_discs" + i).val()) * parseFloat(jQuery("#tasks_qty" + i).val());
                tasks_tax_total += parseFloat(jQuery("#tasks_tot" + i).val());
            }
        }
        get_string += "&action=addTaskpop&tid=" + tid;
        get_string += "&tasks_select_array1=" + tasks_list1.toString() + "";
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
            success: function (result) {
                location.reload();
            }
        });
    }

    function edittyredetails() {
        var url = "edit_apporval_ajax.php";
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
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
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
        jQuery.ajax({url: "edit_apporval_ajax.php", type: 'POST', data: get_string,
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
                url: "edit_apporval_ajax.php",
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
                url: "edit_apporval_ajax.php",
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
                url: "edit_apporval_ajax.php",
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
                url: "edit_apporval_ajax.php",
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
                url: "edit_apporval_ajax.php",
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
                success: function (result) {
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



</script>