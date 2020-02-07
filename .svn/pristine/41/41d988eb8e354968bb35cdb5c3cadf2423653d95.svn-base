<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delstate($_GET['delid']);
}
    $states = getstates($_SESSION['userid']);
    include 'panels/viewstates.php';
    if(isset($states))
    foreach($states as $state)
    {
        echo "<tr>";
        echo "<td>$state->statename</td>";
        echo "<td>$state->nation</td>";
        echo "<td>$state->realname</td>";
        echo "<td>$state->timestamp</td>";        
        echo "<td><a href = 'state.php?id=4&stateid=$state->stateid&statename=$state->name'><i class='icon-pencil'></i></a></td>";
        ?>
            <td><a href = 'state.php?id=2&delid=<?php echo($state->stateid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i></a></td>                
        <?php
        echo "</tr>";
    }
 else
     echo 
     "<tr>
         <td colspan='6'>No State Created</td>
    <tr>";
?>
    </tbody>
</table>
