<form id="mapart" id="mapart" action="route.php" method="POST">
<?php 
include 'panels/mapart.php';
$vehicles = getvehicles();
if(isset($vehicles) && count($vehicles)>0)
{
    $display = "";
    $articles = getallart();
    if(isset($articles))
    {
        foreach ($vehicles as $vehicle)
        {
            $display .= "<tr><td>$vehicle->vehicleno</td>";

            $display .= "<td><select id='$vehicle->vehicleid' name='$vehicle->vehicleid'>";
            $display .= "<option value = '0'>None</option>";
            foreach ($articles as $article)
            {
                if($article->artid == $vehicle->artid)
                {
                    $display .= "<option value = $article->artid selected='selected'>$article->artname</option>";
                }
                else
                {
                    $display .= "<option value = $article->artid>$article->artname</option>";
                }
            }
            $display .= "</select></td></tr>";
        }
        $display .= "<tr><td colspan='100%'><input type='submit' value='Submit' name='map'></td></tr>";
    }
    else
    {
        $display = "<tr><td colspan='100%'>No Article Types Created</td></tr>";
    }
}   
else
{
    $display = "<tr><td colspan='100%'>No Vehicles With Temp Sensor Available</td></tr>";
}
echo $display;
?>
</tbody>
</table>
</form>
