<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 15)
            echo "<li><a class='selected' href='assign.php?id=15'>Add Zone</a></li>";
        else
            echo "<li><a href='assign.php?id=15'>Add Zone</a></li>";
        if ($_GET['id'] == 6)
            echo "<li><a class='selected' href='assign.php?id=6'>View Zone</a></li>";
        else
            echo "<li><a href='assign.php?id=6'>View Zone</a></li>";
    }
    else {
        echo "<li><a class='selected' href='assign.php?id=15'>Add Zone</a></li>";
        echo "<li><a href='assign.php?id=6'>View Zone</a></li>";
        
    }
    ?>
</ul>
<?php
if ($_GET['id'] == 6) {
    include 'pages/zonemaster.php';
} else if ($_GET['id'] == 15) {
    include 'pages/addzone.php';
}
?>