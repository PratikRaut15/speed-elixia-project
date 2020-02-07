<ul id="tabnav">
    <?php
    if (isset($_GET['id'])) {

        if ($_GET['id'] == 2)
            echo "<li><a class='selected' href='insurance.php?id=2'>View Insurance Company</a></li>";
        else
            echo "<li><a href='insurance.php?id=2'>View Insurance Company</a></li>";
        if ($_GET['id'] == 4)
            echo"<li><a class='selected' href='insurance.php?id=4&mid=$_GET[insid]'>Edit Insurance Company</a></li>";
    }
    else {
        echo "<li><a href='insurance.php?id=2'>View Insurance Company</a></li>";
    }
    ?>
</ul>
<?php
include 'insurance_functions.php';

if ($_GET['id'] == 2) {
    include 'pages/viewinsurance.php';
} else if ($_GET['id'] == 4) {
    include 'pages/editinsurance.php';
}