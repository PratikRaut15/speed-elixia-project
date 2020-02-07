<?php
if (isset($_GET['did'])) {
    $vehiclesbygroup = getvehiclesbygroup($_GET['did']);
}

?>
<style>
    .column{
        width:49%;
        margin-right:.5%;
        height:500px;
        background:#fff;
        float:left;
        overflow-y: scroll;
    }
    #column2{
        background-image: url(../../images/drop.png);
        background-position: center;
        background-repeat: no-repeat;
    }
    .heading{
        width:49%;
        margin-right:.5%;
        min-height:21px;
        background:#cfc;
        float:left;
    }
    .column .dragbox{
        margin:5px 2px  20px;
        background:#fff;
        position:"relative";
        border:1px solid #946553;
        -moz-border-radius:5px;
        -webkit-border-radius:5px;
        width: inherit;
    }
    .column .dragbox h2{
        margin:0;
        font-size:12px;
        background:#946553;
        color:#fff;
        border-bottom:1px solid #946553;
        font-family:Verdana;
        cursor:move;
        padding:5px;
    }

    .dragbox-content{
        background:#fff;
        min-height:100px; margin:5px;
        font-family:'Lucida Grande', Verdana; font-size:0.8em; line-height:1.5em;
    }
    .column  .placeholder{
        background: #EED5B7;
        border:1px dashed #946553;
    }
    .alert-info {
        background-color: #d9edf7;
        border-color: #bce8f1;
        color: #3a87ad;
        cursor:move;
    }
</style>
<?php
/*To get Group Region */
$regions = getGroupRegion();

/*To get Group Zones */
$zones      = getZoneForRegions();

if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $vehicle = $_SESSION['Warehouse'];
    } else {
        $vehicle = 'Warehouse';
    }
} else {
    $vehicle = 'Vehicle';
}
?>
<div style="width: 67%;">
    <div class="heading" id="head1"><?php echo $vehicle; ?>s</div>
    <div class="heading" id="head1"><?php echo ($_SESSION['group']); ?> List</div>
    <div id="column1" class="column">
        <?php
        $vehicles = getvehiclesbydefaultid();
        if (isset($vehicles)) {
            foreach ($vehicles as $vehicle) {
                echo '<div class="alert-info" id=' . $vehicle->vehicleid . ' >
					<h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $vehicle->vehicleno . '</h2>
				</div>';
                //echo "<li class='dragbox' id='recordsArray_$checkpoint->checkpointid'><span>::</span>   $checkpoint->cname</li>";
            }
        }
        ?>
    </div>
    <div class="column" id="column2" >
        <?php
        if (isset($vehiclesbygroup)) {
            foreach ($vehiclesbygroup as $vehicle) {
                echo '<div class="alert-info" id=' . $vehicle->vehicleid . ' >
					<h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>   ' . $vehicle->vehicleno . '</h2>
				</div>';
                //echo "<li class='dragbox' id='recordsArray_$checkpoint->checkpointid'><span>::</span>   $checkpoint->cname</li>";
            }
        }
        ?>
    </div>
</div>
<div style="clear:both;"></div>
<?php

if ($_SESSION['switch_to'] == 1) {
    if ($edit_permission == 1 || $_SESSION['role_modal'] == 'elixir') {
        include 'panels/editgroup.php';
        $group = getgroup($_GET['did']);
        ?>
        <tr>
            <td><?php echo ($_SESSION['group']); ?> Name</td>
            <td>
                <input type="hidden" name="groupid" id="groupid" value="<?php echo $_GET['did']; ?>" maxlength="20">
                <input type="text" name="groupname" id="groupname" value="<?php echo $_GET['groupname']; ?>" >
            </td>
        </tr>
        <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>
            <tr>
                <td>Code</td>
                <td>
                    <input type="text" name="code" id="code" placeholder="Code" value="<?php echo $group->code; ?>" maxlength="20">
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td colspan="2" align="center">
                <input type="hidden" value="<?php echo $_SESSION['roleid']; ?>" name="roleid" id="roleid">
                <input type="button" value="Save" class="g-button g-button-submit" onclick="modifygroupchk();">
            </td>
        </tr>
        </tbody>
        </table>
        <?php
    }
} else{
    include 'panels/editgroup.php';
    $group = getgroup($_GET['did']);
    ?>
    <tr>
        <td><?php echo ($_SESSION['group']); ?> Name</td>
        <td>
            <input type="hidden" name="groupid" id="groupid" value="<?php echo $_GET['did']; ?>" maxlength="20">
            <input type="text" name="groupname" id="groupname" value="<?php echo $_GET['groupname']; ?>" >
        </td>
    </tr>
    <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>
        <tr>
            <td>Code</td>
            <td>
                <input type="text" name="code" id="code" placeholder="Code" value="<?php echo $group->code; ?>" maxlength="20">
            </td>
        </tr>
    <?php }
    else if($_SESSION['customerno'] == 64){
         $group = getAllGroupDetails($_GET['did']);
        ?>
          <tr>
        <td>Branch Code</td>
        <td><input type="text" name="code" id="code" placeholder="Group Code" value="<?php echo $group['groupcode'] ?>"></td>
        <td>Branch Region</td>
        <td>
            <select id='cityid' onchange='getZone(this.value)'>
                <option value=''>Select Region</option>
                <?php
                foreach($regions as $regionk=>$regionVal)
                {
                    if($group['regionid'] == $regionVal['regionId'] ){
                        ?>
                            <option value='<?php echo $regionVal['regionId']?>' selected='selected'><?php echo $regionVal['regionName']?></option>
                        <?php
                    }
                        else{
                    ?>
                    <option value='<?php echo $regionVal['regionId']?>'><?php echo $regionVal['regionName']?></option>
                         <?php
                     }
                }
                ?>
            </select>
            <input type="hidden" name="gregion" id="gregion" placeholder="Group Region" maxlength="20">
        </td>
        </tr>

    </tr>
    <?php
        } ?>
    <tr>
        <td colspan="2" align="center">
            <input type="hidden" value="<?php echo $_SESSION['roleid']; ?>" name="roleid" id="roleid">
            <input type="button" value="Save" class="g-button g-button-submit" onclick="modifygroupchk();">
        </td>
    </tr>
    </tbody>
    </table>
    <?php
}
?>
