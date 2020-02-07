<?php
$customerno = $_POST['customerno']; 
$docketid = $_POST['docketid'];
$ticketid = $_POST['ticketid'];
$path = "../../customer/".$customerno."/support/"."D00".$docketid."-".$ticketid.".zip";
if (!file_exists($path))     
        { 

                echo "file not found";
        }
        
    else{
             echo $path;
        }
		

?>