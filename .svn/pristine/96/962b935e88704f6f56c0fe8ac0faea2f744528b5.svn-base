<?php
error_reporting(0);
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");

include("header.php");

class Contact {
    
}
$db = new DatabaseManager();
$customerno = $_GET['cno'];
$SQL = "SELECT * FROM ".DB_PARENT.".contactperson_type_master";
$db->executeQuery($SQL);
$cpdatas = Array();
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $cpdata = new Contact();
        $cpdata->typeid = $row['person_typeid'];
        $cpdata->persontype = $row['person_type'];
        $cpdatas[] = $cpdata;
    }
}

?>
<!---------- Form ------------------------->
<div class="panel">
    <div class="paneltitle" align="center">Add Additional Details Of Customer No. <?php echo $customerno ?></div> 
    <div class="panelcontents">
        <span id="err_type" style="display: none;color: #FF0000"> Please Select Type Of Person</span>
        <span id="err_personname" style=" display: none;color: #FF0000">Enter Person Name </span>
        <span id="err_email" style="display: none;color: #FF0000">Enter Primary Email</span>
        <span id="err_phone" style="display: none;color: #FF0000">Enter Primary Phone No.</span>
        <form method="post" name="detailsform" id="detailsform" action="contactdetails_ajax.php" onsubmit="return validate();">
            <table>
                <tr>
                    <td><input type="hidden" name="cid" id="cid" value="<?php echo $customerno; ?>"></td>
                </tr>
                <tr><td>Type</td>
                    <td> 
                        <select id="type" name="type">
                            <option value='0'>Select Person Type</option>
                            <?php foreach($cpdatas as $datas){
                            ?>   
                            <option value='<?php echo $datas->typeid;?>'>
                            <?php echo $datas->persontype;?>
                            </option>
                            <?php }?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Person Name</td>
                    <td><input type="text" name="person" id="person"></td>
                </tr>
                <tr>
                    <td>Primary Email</td>
                    <td><input type="text" name="email1" id="email1"></td>
                </tr>
                <tr>
                    <td>Secondary Email</td>
                    <td><input type="text" name="email2" id="email2"></td>
                </tr>
                 <tr>
                    <td>Primary Phone No.</td>
                    <td><input type="text" name="phone1" id="phone1"></td>
                </tr>
                <tr>
                    <td>Secondary Phone No.</td>
                    <td><input type="text" name="phone2" id="phone2"></td>
                </tr>
                <tr>
                    <td><input type="submit" class="btn btn-primary"name="add_details" id="add_details" value="Add Details"></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br>
<div class="panel">
<div class="paneltitle" align="center">Address List</div>
<div class="panelcontents">
    <table border="1" width="100%">
    <tr>
        <th>Sr No.</th>
        <th>Person Name</th>
        <th>Person Type</th>
        <th>Primary Email</th>
        <th>Secondary Email</th>
        <th>Primary Phone No.</th>
        <th>Secondary Phone No.</th>
        <th>Edit</th>
        <th>Delete</th>
        
    </tr>
    <tbody id="demo">
        
    </tbody>
</table>
</div>
</div>
<script>
jQuery(document).ready(function(){
    var custno = jQuery('#cid').val();
    jQuery.ajax({
        type: "POST",
	url: "contactdetails_ajax.php",
	cache: false,
	data:{cno:custno},
        success:function(res){
            var data = jQuery.parseJSON(res);
            //console.log(data);
            details(data);
        }
        });
})

function details(data)
{
    var tags = '';
    var imgsrc ="../../images/edit.png";
    jQuery(data).each(function(i,v){
        tags +="<tr><td>"+v.x+"</td>";
        tags +="<td>"+v.person_name+"</td>";
        tags +="<td>"+v.type+"</td>";
        tags +="<td>"+v.email1+"</td>";
        tags +="<td>"+v.email2+"</td>";
        tags +="<td>"+v.phone1+"</td>";
        tags +="<td>"+v.phone2+"</td>";
        tags +="<td><a href='edit_contactdetails.php?cpid="+v.cpid+"'><i class='icon-pencil'></i></a></td>";
        tags +="<td colspan = '2'><a href='contactdetails_ajax.php?delcpid="+v.cpid+"&cust="+v.cust+"'><i class='icon-trash'></i></a></td></tr>"
    });
    
    if (data.length === 0) {
                        var emp = '';
                        emp += "<tr>"
                        emp += "<td colspan=100% style='text-align:center'>No Data Found</td>"
                        emp += "</tr>"
                        jQuery('#demo').html(emp);

                    }

                    else {
                        jQuery('#demo').html(tags);
                    }
}
    
function validate(){

    var type = jQuery('#type').val();
    var person = jQuery('#person').val();
    var email = jQuery('#email1').val();
    var phone = jQuery('#phone1').val();
    if(type == 0){
        jQuery('#err_type').show(3000);
        jQuery('#err_type').hide(3000);
        return false;
    }else if(person == ''){
        jQuery('#err_personname').show(3000);
        jQuery('#err_personname').hide(3000);
        return false;
    }else if(email == ''){
        jQuery('#err_email').show(3000);
        jQuery('#err_email').hide(3000);
        return false;
    }else if(phone == ''){
        jQuery('#err_phone').show(3000);
        jQuery('#err_phone').show(3000);
        return false;
    }else{
        jQuery('#detailsform').submit();
    }
}
</script>