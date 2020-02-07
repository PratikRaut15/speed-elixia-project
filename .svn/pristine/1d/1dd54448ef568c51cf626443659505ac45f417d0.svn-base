<?php
    if (isset($_GET['did'])) {
        $driver = getdriver($_GET['did']);
        //print_r($driver);

        //$driver_alert = getdriveralert_by_did($_GET['did']);
        //echo $driver_alert[0]->driverid;
    }
    $license_text = getcustombyid(14, 'License No');
?>
<form class="form-horizontal well "  style="width:70%;" enctype="multipart/form-data"  method="POST" id="modifydriver">
<?php include 'panels/editdriver.php';?>
<fieldset>
    <div class="control-group">
        <table>
            <tr><th colspan="100%">Driver Detail</th></tr>
                <?php if (!empty($driver->uploadfilename)) {?>
                <tr>
                    <td rowspan="8" style="vertical-align: top;">
                        <img style="flot:right; width:120px; height: 120px;" src='../../customer/<?php echo $_SESSION['customerno'] ?>/driver/<?php echo $driver->driverid ?>/<?php echo $driver->uploadfilename ?>'>
                    </td>
                </tr>
                <?php
                    }
                ?>
            <tr>
                <td class="rightalign"><span class="add-on">Name <span class="mandatory">*</span></span></td>
                <td>
                    <input  type="text" name="dname" id="dname" placeholder="Driver Name" value="<?php echo $driver->drivername; ?>" maxlength="20" autofocus>
                    <input type="hidden" name="driverid" id="driverid" value="<?php echo $driver->driverid; ?>">
                </td>

                <td class="rightalign"><span class="add-on"><?php echo $license_text; ?> <span class="mandatory">*</span></td>
                <td><input type="text" name="drivelicno" id="drivelicno" value="<?php echo $driver->driverlicno; ?>" placeholder="Driver<?php echo $license_text; ?>" maxlength="20"></td>

            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Phone No <span class="mandatory">*</span></span></td>
                <td><input type="text" name="drivephoneno" id="drivephoneno" value="<?php echo $driver->driverphone; ?>" placeholder="Driver Phone No" maxlength="10"></td>

                <td class="rightalign"><span class="add-on">Birth Date </span></td>
                <td><input id="BDate" class="input-small" type="text" value="<?php if ($driver->birthdate != "01-01-1970") {echo $driver->birthdate;}?>" name="BDate"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Age</span></td>
                <td><input type="number" name="age" id="age" value="<?php echo $driver->age; ?>"></td>
                <td class="rightalign"><span class="add-on">Blood Group</span></td>
                <td><input type="text" name="bloodgroup" id="bloodgroup" value="<?php echo $driver->bloodgroup; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Licence Validity</span></td>
                <td><input type="text" name="LicvalidDate" id="LicvalidDate" value="<?php if ($driver->licence_validity !== "01-01-1970") {echo $driver->licence_validity;}?>"></td>
                <td class="rightalign"><span class="add-on">Licence Issue Authority</span></td>
                <td><input type="text" name="lic_issue_auth" id="lic_issue_auth"  value="<?php echo $driver->licence_issue_auth; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Driver Alert</span></td>
                <td style="text-align: left;">
                    <input type="radio" name="driveralert" id="ds1" value="yes" class="rg"<?php //if(isset($driver_alert[0]->alertstatus)){if($driver_alert[0]->alertstatus=='1'){echo "checked";}}?>> Yes
                    <input type="radio"  id="ds2" name="driveralert" value="no" class="rg"                                                                                           <?php //if(isset($driver_alert[0]->alertstatus)){ if($driver_alert[0]->alertstatus=='0'){echo "checked";}}?> checked > No
                </td>
                <td class="alertcontent rightalign" >
                   <span class="add-on"> Alert By :</span>
                </td>
                <td class="alertcontent" style="text-align: left;">
                    <input type="checkbox" name="email" id="sms" value="email"                                                                               <?php if (isset($driver_alert[0]->send_bymail)) {if ($driver_alert[0]->send_bymail == '1') {echo "checked";}}?>> Email <input type="checkbox" name="sms" id="sms" value="sms"<?php if (isset($driver_alert[0]->send_bysms)) {if ($driver_alert[0]->send_bysms == '1') {echo "checked";}}?>>SMS
                </td>
            </tr>
            <tr class="alertcontent"><td colspan="2">&nbsp;</td><td class="alertcontent rightalign"><span class="add-on">Alert Before :</span></td>
                <td style="text-align: left;">
                    <select name="alertdays" id="alertdaysid">
                        <option value="0" <?php if (isset($driver_alert[0]->send_before)) {if ($driver_alert[0]->send_before == '0' || $driver_alert[0]->send_before == '') {echo "selected";}}?>>Select</option>
                        <option value="1" <?php if (isset($driver_alert[0]->send_before)) {if ($driver_alert[0]->send_before == '1') {echo "selected";}}?>>1-Day</option>
                        <option value="7" <?php if (isset($driver_alert[0]->send_before)) {if ($driver_alert[0]->send_before == '7') {echo "selected";}}?>>1-Week</option>
                        <option value="30"<?php if (isset($driver_alert[0]->send_before)) {if ($driver_alert[0]->send_before == '30') {echo "selected";}}?>>1-Month</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="1" class="rightalign"><span class="add-on">Local Address</span></td>
                <td colspan="3"><textarea style="width:300px; float:left;"  name="localadd" id="localadd"                                                                                                          <?php echo $driver->local_address; ?>></textarea></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Local Telephone No.</span></td>
                <td><input type="text" name="loc_tel_no" id="loc_tel_no" value="<?php echo $driver->local_contact; ?>"></td>
                <td class="rightalign"> <span class="add-on">Local Mobile No.</span></td>
                <td><input type="text" name="loc_mob_no" id="loc_mob_no" value="<?php echo $driver->local_contact_mob; ?>"></td>
            </tr>
            <td class="rightalign"><span class="add-on">Are You Using Driver App?</span></td>
                <td style="text-align: left;">

                    <?php if (!empty($driver->userkey) || $driver->vehicleid != 0) {?>

                    <input type="radio" name="edit_rad" id="rad1" value="yes" checked > Yes
                    <input type="radio"  id="rad2" name="edit_rad" value="no"> No
                    <?php } else {
                        ?>
                    <input type="radio" name="edit_rad" id="rad1" value="yes"> Yes
                    <input type="radio"  id="rad2" name="edit_rad" value="no" checked > No
                    <?php }?>
                </td>
<!--            </tr>-->
            <tr class="edit_dislpay">
            <th colspan="100%"> DRIVER CREDENTIALS(For Driver App Only)</th>
            </tr>
            <tr class="edit_dislpay">
                <td class="rightalign"><span class="add-on">Username</span></td>
                <td><input type="text" name="username" id="username" value="<?php echo $driver->usrname; ?>"></td>
                <td class="rightalign"><span class="add-on">Password</span></td>
                <td><input type="password" name="pwd" id="pwd"></td>
            <input type="hidden" name="pwd1" id="pwd1" value="<?php echo $driver->pass ?>">
            </tr>
            <tr class="edit_dislpay">
                <td class="rightalign"><span class="add-on">Sent Email To</span></td>
                <td><input type="text" name="mail" id="mail" placeholder="someone@email.com"value="<?php echo $driver->tripmail; ?>"></td>
                <td class="rightalign"><span class="add-on">Sent SMS To</span></td>
                <td><input type="text" name="phno" id="phno" placeholder="Enter Phone No" maxlength="10" value="<?php echo $driver->tripphone; ?>"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr>
                <th colspan="100%">LOCAL EMERGENCY CONTACT DETAILS</th>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 1.</span></td>
                <td><input type="text" name="emergency_contact_name1" id="emergency_contact_name1" value="<?php echo $driver->emergency_contact1; ?>"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="emergency_contact_no1" id="emergency_contact_no1" value="<?php echo $driver->emergency_contact_no1; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 2.</span></td>
                <td><input type="text" name="emergency_contact_name2" id="emergency_contact_name2" value="<?php echo $driver->emergency_contact2; ?>"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="emergency_contact_no2" id="emergency_contact_no2" value="<?php echo $driver->emergency_contact_no2; ?>"></td>
            </tr>
             <tr>
                <td class="rightalign" colspan="1"><span class="add-on">Native Address</span></td>
                <td colspan="3"><textarea style="width:300px; float:left;"  name="native_addr" id="native_addr"><?php echo $driver->native_address; ?></textarea></td>
            </tr>
             <tr>
                <td class="rightalign"><span class="add-on">Native Telephone No.</span></td>
                <td><input type="text" name="nat_tel_no" id="nat_tel_no" value="<?php echo $driver->native_contact; ?>"></td>
                <td class="rightalign"> <span class="add-on">Native Mobile No.</span></td>
                <td><input type="text" name="nat_mob_no" id="nat_mob_no" value="<?php echo $driver->native_contact_mob; ?>"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr><th colspan="100%">NATIVE EMERGENCY CONTACT DETAILS</th></tr>
             <tr>
                <td class="rightalign"><span class="add-on">Name 1.</span></td>
                <td><input type="text" name="natemergency_contact_name1" id="natemergency_contact_name1" value="<?php echo $driver->native_emergency_contact1; ?>"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="natemergency_contact_no1" id="natemergency_contact_no1" value="<?php echo $driver->native_emergency_contact_no1; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 2.</span></td>
                <td><input type="text" name="natemergency_contact_name2" id="natemergency_contact_name2" value="<?php echo $driver->native_emergency_contact2; ?>"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="natemergency_contact_no2" id="natemergency_contact_no2" value="<?php echo $driver->native_emergency_contact_no2; ?>"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr><th colspan="100%">PREVIOUS EMPLOYEE DETAILS</th></tr>
            <tr>
                <td class="rightalign"><span class="add-on">Previous Employer</span></td>
                <td><input type="text" name="pre_employer" id="pre_employer" value="<?php echo $driver->previous_employer; ?>"></td>
                <td class="rightalign"><span class="add-on">Service Period</span></td>
                <td><input type="text" name="service_period" id="service_period" value="<?php echo $driver->service_period; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Contact Person Name</span></td>
                <td><input type="text" name="oldservice_contact_name" id="oldservice_contact_name" value="<?php echo $driver->service_contact_person; ?>"></td>
                <td class="rightalign"><span class="add-on">Mobile No.</span></td>
                <td><input type="text" name="oldservice_contact" id="oldservice_contact" value="<?php echo $driver->service_contact_no; ?>"></td>
            </tr>
            <tr>
                <td class="rightalign" colspan="1"> <span class="add-on">Add photo</span></td>
                <td colspan="3">
                    <input type="text" name="other1" id="other1"> <input type="file" name="file1" id="file1">
                    <input type="hidden" name="extension" id="extension"/>
                    <input type="hidden" name="oldimage" value="<?php echo $driver->uploadfilename; ?>">
                </td>
            </tr>
            <tr>
                <td colspan="100%">
                    <input type="button" class="btn btn-primary" value="Modify Driver" onclick="editdriver();">&nbsp;
                    <input class="btn btn-danger" type="button" name="delete" value="Delete Driver" onclick = "deletedriver(<?php echo $driver->driverid; ?>);">
                    <input type="hidden" id="action" name="action" value="edit"/>
                    <input type="hidden" name="vehicleid" id="vehicleid" value="<?php echo isset($driver->vehicleid) ? $driver->vehicleid : "0"; ?>">
                    <input type="hidden" name="userkey" id="userkey" value="<?php echo isset($driver->userkey) ? $driver->userkey : " "; ?>">
                </td>
            </tr>
        </table>
    </fieldset>
</form>
