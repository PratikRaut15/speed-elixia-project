<?php
if(isset($getedittripdata) && !empty($getedittripdata)){
    foreach ($getedittripdata as $row) {
        $vehicleno = $row['vehicleno'];
        $vehicleid = $row['vehicleid'];
        $groupid = $row['groupid'];
        $triplogno = $row['triplogno'];
        $tripstatus = $row['tripstatus'];
        $tripstatusid = $row['tripstatusid'];
        $routename = $row['routename'];
        $budgetedkms = $row['budgetedkms'];
        $budgetedhrs = $row['budgetedhrs'];
        $consignor = $row['consignor'];
        $consignee = $row['consignee'];
        $consignorid = $row['consignorid'];
        $consigneeid = $row['consigneeid'];
        $billingparty = $row['billingparty'];
        $mintemp = $row['mintemp'];
        $maxtemp = $row['maxtemp'];
        $drivername = $row['drivername'];
        $drivermobile1 = $row['drivermobile1'];
        $drivermobile2 = $row['drivermobile2'];
        $tripid = $row['tripid'];
        $perdaykm = $row['perdaykm'];
        $remark = $row['remark'];
        $actualodometer = $row['actuallodometer'];
        $previousodometer = $row['previousodometer'];
        $actualkm = $row['actualkms'];
        $estimatedtime = $row['estimatedtime'];
        $actualhrs = $row['actualhrs'];
        $etarrivaldate = isset($row['etarrivaldate']) ? date('d-m-Y', strtotime($row['etarrivaldate'])) : "";
        $materialtype = isset($row['materialtype']) ? $row['materialtype'] : 0;
        if (!is_null($row['statusdate'])) {
            $date = date('d-m-Y', strtotime($row['statusdate']));
            $time = date('H:i', strtotime($row['statusdate']));
        } else {
            $date = '';
            $time = '';
        }
    }
}
    $getstatushistory = gettriphistory_status($_SESSION['customerno'], $_SESSION['userid'], $tripid);
    if($_SESSION['customerno'] == 447 ) {
        $getTripLrDetails = getTripLrDetails($_SESSION['customerno'], $_SESSION['userid'], $tripid);
    }

?>
<br/>
<style>
    div.tripDetails {
        float: left;
        width: 45%;
    }
    div.statusHistory {
        float: right;
        width: 45%;
    }
</style>
<div class='container' style="width:100%">
    <div class="tripDetails">
        <form name="edittripform" id="edittripform" method="POST" action="trips.php?pg=tripview&frm=edit&tripid=<?php echo $tripid; ?>" onsubmit="edittripdetails(); return false;">
            <table class='table table-condensed'>
                <thead>
                    <tr>
                        <th colspan="4" >Edit Trip </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" id="ajaxstatus"></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Vehicle No</td>
                        <td>
                            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="<?php echo $vehicleno; ?>" autocomplete="off" placeholder="Enter Vehicle No" required>
                            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
                            <div id="display" class="listvehicle"></div>
                        </td>
                        <td class='frmlblTd'>Triplog No</td>
                        <td>
                            <?php
                                if ($_SESSION['customerno'] == 52) {
                                    echo '<textarea name="triplogno" id="triplogno">' . $triplogno . '</textarea>';
                                } else {
                                    echo '<input type="text" name="triplogno" id="triplogno" value="' . $triplogno . '" required >';
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Trip Status</td>
                        <td><select name="tripstatus" id='tripstatus'>
                                <option value=''>Select Status</option>
                                <?php
                                    if (isset($gettripstatus)) {
                                        foreach ($gettripstatus as $row) {
                                        ?>
                                        <option value='<?php echo $row['statusid']; ?>'<?php
    if ($tripstatusid == $row['statusid']) {
                echo "selected";
        }
        ?>  ><?php echo $row['tripstatus']; ?></option>
                                                <?php
                                                    }
                                                    }
                                                ?>
                            </select>
                            <?php
                                if ($_SESSION['customerno'] == 206 && $tripstatusid == '3') {
                                ?>
                                <br/>
                                <span id="lblGetUnloadingDateMsg">Click <a id="lnkUnloadingDate" href="#">here</a> to get the unloading dates.</span>
                                <span id="lblUnloadingDateMsg" style="display: none;">Some error occurred</span>
                                <?php
                                    }
                                ?>
                        </td>
                        <td class='frmlblTd'>Date & Time </td>
                        <td>
                            <input type="text" name="SDate" id="SDate"  value="<?php echo $date; ?>" style="width: 90px;">
<!--                            <input type="text" name="STime" id="STime" data-date="<?php echo $time; ?>" value="<?php echo $time ?>" style="width: 40px;">-->
                            <input type="text" name="STime" id="STime" data-date="<?php echo $time; ?>" data-default-value="<?php echo $time ?>" style="width: 40px;">
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Route Name</td>
                        <td><input type="text" name="routename" id="routename" value="<?php echo $routename; ?>" ></td>
                        <td class='frmlblTd'>Budgeted Kms </td>
                        <td><input type="text" name="budgetedkms" id="budgetedkms" value="<?php echo $budgetedkms; ?>" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Budgeted Hrs</td>
                        <td><input type="text" name="budgetedhrs" id="budgetedhrs" value="<?php echo $budgetedhrs; ?>" ></td>
                        <td class='frmlblTd'>Consignor</td>
                        <td>
                            <input type='text' name='consignor' id='consignor' value="<?php echo $consignor; ?>">
                            <input type='hidden' name='consignorid' id='consignorid' value="<?php if (isset($consignorid)) {echo $consignorid;}?>">
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Estimated Date Arrival </td>
                        <td>
                            <input type="text" name="etarrivaldate" id="etarrivaldate" value="<?php echo $etarrivaldate; ?>"  style="width: 120px;">
                        </td>
                        <td class='frmlblTd'>Material type</td>
                        <td>
                            <select name="materialtype" id="materialtype">
                                <option value="0">Select type</option>
                                <option value="1"<?php if ($materialtype == 1) {echo "selected";}?> >Dry</option>
                                <option value="2"<?php if ($materialtype == 2) {echo "selected";}?> >Freeze</option>
                                <option value="3"<?php if ($materialtype == 3) {echo "selected";}?> >Chiller</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Consignee</td>
                        <td>
                            <input type='text' name='consignee' id='consignee' value="<?php echo $consignee; ?>">
                            <input type='hidden' name='consigneeid' id='consigneeid' value="<?php if (isset($consignorid)) {echo $consigneeid;}?>">
                        </td>
                        <td class='frmlblTd'>Billing Party</td>
                        <td><input type="text" name="billingparty" id="billingparty" value="<?php echo $billingparty; ?>"></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Minimum Temperature</td>
                        <td><input type="text" name="mintemp" id="mintemp" value="<?php echo $mintemp; ?>" ></td>
                        <td class='frmlblTd'>Maximum Temperature</td>
                        <td><input type="text" name="maxtemp" id="maxtemp" value="<?php echo $maxtemp; ?>" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Driver Name</td>
                        <td><input type="text" name="drivername" id="drivername" value="<?php echo $drivername; ?>"></td>
                        <td class='frmlblTd'>Driver Mobile NO.</td>
                        <td><input type="text" name="drivermobile1" id="drivermobile1" value="<?php echo $drivermobile1; ?>" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Driver Mobile NO(Other).</td>
                        <td><input type="text" name="drivermobile2" id="drivermobile2" value="<?php echo $drivermobile2; ?>"></td>
                        <td class='frmlblTd'>Per Day-Km </td>
                        <td><input type="text" name="perdaykm" id="perdaykm" value="<?php echo $perdaykm; ?>"/></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Remark</td>
                        <td><textarea name="remark" id="remark"><?php echo $remark; ?></textarea></td>
                        <td class='frmlblTd'>Actual Km Traveled</td>
                        <td><input type="text" style='width:100px;' name="actualkms" id="actualkm" readonly value="<?php echo $actualkm; ?>"/> Km</td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Estimated Time </td>
                        <td><input type="text" style='width:100px;' id="esttime" name="esttime" readonly value="<?php echo $estimatedtime; ?>"> Hrs</td>
                        <td class='frmlblTd'>Actual Hrs Traveled</td>
                        <td><input type="text" style='width:100px;' id="actualhrs" name="actualhrs" readonly value="<?php echo $actualhrs; ?>"> Hrs</td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="checkbox" style="float: right;"  id="istripend" value="1" name="istripend"></td>
                        <td colspan="2"><label style="float: left;" for="istripend"> Trip End</label></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <?php
                                if ($_SESSION['customerno'] == 206 && ($_SESSION['role_modal'] == 'Administrator' || $_SESSION['role_modal'] == 'elixir')) {
                                ?>
                                <fieldset>
                                    <div class="control-group">
                                        <div class="input-prepend " id="group_div">
                                                <span class="add-on">User</span>
                                                <select id="group" name="group" onChange="addgrouptouser()">
                                                    <option selected="">Select User</option>
                                                    <!-- <option value='0'>All</option> -->
                                                    <?php
                                                        $users = getusers();
                                                            if (isset($users)) {
                                                                foreach ($users as $user) {
                                                                    if ($user->roleid == 43) {
                                                                        echo "<option value='$user->userid'>$user->realname</option>";
                                                                    }
                                                                }
                                                        }?>
                                                </select>
                                                <div id="group_list">
                                                    <?php
                                                        $groupsg = getmappedtripusers($_REQUEST['tripid']);
                                                            //print("<pre>"); print_r($groupsg); die;
                                                            if (isset($groupsg)) {
                                                                foreach ($groupsg as $group) {
                                                                ?>
                                                                <input type="hidden" class="mappedgroups" id="hid_g<?php echo ($group->groupid); ?>" rel="<?php echo ($group->groupid); ?>" value="<?php echo ($group->groupname); ?>">
                                                                <input type="hidden" name="oldUsers[]" value="<?php echo ($group->groupid); ?>">
                                                                <?php
                                                                    //$oldUsers =$group->groupid;
                                                                            }
                                                                            /*print("<pre>");
                                                                        print_r($oldUsers);*/
                                                                        } //else {
                                                                    ?>
                                                            <!-- <input type="hidden" class="mappedgroups" id="hid_g0" rel="0" value="All"> -->
                                                        <?php //} ?>
                                                </div>
                                            </div>
                                </fieldset>
                                <?php }?>
                         </td>
                    </tr>
                    <tr>
                        <td colspan="4" class='frmlblTd' style="text-align: center;">
                            <!-- <input type="hidden" name="oldUsers" value="<?php echo $oldUsers; ?>"> -->
                            <input type="submit" name="addtripsubmit" id="btnUpdate" value="Update" class='btn btn-primary'>
                            <input type='hidden' name='tripid' id='tripid' value="<?php echo $tripid; ?>">
                            <input type="hidden" name="groupid" value="<?php echo $groupid; ?>">
                            <input type="hidden" name="isApi" value="1">
                        </td>
                    </tr>
                </tbody>
            </table>
            <!--            <div style="width:42%; border: 1px solid grey; float: left;">
                            <h5>Status History</h5>
                        </div>-->
        </form>
    </div>
    <div class="statusHistory">
        <table id='innertable' class="table newTable" style='width:100%;'>
            <thead>
            <tr>
                <th colspan="3" class="">Status History</th>
            </tr>
            <tr>
                <th width='30%'>Status</th>
                <th width='40%'>Location</th>
                <th width='30%'>Status Time</th>
            </tr>
            </thead>
        <?php
            if (isset($getstatushistory)) {
                foreach ($getstatushistory as $row) {
                    if (!is_null($row['statusdate'])) {
                        echo "<tr><td>" . $row['tripstatus'] . "</td><td>" . $row['location'] . "</td><td>" . date("d-m-Y G:ia", strtotime($row['statusdate'])) . "</td></tr>";
                    } else {
                        echo "<tr><td>" . $row['tripstatus'] . "</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
                    }
                }
            } else {
                echo "<tr><td colspan='100%'> History Not Available</td></tr>";
            }
        ?>
        </table>
        <?php

        if($_SESSION['customerno'] == 447 ) {
            ?>
            <table style="padding-top: 10px;width:100%;" class="table newTable">
            <thead>
            <tr>
                <th colspan="3">Drop Point Details</th>
            </tr>
            <tr>
                <th width="50px;">Sr.No</th>
                <th>Drop Point</th>
                <th>Date Time</th>
            </tr>
            </thead>
            <?php
                if (isset($tripdroppointdata) && !empty($tripdroppointdata)) {
                    foreach ($tripdroppointdata as $key => $droppoint) {
                    ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td>
                                <?php echo location_cmn($droppoint['lat'], $droppoint['lng'], 1, $_SESSION["customerno"]); ?>
                            </td>
                            <td>
                                <?php echo date(speedConstants::DEFAULT_TIMESTAMP, strtotime($droppoint['created_on'])); ?>
                            </td>
                        </tr>
                    <?php
                        }
                        } else {
                            echo "<tr><td colspan='2'>No Data Found</td></tr>";
                        }
                    ?>
        </table>
            <?php
        }
        ?>

        <?php

        if($_SESSION['customerno'] == 447) {
            $lrdelay = '';
            if(isset($getTripLrDetails[0]['varLrCreation']) && isset( $getTripLrDetails[0]['varChitthiCreation'])){
                $lrdelay = getTimeDiff($getTripLrDetails[0]['varLrCreation'], $getTripLrDetails[0]['varChitthiCreation']);
            }

            ?>
                <table style="padding-top: 10px;width:100%;" class="table newTable">
                    <thead>
                    <tr>
                        <th colspan="2">Trip Details</th>
                    </tr>
                    </thead>

                    <tr>
                        <td>Chitthi Creation Time</td>
                        <td><?php echo $getTripLrDetails[0]['varChitthiCreation'];?></td>
                    </tr>
                    <tr>
                        <td>LR Creation Time</td>
                        <td><?php echo $getTripLrDetails[0]['varLrCreation'];?></td>
                    </tr>
                    <tr>
                        <td>LR Delay Time</td>
                        <td><?php echo $lrdelay;?></td>
                    </tr>
                    <tr>
                        <td>Yard Check Out Time</td>
                        <td><?php echo $getTripLrDetails[0]['varYardCheckout'];?></td>
                    </tr>
                    <tr>
                        <td>Yard Detention Time</td>
                        <td><?php echo $getTripLrDetails[0]['varYardCheckin'];?></td>
                    </tr>

                    <tr>
                        <td>Yard Check In Time</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Empty Return Deviation</td>
                        <td></td>
                    </tr>


                </table>
            <?php

        }

        ?>

    </div>
</div>
<script>
    $(function () {
        $("#tripstatus").change(function () {
            var statusid = $("#tripstatus").val();
            if (statusid == 10) {
                var tripid = $("#tripid").val();
                setunloading_values(statusid, tripid);
            }
        });
        $("#vehicleno").autoSuggest({
            ajaxFilePath: "../reports/autocomplete.php",
            ajaxParams: "dummydata=dummyData",
            autoFill: false,
            iwidth: "auto",
            opacity: "0.9",
            ilimit: "10",
            idHolder: "id-holder",
            match: "contains"
        });
    });
    function setunloading_values(statusid, tripid) {
        var sdate = $("#SDate").val();
        var stime = $("#STime").val();
        var data = "statusid=" + statusid + "&tripid=" + tripid + "&sdate=" + sdate + "&stime=" + stime + "&action=getunloadingdata";
        jQuery.ajax({url: "trip_ajax.php", type: 'POST', data: data,
            success: function (result) {
                var obj = jQuery.parseJSON(result);
                if (obj.actualhrs == -0) {
                    alert("Please select other status date");
                }
                else {
                    $("#actualhrs").val(obj.actualhrs);
                    var budgetedhrs = $("#budgetedhrs").val();
                    var esttime = budgetedhrs - obj.actualhrs;
                    $("#esttime").val(esttime);
                }
            },
            complete: function () {
                //jQuery('#pageloaddiv').hide();
            }
        });
    }
    function fill(Value, strparam) {
        $('#vehicleno').val(strparam);
        $('#vehicleid').val(Value);
        $('#display').hide();
    }
    jQuery(function () {
        jQuery("#consignor").autocomplete({
            source: "trip_ajax.php?action=consignorauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consignorid').val(ui.item.id);
            }
        });
        jQuery("#consignee").autocomplete({
            source: "trip_ajax.php?action=consigneeauto", minLength: 1,
            select: function (event, ui) {
                jQuery('#consigneeid').val(ui.item.id);
            }
        });
        jQuery("#lnkUnloadingDate").on("click", function (event) {
            var triplogno = jQuery("#triplogno").val();
            getUnloadingDates(triplogno);
            event.preventDefault();
        });
    });
    function getUnloadingDates(triplogno) {
        jQuery.ajax({
            type: "POST",
            url: "trip_ajax.php",
            cache: false,
            data: {action: 'getunloadingtime', triplogno: triplogno},
            success: function (jsonResult) {
                var result = jQuery.parseJSON(jsonResult);
                if (result.Status === "Success") {
                    jQuery("#tripstatus").val(result.data.unloadingendStatusId);
                    jQuery("#SDate").val(result.data.unloadenddate);
                    jQuery("#STime").val(result.data.unloadendtime);
                    jQuery("#lblGetUnloadingDateMsg").hide();
                    jQuery("#lblUnloadingDateMsg").show();
                    jQuery("#lblUnloadingDateMsg").text('Successfully pulled the trip details.');
                    refreshStatusHistory();
                }
                else {
                    jQuery("#lblGetUnloadingDateMsg").hide();
                    jQuery("#lblUnloadingDateMsg").show();
                    jQuery("#lblUnloadingDateMsg").text(result.Error);
                }
            }
        });
    }
    function refreshStatusHistory() {
        var tripid = jQuery('#tripid').val();
        if (tripid !== undefined && tripid !== '') {
            get_historydetails(tripid);
        }
    }

jQuery(document).ready(function (){
                    jQuery('#etarrivaldate').datepicker({format: "dd-mm-yyyy",autoclose:true});
});
</script>