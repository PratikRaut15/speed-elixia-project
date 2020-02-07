<link rel="stylesheet" type="text/css" href="../../scripts/datatables/jquery.dataTables_new.css">
<style>
    #ajaxstatus{text-align:center;font-weight:bold;display:none}
    .mandatory{color:red;font-weight:bold;}
    #addorders table{width:50%;}
    #addorders .frmlblTd{text-align:center}    
</style>
<br/>
<div class='container' >
    <center>    
        <form id="addorders" method="POST" action="action.php?action=edit-routecheckpoint">
            <table class='table table-condensed'>
                <thead><tr><th colspan="100%" >Edit Route</th></tr></thead>
                <tbody>

                    <tr><td colspan="100%" id="ajaxstatus"></td></tr>

                    <tr><td class='frmlblTd'>Route<span class="mandatory">*</span></td>
                        <td><input type="text" name="route_name" id="route_name" value="<?php echo $routechekpoints[0]['routename'] ?>" required maxlength="20"></td>
                <input type="hidden" name="routemasterid" id="routemasterid" value="<?php echo $routechekpoints[0]['routemasterid'] ?>" />
                    </tr>
                   
                    <tr><td class='frmlblTd'>From Factory<span class="mandatory">*</span></td>
                        <td><input type="text" name="factory_name" id="factory_name"  value="<?php echo $routechekpoints[0]['factoryname'] ?>" required maxlength="20" autocomplete="off"></td>
                <div id="display1" class="listlocation"></div>
                <input type="hidden" name="fromlocationid" id="factoryid" value="<?php echo $routechekpoints[0]['fromlocationid'] ?>" maxlength="50"/>
                </tr>
                <tr><td class='frmlblTd'>To Location<span class="mandatory">*</span></td>
                    <td><input type="text" name="location_name" id="locationname" value="<?php echo $routechekpoints[0]['locationname'] ?>" required maxlength="20" autocomplete="off"></td>
                <input type="hidden" name="tolocationid" id="locationid" value="<?php echo $routechekpoints[0]['tolocationid'] ?>" maxlength="50"/>
                <div id="display" class="listzone"></div> 
                </tr>
               

                <tr><td class='frmlblTd'>Distance (Kms)<span class="mandatory">*</span></td>
                    <td><input type="text" name="routedistance" value="<?php echo $routechekpoints[0]['distance'] ?>" required maxlength="10"></td></tr>
                
               


                <input type='hidden' id='routecheckpointid' name='routecheckpointid' value="<?php echo $routechekpoints[0]['routecheckpointid'] ?>" >
                <tr><td colspan="100%" class='frmlblTd'><input type="submit" value="Save" class='btn btn-primary'></td></tr>
                </tbody>
            </table>
        </form>
    </center>
</div>    

