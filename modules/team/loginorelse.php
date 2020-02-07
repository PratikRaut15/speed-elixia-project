<?php
include_once("session.php");
if(!IsLoggedIn())
{
    header("Location: login.php");
}
?>