<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delcity($_GET['delid']);
}
    $cities = getcities($_SESSION['userid']);
    include 'panels/viewcities.php';
    if(isset($cities))
    foreach($cities as $city)
    {
        echo "<tr>";
        echo "<td>$city->cityname</td>";
        echo "<td>$city->districtname</td>";
        echo "<td>$city->statename</td>";
        echo "<td>$city->nationname</td>";
        echo "<td>$city->realname</td>";
        echo "<td>$city->timestamp</td>";
        echo "<td><a href = 'city.php?id=4&cityid=$city->cityid&districtname=$city->name'><i class='icon-pencil'></i></a></td>";
        ?>
            <td><a href = 'city.php?id=2&delid=<?php echo($city->cityid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                
        <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No City Created</td>
    <tr>";
?>
    </tbody>
</table>
