<?php $user = getuser();?>
<div  style="float:none; padding-left:30%;">
<table id="floatingpanel">
    <thead>
    <tr>
        <th id="formheader" colspan="2">Account</th>
    </tr>
    </thead>
    <tr>
        <td colspan="2" id="perfectinfo" style="display: none">User Information Modified</td>
        <td colspan="2" id="problem" style="display: none">Please Retry</td>        
    </tr>    
    <tr>
        <td>Name</td>
        <td><input type="text" name="name" id="name" size="30" value="<?php echo $user->realname;?>" placeholder="Your Name"></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><input type="email" name="email" id="email" size="30" value="<?php echo $user->email;?>" placeholder="Email Address"></td>
    </tr>
    <tr>
        <td>Phone</td>
        <td><input type="text" name="phoneno" id="phoneno" size="30" value="<?php echo $user->phone;?>" placeholder="Phone Number"></td>
    </tr>
    <tr>
        <td>Role</td>
        <td><input type="text" name="role" id="role" size="30" value="<?php echo $user->role;?>" disabled></td>
    </tr>
    <tfoot>
    <tr>
        <td colspan="2" align="center"><input type="button" name="userdetails" class="btn  btn-primary" value="Modify" onclick="dosaveuserdet();"></td>
    </tr>
    </tfoot>
</table>
<table id="floatingpanel">
    <thead>
    <tr>
        <th id="formheader" colspan="2">Change Password</th>
    </tr>
    </thead>
    <tr>
        <td colspan="2" id="incorrect" style="display: none">Old Password Is Incorrect</td>
        <td colspan="2" id="perfect" style="display: none">Password Changed</td>        
        <td colspan="2" id="newempty" style="display: none">Please Enter New Password</td>                
        <td colspan="2" id="oldempty" style="display: none">Please Enter Old Password</td>                        
    </tr>
    <tr>
        <td colspan="2">
            <input type="password" name="oldpasswd" id="oldpasswd" maxlength="20" size="30" placeholder="Current Password">
        </td>
    </tr>
    <tr>
        <td colspan="2"><input type="password" name="newpasswd" id="newpasswd" maxlength="20" size="30" placeholder="New Password"></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="button" name="password" class="btn  btn-primary" value="Modify" onclick="dosave();"></td>
    </tr>
</table>
</div>