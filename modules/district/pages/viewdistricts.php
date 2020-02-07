<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    deldistrict($_GET['delid']);
}
    $districts = getdistricts($_SESSION['userid']);
    include 'panels/viewdistricts.php';
    if(isset($districts))
    foreach($districts as $district)
    {
        echo "<tr>";
        echo "<td>$district->districtname</td>";
        echo "<td>$district->statename</td>";
        echo "<td>$district->nationname</td>";
        echo "<td>$district->realname</td>";
        echo "<td>$district->timestamp</td>";        
        echo "<td><a href = 'district.php?id=4&districtid=$district->districtid&districtname=$district->name'><i class='icon-pencil'></i></a></td>";
        ?>
            <td><a href = 'district.php?id=2&delid=<?php echo($district->districtid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                
        <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No District Created</td>
    <tr>";
?>
    </tbody>
</table>
