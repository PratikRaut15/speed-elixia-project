<script>
$(function() {
	$("#vehicleno").autoSuggest({
		ajaxFilePath	 : "autocomplete.php", 
		ajaxParams	 : "dummydata=dummyData", 
		autoFill	 : false, 
		iwidth		 : "auto",
		opacity		 : "0.9",
		ilimit		 : "10",
		idHolder	 : "id-holder",
		match		 : "contains"
	});
  });
function fill(Value, strparam)
{
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();
    
}
</script>
<form action="reports.php?id=11" method="POST">
<?php
class tempcontrol{
    
}

include 'panels/temprep.php';
if(!isset($_POST['STdate'])) { $StartDate = getdate_IST();} else { $StartDate = strtotime ($_POST['STdate']);}
if(!isset($_POST['EDdate'])) { $EndDate = $StartDate; } else { $EndDate = strtotime ($_POST['EDdate']);}          
if(!isset($_POST['STime'])) { $stime = "00:00"; } else { $stime = $_POST['STime']; }
if(!isset($_POST['ETime'])) { $etime = "23:59"; } else { $etime = $_POST['ETime']; }
if(isset($_POST['vehicleno'])) { $vehicleno = $_POST['vehicleno']; }
if(isset($_POST['vehicleid'])) { $vehicleid = $_POST['vehicleid']; }
if($_SESSION["temp_sensors"] == 2)
{
    $select="";
    if(isset($_POST['tempsel']))
    {
        switch ($_POST['tempsel'])
        {
              case '2':
                  $select .= '<option value="1">Temperature 1</option>';
                  $select .= '<option value="2" selected="selected">Temperature 2</option>';
                  break;
              case '1':
                  $select .= '<option value="1" selected="selected">Temperature 1</option>';
                  $select .= '<option value="2">Temperature 2</option>';
                  break;
          }
    }
    else
    {
        $select .= '<option value="1">Temperature 1</option>';
        $select .= '<option value="2">Temperature 2</option>';
    }
}

?>
      <?php
if($_SESSION['ecodeid']){
    ?>
<input type="hidden" name="s_start" id="s_start" value="<?php echo $_SESSION['startdate'];?>" />
<input type="hidden" name="e_end" id="e_end" value="<?php echo $_SESSION['enddate'];?>" />
    <?php
}
?>
    <tr>
        <td>
            <input  type="text" name="vehicleno" id="vehicleno" size="20" value="<?php echo $vehicleno;?>" autocomplete="off" placeholder="Enter Vehicle No" required>
            <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid;?>"/>
            <div id="display" class="listvehicle"></div>
        </td>
        <?php
        if($_SESSION["temp_sensors"] == 2)
        {
        ?>
        <td>
            <select id="tempsel" name="tempsel">
            <?php echo $select;?>
            </select>
        </td>
        <?php
        }
        ?>
        <td>Start Date</td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
        </td>
        <td>Start Hour
        <input id="STime" name="STime" type="text" class="input-mini" data-date="<?php echo $stime;?>" /></td>
        <td>End Date</td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/></td>
        <td>End Hour
            <input id="ETime" name="ETime" type="text" class="input-mini" data-date2="<?php echo $etime;?>"/></td>
        <td><input type="hidden" id="report" name="report" value="TemperatureDaily" /></td>
        <td><input type="submit" class="g-button g-button-submit" value="Get Report" name="GetReport"></td>
        
    </tr>
    <tr id="Temperature" class="tr" style="display: none;"></tr>
</tbody>
</table>
</form>
<br>
<div id="graph_div" class="myDivToPrint">
<div id="chart_div" style="height: 500px;">
</div>
<?php 
if(isset($_POST['STdate']))
{
    //include '../../lib/system/utilities.php';
    $STdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $currentdate = date('Y-m-d');
    $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
    if($_SESSION["temp_sensors"] == 2)
    {
        $tempselect = GetSafeValueString($_POST['tempsel'], 'string');    
    }
    $ReportType = GetSafeValueString($_POST['report'], 'string');
    $datediffcheck = date_SDiff($STdate, $EDdate); 
    if($_POST['deviceid']=='Select Vehicle')
    {
        echo "<script>
        jQuery('#error3').show();
        jQuery('#error3').fadeOut(3000)</script>";
    }
    
    else if($ReportType == 'TemperatureDaily')
    {
        if(strtotime($STdate)>strtotime($EDdate))
        {
            echo "<script>
            jQuery('#error2').show();
            jQuery('#error2').fadeOut(3000)</script>";
        }
        else if(strtotime($EDdate)>strtotime($currentdate)){
            echo "<script>
            jQuery('#error4').show();
            jQuery('#error4').fadeOut(3000)</script>";            
        }
        else if($_SESSION['ecodeid'])
        {
            $startdate = date('Y-m-d',strtotime(GetSafeValueString($_POST['s_start'], 'string')));
            $enddate = date('Y-m-d',strtotime(GetSafeValueString($_POST['e_end'], 'string')));
            if(strtotime($STdate) < strtotime($startdate) || strtotime($EDdate) > strtotime($enddate))
            {
                echo "<script>
                jQuery('#error6').show();
                jQuery('#error6').fadeOut(3000)</script>";
            }
            else
            {
              if($datediffcheck <= 30){
                    if($_SESSION["temp_sensors"] == 1)
                    {
                        $reports = getdailyreportfortemp($STdate,$EDdate, $vehicleid,$_POST['STime'],$_POST['ETime']);
                       
                    }
                    if($_SESSION["temp_sensors"] == 2)
                    {
                        $tempcontrol = new tempcontrol();
                        $analogselect1 = 0;                        
                        $analogselect2 = 0;
                        $vehicle = getunitdetailsfromvehid($vehicleid);
                        $analogselect1 = $vehicle->tempsen1;
                        $tempcontrol->min1 = $vehicle->temp1_min;
                        $tempcontrol->max1 = $vehicle->temp1_max;                                                            

                        $analogselect2 = $vehicle->tempsen2;
                        $tempcontrol->min2 = $vehicle->temp2_min;
                        $tempcontrol->max2 = $vehicle->temp2_max;                            
                                
                        if($tempselect == "1")
                        {
                            $reports = getdailyreportfortempselected($STdate,$EDdate, $vehicleid,$analogselect1,$_POST['STime'],$_POST['ETime']);    

                        }
                        if($tempselect == "2")
                        {
                            $reports = getdailyreportfortempselected($STdate,$EDdate, $vehicleid,$analogselect2,$_POST['STime'],$_POST['ETime']);   

                        }
                    }
                    if(isset($reports))
                    {
                        include 'vehicletempdata.php';
                        if(isset($vehiclereps))
                        {
                            include 'vehicletempchart.php';
                            if(isset($ReportType))
                                {
                                    echo '<div id="date" style="width: 75%;">
                                        <div style="float: left"><b>'.$_POST['STdate'].'</b></div>
                                        <div style="float: right"><b>'.$_POST['EDdate'].'</b></div>
                                    </div>';
//                                    echo '<br><br><div align="center"><b>';
//
//                                    echo '</b></div>';
                                }
                        }
                    }
                    else
                        echo "<script type='text/javascript'>
                                jQuery('#error').show();jQuery('#error').fadeOut(3000);
                            </script>";
            }
            else{
                        echo "<script type='text/javascript'>
                                jQuery('#error5').show();jQuery('#error5').fadeOut(3000);
                            </script>";
            }  
            }
        }
        else{
            if($datediffcheck <= 30){
                    if($_SESSION["temp_sensors"] == 1)
                    {
                        $reports = getdailyreportfortemp($STdate,$EDdate, $vehicleid,$_POST['STime'],$_POST['ETime']);
                       
                    }
                    if($_SESSION["temp_sensors"] == 2)
                    {
                        $tempcontrol = new tempcontrol();
                        $analogselect1 = 0;                        
                        $analogselect2 = 0;
                        $vehicle = getunitdetailsfromvehid($vehicleid);

                        $analogselect1 = $vehicle->tempsen1;
                        $tempcontrol->min1 = $vehicle->temp1_min;
                        $tempcontrol->max1 = $vehicle->temp1_max;                                                            

                        $analogselect2 = $vehicle->tempsen2;
                        $tempcontrol->min2 = $vehicle->temp2_min;
                        $tempcontrol->max2 = $vehicle->temp2_max;                            
                            
                        if($tempselect == "1")
                        {
                            $reports = getdailyreportfortempselected($STdate,$EDdate, $vehicleid,$analogselect1,$_POST['STime'],$_POST['ETime']);    

                        }
                        if($tempselect == "2")
                        {
                            $reports = getdailyreportfortempselected($STdate,$EDdate, $vehicleid,$analogselect2,$_POST['STime'],$_POST['ETime']);   

                        }
                    }
                    if(isset($reports))
                    {
                        include 'vehicletempdata.php';
                        if(isset($vehiclereps))
                        {
                            include 'vehicletempchart.php';
                            if(isset($ReportType))
                                {
                                    echo '<div id="date" style="width: 75%;">
                                        <div style="float: left"><b>'.$_POST['STdate'].'</b></div>
                                        <div style="float: right"><b>'.$_POST['EDdate'].'</b></div>
                                    </div>';
//                                    echo '<br><br><div align="center"><b>';
//
//                                    echo '</b></div>';
                                }
                        }
                    }
                    else
                        echo "<script type='text/javascript'>
                                jQuery('#error').show();jQuery('#error').fadeOut(3000);
                            </script>";
            }
            else{
                        echo "<script type='text/javascript'>
                                jQuery('#error5').show();jQuery('#error5').fadeOut(3000);
                            </script>";
            }
        }

    }
}
?>
</div>
<br/><br/><br/>
<?php 
    if(isset($_POST['STdate']) && isset($_POST['EDdate'])){
?>
<input type="button" value="Print Graph" onclick="PrintElem('#graph_div')" />
<?php } ?>