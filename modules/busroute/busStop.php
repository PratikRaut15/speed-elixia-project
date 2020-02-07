<?php include '../panels/header.php';?>
    <div class="entry">
    <center>
        <ul id="tabnav">
        <?php
        echo "<li><a class='selected' href='busStop.php'>Bus Stops</a></li>";
        ?>
        </ul>
        <?php
            include_once 'busrouteFunctions.php';
            include_once 'pages/viewBusStops.php';
        ?>
    </center>
    </div>
<?php include '../panels/footer.php';?>
