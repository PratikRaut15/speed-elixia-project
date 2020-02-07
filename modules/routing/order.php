<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 8)
            echo "<li><a class='selected' href='assign.php?id=8'>Add Order</a></li>";
        else
            echo "<li><a href='assign.php?id=8'>Add Order</a></li>";
        if ($_GET['id'] == 3)
            echo "<li><a class='selected' href='assign.php?id=3'>View Orders</a></li>";
        else
            echo "<li><a href='assign.php?id=3'>View Orders</a></li>";
    }
    else {
        echo "<li><a class='selected' href='assign.php?id=18'>Add Order</a></li>";
        echo "<li><a href='assign.php?id=17'>View Orders</a></li>";
        
    }
    ?>
</ul>
<?php
if ($_GET['id'] == 3) {
    include 'pages/orders.php';
} else if ($_GET['id'] == 8) {
    include 'pages/add_orders.php';
   
}
?>