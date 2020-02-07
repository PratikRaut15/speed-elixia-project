<?php
include_once("../../../session.php");
include_once("../../../lib/system/utilities.php");

class json{
// Empty class
}

if(isset($_GET["customerno"])  && isset($_GET["devicekey"]))
{
    $jsonstatus = new json();
    $jsonstatus->feedback = Array();
//    $jsonstatus->servicelist = Array();
    $devicekey = GetSafeValueString($_GET["devicekey"],"string");
    $customerno = GetSafeValueString($_GET["customerno"],"string");    
        
    // Service Call Manager
    include_once("../../../lib/wbo/ServiceCallManager.php");
    $scm = new ServiceCallManager($customerno);        
    $data = $scm->getdatafromdevicekey($devicekey);            
    $questions = $scm->getfeedbackquestions();
    $jsonstatus->status = "successful"; 
    if(isset($questions))
    {
        foreach($questions as $thisquestion)
        {
            if(isset($thisquestion->customerno))
            { 
                $options = $scm->getfboptions($thisquestion->feedbackquestionid);                
                $thisquestion->options = Array();
                if(isset($options))
                {
                    foreach($options as $thisoption)
                    {
                        $thisquestion->options[] = $thisoption;
                    }
                }
                $jsonstatus->feedback[] = $thisquestion;
            }            
        }
        $scm->updatepushfeedback($data->trackeeid);        
    }            
}
else    
{
    $jsonstatus->status = "unsuccessful";        
}    
echo json_encode($jsonstatus);
?>