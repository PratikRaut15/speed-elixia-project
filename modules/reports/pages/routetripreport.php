<script type="text/javascript">
function getRouteReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#routereportForm").serialize();
    jQuery.ajax({
        url:"routetripreport_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}
</script>
<form action="reports.php?id=31" method="POST" id='routereportForm' onsubmit="getRouteReport();return false;">
<?php
include 'panels/routetripreport.php';

$routes = getroutes_enh();
$routesopt = "";
foreach ($routes as $route){
    $routesopt .= "<option value = '$route->routeid'>$route->routename</option>";
    
}
$today = date('d-m-Y');
?>
    <tr>
        <td>Route</td>
        <td>Start Date</td>
        <td>End Date</td>
        <td></td>
    </tr>
    <tr>
        <td>
            <select id="routeid" name="routeid" required>
                <option value="">Select Route</option>
                <?php echo $routesopt;?>
            </select>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today; ?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today;?>" required/></td>
        <input type="hidden" id="tripreport" name="tripreport" value="tripreport"></td>
        <td><input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport"></td>
    </tr>
    </tbody>
    </table>
</form>
<br/><br/>
<center id='centerDiv'></center>