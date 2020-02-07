<?php
/**
 * Edit Client Master form
 * 30-11--0001
 */
require_once "mobility_function.php";
$customerno = $_SESSION['customerno'];
$userid = $_SESSION['userid'];
$mob = new Mobility($customerno,$userid);
$getcity = $mob->getcity();

$groups = $mob->getgroupsbyuserid_client($userid);
$getpackage = $mob->getpackage();

$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=client-master');
}
$mob = new Mobility($customerno,$userid);
$getclientdata = $mob->getclientdata_byid($id);
$getclient_address = $mob->getclientaddress_byid($id);  //get client address 
$dob = $getclientdata[0]['dob'];
$anniversary = $getclientdata[0]['anniversary'];
$newaaDate = date("d-m-Y", strtotime($anniversary));
$newdobDate = date("d-m-Y", strtotime($dob));
//$newMemberDate = date("d-m-Y", strtotime($member_validity));
if($newdobDate=="00-00-0000" || $newdobDate=='30-11--0001'){
    $newdobDate1 = date('d-m-Y');
}else{
    $newdobDate1 = date("d-m-Y", strtotime($dob));    
}
if($newaaDate=="00-00-0000" || $newaaDate =="30-11--0001"){
    $newaaDate1 = date("d-m-Y");
}else{
    $newaaDate1 = date("d-m-Y", strtotime($anniversary));    
}

//if($newMemberDate=="00-00-0000" || $newMemberDate=="30-11--0001"){
//    $newMemberDate1 = date('d-m-Y');
//}else{
//    $newMemberDate1 = date("d-m-Y", strtotime($member_validity));    
//}
?>
<br/>
<div class='container' >
    <center>
    <form id="editclient" method="POST" action="mobility.php?pg=edit-client" onsubmit="editClient();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Client Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Client No <span class="mandatory">*</span></td><td><input type="text" name="clientno" value="<?php echo $getclientdata[0]['clientno'];?>" required></td></tr>
            <tr><td class='frmlblTd'>Mobile <span class="mandatory">*</span></td><td><input type="text" name="cmobile" maxlength="12"  value="<?php echo $getclientdata[0]['mobile'];?>" ></td></tr>
            <tr><td class='frmlblTd'>Name </td><td><input type="text" name="cname" value="<?php echo $getclientdata[0]['client_name'];?>" required></td></tr>
            <tr><td class='frmlblTd'>Email</td><td><input type="email" name="cemail" value="<?php echo $getclientdata[0]['email'];?>" ></td></tr>
            <tr><td class='frmlblTd'>Password <span class="mandatory">*</span></td><td><input type="password" name="cpassword" id="cpassword" maxlength="10">&nbsp;<br><input type="checkbox" name="changepass" id="changepass" value="1"/> Click for Change Password</td></tr>
            <tr><td class='frmlblTd'>DOB</td><td><input type="text" name="cdob" value="<?php echo $newdobDate1;?>"></td></tr>
            <tr><td class='frmlblTd'>Anniversary</td><td><input type="text" name="cannivrsry" value="<?php echo $newaaDate1;?>"></td></tr>
            <tr><td class='frmlblTd'>Phone No</td><td><input type="text" name="cphoneno" maxlength="15"  value="<?php echo $getclientdata[0]['phone'];?>"></td></tr>
                <tr><th colspan="100%">Address</th></tr>
            <tr>
            <td colspan="100%">
                <table class='table table-condensed' style="width:100%;">
                    <tr>
                        <td>Flat No.</td><td><input type="text" name="cflatno" value="<?php if(isset($getclient_address[0]['flatno'])){echo $getclient_address[0]['flatno'];} ?>"></td>
                        <td>Building</td><td><input type="text" name="cbuilding" value="<?php if(isset($getclient_address[0]['building'])){ echo $getclient_address[0]['building'];}?>" ></td>
                    </tr>
                    <tr>
                        <td>Society</td><td><input type="text" name="csociety" value="<?php if(isset($getclient_address[0]['society'])){ echo $getclient_address[0]['society'];}?>"></td>
                        <td>Landmark</td><td><input type="text" name="clandmark" value="<?php if(isset($getclient_address[0]['landmark'])){ echo $getclient_address[0]['landmark'];} ?>"></td>
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
                                if(isset($getclient_address[0]['cityid']))
                                {
                                ?>
                                <option value="<?php echo ($thisvalue['id'])?>"<?php if($getclient_address[0]['cityid']== $thisvalue['id']){ echo "selected";}?>><?php echo($thisvalue['cityname']); ?></option>
                            <?php
                                }else{
                        ?>
                                <option value="<?php echo ($thisvalue['id'])?>"><?php echo($thisvalue['cityname']); ?></option>
                        <?php
                                }
                            }    
                        }            
                    }
                    ?>
                    </select></td>
                    <td>Location</td>
                    <td>
                        <?php $getlocation = $mob->get_locations_bycityid($getclient_address[0]['cityid']);?>
                        <select name="clocation" id="clocation">
                        <?php
                        if(isset($getlocation))
                        {
                            foreach($getlocation as $thisvalue)
                            {
                                if($thisvalue['locid']!=0)
                                {
                                    if(isset($getclient_address[0]['cityid']))
                                    {
                            ?>
                                <option value="<?php echo ($thisvalue['locid'])?>"<?php if($getclient_address[0]['locationid']== $thisvalue['locid']){ echo "selected";}?>><?php echo($thisvalue['location']); ?></option>
                            <?php
                                    }else{
                             ?>
                                    <option value="<?php echo ($thisvalue['locid'])?>"><?php echo($thisvalue['location']); ?></option>        
                            <?php        
                                    }
                                
                                }
                            }    
                        }            
                        ?>
                        </select>
                    </td>
                    </tr>
                    <input type="hidden" name="addressid" id="addressid" value="<?php if(isset($getclient_address[0]['client_add_id'])){echo $getclient_address[0]['client_add_id'];}?>">
                </table>    
            </td>
            </tr>
            <tr><td colspan="100%" style="text-align: right;">
                    <input type="checkbox" name="alternateaddress3" id="alternateaddress3" value="1" <?php if(isset($getclient_address[1])){?>checked="checked" <?php } ?>>&nbsp;Alternate Address</td></tr>
            <?php
            if(isset($getclient_address[1])){
                $style='display:table-row';
            }else{
                $style='display:none';
            }
            ?>
            <tr id="alternateaddress1" style="<?php echo $style;?>">
            <input type="hidden" name="alternateaddress" value="1">
            <td colspan="100%">
                <table class='table table-condensed' style="width:100%;">
                    <tr>
                        <td>Flat No.</td><td><input type="text" name="cflatno1" value="<?php if(isset($getclient_address[1]['flatno'])){ echo $getclient_address[1]['flatno'];} ?>"></td>
                        <td>Building</td><td><input type="text" name="cbuilding1" value="<?php if(isset($getclient_address[1]['flatno'])){echo $getclient_address[1]['flatno'];} ?>" ></td>
                    </tr>
                    <tr>
                        <td>Society</td><td><input type="text" name="csociety1" value="<?php if(isset($getclient_address[1]['society'])){ echo $getclient_address[1]['society'];} ?>"></td>
                        <td>Landmark</td><td><input type="text" name="clandmark1" value="<?php if(isset($getclient_address[1]['landmark'])){ echo $getclient_address[1]['landmark']; } ?>"></td>
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
                                if(isset($getclient_address[1]['cityid']))
                                {
                                ?>
                                <option value="<?php echo ($thisvalue['id'])?>"<?php if($getclient_address[1]['cityid']== $thisvalue['id']){ echo "selected";}?>><?php echo($thisvalue['cityname']); ?></option>
                            <?php
                                }else{
                        ?>
                                <option value="<?php echo ($thisvalue['id'])?>"><?php echo($thisvalue['cityname']); ?></option>
                        <?php
                                }
                            }    
                        }            
                    }
                    ?>
                    </select></td>
                    <td>Location</td>
                    <td>
                        <?php 
                        if(isset($getclient_address[1]['cityid'])){
                        $getlocation = $mob->get_locations_bycityid($getclient_address[1]['cityid']);?>
                        <select name="clocation1" id="clocation1">
                        <?php
                        if(isset($getlocation))
                        {
                            foreach($getlocation as $thisvalue)
                            {
                                if($thisvalue['locid']!=0)
                                {
                                    if(isset($getclient_address[1]['cityid']))
                                    {
                            ?>
                                <option value="<?php echo ($thisvalue['locid'])?>"<?php if($getclient_address[1]['locationid']== $thisvalue['locid']){ echo "selected";}?>><?php echo($thisvalue['location']); ?></option>
                            <?php
                                    }else{
                             ?>
                                    <option value="<?php echo ($thisvalue['locid'])?>"><?php echo($thisvalue['location']); ?></option>        
                            <?php        
                                    }
                                
                                }
                            }    
                        }            
                        ?>
                        </select>
                        <?php }else{
                        ?>
                         <select name="clocation1" id="clocation1"></select>
                        <?php
                        } ?>
                    </td>
                    </tr>
                    <input type="hidden" name="addressid1" id="addressid1" value="<?php if(isset($getclient_address[1]['client_add_id'])){echo $getclient_address[1]['client_add_id'];}?>">
                </table>    
            </td>
            </tr>
            <tr>
                <td class='frmlblTd'>Notes</td>
                <td>
                    <textarea name="notes" id="notes" style="width: 80%;"><?php echo $getclientdata[0]['notes'];?></textarea>
                </td>
            </tr>
                <tr><td class='frmlblTd'>Group</td><td>
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
                </td></tr>
            <tr><td class='frmlblTd'>Minimum Billing</td><td><input type="number" name="cminimumbill" step="0.01" value="<?php echo $getclientdata[0]['min_billing'];?>" ></td></tr>
            <tr><td class='frmlblTd'>Package</td><td><input type="checkbox" id="membership1" name="membership" value="1" <?php if($getclientdata[0]['is_member']=='1'){ echo "checked";}?>></td></tr>
            <tr id="membershipcode1"  <?php if($getclientdata[0]['is_member']=='1'){?> style="display: table-row;"<?php }else{?> style="display:none;"<?php } ?>>
                <td class='frmlblTd'>Package Code</td>
                <td>
                    <select name="membershipcd" id="membershipcd" onchange="pullpackage1();">
                        <option value="0">Select</option>
                        <?php
                        if(isset($getpackage))
                        {
                            foreach($getpackage as $thisvalue)
                            {
                                if($thisvalue['pckgid']!=0)
                                {
                                ?>
                                <option value="<?php echo ($thisvalue['pckgid']); ?>" <?php if($getclientdata[0]['pckgid']==$thisvalue['pckgid']){ echo "selected"; } ?>><?php echo($thisvalue['package_code']); ?></option>
                                <?php
                                }
                            }            
                        }
                        ?>
                    </select>
                </td>
            </tr>
            
<?php 
$valdate = date('d-m-Y', strtotime($getclientdata[0]['validity']));
?>

            <tr id="membershipamount1" <?php if($getclientdata[0]['is_member']=='1'){?> style="display: table-row;"<?php }else{?> style="display:none;"<?php } ?>><td class='frmlblTd' >Amount</td><td><input type="text" readonly name="amount" id="amount" value="<?php echo $getclientdata[0]['amount'];?>"></td></tr>
            <tr id="membershipvalidity1" <?php if($getclientdata[0]['is_member']=='1'){?> style="display: table-row;"<?php }else{?> style="display:none;"<?php } ?>><td class='frmlblTd' >Validity</td><td><input type="text" readonly name="mvalidity" id="mvalidity" value="<?php echo $valdate; ?>"></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="clientid" id="clientid" value="<?php echo $getclientdata[0]['clientid'];?>">
    </form>
    </center>
</div>
