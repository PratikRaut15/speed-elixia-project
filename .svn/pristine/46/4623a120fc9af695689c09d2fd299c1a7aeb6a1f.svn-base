<script type="text/javascript">
function getTripRouteReport(){
    jQuery('#centerDiv').html('');
    jQuery('#pageloaddiv').show();
    var data = jQuery("#routerepform").serialize();
    var routeTxt = jQuery('#routeid option:selected').html();
    jQuery.ajax({
        url:"route_report_ajax.php",
        type: 'POST',
        data: data+'&routeTxt='+routeTxt,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}
</script>
<form id="routerepform" action="reports.php?id=22" method="POST" onsubmit="getTripRouteReport();return false;">
<?php

$routes = getroutes();
$routesopt = "";
foreach ($routes as $route){
    $routesopt .= "<option value = '$route->routeid'>$route->routename</option>";
}
$title = "Route Report";
$today = date('d-m-Y');
include 'panels/triproutereport.php';
?>
    <tr>
        <td>Route</td>
        <td>Start Date</td>
        <td>End Date</td>
    </tr>
    <tr>
        <td>
            <select id="routeid" name="routeid" required>
                <option value="">Select Route</option>
                <?php echo $routesopt;?>
            </select>
        </td>
        <td><input id="SDate" name="STdate" type="text" value="<?php echo $today;?>" required/></td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo $today;?>" required/></td>
        <td>
            <input type="hidden" id="tripreport" name="tripreport" value="tripreport">
            <input type="submit" value="Get Report" class="g-button g-button-submit" name="GetReport" >
        </td>
    </tr>
    </tbody>
    </table>
</form>
<br/><br/>
<center id="centerDiv"></center>
