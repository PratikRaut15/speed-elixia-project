<form action="reports.php?id=18" method="POST">
<?php
include 'panels/checkpttriphist.php';

class VOCHKM{}

$devices = getvehicles();
$chkpts = getallcheckpointsname();
//echo '<pre>';print_r($chkpts);
//print_r($_POST);
//Array ( [startchkid] => 537 [endchkid] => 539 [STdate] => 15-07-2014 [STime] => 00:00 [EDdate] => 15-07-2014 [ETime] => 23:59 [GetReport] => Get Report ) 
$startchkptsopt = "";
foreach ($chkpts as $chkpt)     
{      
    //print_r($chkpt);
    //echo $_POST['startchkid']; echo '    &&       '; echo $chkpt->checkpointid.'<br>';
     if(isset($_POST['startchkid']) && $chkpt->checkpointid == $_POST['startchkid'])
    {
        // echo 'yes';
        $startchkptsopt .= "<option value = '$chkpt->checkpointid' selected = 'selected'>$chkpt->cname</option>";
    }
    else
    {
        //echo 'no';
        $startchkptsopt .= "<option value = '$chkpt->checkpointid'>$chkpt->cname</option>";
    }
   
        //$chkptsopt .= "<option value = '$chkpt->cname' selected = 'selected'>$chkpt->cname</option>";
   
}

$endchkptsopt = "";
foreach ($chkpts as $chkpt)     
{      
    //print_r($chkpt);
    //echo $_POST['startchkid']; echo '    &&       '; echo $chkpt->checkpointid.'<br>';
     if(isset($_POST['endchkid']) && $chkpt->checkpointid == $_POST['endchkid'])
    {
        $endchkptsopt .= "<option value = '$chkpt->checkpointid' selected = 'selected'>$chkpt->cname</option>";
    }
    else
    {
        //echo 'no';
        $endchkptsopt .= "<option value = '$chkpt->checkpointid'>$chkpt->cname</option>";
    }
   
        //$chkptsopt .= "<option value = '$chkpt->cname' selected = 'selected'>$chkpt->cname</option>";
   
}

$devicesopt = "";
foreach ($devices as $device) 
{
    if(isset($_POST['vehicleid']) && $device->vehicleid == $_POST['vehicleid'])
    {
        $devicesopt .= "<option value = '$device->vehicleid' selected = 'selected'>$device->vehicleno</option>";
    }
    else
    {
        $devicesopt .= "<option value = '$device->vehicleid'>$device->vehicleno</option>";
    }
}

if(!isset($_POST['STdate'])) { $StartDate = getdate_IST(); } else { $StartDate = strtotime ($_POST['STdate']); }
if(!isset($_POST['EDdate'])) { $EndDate = $StartDate; } else { $EndDate = strtotime ($_POST['EDdate']); }             
if(!isset($_POST['STime'])) { $stime = "00:00"; } else { $stime = $_POST['STime']; }
if(!isset($_POST['ETime'])) { $etime = "23:59"; } else { $etime = $_POST['ETime']; }             
?>
    <tr>
        <td>Start checkpoint</td>
        <td>
             <select id="startchkid" name="startchkid" required>
                
                <?php echo $startchkptsopt;?>
            </select>
        </td>
         <td>End checkpoint</td>
        <td>
             <select id="endchkid" name="endchkid" required>
              
                <?php echo $endchkptsopt;?>
            </select>
        </td>
       <td>
            <select id="vehicleid" name="vehicleid" required>
                <option value="">Select Vehicle</option>
                <?php echo $devicesopt;?>
            </select>
        </td>
        <td>Start Date</td>
        
        <td>
            <input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
        </td>
        <td>Start Hour
        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime;?>" /></td>
        <td>End Date</td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/>
        </td>
        <td>End Hour
            <input id="ETime" name="ETime" type="text" class="input-mini" data-date2="<?php echo $etime;?>"/></td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
        <td><a class="g-button g-button-submit" onclick="table2CSV(jQuery('#search_table_2')); return false;" href="#"> Export to CSV </a>
        </td>
    </tr>
    </tbody>
    </table>
</form>
<?php 
$error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
$error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
if(isset($_POST['STdate']) && isset($_POST['EDdate']))
{
    include '../../lib/system/utilities.php';
    print_r($_POST);
    //Array ( [startchkid] => 533 [endchkid] => 535 [STdate] => 29-06-2014 [STime] => 00:00 [EDdate] => 29-06-2014 [ETime] => 23:59 [GetReport] => Get Report ) 
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    if(strtotime($STdate)>strtotime($EDdate))
    {
        echo $error;
    }
    else if(isset($_POST['startchkid']) && $_POST['startchkid']==-1)
    {
        echo $error1;
    }
    else if(isset($_POST['endchkid']) && $_POST['endchkid']==-1)
    {
        echo $error1;
    }
    else
    {
        $endchkid = GetSafeValueString($_POST['endchkid'], 'string');
        $startchkid = GetSafeValueString($_POST['startchkid'], 'string');

        
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $checkpoints = getcheckpoints($vehicleid);
        //$rawrep = getchkrep($STdate,$EDdate,$_POST['STime'],$_POST['ETime'],$vehicleid,$checkpoints);
        
        $rawrep = getchkrep($STdate,$EDdate,$_POST['STime'],$_POST['ETime'],$vehicleid,$checkpoints,$startchkid,$endchkid);
        
     // echo '<pre>'; print_r($rawrep);
        if(isset($rawrep) && count($rawrep)>0)
        {
            include 'panels/chkpttriphistrep.php';
           
            $chkrep = processchkrep($rawrep);
            echo '<pre>';  print_r($chkrep);
            displayrep($chkrep);
            echo "</tbody>";
            echo "</table>";
        }
        else 
        {
            echo $error;
        }
        
    }
}
?>
