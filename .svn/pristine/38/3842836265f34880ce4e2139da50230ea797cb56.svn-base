
<?php
/**
 * Trip Status edit Master form
 */
?>
<?php 
$tripstatusdata = tripstatusedit($_SESSION['customerno'],$_SESSION['userid'],$statusid);

if(isset($tripstatusdata))
    {
        foreach($tripstatusdata as $row)
        {
            $tripstatus = $row['tripstatus'];
            $statusid = $row['statusid'];
        }
    }
?>
<br/>
<div class='container'>
    <center>
        <form name="statusformedit" id="statusformedit" method="POST" action="trips.php?pg=tripstatusedit&statusid=<?php echo $tripstatusid; ?>"  onsubmit="updatetripstatusdata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >Update Trip Status</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>Status<span class="mandatory">*</span></td><td><input type="text" name="statusname" id="statusname" value="<?php echo $tripstatus; ?>" required></td></tr>
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="statussubmit" value="Update" class='btn btn-primary'></td></tr>
        <input type="hidden" name="statusid" id="statusid" value="<?php echo $statusid; ?>">
        </tbody> 
    </table>
    </form>
    </center>
</div>
