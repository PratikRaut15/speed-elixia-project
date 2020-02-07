<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/components/gui/objectdatagrid.php");
include_once("../../lib/system/DatabaseManager.php");
include_once("../../lib/bo/TeamManager.php");

include_once("header.php");

  $prospectId = isset($_GET['prosId'])?$_GET['prosId']:0;
?>
<style>
</style>
<link rel="stylesheet" href="../../css/invoicePayment.css">
<div class="panel">
    <div class="paneltitle" align="center">Update Prospective Customer</div>
        <div class="panelcontents">
            <form method="post" name="edit_prospCust" id="edit_prospCust" enctype="multipart/form-data">
                <input type="hidden" name="prospectId" id="prospectId" value="<?php echo $prospectId ?>" >  
                <table width="100%" align="center">
                    <tr>
                        <td>Real Name</td><td><input name = "realname" id="realname" type="text" placeholder="Enter Name"></td>
                        <td>Company <span style="color:red;">*</span></td><td><input name="company_name" id="company_name" required type="text" placeholder="Enter Company Name"></td>
                        <td>Address</td><td><textarea maxlength="255" placeholder="Enter Address" name="company_add" id="company_add"></textarea></td>
                        
                    </tr>
                    <tr>
                        <td>Contact Number</td><td><input name = "phone" id="phone" type="text" placeholder="Enter Number"></td>
                        <td>Email Address <span style="color:red;">*</span></td><td><input id="emailaddress" name="emailaddress" type="text" placeholder="Enter Email" required/></td> 
                        <td>Remarks</td><td><textarea maxlength="255" name="remarks" id="remarks" placeholder="Enter Remarks"></textarea></td>
                    </tr>
                </table>
                <input type="button" value="Update" onclick="updateProspect();">
            </form>
        </div>
</div>
<script>
    var prospectId;
    jQuery(document).ready(function () { 
       prospectId= <?php echo $prospectId?>;
       
       fetch_prospCustomers();
    });  
</script>
<?php
include_once("footer.php");
?>
<script src='../../scripts/team/editProspCust.js'></script>