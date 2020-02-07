<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delshiper($_GET['delid']);
}
include 'panels/viewshipers.php';
$users = getshipers();
if(isset($users) && count($users)>0)
{
?>    
    <?php
    foreach ($users as $user)
    {
        
    ?>
    <tr>
      
        <td><?php echo $user->sid;?></td>
        <td><?php echo $user->sname;?></td>
        <td><?php echo $user->email;?></td>
        <td><?php echo $user->phone;?></td>
		<td>
		 <a href = 'shiper.php?id=3&uid=<?php echo $user->sid;?>'>
             <i class='icon-pencil'></i> 
            </a>
			</td>
        <td>
                  
            <a href = 'shiper.php?id=2&delid=<?php echo($user->sid); ?>' onclick="return confirm('Are you sure you want to delete?');">                
               <i class='icon-trash'></i> </a>
               
        </td>
    </tr>
    <?php 
    }
}
else
    echo '<tr><td colspan="7">No Users Created</td></tr>';
?>
    </tbody>
</table>