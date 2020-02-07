<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delvendor($_GET['delid']);
}
include 'panels/viewvendors.php';
$users = getvendors();
if(isset($users) && count($users)>0)
{
?>    
    <?php
    foreach ($users as $user)
    {
        
    ?>
    <tr>
      
       
       <td><?php echo $user->vendorid;?></td>
        <td><?php echo $user->vendorname;?></td>
        <td><?php echo $user->company;?></td>
        
        <td><?php echo $user->email;?></td>
        <td><?php echo $user->phone;?></td>
		<td>
		 <a href = 'vendor.php?id=3&uid=<?php echo $user->vendorid;?>'>
             <i class='icon-pencil'></i> 
            </a>
			</td>
        <td>
                  
            <a href = 'vendor.php?id=2&delid=<?php echo($user->vendorid); ?>' onclick="return confirm('Are you sure you want to delete?');">                
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