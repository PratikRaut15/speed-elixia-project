<?php
if(isset($_GET['delid']) && $_GET['delid'])
{
    delpkpboy($_GET['delid']);
}
?>
<table class="table  table-bordered table-striped dTableR dataTable"  style=" width:70%">
    <thead>
    
    <tr>
       
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th colspan="2">Options</th>
    </tr>
    </thead>
    <tbody>
<?php
$pkps = getpickup();

if(isset($pkps) && count($pkps)>0)
{
    foreach ($pkps as $pkp)
    {
    ?>
    <tr>
      
        
        <td><?php echo $pkp->name;?></td>
        <td><?php echo $pkp->username;?></td>
        <td><?php echo $pkp->email;?></td>
        <td><?php echo $pkp->phone;?></td>
        <td>
		 <a href = 'pickup.php?id=3&uid=<?php echo $pkp->pid;?>'>
             <i class='icon-pencil'></i> 
            </a>
			</td>
        <td>
            <a href = 'pickup.php?id=2&delid=<?php echo($pkp->pid); ?>' onclick="return confirm('Are you sure you want to delete?');"><i class='icon-trash'></i> </a>
               
        </td>
    </tr>
    <?php 
    }
}
else
    echo '<tr><td colspan="4">No Pickup boy Created</td></tr>';
?>
    </tbody>
</table>