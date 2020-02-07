<?php
/**
 * Edit Service-list form
 */

require_once "mobility_function.php";
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=edit-service');
}
$mob = new Mobility($_SESSION['customerno'], $_SESSION['userid']);
$getservicedata = $mob->getservicedata_byid($id);
$service_cat = $mob->getcatdata();
?>
<br/>
<div class='container' >
    <center>
    <form id="editsvlform" method="POST" action="mobility.php?pg=edit-service" onsubmit="editService();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Service</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Service Category <span class="mandatory">*</span></td>
                <td>
                    <select name="servicecat" id="servicecat" required style="width:150px;">
                    <?php 
                    for($i=0; $i< count($service_cat);$i++){
                    ?>
                    <option value="<?php echo $service_cat[$i]['id']; ?>" <?php if($getservicedata[0]['catid']==$service_cat[$i]['id']){echo "selected"; } ?>><?php echo $service_cat[$i]['value'];?></option> 
                    <?php   
                    }
                    ?>  
                    </select>
                </td>
            </tr>
             <tr><td class='frmlblTd'>Add New Category</td><td><input type="checkbox" name="addnewcat" id="addnewcat" value="1"></td></tr>
            <tr id="newcat"><td class='frmlblTd'>&nbsp;</td><td><input type="text" name="newcat"></td></tr>
            <tr><td class='frmlblTd'>Service Name <span class="mandatory">*</span></td><td><input type="text" name="servicename" value="<?php echo $getservicedata[0]['service_name'];?>" required></td></tr>
            <tr><td class='frmlblTd'>Cost <span class="mandatory">*</span></td><td><input type="number" name="cost" step="0.01" value="<?php echo $getservicedata[0]['cost'];?>" required></td></tr>
            <tr><td class='frmlblTd'>Expected time <span class="mandatory">*</span></td><td><input type="number" name="expTime" value="<?php echo $getservicedata[0]['expected'];?>" required><br/>(Numerical value In minutes)</td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="serviceid" id="serviceid" value="<?php echo $getservicedata[0]['serviceid'];?>">
    </form>
    </center>
</div>
