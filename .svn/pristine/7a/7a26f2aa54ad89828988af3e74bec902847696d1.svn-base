<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
delroute($_GET['delid']);
header("Location : enh_route.php?id=2");
}
    include 'panels/viewroutes.php';
    $routes = getroutes_enh();
    if(isset($routes))
    foreach($routes as $route)
    {
        echo "<tr>";
        echo "<td>$route->routename</td>";
        
            echo "<td><a href ='enh_route.php?id=4&did=$route->routeid&routename=$route->routename'><i class='icon-pencil'></i></a></td>";
        
         ?>
        <td><a href ='enh_route.php?id=2&delid=<?php echo $route->routeid;?>' onclick='return confirm("Are you sure you want to delete?");'><i class='icon-trash'></i></a></td>
       <?php echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No route Created</td>
    <tr>";
?>
    </tbody>
</table>
