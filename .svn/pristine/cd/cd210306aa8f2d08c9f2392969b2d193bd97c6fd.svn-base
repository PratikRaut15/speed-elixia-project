<?php
if(IsLoggedIn())
{
?>
    <div align="center">
    <div style="width:auto">
    <ul class="menu">
    <li><a href='customers.php'>Customers</a></li>        
    <?php
    if(IsHead())
    {
    ?>        
        <li><a href='payment.php'>Payment</a></li>        
    <?php
    }
    if(IsHead() || IsSourcing())
    {
    ?>
        <li><a href='purchase.php'>Purchase</a></li>
    <?php
    }
    ?>
    <li><a href='helpdesk.php'>Help Desk</a></li>    
    <li><a href='team.php'>Team</a></li>        
    <li><a href='logout.php'>Log Out</a></li>
    </ul>
    </div>
    </div>
<?php 
} 
?>