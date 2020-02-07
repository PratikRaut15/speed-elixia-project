<?php
//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

include_once("session.php");
include("loginorelse.php");
include_once("../../constants/constants.php");
include_once("db.php");
include_once("../../lib/system/DatabaseManager.php");
include("header.php");

class InvMap{
    
}
$db = new DatabaseManager();
//print_r($_POST);
$customerno = $_GET['cno'];
if(isset($_POST['mapveh']))
{
    $vid=Array();
    $incid = GetSafeValueString($_POST['ciname'],"string");
    
    $vehicles =Array();
    foreach($_POST as $single_post_name=>$single_post_value)
    {
        if (substr($single_post_name, 0, 11) == "to_vehicle_")
            $vehicles[]= substr($single_post_name, 11,12);
    }
    //print_r($vehicles);
    //die();
    //to clear previous list
    $SQL ="UPDATE vehicle SET invcustid =0 WHERE invcustid =$incid";
    $db->executeQuery($SQL);
    
    //to update new list or enter new list
    foreach($vehicles as $vdata)
    {
        $SQL1 = sprintf("UPDATE vehicle SET invcustid =$incid WHERE vehicleid=%d",$vdata);
        $db->executeQuery($SQL1);
    }  
}
//-----------populate customerno list-------
function getcustomer_detail() {
        $db = new DatabaseManager();
        $customernos = Array();
        $SQL = sprintf("SELECT customerno,customername,customercompany FROM customer");
        $db->executeQuery($SQL);
        if ($db->get_rowCount() > 0) {
            while ($row = $db->get_nextRow()) {
                $customer = new InvMap();
                $customer->customerno = $row['customerno'];
                $customer->customername = $row['customername'];
                $customer->customercompany = $row['customercompany'];
                $customernos[] = $customer;
            }
            return $customernos;
            //print_r($customernos);
            }
        return false;
    }
?>
<style>
    .recipientbox {
    border: 1px solid #999999;
    float: left;
    font-weight: 700;
    padding: 4px 27px;
/*    width: 100px;*/
	
	
float:left;
-webkit-transition:all 0.218s;
-webkit-user-select:none;
background-color:#000;
/*background-image:-webkit-linear-gradient(top, #4D90FE, #4787ED);*/
border:1px solid #3079ED;
color:#FFFFFF;
text-shadow:rgba(0, 0, 0, 0.0980392) 0 1px;
border:1px solid #DCDCDC;
border-bottom-left-radius:2px;
border-bottom-right-radius:2px;
border-top-left-radius:2px;
border-top-right-radius:2px;

cursor:default;
display:inline-block;
font-size:11px;
font-weight:bold;
height:27px;
line-height:27px;
min-width:46px;
padding:0 8px;
text-align:center;

border: 1px solid rgba(0, 0, 0, 0.1);
color:#fff !important;
font-size: 11px;
font: bold 11px/27px Arial,sans-serif !important;
vertical-align: top;
margin-left:5px;
margin-top:5px;
text-align:left;
}
.recipientbox img {
float:right;
padding-top:5px;
}

</style>
<div class="panel">
        <div class="paneltitle" align="center">Select Customer</div> 
        <div class="panelcontents">
            <form method="post" name="invoiceform" id="invoiceform">
            <table>
                <tr><td>Client</td>
        <td>    
            <select name="cno" id="cno" style="width:200px;" onchange="getInvAddress()">
                 <option value="0">Select Client</option>
                <?php
                       
                        $cms = getcustomer_detail();
                       foreach($cms as $customer)
                       {
                ?> 
                <option value="<?php echo($customer->customerno);?> "
                        <?php if(isset($customerno) && $customer->customerno == $customerno){
                            echo "selected";
                        } 
                        ?>><?php echo $customer->customerno;?> - <?php echo $customer->customercompany?></option>
                <?php
                        }
                ?> 
        
        </select>
            
                </tr>
            </table>
        </form>
        </div>
</div>
<br>
<div class="panel">
        <div class="paneltitle" align="center">Map Invoice Address Vehicle</div> 
        <div class="panelcontents">
            <form method="post" name="vmap" id="vmap" action="invoice_mapvehicle.php?cno=<?php echo $customerno;?>">
            <table>
                <tr>
                    <td>Customer Invoice Name</td>
                    <td>
                    <select name="ciname" id="ciname" style="width:200px;" onchange="getMappedveh()">
                        <!-----fetched data by function Invcust_details(data)-->
                    </select>
                    </td>
                    
                    <td>Unmapped Vehicles</td>
                    <td>
                        <select name="vno" id="vno" style="width:200px;" onchange="AssignVehicle()">
                    
                    </select>

                    </td>
                </tr>
                <tr>
                    <td colspan="100%"><div id="vehicle_list">
                        
                        </div></td>
                </tr>
                
            </table>
                <br>
                <input type ="submit" name="mapveh" id="mapveh" class="btn-default" value="Map Vehicle">
            </form>
        </div>
</div>
<script>
jQuery(document).ready(function(){
    var cno = <?php echo $customerno; ?>;
    var custno = jQuery('#cno').val();
    if(cno == custno){
        getInvAddress();
    }
});
function getInvAddress(){
    
  var custno = jQuery('#cno').val();
    jQuery.ajax({
        type: "POST",
	url: "invoice_ajax.php",
	cache: false,
	data:{cno:custno},
        success:function(res){
            var data = jQuery.parseJSON(res);
            //console.log(data);
            Invcust_details(data);
        }
        });  
        //to fetch vehicles
        jQuery.ajax({
        type: "POST",
	url: "invoice_ajax.php",
	cache: false,
	data:{customerno:custno},
        success:function(result){
            var DATA = jQuery.parseJSON(result);
            //console.log(data);
            vehicle_details(DATA);
        }
        });  
    }
    
function Invcust_details(data)
{
    var detail ='';
    detail +="<option value="+0+">SelectOption</option>"
    jQuery(data).each(function(i,v){
    detail +="<option value="+v.invid+">"+v.invcust+"</option>";
       
    });
    jQuery("#ciname").html(detail);
}

function vehicle_details(DATA)
{
    var vdetail ='';
    vdetail +="<option value="+0+">SelectOption</option>"
    jQuery(DATA).each(function(i,v){
    vdetail +="<option value="+v.vehicleid+">"+v.vehicleno+"</option>";
       
    });
    jQuery("#vno").html(vdetail);
}

function AssignVehicle() {
	var vehicleid = jQuery('#vno').val();
        
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
	var selected_name = jQuery("#vno option:selected").text();
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery("#vehicle_list").append(div);
		jQuery(div).append(remove_image);
	}
	//jQuery("#vno").val('');
}
function getMappedveh()
{
    var icid = jQuery('#ciname').val();
    //alert(icid);
    jQuery.ajax({
        type: "POST",
	url: "invoice_ajax.php",
	cache: false,
	data:{incust:icid},
        success:function(res){
            var map = jQuery.parseJSON(res);
            //console.log(data);
            printMapvehicle(map);
        }
        });  
}

function printMapvehicle(map)
{
    var print ='';
    jQuery(map).each(function(i,v){
        if (v.vehicleid > -1 && jQuery('#to_vehicle_div_' + v.vehicleid).val() == null) {
                var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = '../../images/boxdelete.png';
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(v.vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' +v.vehicleid;
		div.innerHTML = "<span>" + v.vehicleno + "</span><input type='hidden' class='v_list_element' name='to_vehicle_" + v.vehicleid + "' value= '"+ v.vehicleid + "'/>";
		jQuery("#vehicle_list").append(div);
		jQuery(div).append(remove_image);
            }
    });
}    
function removeVehicle(vehicleid) {
	jQuery('#to_vehicle_div_' + vehicleid).remove();
}

</script>
            
  
                