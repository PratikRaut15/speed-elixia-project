<?php $location = getloc($_GET['geotestid']);
?>
<form name="loccreate" id="loccreate" action="route.php" method="POST">
<?php include 'panels/editloc.php';?>
    <tr>
        <td>Name</td>
        <td><input type="text" name="locName" id="locName" value="<?php echo $location->location;?>">
            <input type="hidden" name="locNameOld" id="locNameOld" value="<?php echo $location->location;?>">
        <input type="hidden" name="geotestid" id="geotestid" value="<?php echo $location->geotestid;?>"></td>
        <td>City</td>
        <td><input type="text" name="tbCity" id="tbCity" value="<?php echo $location->city;?>" readonly></td>        
        <td>
        <td>State</td>
        <td><input type="text" name="tbState" id="tbState" value="<?php echo $location->state;?>" readonly></td>        
        <td>
            <input class="btn  btn-primary" type="button" name="modifychk" id="modifyloc" value="Modify Location" onclick="checklocname();">&nbsp;
            <input type="hidden" id="geolat" name="geolat" value="<?php echo $location->lat;?>">
            <input type="hidden" id="geolong" name="geolong" value="<?php echo $location->long;?>">
        </td>        
    </tr>
    </tbody>
</table>
</form>
<div id="map"></div>
<div id="info" align="center">
    <table>
        <tr>
            <td><input type="hidden" name="tbUnit" id="tbUnit"  class="chkp_inp" placeholder="unit"></td>
            <td><input type="hidden" name="tbStreet" id="tbStreet"  class="chkp_inp" placeholder="street"></td>
        </tr>
        <tr>
            <td><input type="hidden" name="tbZip" id="tbZip"  class="chkp_inp" placeholder="zip"></td>
            <td><input type="hidden" name="tbAddress" id="tbAddress"  class="chkp_inp" placeholder="Address"></td>
        </tr>
    </table>
</div>  