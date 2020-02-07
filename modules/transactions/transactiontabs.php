
<?php
if(isset($_GET['id']))
{
    
    if($_GET['id']==1){
        echo "<ul id='tabnav'>";
        echo "<li><a class='selected' href='transaction.php?id=1'>Add Transaction</a></li>";
        echo "<li><a href='transaction.php?id=2'>View Transactions</a></li>";
        echo"</ul>";
    }
    
}
    //if($_GET['id']==1)
        //echo "<li><a class='selected' href='transaction.php?id=1'>Add Transaction</a></li>";
    //else
        //echo "<li><a href='transaction.php?id=1'>Add Transaction</a></li>";
    //if($_GET['id']==2 || $_GET['id'] == 3 || $_GET['id'] == 6)
     //   echo "<li><a class='selected' href='transaction.php?id=2'>View Transactions</a></li>";
    //else
      //  echo "<li><a href='transaction.php?id=2'>View Transactions</a></li>";
   // }
   // else
    //{
        //echo "<li><a href='transaction.php?id=1'>Add Transaction</a></li>";
      //  echo "<li><a class='selected' href='transaction.php?id=2'>View Transactions</a></li>";
    //}
?>
<!--</ul>-->
<?php if($_GET['id']==2){ ?>
<br>
<div class="test" style="text-align: right; margin: 0px 10px 0px 0px;">    
    <a href="transaction.php?id=1" alt="Add Transaction">  <button style="margin:5px; width:auto; display: inline-block;" class="btn-primary">Add Transaction <img src="../../images/show.png"></button></a>
</div>
<br>
<?php    
}?>
<?php
include 'transaction_functions.php';
if(!isset($_GET['id']) || $_GET['id']==1)
    include 'pages/addtransaction.php';
else if($_GET['id']==2)
    include 'pages/viewtransactions.php';
else if($_GET['id']==3)
    include 'pages/approvetransactions.php';
else if($_GET['id']==4)
    include 'pages/accidenttransactions.php';
else if($_GET['id']==5)
    include 'pages/closetransactions.php';
else if($_GET['id']==6)
    include 'pages/accidentclose.php';
else if($_GET['id']==7)
    include 'pages/fuel_edit.php';

?>
