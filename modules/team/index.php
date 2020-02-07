<?php
session_start();
if(isset($_SESSION["sessionteamusername"]))
    header("Location: customers.php");
else
    header("Location: login.php");
?>
