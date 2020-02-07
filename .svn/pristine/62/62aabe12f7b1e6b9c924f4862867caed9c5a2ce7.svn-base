<?php
include_once("session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Elixia Team Portal</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link href="stylesheet.css" rel="stylesheet" type="text/css">
        <?php
        
$_stylesheets[]="../jscalendar/skins/aqua/theme.css";

$rootpath = (isset($subdirinurl)?$subdirinurl:"")."/";            

// Process any extra Styles
if(isset($_stylesheets))
{

    foreach($_stylesheets as $thisstyle)
    {
        echo('<link href="'.$thisstyle.'" rel="stylesheet" type="text/css">');
    }
}
?>
        <script src="../js/prototype.js" type="text/javascript"></script>
        <script src="../js/sorttable.js" type="text/javascript"></script>
        <?php
        
// Always need the Calendar
$_scripts[] = "../jscalendar/calendar.js";
$_scripts[] = "../jscalendar/lang/calendar-en.js";
$_scripts[] = "../jscalendar/calendar-setup.js";
        
// Process any extra Styles
if(isset($_scripts))
{
    foreach($_scripts as $thisscript)
    {
        echo('<script src="'.$thisscript.'" type="text/javascript"></script>');
    }
}
?>
    </head>
    <body link="#000000" alink="#000000" vlink="#000000" >

<div class="mainpage">
    <?php
    include("banner.php");
    include("menu.php");
    ?>