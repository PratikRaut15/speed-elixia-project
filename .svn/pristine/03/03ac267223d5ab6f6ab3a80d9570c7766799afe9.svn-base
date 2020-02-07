<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {
        if ($_GET['id'] == 1) {
            echo "<li><a class='selected' href='student.php?id=1'>View Students</a></li>";
        } else {
            echo "<li><a href='student.php?id=1'>View Students</a></li>";
        }
    } else {
        echo "<li><a href='student.php?id=1'>View Students</a></li>";
    }
    ?>
</ul>
    <?php
    include 'busrouteFunctions.php';
    if (!isset($_GET['id']) || $_GET['id'] == 1){
        include 'pages/viewstudents.php';
    }
    ?>
