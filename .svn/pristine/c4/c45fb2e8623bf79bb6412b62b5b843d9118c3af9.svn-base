<form class="form-horizontal well " enctype="multipart/form-data"  style="width:70%;" name="createdriver" id="createdriver" method="POST">
<?php 
include 'panels/adddriver.php';
$license_text = getcustombyid(14, 'License No');
?>   
    
<fieldset>
    <div class="control-group">
      <table>
            <tr><th colspan="100%">Driver Detail</th></tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name <span class="mandatory">*</span></span></td>
                <td><input  type="text" name="drivername" id="drivername" placeholder="Driver Name" maxlength="20" autofocus></td>
                
                <td class="rightalign"><span class="add-on"><?php echo $license_text; ?> <span class="mandatory">*</span></td>
                <td><input type="text" name="drivelicno" id="drivelicno" placeholder="Driver <?php echo $license_text; ?>" maxlength="20"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Phone No <span class="mandatory">*</span></span></td>
                <td><input type="text" name="drivephoneno" id="drivephoneno" placeholder="Driver Phone No" maxlength="10"></td>
                
                <td class="rightalign"><span class="add-on">Birth Date </span></td>
                <td><input id="BDate" class="input-small" type="text" name="BDate"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Age</span></td>
                <td><input type="number" name="age" id="age"></td>
                <td class="rightalign"><span class="add-on">Blood Group</span></td>
                <td><input type="text" name="bloodgroup" id="bloodgroup"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Licence Validity</span></td>
                <td><input type="text" name="LicvalidDate" id="LicvalidDate"></td>
                <td class="rightalign"><span class="add-on">Licence Issue Authority</span></td>
                <td><input type="text" name="lic_issue_auth" id="lic_issue_auth"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Driver Alert</span></td>
                <td style="text-align: left;">
                    <input type="radio" name="driveralert" id="ds1" value="yes" class="rg"> Yes <input type="radio"  id="ds2" name="driveralert" value="no" class="rg"> No
                </td>
                <td class="alertcontent rightalign">
                   <span class="add-on"> Alert By :</span>
                </td>
                <td class="alertcontent" style="text-align: left;">
                    <input type="checkbox" name="email" id="sms" value="email"> Email <input type="checkbox" name="sms" id="sms" value="sms">SMS
                </td>
                
            </tr>
            <tr class="alertcontent"><td colspan="2">&nbsp;</td><td class="alertcontent rightalign"><span class="add-on">Alert Before :</span></td>
                <td style="text-align: left;">
                    <select name="alertdays" id="alertdaysid">
                    <option value="0">Select</option>
                    <option value="1">1-Day</option>
                    <option value="7">1-Week</option>
                    <option value="30">1-Month</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="1" class="rightalign"><span class="add-on">Local Address</span></td>
                <td colspan="3"><textarea style="width:300px; float:left;"  name="localadd" id="localadd"></textarea></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Local Telephone No.</span></td>
                <td><input type="text" name="loc_tel_no" id="loc_tel_no"></td>
                <td class="rightalign"> <span class="add-on">Local Mobile No.</span></td>
                <td><input type="text" name="loc_mob_no" id="loc_mob_no"></td>
            </tr>
<!--            <tr>
            <td class="rightalign"><span class="add-on">Are You Using Driver App?</span></td>
                <td style="text-align: left;">
                    <input type="radio" name="rad" id="rad1" value="yes"> Yes <input type="radio"  id="rad2" name="rad" value="no"> No
                </td>
            </tr>-->
<!--            <tr class="dislpay">
            <th colspan="100%"> DRIVER CREDENTIALS(For Driver App Only)</th>
            </tr>
            <tr class="dislpay">
                <td class="rightalign"><span class="add-on">Username</span></td>
                <td><input type="text" name="username" id="username"></td>
                <td class="rightalign"><span class="add-on">Password</span></td>
                <td><input type="password" name="pwd" id="pwd"></td>
            
            </tr>-->
            <tr class="dislpay">
                <td class="rightalign"><span class="add-on">Sent Email To</span></td>
                <td><input type="text" name="mail" id="mail" placeholder="someone@email.com"></td>
                <td class="rightalign"><span class="add-on">Sent SMS To</span></td>
                <td><input type="text" name="phno" id="phno" placeholder="Enter Phone No" maxlength="10"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr>
                <th colspan="100%">LOCAL EMERGENCY CONTACT DETAILS</th>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 1.</span></td>
                <td><input type="text" name="emergency_contact_name1" id="emergency_contact_name1"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="emergency_contact_no1" id="emergency_contact_no1"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 2.</span></td>
                <td><input type="text" name="emergency_contact_name2" id="emergency_contact_name2"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="emergency_contact_no2" id="emergency_contact_no2"></td>
            </tr>
             <tr>
                <td class="rightalign" colspan="1"><span class="add-on">Native Address</span></td>
                <td colspan="3"><textarea style="width:300px; float:left;"  name="native_addr" id="native_addr"></textarea></td>
            </tr>
             <tr>
                <td class="rightalign"><span class="add-on">Native Telephone No.</span></td>
                <td><input type="text" name="nat_tel_no" id="nat_tel_no"></td>
                <td class="rightalign"> <span class="add-on">Native Mobile No.</span></td>
                <td><input type="text" name="nat_mob_no" id="nat_mob_no"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr><th colspan="100%">NATIVE EMERGENCY CONTACT DETAILS</th></tr>
             <tr>
                <td class="rightalign"><span class="add-on">Name 1.</span></td>
                <td><input type="text" name="natemergency_contact_name1" id="natemergency_contact_name1"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="natemergency_contact_no1" id="natemergency_contact_no1"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Name 2.</span></td>
                <td><input type="text" name="natemergency_contact_name2" id="natemergency_contact_name2"></td>
                <td class="rightalign"><span class="add-on">Tel / Mobile No.</span></td>
                <td><input type="text" name="natemergency_contact_no2" id="natemergency_contact_no2"></td>
            </tr>
            <tr>
                <td colspan="100%">&nbsp;</td>
            </tr>
            <tr><th colspan="100%">PREVIOUS EMPLOYEE DETAILS</th></tr>
            <tr>
                <td class="rightalign"><span class="add-on">Previous Employer</span></td>
                <td><input type="text" name="pre_employer" id="pre_employer"></td>
                <td class="rightalign"><span class="add-on">Service Period</span></td>
                <td><input type="text" name="service_period" id="service_period"></td>
            </tr>
            <tr>
                <td class="rightalign"><span class="add-on">Contact Person Name</span></td>
                <td><input type="text" name="oldservice_contact_name" id="oldservice_contact_name"></td>
                <td class="rightalign"><span class="add-on">Mobile No.</span></td>
                <td><input type="text" name="oldservice_contact" id="oldservice_contact"></td>
            </tr>
            <tr>
                <td class="rightalign" colspan="1"> <span class="add-on">Add photo</span></td>
                <td colspan="3"><input type="text" name="other1" id="other1"> <input type="file" name="file1" id="file1">
                    <input type="hidden" name="extension" id="extension"/>
                </td>
            </tr>
            <tr>
                <td colspan="100%">
                    <div class="control-group pull-right">
                        <input type="button" class="btn btn-primary" value="Add New Driver" onclick="adddriver();">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" id="action" name="action" value="add"/>
<!--<fieldset>
    <div class="control-group pull-right">
        <input type="button" class="btn btn-primary" value="Add New Driver" onclick="adddriver();">
    </div>
</fieldset>-->
</fieldset>
</form>
