<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 16)
            echo "<li><a class='selected' href='assign.php?id=16'>Add Area</a></li>";
        else
            echo "<li><a href='assign.php?id=16'>Add Area</a></li>";
        if ($_GET['id'] == 7)
            echo "<li><a class='selected' href='assign.php?id=7'>View Area</a></li>";
        else
            echo "<li><a href='assign.php?id=7'>View Area</a></li>";
    }
    else {
        echo "<li><a class='selected' href='assign.php?id=16'>Add Area</a></li>";
        echo "<li><a href='assign.php?id=7'>View Area</a></li>";
        
    }
    ?>
</ul>
<?php
if ($_GET['id'] == 7) {
    include 'pages/areamaster.php';
} else if ($_GET['id'] == 16) {
    include 'pages/addarea.php';
}
?>