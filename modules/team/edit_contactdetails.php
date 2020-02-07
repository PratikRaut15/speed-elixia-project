<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include_once ("../../lib/system/utilities.php");
include_once ("../../lib/system/Sanitise.php");



class EditContact {
    
}

$db = new DatabaseManager();

$SQL = "SELECT * FROM ".DB_PARENT.".contactperson_type_master";
$db->executeQuery($SQL);
$cpdatas = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $cpdata = new EditContact();
        $cpdata->typeid = $row['person_typeid'];
        $cpdata->persontype = $row['person_type'];
        $cpdatas[] = $cpdata;
    }
}
$cpid = $_GET['cpid'];
$SQL1 = sprintf("SELECT * FROM ".DB_PARENT.".contactperson_details WHERE cpdetailid = %d", Sanitise::Long($cpid));
$db->executeQuery($SQL1);
if ($db->get_rowCount() > 0) {
    $row = $db->get_nextRow();
    $cpdid = $row['cpdetailid'];
    $type = $row['typeid'];
    $person_name = $row['person_name'];
    $email1 = $row['cp_email1'];
    $email2 = $row['cp_email2'];
    $phone1 = $row['cp_phone1'];
    $phone2 = $row['cp_phone2'];
    $customerno = $row['customerno'];
}

if (isset($_POST['edit_details'])) {
    $today = date('Y-m-d H:i:s');
    $custno = GetSafeValueString($_POST['cid'], "string");
    $type = GetSafeValueString($_POST['type'], "string");
    $person = GetSafeValueString($_POST['person'], "string");
    $email1 = GetSafeValueString($_POST['email1'], "string");
    $email2 = GetSafeValueString($_POST['email2'], "string");
    $phone1 = GetSafeValueString($_POST['phone1'], "string");
    $phone2 = GetSafeValueString($_POST['phone2'], "string");
    $cpid = GetSafeValueString($_POST['cpid'], "string");

    $SQL2 = sprintf("UPDATE ".DB_PARENT.".contactperson_details SET typeid = %d ,person_name = '%s',cp_email1 = '%s',cp_email2 = '%s',cp_phone1 = '%s',cp_phone2 = '%s',updatedby = %d,updatedon = '%s' WHERE customerno = %d AND cpdetailid = %d", 
            Sanitise::Long($type), Sanitise::String($person), Sanitise::String($email1), Sanitise::String($email2), Sanitise::String($phone1), Sanitise::String($phone2), GetLoggedInUserId(), Sanitise::DateTime($today), Sanitise::Long($custno), Sanitise::Long($cpid));
    $db->executeQuery($SQL2);
    header('Location:contactdetails.php?cno='.$custno);
}

include("header.php");
?>
<div class="panel">
    <div class="paneltitle" align="center">Edit Details <?php echo $customerno ?></div> 
    <div class="panelcontents">
        <span id="err_type" style="display: none;color: #FF0000"> Please Select Type Of Person</span>
        <span id="err_personname" style=" display: none;color: #FF0000">Enter Person Name </span>
        <span id="err_email" style="display: none;color: #FF0000">Enter Primary Email</span>
        <span id="err_phone" style="display: none;color: #FF0000">Enter Primary Phone No.</span>
        <form method="post" name="detailsform" id="detailsform" action="edit_contactdetails.php" onsubmit="return validate();">
            <table>
                <tr>
                    <td><input type="hidden" name="cid" id="cid" value="<?php echo $customerno; ?>"></td>
                    <td><input type="hidden" name="cpid" id="cpid" value="<?php echo $cpdid ; ?>"></td>
                </tr>
                <tr><td>Type</td>
                    <td> 
                        <select id="type" name="type" autocomplete="off">
                            <option value='0'>Select Person Type</option>
<?php foreach ($cpdatas as $datas) {
    ?>   
                                <option value='<?php echo $datas->typeid; ?>'<?php if ($type == $datas->typeid) { ?> selected <?php } ?>> <?php echo $datas->persontype; ?>
                                </option>
<?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Person Name</td>
                    <td><input type="text" name="person" id="person" value="<?php echo $person_name; ?>"></td>
                </tr>
                <tr>
                    <td>Primary Email</td>
                    <td><input type="text" name="email1" id="email1" value="<?php echo $email1; ?>"></td>
                </tr>
                <tr>
                    <td>Secondary Email</td>
                    <td><input type="text" name="email2" id="email2" value="<?php echo $email2; ?>"></td>
                </tr>
                <tr>
                    <td>Primary Phone No.</td>
                    <td><input type="text" name="phone1" id="phone1" value="<?php echo $phone1; ?>"></td>
                </tr>
                <tr>
                    <td>Secondary Phone No.</td>
                    <td><input type="text" name="phone2" id="phone2" value="<?php echo $phone2; ?>"></td>
                </tr>
                <tr>
                    <td><input type="submit" class="btn btn-primary"name="edit_details" id="edit_details" value="Edit Details"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<script>
    function validate() {

        var type = jQuery('#type').val();
        var person = jQuery('#person').val();
        var email = jQuery('#email1').val();
        var phone = jQuery('#phone1').val();
        if (type == 0) {
            jQuery('#err_type').show(3000);
            jQuery('#err_type').hide(3000);
            return false;
        } else if (person == '') {
            jQuery('#err_personname').show(3000);
            jQuery('#err_personname').hide(3000);
            return false;
        } else if (email == '') {
            jQuery('#err_email').show(3000);
            jQuery('#err_email').hide(3000);
            return false;
        } else if (phone == '') {
            jQuery('#err_phone').show(3000);
            jQuery('#err_phone').show(3000);
            return false;
        } else {
            jQuery('#edit_details').submit();
        }
    }
</script>