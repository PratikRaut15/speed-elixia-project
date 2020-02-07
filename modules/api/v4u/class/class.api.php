<?php

class VODevices {
    
}

class Object {
    
}

class api {

    var $status;
    var $status_time;

    // construct
    function __construct() {
        $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    }

    // <editor-fold defaultstate="collapsed" desc="API functions">
    function register($name, $email, $phone, $type, $desc, $otpverified, $city, $institution, $image) {
        $arr_p = Array();
        try {
            // Register
            $date = date('Y-m-d H:i:s');
            $otp = rand(1000, 9999);            
            $Query = "INSERT INTO request (`name`,`email`,`phone`,`type`, `desc`, `isapproved`, `timstamp`, `otp`, `isotpverified`, `city`, `institution`, `image`) "
                    . "VALUES ( '$name', '$email', '$phone', '$type', '$desc', 0, '$date', '$otp', '$otpverified', '$city', '$institution', '$image')";
            $this->db->query($Query, __FILE__, __LINE__);
            $lastid = $this->db->last_insert_id();

            if($otpverified != 1)
            {
                $this->sendSMS($phone, "Password for V4U: " . $otp);
            }
            $to = "sanketsheth1@gmail.com";
            $subject = "V4U Enquiry";
            if($type == 1)
            {
                $typevar = "Donor";                
            }
            elseif($type == 0)
            {
                $typevar = "Recipient";
            }
            $message = "Type: ".$typevar."<br/>";            
            $message.= "Name: ".$name."<br/>";
            $message.= "Email: ".$email."<br/>";
            $message.= "Phone: ".$phone."<br/>";
            $message.= "Description: ".$desc."<br/>";
            $message.= "Institution: ".$institution."<br/>";
            
            $this->sendMail($to, $subject, $message);
            $arr_p['success'] = 0;
            $arr_p['message'] = "";
            $arr_p['lastid'] = $lastid;
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function otpverification($lastid, $otp) {
        $arr_p = Array();
        try {
            if ($otp != "") {
                $q = "SELECT * FROM request WHERE requestid = %d AND otp = %d AND isotpverified = 0 LIMIT 1";
                $dq = sprintf($q, $lastid, $otp);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
                while ($row = $this->db->fetch_array($record)) {
                    $Query = "UPDATE request "
                            . "SET isotpverified = 1 "
                            . "WHERE requestid = '$lastid' AND otp = $otp AND isotpverified = 0";
                    $this->db->query($Query, __FILE__, __LINE__);
                    $arr_p['success'] = 0;
                    $arr_p['message'] = "";
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function pullrequests() {
        $arr_p = Array();
        $json = Array();
        $x = 0;
        try {
            $q = "SELECT * FROM request WHERE isapproved = 1 AND type = 0";
            $dq = sprintf($q);
            $record = $this->db->query($dq, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['success'] = 0;
                    $arr_p['message'] = "";
                    $json[$x]['requestid'] = $row["requestid"];                    
                    $json[$x]['desc'] = $row["desc"];
                    $json[$x]['timestamp'] = "Request Date: ".date("d-m-Y", strtotime($row["timstamp"]));                    
                    $json[$x]['city'] = "City: ".$row["city"];                    
                    $x++;
                }
                $arr_p['data'] = $json;
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "No Requests Found";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }


    // </editor-fold>
    // 
    // <editor-fold defaultstate="collapsed" desc="Helper functions">

    function sendSMS($phone, $message) {
        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91" . urlencode($phone) . "&source=ELIXIA&message=" . urlencode($message);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $result = curl_exec($ch);
        curl_close($ch);
        return true;
    }

    
    function sendMail( $to, $subject , $content)
    {
        $subject = $subject;

        $headers = "From: noreply@elixiatech.com\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";    
        if (!@mail($to, $subject, $content, $headers)) {
            // message sending failed
            return false;
        }
        return true;        
    }
    
}
?>