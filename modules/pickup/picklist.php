<?php include '../panels/header.php';?>
<?php include_once 'pickup_functions.php'; ?>


<div class="container" style="margin-top: 50px;">
    
    <center>
       
        <table class="table table-bordered"  style=" width:70%">
            <thead>
                <tr>
                    <th colspan="100%">Dashboard</th>
                </tr>
                
            </thead>
       
    <tbody>
<?php
$pkps = getpickup();


?>
    </tbody>
</table>
   
<?php    
if(isset($pkps) && count($pkps)>0)
{
    
    foreach ($pkps as $pkp)
    {
    ?>
    
      
    <div style="width: 45%; border-radius: 5px; border: 1 px solid #ccc; float: left;  margin: 0 auto; padding: 10px;  ">
        <table style="width: 100%; border-radius: 5px;">
            <tr>
                <th colspan="100%"><?php echo $pkp->name;?> </th>
            </tr>
            <tr>
                <td>Total Pickups</td>
                <td><?php echo getordercount($pkp->pid);?></td>
            </tr>
            <tr>
                <td>Total Picked Up</td>
                <td><?php echo getordercount_status($pkp->pid, 1);?></td>
            </tr>
            <tr>
                <td>Total Cancelled</td>
                <td><?php echo getordercount_status($pkp->pid, 2);?></td>
            </tr>
            <tr>
                <td>Total Ongoing</td>
                <td><?php echo getordercount_status($pkp->pid, 0);?></td>
            </tr>
        </table>
        
        
        
    </div>
        
    
    <?php 
    }
    
}

?>
    
    </center>
</div>

<?php include '../panels/footer.php';?>
