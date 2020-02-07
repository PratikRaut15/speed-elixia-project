<?php
/**
 * State edit Master form
 */
?>
<?php 
$stateeditdata = stateedit($_SESSION['customerno'],$_SESSION['userid'],$stateid);

if(isset($stateeditdata))
    {
        foreach($stateeditdata as $row)
        {
            $statename = $row['statename'];
            $stateid = $row['stateid'];

        }
    }
?>

<br/>
<div class='container'>
    <center>
    <form name="stateeditform" id="stateeditform" method="POST" action="sales.php?pg=stateedit" onsubmit="updatestatedata();return false;">
    <table class='table table-condensed'>
        <thead><tr><th colspan="100%" >State Update</th></tr></thead>
        <tbody>
            <tr><td colspan="100%" id="ajaxstatus"></td></tr>
            <tr><td class='frmlblTd'>State Name <span class="mandatory">*</span></td><td><input type="text" name="statename" value="<?php echo $statename;?>" required></td></tr>
           
            <tr><td colspan="100%" class='frmlblTd'><input type="submit" name="tracksubmit" value="Add" class='btn btn-primary'></td></tr>
        </tbody>
    </table>
        <input type="hidden" name="stateid" id="stateid" value="<?php echo $stateid;?>">   
    </form>
    </center>
</div>
