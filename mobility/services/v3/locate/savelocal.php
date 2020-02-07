<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");
include_once("../../../lib/wbo/TrackManager.php");
require 'files/push_sqlite.php';

class json{
// Empty class
}
class basket{
// Empty class
}

if(isset($_GET["customerno"]) && isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $jsonstatus->status = "unsuccessful";
    $customerno = GetSafeValueString($_GET["customerno"],"string");
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    
    $sm = new TrackManager($customerno);
    $data = $sm->getdatafromdevicekey($devicekey);
    
    $target_path_customer = $_SERVER['DOCUMENT_ROOT'].$subdir."//customer/".$customerno."/".$devicekey."/";    
    if(!is_dir( $target_path_customer ))
    {
        mkdir( $target_path_customer ,0777, true ) or die ("Could not create directory");
    }    
    
    $target_path = $target_path_customer."sqlitefiles/";    
    if(!is_dir( $target_path ))
    {
        mkdir( $target_path ,0777, true ) or die ("Could not create directory");
    }    
    
    $target_path_history = $target_path . "history/";
    
    if(!is_dir( $target_path_history ))
    {
        mkdir( $target_path_history ,0777, true ) or die ("Could not create directory");
    }                

    $target_path_old = $target_path;
    $target_path = $target_path. basename( $_FILES['uploadedfile']['name']);
    if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
    {
       chmod($target_path.basename( $_FILES['uploadedfile']['name']), 0777);       
       rename($target_path, $target_path_old."/informationlocal.sqlite" );
        copy( $target_path_old."informationlocal.sqlite", $target_path_history.time()."_informationlocal.sqlite");    
        try
        {
              //create or open the database
            $database = new PDO("sqlite:../../../customer/".$customerno."/".$devicekey."/sqlitefiles/informationlocal.sqlite");
            $result = $database->query('SELECT * FROM localdata WHERE ispushed = 0');
            if(isset($result))
            {
                foreach($result as $row)
                {
                    $localdate = $row["localdate"];
                    $latitude = $row["latitude"];
                    $longitude = $row["longitude"];
                    echo json_encode($jsonstatus);                                            
                    ChkSqlite($customerno,$devicekey,$localdate,$data->deviceid,$latitude,$longitude,$data->trackeeid);
                    $jsonstatus->status = "successful";                    
                }  
            }
        }
        catch(PDOException $e)
        {
            print 'Exception : '.$e->getMessage();
        }              
    } 
    else
    {
    // Do_Nothing
    }    
    
echo json_encode($jsonstatus);    
}
?>