<?php
/**
 * Edit Trackie Master form
 */
require_once "mobility_function.php";
$id = $_GET['id'];
if($id==""|| $id=="0"){
    header('location:mobility.php?pg=add-therapist');
}

$mob = new Mobility($_SESSION['customerno'],$_SESSION['userid']);
$gettrackiedata = $mob->gettrackiedata_byid($id);
$getservicelist = $mob->getservice_list();
$getservicelistbyid = $mob->getservice_listbyid($id);
$editids = array();

if(!empty($getservicelistbyid)){
    foreach ($getservicelistbyid as $key=>$value) {
        $editids[] = $key;
    }
}
$ids =  implode(',', $editids);
$weeklyoff = $gettrackiedata[0]['weekly_off'];

$woff= array();
$woff = explode (",",$weeklyoff);
?>
<br/>
<div class='container'>
    <center>
    <form name="edittrackieform" id="edittrackieform" method="POST" action="mobility.php?pg=edit-<?php echo $rename;?>" onsubmit="edittrackiedata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update <?php echo ucfirst($rename);?> Master</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Name <span class='mandatory'>*</span></td><td><input type="text" name="trackiename" value="<?php echo $gettrackiedata[0]['name'];?>" required></td></tr>
            <tr><td class='frmlblTd'>Phone <span class="mandatory">*</span></td><td><input type="text" name="phone" required  value="<?php echo $gettrackiedata[0]['phone'];?>"></td></tr>
            <tr><td class='frmlblTd'>Email <span class="mandatory">*</span></td><td><input type="email" name="emailid" required value="<?php echo $gettrackiedata[0]['email'];?>"></td></tr>
            <tr><td class='frmlblTd'>Address </td><td><textarea name="address" id="address"><?php echo $gettrackiedata[0]['address'];?></textarea></td></tr>
            <tr><td class='frmlblTd'>Weekly Off </td><td>
            <select multiple name="weeklyoff[]" id="weeklyoff">    
            <?php 
                foreach ($weeklyoff_arr as $key => $value)
                {
            ?>
                    <option value="<?php echo $key;?>"<?php if(in_array($key,$woff)){ echo "selected";}?>><?php echo $value;?></option>
            <?php                 
                }
            ?>
            </select>
                 </td></tr>
            <tr>
                <td class='frmlblTd'>Location </td>
                <td><input type="text" name="location" id="location" value="<?php echo $gettrackiedata[0]['location']; ?>"/>
                    <input type="hidden" name="locid" id="locid" value="<?php echo $gettrackiedata[0]['locid']; ?>"/>
                </td>
            </tr>
             <tr>
                <td class='frmlblTd'>Skill Sets</td>
                <td><input type="button" value="Skills" name="skills" id="skills"/></td>
            </tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Update" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" id="trackid" name="trackid" value="<?php echo $id;?>">
        <input type="hidden" name="uncheckids" id="uncheckedids" value ="<?php echo $ids; ?>"/>  
    </form>
        
<!--change status pop starts----->
<div id='styleBuble' class="bubble row">
    <div class="col-xs-12" >
        <div class='row'>
            <div class="col-xs-12 bubbleclose" >X</div>
        </div>
        <div class='row'>
            <div class="col-xs-12">
                <h3 style='text-align:center;'>Edit Skills</h3>
                <div id='ajaxBstatus'></div>
                <?php
                $servicecount = count($getservicelist);
                $scroll='';
                if($servicecount > 6)
                {
                 $scroll = "overflow-y: scroll; width:250px; height: 150px;";   
                }
                ?>    
                <div style="text-align: left; <?php echo $scroll;?>">
                    <ul>
                    <?php
                    foreach ($getservicelist as $key => $value){
                        if(!empty($getservicelistbyid)){
                    ?>    
                        <div style="display:inline;"><input type="checkbox" style="display: inline-block;" <?php if(!array_key_exists($value['id'], $getservicelistbyid)){ ?>checked="checked"<?php } ?>  id="servicelist<?php echo $value['id'];?>" value="<?php echo $value['id'];?>"/>&nbsp;<label for="servicelist<?php echo $value['id'];?>" style="display: inline-block;"><?php echo ucfirst($value['value']);?></label></div><br>
                    <?php    
                        }else{
                        ?>
                        <div style="display:inline;"><input type="checkbox" style="display: inline-block;" checked="checked" id="servicelist<?php echo $value['id'];?>" value="<?php echo $value['id'];?>"/>&nbsp;<label for="servicelist<?php echo $value['id'];?>" style="display: inline-block;"><?php echo ucfirst($value['value']);?></label></div><br>
                        <?php 
                        }
                    }
                    ?>
                    </ul>
                </div>
                <br>
            </div>
        </div>
        <div class='row'>

                <div class='col-xs-12' style='text-align:right;'><input type="submit" class="btn" value="Submit" id="getuncheckvalues"/> <input type="submit" class="btn btn-primary bubbleclose" value="Close" /></div>
        </div><br/>
    </div>
</div>
<!--change status pop ends-->
        
        
        
    </center>
</div>