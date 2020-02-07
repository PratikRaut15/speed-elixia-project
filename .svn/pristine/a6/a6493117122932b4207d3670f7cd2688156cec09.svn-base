<?php echo "<script src='". $_SESSION['subdir']."/scripts/sales/prototype.js' type='text/javascript'></script>"; ?>
<script language="Javascript">
// We don't want to select text, other browsers use a style attribute to do this, but Oh No, not IE.
if(document.all)
{
    document.onselectstart=new Function('return false');
    function ds(e){return false;}
    function ra(){return true;}
    document.onmousedown=ds;
    document.onclick=ra;
}

    
</script>

<div class='container'>
    <br><br>
    <center>
        <span id="manalert" class="mandatory">Please select a device and a Sales Team Member for mapping.</span>  
        <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Map Devices to Sales Team</th></tr></thead>
        <tr>
            <th width="45%">Devices</th>
            <th width="10%">&nbsp;</th>
            <th width="45%">Sales Team</th>
        </tr>
        <tr>
             <td><div id="devices" class="whitepanel" style="height:auto;"><?php include("devicelistAjax.php"); ?></div></td>
             <td align="center">    
                    <input id="btnMapper" name="btnMapper"class='btn  btn-primary' type="button" value="Assign" onclick="mapselection()"/><br/>
                    <input id="btnDemap" name="btnDemap"  class='btn  btn-primary' type="button" value="De-map" onclick="demap()"/>    
             </td>
             <td><div id="trackee" class="whitepanel" style="height:auto;"><?php include("salesListAjax.php"); ?></div></td>
        </tr>
        </table>
    </center>
</div>