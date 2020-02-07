<div id="gc-topnav2"  class="ch_bar  drag"  style="background-color:#ffffff;
display:none;
width:360px;
height:auto; 
position:absolute; top:15%;
left:40%;">
<div>
<form name="createloc" id="createloc" action="route.php" method="POST">
<?php include 'panels/addloc.php';?>
<div id="p1">
<div class="formlineloc">

<div style="height:30px; float:left; text-align:left;">
    <a class="a" id="address" style="padding-right:2px;margin-top:-8px;"> Address </a>
    <input type="text" name="locA" id="locA"  class="chkp_inp" style="width: 200px;margin-top:-8px;">
    <input type="button" value="Locate" onclick="locate();"  id="locateinp" class="btn btn-primary" style="padding-right:5px;margin-top:-8px;">
    <a class="a" id="lname"> Name </a><input type="text" name="locName" id="locName"  class="chkp_inp"><input type="button" value="Create" onclick="checklocname();"  id="createinp" class="..btn .btn-primary">
</div>	
</div>
</div>
</div>
</div>
<div id="map"></div>
<div id="info" align="center">
    <table>
        <tr>
            <td><input type="hidden" name="tbUnit" id="tbUnit"  class="chkp_inp" placeholder="unit"></td>
            <td><input type="hidden" name="tbStreet" id="tbStreet"  class="chkp_inp" placeholder="street"></td>
        </tr>
        <tr>
            <td><input type="hidden" name="tbCity" id="tbCity"  class="chkp_inp" placeholder="city"></td>
            <td><input type="hidden" name="tbState" id="tbState"  class="chkp_inp" placeholder="state"></td>
        </tr>
        <tr>
            <td><input type="hidden" name="tbZip" id="tbZip"  class="chkp_inp" placeholder="zip"></td>
            <td><input type="hidden" name="tbAddress" id="tbAddress"  class="chkp_inp" placeholder="Address"></td>
        </tr>
        <tr>
            <td>
                <input type="hidden" id="geolat" name="geolat">
            </td>
            <td>
                <input type="hidden" id="geolong" name="geolong"> 
            </td>
        </tr>
    </table>
</div>           
</form>    

