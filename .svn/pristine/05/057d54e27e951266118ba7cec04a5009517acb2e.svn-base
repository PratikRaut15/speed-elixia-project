<form action="reports.php?id=3" method="POST">
<?php
include 'panels/variablehist.php';
if(!isset($_POST['STdate']))
{
    $StartDate = getdate_IST();
}
else
{
    $StartDate = strtotime ($_POST['STdate']);
}
if(!isset($_POST['EDdate']))
{
    $EndDate = $StartDate;
}
else
{
    $EndDate = strtotime ($_POST['EDdate']);
}
$select = "";
if(isset($_POST['report']))
{
    switch ($_POST['report'])
    {
        case 'Mileage':
            
            $select .= '<option value="Mileage" selected="selected">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset">Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'IdleTime':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime" selected="selected">IdleTime Report</option>';
            $select .= '<option value="Genset">Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'Overspeed':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset">Genset Usage Report</option>';
            $select .= '<option value="Overspeed" selected="selected">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'FenceConflict':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset">Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict" selected="selected">FenceConflict Report</option>';
            $select .= '<option value="Location">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'Genset':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset" selected="selected">Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'Location':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset" >Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location" selected="selected">Location Report</option>';
            $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
            break;
        case 'Fuel':
            $select .= '<option value="Mileage">Distance Report</option>';
            $select .= '<option value="IdleTime">IdleTime Report</option>';
            $select .= '<option value="Genset" >Genset Usage Report</option>';
            $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
            $select .= '<option value="FenceConflict">FenceConflict Report</option>';
            $select .= '<option value="Location" >Location Report</option>';
            $select .= '<option value="Fuel" selected="selected">Fuel Comsumption Report</option>';
            break;
    }
}
else
{
    $select .= '<option value="Mileage">Distance Report</option>';
    $select .= '<option value="IdleTime">IdleTime Report</option>';
    $select .= '<option value="Genset">Genset Usage Report</option>';
    $select .= '<option value="Overspeed">Overspeeding Incident Report</option>';
    $select .= '<option value="FenceConflict">FenceConflict Report</option>';
    $select .= '<option value="Location">Location Report</option>';
    $select .= '<option value="Fuel">Fuel Comsumption Report</option>';
}
?>
    <tr>
        <td>Start Date</td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/></td>
        <td><!--<button id="SDate" class="g-button g-button-submit">...</button>--></td>
        <td>End Date</td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/></td>
        <td><!--<button id="EDate" class="g-button g-button-submit">...</button>--></td>
        <td><select name="report">
                <?php echo $select;?>
            </select></td>
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
		<td>
		
                    <a href="#" class="g-button g-button-submit" id="btnExport" style="display: none;" > 
		Export to Excel
		</a> 

		</td>
    
    </tr>
    </tbody>
    </table>
 
 

</form>
   <script>
$(document).ready(function () {
$("#btnExport").click(function () {
$("#search_table_2").btechco_excelexport({
containerid: "search_table_2",
name:"Report.xls",
datatype: $datatype.Table
});
});
});
</script>
<br>
<?php 

if(isset($_POST['STdate']) && isset($_POST['EDdate']))
{
    //include '../../lib/system/utilities.php';
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $diff = (int)substr($EDdate,0,2) - (int)substr($STdate, 0,2);
    
    if($diff>30)
    {
        echo "<script>
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000)</script>";
    }
    else if(strtotime($STdate)>strtotime($EDdate))
    {
        echo "<script>
        jQuery('#error2').show();
        jQuery('#error2').fadeOut(3000)</script>";
    }
    else
    {
         $reports = getdailyreport($STdate, $EDdate);
        if(isset($reports))
        {  
            echo "<script>
            jQuery('#btnExport').show();
            </script>";
            
            include 'panels/variablehistrep.php';
            include 'displayvariabledata.php';
        }
        else
            echo "<script type='text/javascript'>
                    jQuery('#error').show();jQuery('#error').fadeOut(3000);
                </script>";
    }
 
}
?>
