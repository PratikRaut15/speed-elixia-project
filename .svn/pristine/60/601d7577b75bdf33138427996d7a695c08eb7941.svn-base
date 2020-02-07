<?php 
function sendMail( $name, $email , $subject,$message)
{
	
	$content=" From : ".$name." \n";
	$content.=" Email : ".$email." \n";
	$content.=" message : ".$message." \n";
	

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
    if (!@mail("sanketsheth1@gmail.com", "Enquiry: ".$subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;        
}


extract($_REQUEST);

$res=array();
$res['status']=false;


if($name!="" && $message!="" && $subject!="" ){
	$res['status']=true;
	sendMail( $name, $email , $subject,$message);
}else{
	$res['status']=false;
}
echo json_encode($res);

?>