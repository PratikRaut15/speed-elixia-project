
<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(-1);
include_once("session.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>Elixia Team</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="icon" href="../../images/team_favicon.ico" type="image/x-icon">
        <link href="stylesheet.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
            <!-- Required For Bootstrap -->    
            <link type="text/css" rel="stylesheet" href="../../bootstrap/css/bootstrap.css" />    
            <link type="text/css" rel="stylesheet" href="../../css/bootstrap.css" />    
            <?php
            $_stylesheets[] = "../../scripts/trash/jscalendar/skins/aqua/theme.css";
            $_stylesheets[] = "../../scripts/trash/timepicker/jquery.timepicker.css";
            $_stylesheets[] = "../../scripts/autocomplete/jquery-ui.min.css";
            $rootpath = (isset($subdirinurl) ? $subdirinurl : "") . "/";

// Process any extra Styles
            if (isset($_stylesheets)) {

                foreach ($_stylesheets as $thisstyle) {
                    echo('<link href="' . $thisstyle . '" rel="stylesheet" type="text/css">');
                }
            }
            ?>
            <?php
//Required For Bootstarp DropDown Menu

            $_scripts[] = "../../bootstrap/js/jquery.min.js";
            $_scripts[] = "../../bootstrap/js/bootstrap.js";
// Always need the Calendar
            $_scripts[] = "../../scripts/trash/jscalendar/calendar.js";
            $_scripts[] = "../../scripts/trash/jscalendar/lang/calendar-en.js";
            $_scripts[] = "../../scripts/trash/jscalendar/calendar-setup.js";
            $_scripts[] = "../../scripts/trash/jscalendar/calendar-setup.js";
            $_scripts[] = "../../scripts/trash/timepicker/jquery.timepicker.js";
            $_scripts[] = "../../bootstrap/js/bootstrap-datepicker.js";
            $_scripts[] = "../../scripts/autocomplete/jquery-ui.min.js";
//echo "<script src='../../bootstrap/js/bootstrap.js' type='text/javascript'></script>";
// Process any extra Styles
            if (isset($_scripts)) {
                foreach ($_scripts as $thisscript) {
                    echo('<script src="' . $thisscript . '" type="text/javascript"></script>');
                }
            }

            if (isset($_scripts_custom)) {
                foreach ($_scripts_custom as $thisscript) {
                    echo('<script src="' . $thisscript . '" type="text/javascript"></script>');
                }
            }
            ?>
    </head>
    <body link="#000000" alink="#000000" vlink="#000000" >


        <?php
//    include("banner.php");
        include("menu_new.php");
        ?>