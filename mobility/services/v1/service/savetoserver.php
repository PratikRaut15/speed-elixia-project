<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

class feedbackmanagedb{
    // Empty class
}

class servicesmanagedb{
    // Empty class
}

if(isset($_GET["customerno"]) && isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $jsonstatus->status = "unsuccessful";
    
    $customerno = GetSafeValueString($_GET["customerno"],"string");
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    
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
    
    $target_path_signature = $target_path_customer . "signature/";
    
    if(!is_dir( $target_path_signature ))
    {
        mkdir( $target_path_signature ,0777, true ) or die ("Could not create directory");
    }        
    
    $target_path_sig_service = $target_path_customer . "service/";
    
    if(!is_dir( $target_path_sig_service ))
    {
        mkdir( $target_path_sig_service ,0777, true ) or die ("Could not create directory");
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
       chmod ($target_path.basename( $_FILES['uploadedfile']['name']), 0777);       
       rename($target_path, $target_path_old."/information.sqlite" );
        copy( $target_path_old."information.sqlite", $target_path_history.time()."_information.sqlite");    
        // Save jpg       
        try
        {
              //create or open the database
 
            $database = new PDO("sqlite:../../../customer/".$customerno."/".$devicekey."/sqlitefiles/information.sqlite");
            
            // Service Call Signature
            $result = $database->query('SELECT * FROM calls WHERE ispushed = 0');
            if(isset($result))
            {
                include_once("../../../lib/wbo/ServiceCallManager.php");                           
                $sm = new ServiceCallManager($customerno);                                    
                foreach($result as $row)
                {
                    $jsonstatus->status = "successful";                                                                                
                    $serviceid = $row["serviceid"];
                    $status = $row["status"];                                        
                    $closedon = $row["closedon"];
                    $starttime = $row["starttime"];
                    $endtime = $row["endtime"];
                    $departtime = $row["departtime"];
                    $remarkid = $row["remarkid"];
                    $totalbill = $row["totalbill"];
                    $discountcode = $row["discountcode"];
                    $discountamount = $row["discountamount"];
                    $iscard = $row["iscard"];
                    $sm->setremark($remarkid, $serviceid);
                    $sm->settotalbill($serviceid, $totalbill, $discountamount, $discountcode);
                    if($status == 5)
                    {
                        $image = imagecreatefromstring($row['signature']);     
                        imagejpeg($image, $_SERVER['DOCUMENT_ROOT'].$subdir."/customer/".$customerno."/sqlitefiles/".$devicekey."/signature/service/".$serviceid.".jpeg", 80);
                        $sm->updatecalls($serviceid, $closedon, $status, $starttime, $endtime, $departtime, $iscard);                
                    }
                    if($status == 4)
                    {
                        $sm->updateendcall($serviceid, $status, $starttime, $endtime, $departtime);                
                    }
                    if($status == 3)
                    {
                        $sm->updatestartcall($serviceid, $status, $starttime, $departtime);                                                                
                    }
                    if($status == 2)
                    {
                        $sm->updatedepartcall($serviceid, $status, $departtime);                
                    }
                    if($status == 1)
                    {
                        $sm->updateviewcall($serviceid, $status);                                        
                    }
                    
                }  
            }
            
            $trackeeid = 0;
            
            // Pull Trackee
            $resultcr = $database->query('SELECT * FROM criticaldata');
            if(isset($resultcr))
            {
                foreach($resultcr as $row)
                {
                    $trackeeid = $row["trackeeid"];
                }
            }
            
            // Update Feedback
            $resultfb = $database->query('SELECT * FROM feedbackmanage WHERE ispushed = 0');
            if(isset($resultfb))
            {
                $jsonstatus->status = "successful";                                                                                                
                include_once("../../../lib/wbo/ServiceCallManager.php");                           
                $sm = new ServiceCallManager($customerno);                                                        
                foreach($resultfb as $row)
                {
                    $feedbackmanage = new feedbackmanagedb();
                    $feedbackmanage->feedbackoptionid = $row["feedbackoptionid"];
                    $feedbackmanage->feedbackqid = $row["feedbackqid"];
                    $serviceid = $row["serviceid"];
                    $sm->pushfeedback($feedbackmanage, $serviceid, $trackeeid);
                }
            }
            
            // Update Services
            $resultfb = $database->query('SELECT * FROM servicemanage WHERE ispushed = 0');
            if(isset($resultfb))
            {
                $jsonstatus->status = "successful";                                                                                                
                include_once("../../../lib/wbo/ServiceCallManager.php");                           
                $sm = new ServiceCallManager($customerno);                                                        
                foreach($resultfb as $row)
                {
                    $servicesmanage = new servicesmanagedb();
                    $servicesmanage->id = $row["id"];
                    $servicesmanage->servicelistid = $row["servicelistid"];
                    $servicesmanage->serviceid = $row["serviceid"];
                    $servicesmanage->isdeleted = $row["isdeleted"];
                    $servicesmanage->bytrackee = $row["bytrackee"];
                    $servicesmanage->createdby = $row["createdby"];                    
                    $sm->pushservicesdb($servicesmanage, $trackeeid);
                }
            }
                        
//            $database->__sleep();
            
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