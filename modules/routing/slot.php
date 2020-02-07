<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 18)
            echo "<li><a class='selected' href='assign.php?id=18'>Add Slot</a></li>";
        else
            echo "<li><a href='assign.php?id=18'>Add Slot</a></li>";
        if ($_GET['id'] == 17)
            echo "<li><a class='selected' href='assign.php?id=17'>View Slot</a></li>";
        else
            echo "<li><a href='assign.php?id=17'>View Slot</a></li>";
    }
    else {
        echo "<li><a class='selected' href='assign.php?id=18'>Add Slot</a></li>";
        echo "<li><a href='assign.php?id=17'>View Slot</a></li>";
        
    }
    ?>
</ul>
<?php
if ($_GET['id'] == 17) {
    include 'pages/slotmaster.php';
} else if ($_GET['id'] == 18) {
    include 'pages/addslot.php';
   
}
?>