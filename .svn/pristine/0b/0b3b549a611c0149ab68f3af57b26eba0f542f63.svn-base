<form id="routerepform" action="reports.php?id=9" method="POST">
<?php
include 'panels/routereport.php';

$routes = getroutes();
$routesopt = "";
foreach ($routes as $route) 
{
    if(isset($_POST['routeid']) && $route->routeid == $_POST['routeid'])
    {
        $routesopt .= "<option value = '$route->routeid' selected = 'selected'>$route->routename</option>";
    }
    else
    {
        $routesopt .= "<option value = '$route->routeid'>$route->routename</option>";
    }
}
if(!isset($_POST['STdate'])) { $StartDate = getdate_IST(); } else { $StartDate = strtotime ($_POST['STdate']); }
if(!isset($_POST['EDdate'])) { $EndDate = $StartDate; } else { $EndDate = strtotime ($_POST['EDdate']); }            
?>
    <tr>
        <td>
            <select id="routeid" name="routeid" required>
                <option value="">Select Route</option>
                <?php echo $routesopt;?>
            </select>
        </td>
        <td>Start Date</td>
        <td>
            <input id="SDate" name="STdate" type="text" value="<?php echo date('d-m-Y',$StartDate);?>" required/>
        </td>
        <td><!--<button id="SDate" class="g-button g-button-submit">...</button>--></td>
        <td>End Date</td>
        <td><input id="EDate" name="EDdate" type="text" value="<?php echo date('d-m-Y',$EndDate);?>" required/>
        </td>
        <td><!--<button id="EDate" class="g-button g-button-submit">...</button>--></td>
        <td><input type="button" value="Get Report" class="g-button g-button-submit" name="GetReport" onClick="generatereport();"></td>
    </tr>
    </tbody>
    </table>
</form>
<div id="routereportdiv"></div>
