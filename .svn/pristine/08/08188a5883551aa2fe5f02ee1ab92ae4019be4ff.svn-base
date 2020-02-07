<?php include '../panels/header.php';?>
<div class="entry">
    <center>
        <ul id="tabnav">
        <?php
            if (isset($_GET['id'])) {
                if ($_GET['id'] == 1) {
                    echo "<li><a class='selected' href='checkpointException?id=1'>Create Exception</a></li>";
                } else {
                    echo "<li><a href='checkpointException.php?id=1'>Create Exception</a></li>";
                }
                if ($_GET['id'] == 2) {
                    echo "<li><a class='selected' href='checkpointException.php?id=2'>View Exception</a></li>";
                } else {
                    echo "<li><a href='checkpointException.php?id=2'>View Exception</a></li>";
                }
            } else {
                echo "<li><a class='selected' href='checkpointException.php?id=1'>Create Exception</a></li>";
                echo "<li><a href='checkpointException.php?id=2'>View Exception</a></li>";
            }
        ?>
        </ul>
        <?php
            include 'checkpoint_functions.php';
            if (!isset($_GET['id']) || $_GET['id'] == 1) {
                include 'pages/createException.php';
            } elseif ($_GET['id'] == 2) {
                include 'pages/viewException.php';
            }
        ?>
    </center>
</div>
<?php include '../panels/footer.php';?>
