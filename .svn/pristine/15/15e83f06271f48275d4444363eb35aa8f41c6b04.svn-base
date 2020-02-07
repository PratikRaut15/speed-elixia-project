<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delnation($_GET['delid']);
}
    $nations = getnations($_SESSION['userid']);
    include 'panels/viewnations.php';
    if(isset($nations))
    foreach($nations as $nation)
    {
        echo "<tr>";
        echo "<td>$nation->name</td>";
        echo "<td>$nation->realname</td>";
        echo "<td>$nation->timestamp</td>";        
        echo "<td><a href = 'nation.php?id=4&nationid=$nation->nationid&nationname=$nation->name'><i class='icon-pencil'></i></a></td>";
        ?>
            <td><a href = 'nation.php?id=2&delid=<?php echo($nation->nationid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                
        <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No Nation Created</td>
    <tr>";
?>
    </tbody>
</table>
