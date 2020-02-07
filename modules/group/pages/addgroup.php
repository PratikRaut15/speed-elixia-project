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
if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $vehicle = $_SESSION['Warehouse'];
    } else {
        $vehicle = 'Warehouse';
    }
} else {
    $vehicle = 'Vehicle';
}
/*To get Group Region */
$regions = getGroupRegion();
?>
<div style="width: 67%;">
    <div class="heading" id="head1"><?php echo $vehicle; ?>s</div>
    <div class="heading" id="head1"><?php echo ($_SESSION['group']); ?> List</div>
    <div id="column1" class="column">
        <?php
        $vehicles = getvehiclesbydefaultid();
        if (isset($vehicles)) {
            foreach ($vehicles as $vehicle) {
                echo '<div class="alert-info" id=' . $vehicle->vehicleid . ' style="clear:both;">
					<h2 style="font-size: 14px;font-weight: normal; margin: 2px;"><span>::</span>  ' . $vehicle->vehicleno . '</h2>
				</div>';
                //echo "<li class='dragbox' id='recordsArray_$checkpoint->checkpointid'><span>::</span>   $checkpoint->cname</li>";
            }
        }
        ?>
    </div>
    <div class="column" id="column2" >
    </div>
</div>
<div style="clear:both;"></div>
<?php
$zones      = getZoneForRegions();


if ($_SESSION['switch_to'] == 1) {
    if ($addpermission == 1 || $_SESSION['role_modal'] == 1){
        ?>
        <?php include 'panels/addgroup.php'; ?>
        <tr>
            <td><?php echo ($_SESSION['group']); ?> Name</td>
            <td><input type="text" name="groupname" id="groupname" placeholder="Group Name" maxlength="20"></td>
        </tr>
        <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>
            <tr>
                <td>Code</td>
                <td><input type="text" name="code" id="code" placeholder="Code" maxlength="20"></td>
            </tr>
        <?php }
        ?>
        <tr>
            <td colspan="2" align="center">
                <input type="hidden" value="<?php echo $_SESSION['roleid']; ?>" name="roleid" id="roleid">
                <input type="button" class="g-button g-button-submit" value="Save" onclick="checkgroup();">
            </td>
        </tr>
        </tbody>
        </table>
        <?php
    }
}else{
    ?>    <?php include 'panels/addgroup.php'; ?>
    <tr>
        <td><?php echo ($_SESSION['group']); ?> Name</td>
        <td><input type="text" name="groupname" id="groupname" placeholder="Group Name" maxlength="20">
        </td>
   </tr>
    <?php if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1' && $_SESSION['switch_to'] == '1') { ?>
        <tr>
            <td>Code</td>
            <td><input type="text" name="code" id="code" placeholder="Code" maxlength="20"></td>
        </tr>
    <?php }
   if($_SESSION['customerno'] == '64'){
    ?>
    <tr>
        <td>Branch Code</td>
        <td><input type="text" name="code" id="code" placeholder="Group Code" maxlength="20"></td>
        <td>Branch Region</td>
        <td>
            <select name='cityid' id='cityid' onchange='getZone(this.value)'>
                <option value=''>Select Region</option>
                <?php
                foreach($regions as $regionk=>$regionVal)
                {
                    ?>
                    <option value='<?php echo $regionVal['regionId']?>'><?php echo $regionVal['regionName']?></option>
                         <?php
                }
                ?>
            </select>
            <input type="hidden" name="gregion" id="gregion" placeholder="Group Region" value="<?php echo $regionVal['regionId']?>">
        </td>
        <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRegion">Add New Region</button></td>
        </tr>
        <tr>
        <td>Label</td>
        <td><select name='zone' id='zone' style="width:100%">
            <option value=''>Select Region</option>
                    <?php
                        if(isset($zones) && !empty($zones)){
                            foreach($zones as $zoneK=>$zoneVal){
                                ?>
                                <option value='<?php echo $zoneVal['zoneId'];?>' id='selectedLabel_<?php echo $zoneVal['zoneId'];?>'><?php echo $zoneVal['zoneName'];?></option>
                                <?php
                            }
                        }
                        ?>
            </select>
            <input type="hidden" name="zoneId" id="hiddenzone" value="">
           <td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newZone">Add New Zone</button></td>
        </td>
        </tr>
    </tr>
    <?php }
    ?>
    <tr></tr>
        <td colspan="2" align="center">
            <input type="hidden" value="<?php echo $_SESSION['roleid']; ?>" name="roleid" id="roleid">
            <input type="button" class="g-button g-button-submit" value="Save" onclick="checkgroup();">
        </td>
    </tr>
    </tbody>
    </table>
    <?php /*To Add New Region*/ ?>
        <div class="modal fade" tabindex="-1" id="newRegion">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#0679c0">Add New Region</h4>
                    </div>
                    <div class="modal-body">
                        <form id="forregion" class="form-inline"  method="POST" action="modules/user/route.php">
                            <span colspan="2" id="regionnamecomp" style="display: none">Please enter Region Name.</span>    
                             <span colspan="2" id="regioncodecomp" style="display: none">Please enter Region Code Name.</span>
                            <span colspan="2" id="regionzonecomp" style="display: none">Please select Region Code.</span>
                            <span colspan="2" id="regionnameadd" style="display: none">Region Added</span>
                            <span colspan="2" id="newempty" style="color:#FF0000;display: none">Please Enter New Region</span>
                            <input type="hidden" class="form-control" id="add_region" name="add_region">
                            <input type="hidden" class="form-control" id="user_name" name="user_name">
                            <div class="form-group">
                             <div> Region Name :  <input class="input-lg form-control" id="regionname" name="regionname" type="text" placeholder="Region Name" class="input-xlarge">
                             </div><br/>
                             <div> Region Code :  <input class="input-lg form-control" id="regioncode" name="regioncode" type="text" placeholder="Region Code" class="input-xlarge"></div><br/>
                             <div> Region Zone :
                                <select name='regionZone' id='regionZone'><option value=''>Select Zone</option>
                                <?php
                                if(isset($zones) && !empty($zones)){
                                    foreach($zones as $zoneK=>$zoneVal){
                                        ?>
                                        <option value='<?php echo $zoneVal['zoneId'];?>' id='selected_<?php echo $zoneVal['zoneId'];?>'><?php echo $zoneVal['zoneName'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                              </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button" name="addregion"  class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Add New Region" onclick="add_region();">Save Region</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php /*End Add New Region */?>

<?php
$states     = getStateForZones();
 /*To Add New Zone*/ ?>
      <div class="modal fade" tabindex="-1" id="newZone">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color:#0679c0">Add New Zone</h4>
                    </div>
                    <div class="modal-body">
                        <form id="forregion" class="form-inline"  method="POST" action="modules/user/route.php">
                        <span colspan="2" id="zonenamecomp" style="display: none">Please enter Zone Name.</span>    
                             <span colspan="2" id="zonecodecomp" style="display: none">Please enter Zone Code Name.</span>
                            <span colspan="2" id="statecomp" style="display: none">Please select State.</span>
                            <span colspan="2" id="zonenameadd" style="display: none">Zone Added</span>
                            <span colspan="2" id="newempty" style="color:#FF0000;display: none">Please Enter New Zone</span>
                            <div class="form-group">
                                 <div> Zone Name :  <input class="input-lg form-control" id="zonename" name="zonename" type="text" placeholder="Zone Name" class="input-xlarge">
                                 </div><br/>
                                 <div> Zone Code :  <input class="input-lg form-control" id="zonecode" name="zonecode" type="text" placeholder="Zone Code" class="input-xlarge"></div><br/>
                                 <div> State :
                                         <select name='state' id='state'><option value=''>Select Zone</option>
                                <?php
                                if(isset($states) && !empty($states)){
                                    foreach($states as $stateK=>$stateVal){
                                        ?>
                                        <option value='<?php echo $stateVal['stateId'];?>' id='selected_<?php echo $stateVal['stateId'];?>'><?php echo $stateVal['stateName'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                                 </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="button" name="addregion"  class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Add New Region" onclick="add_zone();">Save Region</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php /*End To Add New Zone*/?>
    <?php
}
?>
