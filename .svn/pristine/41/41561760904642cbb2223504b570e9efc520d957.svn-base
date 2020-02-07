<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    deluser($_GET['delid']);
}
include 'panels/viewcustomers.php';
$users = getcustomers();
if(isset($users) && count($users)>0)
{
?>    
    <?php
    foreach ($users as $user)
    {
        
    ?>
    <tr>
      
        <td><?php echo $user->customerid;?></td>
        <td><?php echo $user->customername;?></td>
        <td><?php echo $user->email;?></td>
        <td><?php echo $user->phone;?></td>
		<td>
		 <a href = 'customer.php?id=3&uid=<?php echo $user->customerid;?>'>
             <i class='icon-pencil'></i> 
            </a>
			</td>
        <td>
                  
            <a href = 'customer.php?id=2&delid=<?php echo($user->customerid); ?>' onclick="return confirm('Are you sure you want to delete?');">                
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