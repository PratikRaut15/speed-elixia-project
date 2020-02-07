<?php $checkpoint = getenhchk($_GET['enh_chkid']);?>
<form name="chkptform" id="chkptform">
<?php include 'panels/editchk.php';?>
    <tr>
        <td>Checkpoint Name</td>
        <td><input type="text" name="chkName" id="chkName" value="<?php echo $checkpoint->cname;?>" readonly>
        <td>Vehicle No</td>
        <td><input type="text" name="vehicleno" id="vehicleno" value="<?php echo $checkpoint->vehicleno;?>" readonly>
        <input type="hidden" name="enh_chkId" id="enh_chkId" value="<?php echo $checkpoint->enh_checkpointid;?>">
        <input type="hidden" name="type" id="type" value="<?php echo $checkpoint->type;?>"></td>
        <td>Communication Detail</td>
        <td><input type="text" name="comdet" id="comdet" value="<?php echo $checkpoint->comdet;?>"></td> 
        <td>
            <input class="btn  btn-primary" type="button" name="modifychk" id="modifychk" value="Modify" onclick="editcheckpoints();">&nbsp;
        </td>        
    </tr>
    </tbody>
</table>
</form>
<div id="map"></div>