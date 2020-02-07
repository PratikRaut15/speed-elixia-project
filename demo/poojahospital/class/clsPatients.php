<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of clsPatients
 *
 * @author Mrudang Vora
 */
//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
require_once "config.inc.php";
require_once "database.inc.php";
define("TABLE_NAME", "patients", true);
class clsPatients {
    public function __construct() {
        $this->db = new demodatabase(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    }

    public function GetLatLong($arrData) {
        //print_r($arrDetails);die();
        foreach ($arrData as $data) {
            $patientid = !empty($data['PatientId']) ? $data['PatientId'] : 0;
            $lat = "";
            $long = "";
            $accuracy = 0;
            $address = "";
            $address .= !empty($data['Address']) ? $data['Address'] : '';
            $address .= !empty($data['Locality']) ? "," . $data['Locality'] : '';
            $address .= !empty($data['City']) ? "," . $data['City'] : '';
            $address .= !empty($data['Pincode']) ? "," . $data['Pincode'] : '';
            //echo $address;
            if (trim($address) != '' && trim($address) != ',,,') {
                $address = urlencode($address);
                $key = "";
                $google_api1 = $this->signUrl("http://maps.google.com/maps/api/geocode/json?address=$address&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, $google_api1);
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                $results1 = curl_exec($ch1);
                $array_data1 = json_decode($results1);
                $partial_match = $this->retval_issetor($array_data1->results[0]->partial_match, null);
                if ($array_data1->status === 'OK' && $partial_match == 0) {
                    $location = $array_data1->results[0]->geometry->location;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 1;
                } elseif ($array_data1->status === 'OK') {
                    $location = $array_data1->results[0]->geometry->location;
                    //$country = $array_data1->results[0]->address_components;
                    //echo $country->short_name;
                    $lat = $location->lat;
                    $long = $location->lng;
                    $accuracy = 4;
                } else {
                    //echo $array_data1->status;
                }
                $this->UpdateLatLong($patientid, $lat, $long, $accuracy);
            }
        }
    }

    private function UpdateLatLong($paramPatientid, $lat, $lng, $paramAccuracy) {
        $sql = "UPDATE " . DATABASE_NAME . "." . TABLE_NAME
            . " SET latitude='" . $lat . "'"
            . ", longitude='" . $lng . "'"
            . ", accuracy='" . $paramAccuracy . "'"
            . " where PatientId = '" . $paramPatientid . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function getMissingLatLong() {
        $selectQuery = "SELECT PatientId
                            ,PatientNumber
                            ,PatientName
                            ,MobileNumber
                            ,ContactNumber
                            ,EmailAddress
                            ,SecondaryMobile
                            ,Gender
                            ,Address
                            ,Locality
                            ,City
                            ,Pincode
                            ,latitude
                            ,longitude
                    FROM " . DATABASE_NAME . "." . TABLE_NAME
            . " where (latitude = 0 or longitude = 0)";
        $result = $this->db->query($selectQuery, __FILE__, __LINE__);
        // Fetch all
        while ($row = $this->db->fetch_all($result)) {
            $patientDetails[] = $row;
        }
        // Free result set
        $this->db->free_result($result);
        return $patientDetails;
    }

    public function getHeatMap() {
        $selectQuery = "select patientid, latitude,longitude, address, locality, pincode "
            . " from " . DATABASE_NAME . "." . TABLE_NAME
            . " where (latitude != 0 or longitude != 0)";
        $result = $this->db->query($selectQuery, __FILE__, __LINE__);
        if ($this->db->get_rowCount($result) > 0) {
            while ($row = $this->db->fetch_all($result)) {
                $data[] = $row;
            }
            foreach ($data as $row) {
                $address = "{$row['patientid']}, {$row['address']} \r\n {$row['locality']} \r\n {$row['pincode']}";
                $modifieddata[] = array(
                    'lat' => $row['latitude'],
                    'lng' => $row['longitude'],
                    'address' => $address
                );
            }
            return $modifieddata;
        } else {
            return null;
        }
    }

    public function signUrl($myUrlToSign, $privateKey) {
        $url = parse_url($myUrlToSign);
        $urlPartToSign = $url['path'] . "?" . $url['query'];
        $decodedKey = $this->decodeBase64UrlSafe($privateKey);
        $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);
        $encodedSignature = $this->encodeBase64UrlSafe($signature);
        return $myUrlToSign; // . "&signature=" . $encodedSignature;
    }

    public function encodeBase64UrlSafe($value) {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
    }

    public function decodeBase64UrlSafe($value) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
    }

    public function retval_issetor(&$var, $def = false) {
        return isset($var) ? $var : $def;
    }
}