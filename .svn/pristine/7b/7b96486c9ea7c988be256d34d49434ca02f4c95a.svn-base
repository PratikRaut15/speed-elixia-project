<script>
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
    });

</script>

<?php
    /**
     * Trip add Master form
     */
?>
<br/>
<?php  
    if(!($_SESSION['customerno'] == speedConstants::CUSTNO_SAFEANDSECURE && $_SESSION['role_modal'] =='Custom' )) {   
 ?>
<div class='container'>
    <center>
        <form name="addtripform" id="addtripform" method="POST" action="trips.php?pg=tripview" onsubmit="addtripdetails();return false;">
            <table class='table table-condensed'style="width: 80%;" >
                <thead><tr><th colspan="100%" >Create Trip </th></tr></thead>
                <tbody>
                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>
                    <tr>
                        <td class='frmlblTd'>Vehicle No</td>
                        <td>
                            <!-- <input type="text" name="vehicleno" id="vehicleno" required>-->
                            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="" autocomplete="off" placeholder="Enter Vehicle No" required >
                            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                            <div id="display" class="listvehicle"></div>
                        </td>
                        <td class='frmlblTd'>Triplog No</td>
                        <td>
                        <?php
                            if ($_SESSION['customerno'] == 52) {
                                echo '<textarea name="triplogno" id="triplogno"></textarea>';
                            } else {
                                echo '<input type="text" name="triplogno" id="triplogno" required >';
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
                                        <option value='<?php echo $row['statusid']; ?>'><?php echo $row['tripstatus']; ?></option>
                                        <?php
                                            }
                                            }
                                        ?>
                            </select>
                            <a href="javascript:void(0);" id="tripstatusaddpop">&nbsp;<img src='../../images/show.png' title="Add New Trip Status" alt="Add New Trip Status"/></a>
                        </td>
                        <td class='frmlblTd'>Date & Time </td>
                        <td>
                            <input type="text" name="SDate" id="SDate"  style="width: 120px;">
                            <input type="text" name="STime" id="STime" style="width: 60px;">
                        </td>
                    </tr>

                    <tr>
                        <td class='frmlblTd'>Route Name</td>
                        <td><input type="text" name="routename" id="routename" ></td>
                        <td class='frmlblTd'>Budgeted Kms </td>
                        <td><input type="text" name="budgetedkms" id="budgetedkms" ></td>

                    </tr>

                    <tr>
                        <td class='frmlblTd'>Budgeted Hrs</td>
                        <td><input type="text" name="budgetedhrs" id="budgetedhrs" ></td>
                        <td class='frmlblTd'>Consignor</td>
                        <td>
                            <input type='text' name='consignor' id='consignor'>
                            <input type='hidden' name='consignorid' id='consignorid'>
                            <a href="javascript:void(0);" id="consignoraddpop">&nbsp;<img src='../../images/show.png' title="Add New Consignor" alt="Add New Consignor"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Estimated Date Arrival </td>
                        <td>
                            <input type="text" name="etarrivaldate" id="etarrivaldate"  style="width: 120px;">
                        </td>
                        <td class='frmlblTd'>Material type</td>
                        <td>
                            <select name="materialtype" id="materialtype">
                                <option value="0">Select type</option>
                                <option value="1">Dry</option>
                                <option value="2">Freeze</option>
                                <option value="3">Chiller</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Consignee</td>
                        <td>
                            <input type='text' name='consignee' id='consignee'>
                            <input type='hidden' name='consigneeid' id='consigneeid'>
                            <a href="javascript:void(0);" id="consigneeaddpop">&nbsp;<img src='../../images/show.png' title="Add New Consignee" alt="Add New Consignee"/></a>
                        </td>
                        <td class='frmlblTd'>Billing Party</td>
                        <td><input type="text" name="billingparty" id="billingparty" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Minimum Temperature</td>
                        <td><input type="text" name="mintemp" id="mintemp" ></td>
                        <td class='frmlblTd'>Maximum Temperature</td>
                        <td><input type="text" name="maxtemp" id="maxtemp" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Driver Name</td>
                        <td><input type="text" name="drivername" id="drivername" ></td>
                        <td class='frmlblTd'>Driver Mobile NO.</td>
                        <td><input type="text" name="drivermobile1" id="drivermobile1" ></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Driver Mobile NO(Other).</td>
                        <td><input type="text" name="drivermobile2" id="drivermobile2"></td>
                        <td class='frmlblTd'>Per Day-Km </td>
                        <td><input type="text" name="perdaykm" id="perdaykm"/></td>
                    </tr>
                    <tr>
                        <td class='frmlblTd'>Remark</td>
                        <td colspan="3"><textarea name="remark" id="remark"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="100%" class='frmlblTd' style="text-align: center;">
                            <input type="submit" name="addtripsubmit" value="Create" class='btn btn-primary'>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </center>
</div>
<?php } ?>


<!--Add status pop starts----->
<div id='addStatusBuble' class="bubble row" style='position: absolute;' oncontextmenu="return false;">
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h3 style='text-align:center;'>Add Trip Status</h3>
                <div id='ajaxBstatus2'></div>
                <table class="table" style='width: 85%;'>
                    <tbody>
                        <tr><td>Trip Status <span class="mandatory">*</span>:</td><td><input type="text" name="tripstatusp" id="tripstatusp"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="button" class="btn btn-primary" value="Submit" id="addstatuspopdata" onclick="addstatusdatapop();"/>
                <input type="button" class="btn  bubbleclose1" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->

<!--Add consignor pop starts----->
<div id='addConsignorBuble' class="bubble row" style='position: absolute;' oncontextmenu="return false;">
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h3 style='text-align:center;'>Add Consignor</h3>
                <div id='ajaxBstatus'></div>
                <table class="table" style='width: 85%;'>
                    <tbody>
                        <tr><td>Consignor Name <span class="mandatory">*</span>:</td><td><input type="text" name="consrname" id="consrname"></td></tr>
                        <tr><td>email :</td><td><input type="text" name="consremail" id="consremail"></td></tr>
                        <tr><td>Phone :</td><td><input type="text" name="consrmobile" id="consrmobile"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="button" class="btn btn-primary" value="Submit" id="addconsrtdata" onclick="addconsignordatapop();"/>
                <input type="button" class="btn  bubbleclose1" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change consignor pop ends-->

<!--Add consignee pop starts----->
<div id='addConsigneeBuble' class="bubble row" style='position: absolute;' oncontextmenu="return false;">
    <div class="col-xs-12">
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h3 style='text-align:center;'>Add Consignee</h3>
                <div id='ajaxBstatus1'></div>
                <table class="table"  style='width: 85%;'>
                    <tbody>
                        <tr><td>Consignee Name <span class="mandatory">*</span>:</td><td><input type="text" name="consname" id="consname"></td></tr>
                        <tr><td>email :</td><td><input type="text" name="consemail" id="consemail"></td></tr>
                        <tr><td>Phone :</td><td><input type="text" name="consmobile" id="consmobile"></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='row'>
            <div class='col-xs-12' style='text-align:right;'>
                <input type="button" class="btn btn-primary" value="Submit" id="addconsrtdata" onclick="addconsigneedatapop();"/>
                <input type="button" class="btn  bubbleclose1" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change consignee pop ends-->
