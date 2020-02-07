<?php
/**
 * Client Master form
 */
require_once "mobility_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$mob = new Mobility($customerno,$userid);
$getcity = $mob->getcity();
$groups = $mob->getgroupsbyuserid_client($userid);
$getpackage = $mob->getpackage();
?>
<br/>
<div class='container' >
    <center>
    <form id="addclient" method="POST" action="mobility.php?pg=client-master" onsubmit="addClient();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Client Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Client No <span class="mandatory">*</span></td><td><input type="text" name="clientno" required></td></tr>
            <tr><td class='frmlblTd'>Mobile <span class="mandatory">*</span></td><td><input type="text" name="cmobile" maxlength="12" ></td></tr>
            <tr><td class='frmlblTd'>Name</td><td><input type="text" name="cname" required></td></tr>
            <tr><td class='frmlblTd'>Email</td><td><input type="email" name="cemail" ></td></tr>
            <tr><td class='frmlblTd'>Password <span class="mandatory">*</span></td><td><input type="password" name="cpassword" id="cpassword" maxlength="10"></td></tr>
            <tr><td class='frmlblTd'>DOB</td><td><input type="text" name="cdob" ></td></tr>
            <tr><td class='frmlblTd'>Anniversary</td><td><input type="text" name="cannivrsry" ></td></tr>
            <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="cphoneno" maxlength="15"></td></tr>
            <tr><th colspan="100%">Address</th></tr>
            <tr>
            <td colspan="100%">
                <table class='table table-condensed' style="width:100%;">
                    <tr>
                        <td>Flat No.</td><td><input type="text" name="cflatno" ></td>
                        <td>Building</td><td><input type="text" name="cbuilding" ></td>
                    </tr>
                    <tr>
                        <td>Society</td><td><input type="text" name="csociety" ></td>
                        <td>Landmark</td><td><input type="text" name="clandmark"></td>
                    </tr>
                    <tr>
                        <td>City</td><td> <select name="ccity" id="ccity" onchange="pullcity();">
                    <option value="0">Select City</option>
                    <?php
                    if(isset($getcity))
                    {
                        foreach($getcity as $thisvalue)
                        {
                            if($thisvalue['id']!=0)
                            {
                        ?>
                        <option value="<?php echo ($thisvalue['id']); ?>"><?php echo($thisvalue['cityname']); ?></option>
                        <?php
                        }    }            
                    }
                ?>
                    </select></td>
                    <td>Location</td>
                    <td>
                        <select name="clocation" id="clocation">
                            <option value="0">Select Location</option>
                        </select>
                    </td>
                    </tr>
                </table>    
            </td>
            </tr>
            <tr><td colspan="100%" style="text-align: right;">
                    <input type="checkbox" name="alternateaddress2" id="alternateaddress2" value="1">&nbsp;Alternate Address</td></tr>
            <tr id="alternateaddress">
            <td colspan="100%">
                <table class='table table-condensed' style="width:100%;">
                    <tr>
                        <td>Flat No.</td><td><input type="text" name="cflatno1" ></td>
                        <td>Building</td><td><input type="text" name="cbuilding1" ></td>
                    </tr>
                    <tr>
                        <td>Society</td><td><input type="text" name="csociety1" ></td>
                        <td>Landmark</td><td><input type="text" name="clandmark1"></td>
                    </tr>
                    <tr>
                        <td>City</td><td> <select name="ccity1" id="ccity1" onchange="pullcity1();">
                    <option value="0">Select City</option>
                    <?php
                    if(isset($getcity))
                    {
                        foreach($getcity as $thisvalue)
                        {
                            if($thisvalue['id']!=0)
                            {
                        ?>
                        <option value="<?php echo ($thisvalue['id']); ?>"><?php echo($thisvalue['cityname']); ?></option>
                        <?php
                        }    }            
                    }
                ?>
                    </select></td>
                    <td>Location</td>
                    <td>
                        <select name="clocation1" id="clocation1">
                            <option value="0">Select Location</option>
                        </select>
                    </td>
                    </tr>
                </table>    
            </td>
            </tr>
            <tr>
                <td class='frmlblTd'>Notes</td>
                <td>
                    <textarea name="notes" id="notes" style="width: 80%;"></textarea>
                </td>
            </tr>
            <tr>
                    <td class='frmlblTd'>Group</td>
                    <td>
                    <select name="groupid" id="groupid">
                          <?php
                          if(isset($groups)){
                            foreach ($groups as $group){
                                  echo "<option value='$group->groupid'>$group->groupname</option>";
                            }
                        }else{
                            echo "<option value='0'>Select Group</option>";
                        }
                        ?>
                    </select>
                </td>
                </tr>
            <tr><td class='frmlblTd'>Minimum Billing</td><td><input type="number" name="cminimumbill" step="0.01" ></td></tr>
            
            <tr><td class='frmlblTd'>Package</td><td><input type="checkbox" id="membership" name="membership" value="1"></td></tr>
            <tr id="membershipcode">
                <td class='frmlblTd'>Package Code</td>
                <td>
                    <select name="membershipcd" id="membershipcd" onchange="pullpackage();">
                        <option value="0">Select</option>
                        <?php
                        if(isset($getpackage))
                        {
                            foreach($getpackage as $thisvalue)
                            {
                                if($thisvalue['pckgid']!=0)
                                {
                                ?>
                                <option value="<?php echo ($thisvalue['pckgid']); ?>"><?php echo($thisvalue['package_code']); ?></option>
                                <?php
                                }
                            }            
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr id="membershipamount"><td class='frmlblTd' >Amount</td><td><input type="text" readonly name="amount" id="amount"></td></tr>
            <tr id="membershipvalidity"><td class='frmlblTd' >Validity</td><td><input type="text" readonly name="mvalidity" id="mvalidity"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
    </form>
    </center>
</div>
