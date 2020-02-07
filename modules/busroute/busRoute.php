<?php include '../panels/header.php';?>
    <div class="entry">
    <center>
        <ul id="tabnav">
        <li><a class='selected' href='busRoute.php'>Bus Routes</a></li>

        </ul>
        <?php
            include_once 'busrouteFunctions.php';
            include_once 'pages/viewBusRoutes.php';
        ?>
    </center>
    </div>
<?php include '../panels/footer.php';?>
